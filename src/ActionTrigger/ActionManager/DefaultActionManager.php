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

namespace CustomerManagementFrameworkBundle\ActionTrigger\ActionManager;

use CustomerManagementFrameworkBundle\ActionTrigger\Action\ActionDefinitionInterface;
use CustomerManagementFrameworkBundle\ActionTrigger\Action\ActionInterface;
use CustomerManagementFrameworkBundle\Factory;
use CustomerManagementFrameworkBundle\Model\CustomerInterface;
use CustomerManagementFrameworkBundle\Traits\LoggerAware;

class DefaultActionManager implements ActionManagerInterface
{
    use LoggerAware;

    public function processAction(ActionDefinitionInterface $action, CustomerInterface $customer)
    {
        $this->getLogger()->info(sprintf('process action ID %s', $action->getId()));

        if (class_exists($action->getImplementationClass())) {
            $actionImpl = Factory::getInstance()->createObject(
                $action->getImplementationClass(),
                ActionInterface::class,
                ['logger' => $this->getLogger()]
            );

            $actionImpl->process($action, $customer);
        } else {
            $this->getLogger()->error(
                sprintf('action implementation class %s not found', $action->getImplementationClass())
            );
        }
    }
}
