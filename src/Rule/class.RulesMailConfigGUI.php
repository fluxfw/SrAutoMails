<?php

namespace srag\Plugins\SrAutoMails\Rule;

use ilConfirmationGUI;
use ilSrAutoMailsPlugin;
use ilUtil;
use srag\DIC\SrAutoMails\DICTrait;
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

    const PLUGIN_CLASS_NAME = ilSrAutoMailsPlugin::class;
    const CMD_APPLY_FILTER = "applyFilter";
    const CMD_DISABLE_RULES = "disableRules";
    const CMD_ENABLE_RULES = "enableRules";
    const CMD_LIST_RULES = "listRules";
    const CMD_REMOVE_RULES = "removeRules";
    const CMD_REMOVE_RULES_CONFIRM = "removeRulesConfirm";
    const CMD_RESET_FILTER = "resetFilter";
    const LANG_MODULE = "rules";
    const TAB_LIST_RULES = "list_rules";


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
            case strtolower(RuleMailConfigGUI::class):
                self::dic()->ctrl()->forwardCommand(new RuleMailConfigGUI($this));
                break;

            default:
                $cmd = self::dic()->ctrl()->getCmd();

                switch ($cmd) {
                    case self::CMD_APPLY_FILTER:
                    case self::CMD_DISABLE_RULES:
                    case self::CMD_ENABLE_RULES:
                    case self::CMD_LIST_RULES:
                    case self::CMD_REMOVE_RULES:
                    case self::CMD_REMOVE_RULES_CONFIRM:
                    case self::CMD_RESET_FILTER:
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
    public static function addTabs()/*: void*/
    {
        self::dic()->tabs()->addTab(self::TAB_LIST_RULES, self::plugin()->translate("rules", self::LANG_MODULE), self::dic()->ctrl()
            ->getLinkTargetByClass(self::class, self::CMD_LIST_RULES));
    }


    /**
     *
     */
    protected function setTabs()/*: void*/
    {

    }


    /**
     *
     */
    protected function listRules()/*: void*/
    {
        $table = self::srAutoMails()->rules()->factory()->newTableInstance($this);

        self::output()->output($table);
    }


    /**
     *
     */
    protected function applyFilter()/*: void*/
    {
        $table = self::srAutoMails()->rules()->factory()->newTableInstance($this, self::CMD_APPLY_FILTER);

        $table->writeFilterToSession();

        $table->resetOffset();

        //$this->redirect(self::CMD_LIST_RULES);
        $this->listRules(); // Fix reset offset
    }


    /**
     *
     */
    protected function resetFilter()/*: void*/
    {
        $table = self::srAutoMails()->rules()->factory()->newTableInstance($this, self::CMD_RESET_FILTER);

        $table->resetOffset();

        $table->resetFilter();

        //$this->redirect(self::CMD_LIST_RULES);
        $this->listRules(); // Fix reset offset
    }


    /**
     *
     */
    protected function enableRules()/*: void*/
    {
        $rule_ids = filter_input(INPUT_POST, RuleMailConfigGUI::GET_PARAM_RULE_ID, FILTER_DEFAULT, FILTER_FORCE_ARRAY);

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

        ilUtil::sendSuccess(self::plugin()->translate("enabled_rules", self::LANG_MODULE), true);

        self::dic()->ctrl()->redirect($this, self::CMD_LIST_RULES);
    }


    /**
     *
     */
    protected function disableRules()/*: void*/
    {
        $rule_ids = filter_input(INPUT_POST, RuleMailConfigGUI::GET_PARAM_RULE_ID, FILTER_DEFAULT, FILTER_FORCE_ARRAY);

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

        ilUtil::sendSuccess(self::plugin()->translate("disabled_rules", self::LANG_MODULE), true);

        self::dic()->ctrl()->redirect($this, self::CMD_LIST_RULES);
    }


    /**
     *
     */
    protected function removeRulesConfirm()/*: void*/
    {
        $rule_ids = filter_input(INPUT_POST, RuleMailConfigGUI::GET_PARAM_RULE_ID, FILTER_DEFAULT, FILTER_FORCE_ARRAY);

        /**
         * @var Rule[] $rules
         */
        $rules = array_map(function (int $rule_id)/*: ?Rule*/ {
            return self::srAutoMails()->rules()->getRuleById($rule_id);
        }, $rule_ids);

        $confirmation = new ilConfirmationGUI();

        $confirmation->setFormAction(self::dic()->ctrl()->getFormAction($this));

        $confirmation->setHeaderText(self::plugin()->translate("remove_rules_confirm", self::LANG_MODULE));

        foreach ($rules as $rule) {
            $confirmation->addItem(RuleMailConfigGUI::GET_PARAM_RULE_ID . "[]", $rule->getRuleId(), $rule->getTitle());
        }

        $confirmation->setConfirm(self::plugin()->translate("remove", self::LANG_MODULE), self::CMD_REMOVE_RULES);
        $confirmation->setCancel(self::plugin()->translate("cancel", self::LANG_MODULE), self::CMD_LIST_RULES);

        self::output()->output($confirmation);
    }


    /**
     *
     */
    protected function removeRules()/*: void*/
    {
        $rule_ids = filter_input(INPUT_POST, RuleMailConfigGUI::GET_PARAM_RULE_ID, FILTER_DEFAULT, FILTER_FORCE_ARRAY);

        /**
         * @var Rule[] $rules
         */
        $rules = array_map(function (int $rule_id)/*: ?Rule*/ {
            return self::srAutoMails()->rules()->getRuleById($rule_id);
        }, $rule_ids);

        foreach ($rules as $rule) {
            self::srAutoMails()->rules()->deleteRule($rule);
        }

        ilUtil::sendSuccess(self::plugin()->translate("removed_rules", self::LANG_MODULE), true);

        self::dic()->ctrl()->redirect($this, self::CMD_LIST_RULES);
    }
}
