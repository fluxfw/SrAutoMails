<?php

namespace srag\Plugins\SrAutoMails\Rule;

use ilConfirmationGUI;
use ilSrAutoMailsPlugin;
use ilUtil;
use srag\DIC\SrAutoMails\DICTrait;
use srag\Notifications4Plugin\SrAutoMails\Utils\Notifications4PluginTrait;
use srag\Plugins\SrAutoMails\Notification\Ctrl\Notifications4PluginCtrl;
use srag\Plugins\SrAutoMails\Notification\Notification\Language\NotificationLanguage;
use srag\Plugins\SrAutoMails\Notification\Notification\Notification;
use srag\Plugins\SrAutoMails\Utils\SrAutoMailsTrait;

/**
 * Class RulesMailConfigGUI
 *
 * @package           srag\Plugins\SrAutoMails\Rule
 *
 * @author            studer + raimann ag - Team Custom 1 <support-custom1@studer-raimann.ch>
 *
 * @ilCtrl_isCalledBy srag\Plugins\SrAutoMails\Rule\RulesMailConfigGUI: ilSrAutoMailsConfigGUI
 */
class RulesMailConfigGUI
{

    use DICTrait;
    use SrAutoMailsTrait;
    use Notifications4PluginTrait;
    const PLUGIN_CLASS_NAME = ilSrAutoMailsPlugin::class;
    const TAB_RULES = "rules";
    const TAB_RULE = "rule";
    const TAB_NOTIFICATION = Notifications4PluginCtrl::TAB_NOTIFICATION;
    const CMD_LIST_RULES = "listRules";
    const CMD_ADD_RULE = "addRule";
    const CMD_CREATE_RULE = "createRule";
    const CMD_EDIT_RULE = "editRule";
    const CMD_UPDATE_RULE = "updateRule";
    const CMD_REMOVE_RULE_CONFIRM = "removeRuleConfirm";
    const CMD_REMOVE_RULE = "removeRule";
    const CMD_ENABLE_RULES = "enableRules";
    const CMD_DISABLE_RULES = "disableRules";
    const CMD_REMOVE_RULES_CONFIRM = "removeRulesConfirm";
    const CMD_REMOVE_RULES = "removeRules";
    const GET_PARAM_RULE_ID = "rule_id";
    const LANG_MODULE_RULES = "rules";


    /**
     * RulesMailConfigGUI constructor
     */
    public function __construct()
    {

    }


    /**
     *
     */
    public function executeCommand()/*: void*/
    {
        $this->setTabs();

        $next_class = self::dic()->ctrl()->getNextClass($this);

        switch (strtolower($next_class)) {
            case strtolower(Notifications4PluginCtrl::class):
                self::dic()->ctrl()->forwardCommand(new Notifications4PluginCtrl());
                break;

            default:
                $cmd = self::dic()->ctrl()->getCmd();

                switch ($cmd) {
                    case self::CMD_LIST_RULES:
                    case self::CMD_ADD_RULE:
                    case self::CMD_CREATE_RULE:
                    case self::CMD_EDIT_RULE:
                    case self::CMD_UPDATE_RULE:
                    case self::CMD_REMOVE_RULE_CONFIRM:
                    case self::CMD_REMOVE_RULE:
                    case self::CMD_ENABLE_RULES:
                    case self::CMD_DISABLE_RULES:
                    case self::CMD_REMOVE_RULES_CONFIRM:
                    case self::CMD_REMOVE_RULES:
                        $this->{$cmd}();
                        break;

                    default:
                        break;
                }
                break;
        }
    }


    /**
     *
     */
    protected function setTabs()/*: void*/
    {
        self::dic()->tabs()->activateTab(self::TAB_RULES);
    }


    /**
     * @param string $cmd
     *
     * @return RulesTableGUI
     */
    protected function getRulesTable(string $cmd = self::CMD_LIST_RULES) : RulesTableGUI
    {
        $table = new RulesTableGUI($this, $cmd);

        return $table;
    }


    /**
     *
     */
    protected function listRules()/*: void*/
    {
        $table = $this->getRulesTable();

        self::output()->output($table);
    }


