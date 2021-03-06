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

namespace CustomerManagementFrameworkBundle\ActionTrigger\Action;

use Psr\Log\LoggerInterface;

abstract class AbstractAction implements ActionInterface
{
    protected $logger;

    protected static $actionDelayMultiplier = [
        'm' => 1,
        'h' => 60,
        'd' => 60 * 24,
    ];

    public function __construct(LoggerInterface $logger)
    {
        $this->logger = $logger;
    }

    public static function createActionDefinitionFromEditmode(\stdClass $setting)
    {
        $actionDelayMultiplier = isset(self::$actionDelayMultiplier[$setting->options->actionDelayGuiType]) ? self::$actionDelayMultiplier[$setting->options->actionDelayGuiType] : 1;

        $action = new \CustomerManagementFrameworkBundle\Model\ActionTrigger\ActionDefinition();
        $action->setId($setting->id);
        $action->setCreationDate($setting->creationDate);
        $action->setOptions(json_decode(json_encode($setting->options), true));
        $action->setImplementationClass($setting->implementationClass);
        $action->setActionDelay($setting->options->actionDelayGuiValue * $actionDelayMultiplier);

        return $action;
    }

    public static function getDataForEditmode(ActionDefinitionInterface $actionDefinition)
    {
        return $actionDefinition->toArray();
    }
}
