<?php

namespace srag\Plugins\SrAutoMails\Rule;

require_once __DIR__ . "/../../vendor/autoload.php";

use ilConfirmationGUI;
use ilSrAutoMailsPlugin;
use ilUtil;
use srag\DIC\SrAutoMails\DICTrait;
use srag\Notifications4Plugin\SrAutoMails\Notification\NotificationCtrl;
use srag\Notifications4Plugin\SrAutoMails\Notification\NotificationsCtrl;
use srag\Plugins\SrAutoMails\Utils\SrAutoMailsTrait;

/**
 * Class RuleMailConfigGUI
 *
 * @package           srag\Plugins\SrAutoMails\Rule
 *
 * @ilCtrl_isCalledBy srag\Plugins\SrAutoMails\Rule\RuleMailConfigGUI: srag\Plugins\SrAutoMails\Rule\RulesMailConfigGUI
 * @ilCtrl_isCalledBy srag\Notifications4Plugin\SrAutoMails\Notification\NotificationsCtrl: srag\Plugins\SrAutoMails\Rule\RuleMailConfigGUI
 */
class RuleMailConfigGUI
{

    use DICTrait;
    use SrAutoMailsTrait;

    const CMD_ADD_RULE = "addRule";
    const CMD_BACK = "back";
    const CMD_CREATE_RULE = "createRule";
    const CMD_EDIT_RULE = "editRule";
    const CMD_REMOVE_RULE = "removeRule";
    const CMD_REMOVE_RULE_CONFIRM = "removeRuleConfirm";
    const CMD_UPDATE_RULE = "updateRule";
    const GET_PARAM_RULE_ID = "rule_id";
    const PLUGIN_CLASS_NAME = ilSrAutoMailsPlugin::class;
    const TAB_EDIT_RULE = "edit_rule";
    /**
     * @var RulesMailConfigGUI
     */
    protected $parent;
    /**
     * @var Rule
     */
    protected $rule;


    /**
     * RuleMailConfigGUI constructor
     *
     * @param RulesMailConfigGUI $parent
     */
    public function __construct(RulesMailConfigGUI $parent)
    {
        $this->parent = $parent;
    }


