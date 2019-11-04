<?php

namespace srag\Plugins\SrAutoMails\Notification\Ctrl;

use ilSrAutoMailsPlugin;
use srag\Notifications4Plugin\SrAutoMails\Ctrl\AbstractCtrl;
use srag\Plugins\SrAutoMails\Notification\Notification\Language\NotificationLanguage;
use srag\Plugins\SrAutoMails\Notification\Notification\Notification;
use srag\Plugins\SrAutoMails\Rule\RulesConfigGUI;
use srag\Plugins\SrAutoMails\Utils\SrAutoMailsTrait;

/**
 * Class Notifications4PluginCtrl
 *
 * @package           srag\Plugins\SrAutoMails\Notification\Ctrl
 *
 * @author            studer + raimann ag - Team Custom 1 <support-custom1@studer-raimann.ch>
 *
 * @ilCtrl_isCalledBy srag\Plugins\SrAutoMails\Notification\Ctrl\Notifications4PluginCtrl: srag\Plugins\SrAutoMails\Rule\RulesConfigGUI
 */
class Notifications4PluginCtrl extends AbstractCtrl
{

    use SrAutoMailsTrait;
    const NOTIFICATION_CLASS_NAME = Notification::class;
    const LANGUAGE_CLASS_NAME = NotificationLanguage::class;
    const PLUGIN_CLASS_NAME = ilSrAutoMailsPlugin::class;


    /**
     * @inheritdoc
     */
    public function executeCommand()/*: void*/
    {
        $rule_id = intval(filter_input(INPUT_GET, RulesConfigGUI::GET_PARAM_RULE_ID));
        $rule = self::rules()->getRuleById($rule_id);
        (new RulesConfigGUI())->getRuleForm($rule);

        self::dic()->tabs()->activateSubTab(RulesConfigGUI::TAB_NOTIFICATION);

        parent::executeCommand();
    }


    /**
     * @inheritdoc
     */
    protected function listNotifications()/*: void*/
    {
        self::dic()->ctrl()->redirectByClass(RulesConfigGUI::class, RulesConfigGUI::CMD_EDIT_RULE);
    }


    /**
     * @inheritdoc
     */
    public function getPlaceholderTypes() : array
    {
        $rule_id = intval(filter_input(INPUT_GET, RulesConfigGUI::GET_PARAM_RULE_ID));
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