    /**
     * @param Rule $rule
     *
     * @return RuleFormGUI
     */
    public function getRuleForm(Rule $rule) : RuleFormGUI
    {
        self::dic()->ctrl()->saveParameter($this, self::GET_PARAM_RULE_ID);

        $form = new RuleFormGUI($this, $rule);

        if (!empty($rule->getRuleId())) {
            if (empty($rule->getMailTemplateName())) {
                $rule->setMailTemplateName("rule_" . $rule->getRuleId());

                self::srAutoMails()->rules()->storeRule($rule);
            }

            $notification = self::notification(Notification::class, NotificationLanguage::class)->getNotificationByName($rule->getMailTemplateName());

            if ($notification === null) {
                $notification = self::notification(Notification::class, NotificationLanguage::class)->factory()->newInstance();

                $notification->setName($rule->getMailTemplateName());

                self::notification(Notification::class, NotificationLanguage::class)->storeInstance($notification);
            }

            self::dic()->ctrl()->setParameterByClass(Notifications4PluginCtrl::class, Notifications4PluginCtrl::GET_PARAM, $notification->getId());

            self::dic()->tabs()->addSubTab(self::TAB_RULE, self::plugin()->translate(self::TAB_RULE, self::LANG_MODULE_RULES), self::dic()->ctrl()
                ->getLinkTarget($this, self::CMD_EDIT_RULE));
            self::dic()->tabs()->activateSubTab(self::TAB_RULE);

            self::dic()->tabs()->addSubTab(Notifications4PluginCtrl::TAB_NOTIFICATION, self::plugin()->translate(self::TAB_NOTIFICATION, self::LANG_MODULE_RULES), self::dic()->ctrl()
                ->getLinkTargetByClass(Notifications4PluginCtrl::class, Notifications4PluginCtrl::CMD_EDIT_NOTIFICATION));
        }

        return $form;
    }


    /**
     *
     */
    protected function addRule()/*: void*/
    {
        $form = $this->getRuleForm(self::srAutoMails()->rules()->factory()->newInstance());

        self::output()->output($form);
    }


    /**
     *
     */
    protected function createRule()/*: void*/
    {
        $form = $this->getRuleForm(self::srAutoMails()->rules()->factory()->newInstance());

        if (!$form->storeForm()) {
            self::output()->output($form);

            return;
        }

        ilUtil::sendSuccess(self::plugin()->translate("added_rule", self::LANG_MODULE_RULES, [$form->getObject()->getTitle()]), true);

        self::dic()->ctrl()->setParameter($this, self::GET_PARAM_RULE_ID, $form->getObject()->getRuleId());

        self::dic()->ctrl()->redirect($this, self::CMD_EDIT_RULE);
    }


    /**
     *
     */
    protected function editRule()/*: void*/
    {
        $rule_id = intval(filter_input(INPUT_GET, self::GET_PARAM_RULE_ID));
        $rule = self::srAutoMails()->rules()->getRuleById($rule_id);

        $form = $this->getRuleForm($rule);

        self::output()->output($form);
    }


    /**
     *
     */
    protected function updateRule()/*: void*/
    {
        $rule_id = intval(filter_input(INPUT_GET, self::GET_PARAM_RULE_ID));
        $rule = self::srAutoMails()->rules()->getRuleById($rule_id);

        $form = $this->getRuleForm($rule);

        if (!$form->storeForm()) {
            self::output()->output($form);

            return;
        }

        ilUtil::sendSuccess(self::plugin()->translate("saved_rule", self::LANG_MODULE_RULES, [$rule->getTitle()]), true);

        self::dic()->ctrl()->redirect($this, self::CMD_LIST_RULES);
    }


    /**
     *
     */
    protected function removeRuleConfirm()/*: void*/
    {
        $rule_id = intval(filter_input(INPUT_GET, self::GET_PARAM_RULE_ID));
        $rule = self::srAutoMails()->rules()->getRuleById($rule_id);

        $confirmation = new ilConfirmationGUI();

        self::dic()->ctrl()->setParameter($this, self::GET_PARAM_RULE_ID, $rule->getRuleId());
        $confirmation->setFormAction(self::dic()->ctrl()->getFormAction($this));
        self::dic()->ctrl()->setParameter($this, self::GET_PARAM_RULE_ID, null);

        $confirmation->setHeaderText(self::plugin()->translate("remove_rule_confirm", self::LANG_MODULE_RULES, [$rule->getTitle()]));

        $confirmation->addItem(self::GET_PARAM_RULE_ID, $rule->getRuleId(), $rule->getTitle());

        $confirmation->setConfirm(self::plugin()->translate("remove", self::LANG_MODULE_RULES), self::CMD_REMOVE_RULE);
        $confirmation->setCancel(self::plugin()->translate("cancel", self::LANG_MODULE_RULES), self::CMD_LIST_RULES);

        self::output()->output($confirmation);
    }


