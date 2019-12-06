<?php

namespace srag\Plugins\SrAutoMails\Notification\Ctrl;

use ilSrAutoMailsPlugin;
use srag\Notifications4Plugin\SrAutoMails\Ctrl\AbstractCtrl;
use srag\Plugins\SrAutoMails\Notification\Notification\Language\NotificationLanguage;
use srag\Plugins\SrAutoMails\Notification\Notification\Notification;
use srag\Plugins\SrAutoMails\Rule\RulesMailConfigGUI;
use srag\Plugins\SrAutoMails\Utils\SrAutoMailsTrait;

/**
 * Class Notifications4PluginCtrl
 *
 * @package           srag\Plugins\SrAutoMails\Notification\Ctrl
 *
 * @author            studer + raimann ag - Team Custom 1 <support-custom1@studer-raimann.ch>
 *
 * @ilCtrl_isCalledBy srag\Plugins\SrAutoMails\Notification\Ctrl\Notifications4PluginCtrl: srag\Plugins\SrAutoMails\Rule\RulesMailConfigGUI
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
        $rule_id = intval(filter_input(INPUT_GET, RulesMailConfigGUI::GET_PARAM_RULE_ID));
        $rule = self::srAutoMails()->rules()->getRuleById($rule_id);
        (new RulesMailConfigGUI())->getRuleForm($rule);

        self::dic()->tabs()->activateSubTab(RulesMailConfigGUI::TAB_NOTIFICATION);

        parent::executeCommand();
    }


    /**
     * @inheritdoc
     */
    protected function listNotifications()/*: void*/
    {
        self::dic()->ctrl()->redirectByClass(RulesMailConfigGUI::class, RulesMailConfigGUI::CMD_EDIT_RULE);
    }


    /**
     * @inheritdoc
     */
    public function getPlaceholderTypes() : array
    {
        $rule_id = intval(filter_input(INPUT_GET, RulesMailConfigGUI::GET_PARAM_RULE_ID));
        $rule = self::srAutoMails()->rules()->getRuleById($rule_id);

        if ($rule !== null) {

            $object_type_definiton = self::srAutoMails()->objectTypes()->factory()->getByObjectType($rule->getObjectType());

            if ($object_type_definiton !== null) {
                return $object_type_definiton->getMailPlaceholderKeyTypes();
            }
        }

        return [];
    }
}
