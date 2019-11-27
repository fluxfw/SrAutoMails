<?php

namespace srag\Plugins\SrAutoMails\Notification;

use ilSrAutoMailsPlugin;
use srag\Notifications4Plugin\SrAutoMails\Notification\AbstractNotificationCtrl;
use srag\Plugins\SrAutoMails\Rule\RulesMailConfigGUI;
use srag\Plugins\SrAutoMails\Utils\SrAutoMailsTrait;

/**
 * Class NotificationCtrl
 *
 * @package           srag\Plugins\SrAutoMails\Notification
 *
 * @author            studer + raimann ag - Team Custom 1 <support-custom1@studer-raimann.ch>
 *
 * @ilCtrl_isCalledBy srag\Plugins\SrAutoMails\Notification\NotificationCtrl: ilSrAutoMailsConfigGUI
 */
class NotificationCtrl extends AbstractNotificationCtrl
{

    use SrAutoMailsTrait;
    const PLUGIN_CLASS_NAME = ilSrAutoMailsPlugin::class;


    /**
     * @inheritdoc
     */
    public function executeCommand()/*: void*/
    {
        $rule_id = intval(filter_input(INPUT_GET, RulesMailConfigGUI::GET_PARAM_RULE_ID));
        $rule = self::rules()->getRuleById($rule_id);
        (new RulesMailConfigGUI())->getRuleForm($rule);

        self::dic()->tabs()->activateSubTab(RulesMailConfigGUI::TAB_NOTIFICATION);

        parent::executeCommand();
    }


    /**
     * @inheritdoc
     */
    public function getPlaceholderTypes() : array
    {
        $rule_id = intval(filter_input(INPUT_GET, RulesMailConfigGUI::GET_PARAM_RULE_ID));
        $rule = self::rules()->getRuleById($rule_id);

        if ($rule !== null) {

            $object_type_definiton = self::objectTypes()->factory()->getByObjectType($rule->getObjectType());

            if ($object_type_definiton !== null) {
                return $object_type_definiton->getMailPlaceholderKeyTypes();
            }
        }

        return [];
    }
}