    /**
     *
     */
    protected function removeRule()/*: void*/
    {
        $rule_id = intval(filter_input(INPUT_GET, self::GET_PARAM_RULE_ID));
        $rule = self::srAutoMails()->rules()->getRuleById($rule_id);

        self::srAutoMails()->rules()->deleteRule($rule);

        ilUtil::sendSuccess(self::plugin()->translate("removed_rule", self::LANG_MODULE_RULES, [$rule->getTitle()]), true);

        self::dic()->ctrl()->redirect($this, self::CMD_LIST_RULES);
    }


    /**
     *
     */
    protected function enableRules()/*: void*/
    {
        $rule_ids = filter_input(INPUT_POST, self::GET_PARAM_RULE_ID, FILTER_DEFAULT, FILTER_FORCE_ARRAY);

        /**
         * @var Rule[] $rules
         */
        $rules = array_map(function (int $rule_id)/*: ?Rule*/ {
            return self::srAutoMails()->rules()->getRuleById($rule_id);
        }, $rule_ids);

        foreach ($rules as $rule) {
            $rule->setEnabled(true);

            self::srAutoMails()->rules()->storeRule($rule);
        }

        ilUtil::sendSuccess(self::plugin()->translate("enabled_rules", self::LANG_MODULE_RULES), true);

        self::dic()->ctrl()->redirect($this, self::CMD_LIST_RULES);
    }


    /**
     *
     */
    protected function disableRules()/*: void*/
    {
        $rule_ids = filter_input(INPUT_POST, self::GET_PARAM_RULE_ID, FILTER_DEFAULT, FILTER_FORCE_ARRAY);

        /**
         * @var Rule[] $rules
         */
        $rules = array_map(function (int $rule_id)/*: ?Rule*/ {
            return self::srAutoMails()->rules()->getRuleById($rule_id);
        }, $rule_ids);

        foreach ($rules as $rule) {
            $rule->setEnabled(false);

            self::srAutoMails()->rules()->storeRule($rule);
        }

        ilUtil::sendSuccess(self::plugin()->translate("disabled_rules", self::LANG_MODULE_RULES), true);

        self::dic()->ctrl()->redirect($this, self::CMD_LIST_RULES);
    }


    /**
     *
     */
    protected function removeRulesConfirm()/*: void*/
    {
        $rule_ids = filter_input(INPUT_POST, self::GET_PARAM_RULE_ID, FILTER_DEFAULT, FILTER_FORCE_ARRAY);

        /**
         * @var Rule[] $rules
         */
        $rules = array_map(function (int $rule_id)/*: ?Rule*/ {
            return self::srAutoMails()->rules()->getRuleById($rule_id);
        }, $rule_ids);

        $confirmation = new ilConfirmationGUI();

        $confirmation->setFormAction(self::dic()->ctrl()->getFormAction($this));

        $confirmation->setHeaderText(self::plugin()->translate("remove_rules_confirm", self::LANG_MODULE_RULES));

        foreach ($rules as $rule) {
            $confirmation->addItem(self::GET_PARAM_RULE_ID . "[]", $rule->getRuleId(), $rule->getTitle());
        }

        $confirmation->setConfirm(self::plugin()->translate("remove", self::LANG_MODULE_RULES), self::CMD_REMOVE_RULES);
        $confirmation->setCancel(self::plugin()->translate("cancel", self::LANG_MODULE_RULES), self::CMD_LIST_RULES);

        self::output()->output($confirmation);
    }


    /**
     *
     */
    protected function removeRules()/*: void*/
    {
        $rule_ids = filter_input(INPUT_POST, self::GET_PARAM_RULE_ID, FILTER_DEFAULT, FILTER_FORCE_ARRAY);

        /**
         * @var Rule[] $rules
         */
        $rules = array_map(function (int $rule_id)/*: ?Rule*/ {
            return self::srAutoMails()->rules()->getRuleById($rule_id);
        }, $rule_ids);

        foreach ($rules as $rule) {
            self::srAutoMails()->rules()->deleteRule($rule);
        }

        ilUtil::sendSuccess(self::plugin()->translate("removed_rules", self::LANG_MODULE_RULES), true);

        self::dic()->ctrl()->redirect($this, self::CMD_LIST_RULES);
    }
}
