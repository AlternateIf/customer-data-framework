<?php

/**
 * Pimcore
 *
 * This source file is available under two different licenses:
 * - GNU General Public License version 3 (GPLv3)
 * - Pimcore Enterprise License (PEL)
 * Full copyright and license information is available in
 * LICENSE.md which is distributed with this source code.
 *
 *  @copyright  Copyright (c) Pimcore GmbH (http://www.pimcore.org)
 *  @license    http://www.pimcore.org/license     GPLv3 and PEL
 */

namespace CustomerManagementFrameworkBundle\Event;

use CustomerManagementFrameworkBundle\CustomerSaveManager\CustomerSaveManagerInterface;
use CustomerManagementFrameworkBundle\Model\AbstractObjectActivity;
use CustomerManagementFrameworkBundle\Model\ActivityInterface;
use CustomerManagementFrameworkBundle\Model\CustomerInterface;
use CustomerManagementFrameworkBundle\Model\CustomerSegmentInterface;
use CustomerManagementFrameworkBundle\SegmentManager\SegmentManagerInterface;
use Pimcore\Event\Model\DataObjectEvent;
use Pimcore\Event\Model\ElementEventInterface;
use Pimcore\Model\DataObject\LinkActivityDefinition;

class PimcoreObjectEventListener
{
    /**
     * @var CustomerSaveManagerInterface
     */
    protected $customerSaveManager;

    public function __construct(CustomerSaveManagerInterface $customerSaveManager)
    {
        $this->customerSaveManager = $customerSaveManager;
    }

    public function onPreUpdate(ElementEventInterface $e)
    {
        if (!$e instanceof DataObjectEvent) {
            return;
        }

        $object = $e->getObject();

        if ($object instanceof CustomerInterface) {
            $this->customerSaveManager->preUpdate($object);
        } elseif ($object instanceof CustomerSegmentInterface) {
            \Pimcore::getContainer()->get(SegmentManagerInterface::class)->preSegmentUpdate($object);
        }
    }

    public function onPostUpdate(ElementEventInterface $e)
    {
        if (!$e instanceof DataObjectEvent) {
            return;
        }

        $object = $e->getObject();

        if ($object instanceof CustomerInterface) {
            \Pimcore::getContainer()->get(CustomerSaveManagerInterface::class)->postUpdate($object);
        } elseif ($object instanceof AbstractObjectActivity) {
            $trackIt = true;
            if (!$object->cmfUpdateOnSave()) {
                if (\Pimcore::getContainer()->get('cmf.activity_store')->getEntryForActivity($object)) {
                    $trackIt = false;
                }
            }

            if ($trackIt) {
                \Pimcore::getContainer()->get('cmf.activity_manager')->trackActivity($object);
            }
        }
    }

    public function onPreAdd(ElementEventInterface $e)
    {
        if (!$e instanceof DataObjectEvent) {
            return;
        }

        $object = $e->getObject();

        if ($object instanceof CustomerInterface) {
            $this->customerSaveManager->preAdd($object);
        } elseif ($object instanceof LinkActivityDefinition) {
            $object->setCode(uniqid());
        }
    }

    public function onPostAdd(ElementEventInterface $e)
    {
        if (!$e instanceof DataObjectEvent) {
            return;
        }

        $object = $e->getObject();

        if ($object instanceof CustomerInterface) {
            $this->customerSaveManager->postAdd($object);
        }
    }

    public function onPreDelete(ElementEventInterface $e)
    {
        if (!$e instanceof DataObjectEvent) {
            return;
        }

        $object = $e->getObject();

        if ($object instanceof CustomerInterface) {
            $this->customerSaveManager->preDelete($object);
        }
    }

    public function onPostDelete(ElementEventInterface $e)
    {
        if (!$e instanceof DataObjectEvent) {
            return;
        }

        $object = $e->getObject();

        if ($object instanceof CustomerInterface) {
            $this->customerSaveManager->postDelete($object);
        } elseif ($object instanceof ActivityInterface) {
            \Pimcore::getContainer()->get('cmf.activity_manager')->deleteActivity($object);
        }
    }
}