    /**
     *
     */
    public function executeCommand()/*: void*/
    {
        $this->rule = self::srAutoMails()->rules()->getRuleById(intval(filter_input(INPUT_GET, self::GET_PARAM_RULE_ID)));

        self::dic()->ctrl()->saveParameter($this, self::GET_PARAM_RULE_ID);

        $this->setTabs();

        $next_class = self::dic()->ctrl()->getNextClass($this);

        switch (strtolower($next_class)) {
            case strtolower(NotificationsCtrl::class):
                if (self::dic()->ctrl()->getCmd() === NotificationsCtrl::CMD_LIST_NOTIFICATIONS) {
                    self::dic()->ctrl()->redirect($this, self::CMD_EDIT_RULE);

                    return;
                }
                self::dic()->tabs()->activateTab(self::TAB_EDIT_RULE);
                self::dic()->tabs()->activateSubTab(NotificationsCtrl::TAB_NOTIFICATIONS);
                self::dic()->ctrl()->forwardCommand(new NotificationsCtrl());
                break;

            default:
                $cmd = self::dic()->ctrl()->getCmd();

                switch ($cmd) {
                    case self::CMD_ADD_RULE:
                    case self::CMD_BACK:
                    case self::CMD_CREATE_RULE:
                    case self::CMD_EDIT_RULE:
                    case self::CMD_REMOVE_RULE:
                    case self::CMD_REMOVE_RULE_CONFIRM:
                    case self::CMD_UPDATE_RULE:
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
    protected function addRule()/*: void*/
    {
        $form = self::srAutoMails()->rules()->factory()->newFormInstance($this, $this->rule);

        self::output()->output($form);
    }


    /**
     *
     */
    protected function back()/*: void*/
    {
        self::dic()->ctrl()->redirectByClass(RulesMailConfigGUI::class, RulesMailConfigGUI::CMD_LIST_RULES);
    }


    /**
     *
     */
    protected function createRule()/*: void*/
    {
        $form = self::srAutoMails()->rules()->factory()->newFormInstance($this, $this->rule);

        if (!$form->storeForm()) {
            self::output()->output($form);

            return;
        }

        self::dic()->ctrl()->setParameter($this, self::GET_PARAM_RULE_ID, $this->rule->getRuleId());

        ilUtil::sendSuccess(self::plugin()->translate("added_rule", RulesMailConfigGUI::LANG_MODULE, [$this->rule->getTitle()]), true);

        self::dic()->ctrl()->redirect($this, self::CMD_EDIT_RULE);
    }


    /**
     *
     */
    protected function editRule()/*: void*/
    {
        self::dic()->tabs()->activateTab(self::TAB_EDIT_RULE);
        self::dic()->tabs()->activateSubTab(self::TAB_EDIT_RULE);

        $form = self::srAutoMails()->rules()->factory()->newFormInstance($this, $this->rule);

        self::output()->output($form);
    }


    /**
     *
     */
    protected function removeRule()/*: void*/
    {
        self::srAutoMails()->rules()->deleteRule($this->rule);

        ilUtil::sendSuccess(self::plugin()->translate("removed_rule", RulesMailConfigGUI::LANG_MODULE, [$this->rule->getTitle()]), true);

        self::dic()->ctrl()->redirect($this, self::CMD_BACK);
    }


    /**
     *
     */
    protected function removeRuleConfirm()/*: void*/
    {
        $confirmation = new ilConfirmationGUI();

        $confirmation->setFormAction(self::dic()->ctrl()->getFormAction($this));

        $confirmation->setHeaderText(self::plugin()->translate("remove_rule_confirm", RulesMailConfigGUI::LANG_MODULE, [$this->rule->getTitle()]));

        $confirmation->addItem(self::GET_PARAM_RULE_ID, $this->rule->getRuleId(), $this->rule->getTitle());

        $confirmation->setConfirm(self::plugin()->translate("remove", RulesMailConfigGUI::LANG_MODULE), self::CMD_REMOVE_RULE);
        $confirmation->setCancel(self::plugin()->translate("cancel", RulesMailConfigGUI::LANG_MODULE), self::CMD_BACK);

        self::output()->output($confirmation);
    }


    /**
     *
     */
    protected function setTabs()/*: void*/
    {
        self::dic()->tabs()->clearTargets();

        self::dic()->tabs()->setBackTarget(self::plugin()->translate("rules", RulesMailConfigGUI::LANG_MODULE), self::dic()->ctrl()
            ->getLinkTarget($this, self::CMD_BACK));

        if ($this->rule !== null) {
            $object_type_definiton = self::srAutoMails()->objectTypes()->factory()->getByObjectType($this->rule->getObjectType());

            if ($object_type_definiton !== null) {
                self::srAutoMails()->notifications4plugin()->withPlaceholderTypes($object_type_definiton->getMailPlaceholderKeyTypes());
            }

            if (self::dic()->ctrl()->getCmd() === self::CMD_REMOVE_RULE_CONFIRM) {
                self::dic()->tabs()->addTab(self::TAB_EDIT_RULE, self::plugin()->translate("remove_rule", RulesMailConfigGUI::LANG_MODULE), self::dic()->ctrl()
                    ->getLinkTarget($this, self::CMD_REMOVE_RULE_CONFIRM));
            } else {
                self::dic()->tabs()->addTab(self::TAB_EDIT_RULE, self::plugin()->translate("edit_rule", RulesMailConfigGUI::LANG_MODULE), self::dic()->ctrl()
                    ->getLinkTarget($this, self::CMD_EDIT_RULE));

                self::dic()->tabs()->addSubTab(self::TAB_EDIT_RULE, self::plugin()->translate("edit_rule", RulesMailConfigGUI::LANG_MODULE), self::dic()->ctrl()
                    ->getLinkTarget($this, self::CMD_EDIT_RULE));

                self::dic()
                    ->ctrl()
                    ->setParameterByClass(NotificationCtrl::class, NotificationCtrl::GET_PARAM_NOTIFICATION_ID,
                        self::srAutoMails()->notifications4plugin()->notifications()->getNotificationByName($this->rule->getMailTemplateName())->getId());

                self::dic()->tabs()->addSubTab(NotificationsCtrl::TAB_NOTIFICATIONS, self::plugin()->translate("notification", RulesMailConfigGUI::LANG_MODULE), self::dic()->ctrl()
                    ->getLinkTargetByClass([NotificationsCtrl::class, NotificationCtrl::class], NotificationCtrl::CMD_EDIT_NOTIFICATION));

                self::dic()->locator()->addItem($this->rule->getTitle(), self::dic()->ctrl()->getLinkTarget($this, self::CMD_EDIT_RULE));
            }
        } else {
            $this->rule = self::srAutoMails()->rules()->factory()->newInstance();

            self::dic()->tabs()->addTab(self::TAB_EDIT_RULE, self::plugin()->translate("add_rule", RulesMailConfigGUI::LANG_MODULE), self::dic()->ctrl()
                ->getLinkTarget($this, self::CMD_ADD_RULE));
        }
    }


    /**
     *
     */
    protected function updateRule()/*: void*/
    {
        self::dic()->tabs()->activateTab(self::TAB_EDIT_RULE);
        self::dic()->tabs()->activateSubTab(self::TAB_EDIT_RULE);

        $form = self::srAutoMails()->rules()->factory()->newFormInstance($this, $this->rule);

        if (!$form->storeForm()) {
            self::output()->output($form);

            return;
        }

        ilUtil::sendSuccess(self::plugin()->translate("saved_rule", RulesMailConfigGUI::LANG_MODULE, [$this->rule->getTitle()]), true);

        self::dic()->ctrl()->redirect($this, self::CMD_EDIT_RULE);
    }
}
