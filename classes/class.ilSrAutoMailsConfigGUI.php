<?php

require_once __DIR__ . "/../vendor/autoload.php";

use srag\ActiveRecordConfig\SrAutoMails\ActiveRecordConfigGUI;
use srag\Notifications4Plugin\SrAutoMails\Utils\Notifications4PluginTrait;
use srag\Plugins\SrAutoMails\Notification\Ctrl\Notifications4PluginCtrl;
use srag\Plugins\SrAutoMails\Notification\Notification\Language\NotificationLanguage;
use srag\Plugins\SrAutoMails\Notification\Notification\Notification;
use srag\Plugins\SrAutoMails\Rule\Rule;
use srag\Plugins\SrAutoMails\Rule\RuleFormGUI;
use srag\Plugins\SrAutoMails\Rule\RulesTableGUI;
use srag\Plugins\SrAutoMails\Utils\SrAutoMailsTrait;

/**
 * Class ilSrAutoMailsConfigGUI
 *
 * @author studer + raimann ag - Team Custom 1 <support-custom1@studer-raimann.ch>
 */
class ilSrAutoMailsConfigGUI extends ActiveRecordConfigGUI {

	use SrAutoMailsTrait;
	use Notifications4PluginTrait;
	const PLUGIN_CLASS_NAME = ilSrAutoMailsPlugin::class;
	const TAB_RULES = "rules";
	const TAB_RULE = "rule";
	const TAB_NOTIFICATION = Notifications4PluginCtrl::TAB_NOTIFICATION;
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
	/**
	 * @var array
	 */
	protected static $tabs = [ self::TAB_RULES => RulesTableGUI::class ];
	/**
	 * @var array
	 */
	protected static $custom_commands = [
		self::CMD_ADD_RULE,
		self::CMD_CREATE_RULE,
		self::CMD_EDIT_RULE,
		self::CMD_UPDATE_RULE,
		self::CMD_REMOVE_RULE,
		self::CMD_REMOVE_RULE_CONFIRM,
		self::CMD_REMOVE_RULES_CONFIRM,
		self::CMD_ENABLE_RULES,
		self::CMD_DISABLE_RULES,
		self::CMD_REMOVE_RULES
	];


	/**
	 * @param Rule $rule
	 *
	 * @return RuleFormGUI
	 */
	public function getRuleForm(Rule $rule): RuleFormGUI {
		self::dic()->ctrl()->saveParameter($this, self::GET_PARAM_RULE_ID);

		$form = new RuleFormGUI($this, self::TAB_RULES, $rule);

		if (!empty($rule->getRuleId())) {
			if (empty($rule->getMailTemplateName())) {
				$rule->setMailTemplateName("rule_" . $rule->getRuleId());

				self::rules()->storeRule($rule);
			}

			$notification = self::notification(Notification::class, NotificationLanguage::class)->getNotificationByName($rule->getMailTemplateName());

			if ($notification === null) {
				$notification = self::notification(Notification::class, NotificationLanguage::class)->factory()->newInstance();

				$notification->setName($rule->getMailTemplateName());

				self::notification(Notification::class, NotificationLanguage::class)->storeInstance($notification);
			}

			self::dic()->ctrl()->setParameterByClass(Notifications4PluginCtrl::class, Notifications4PluginCtrl::GET_PARAM, $notification->getId());

			self::dic()->tabs()->addSubTab(self::TAB_RULE, $this->txt(self::TAB_RULE), self::dic()->ctrl()
				->getLinkTarget($this, self::CMD_EDIT_RULE));
			self::dic()->tabs()->activateSubTab(self::TAB_RULE);

			self::dic()->tabs()->addSubTab(Notifications4PluginCtrl::TAB_NOTIFICATION, $this->txt(self::TAB_NOTIFICATION), self::dic()->ctrl()
				->getLinkTargetByClass(Notifications4PluginCtrl::class, Notifications4PluginCtrl::CMD_EDIT_NOTIFICATION));
		}

		return $form;
	}


	/**
	 *
	 */
	protected function addRule()/*: void*/ {
		self::dic()->tabs()->activateTab(self::TAB_RULES);

		$form = $this->getRuleForm(self::rules()->factory()->newInstance());

		self::output()->output($form);
	}


	/**
	 *
	 */
	protected function createRule()/*: void*/ {
		self::dic()->tabs()->activateTab(self::TAB_RULES);

		$form = $this->getRuleForm(self::rules()->factory()->newInstance());

		if (!$form->storeForm()) {
			self::output()->output($form);

			return;
		}

		ilUtil::sendSuccess(self::plugin()->translate("added_rule", self::LANG_MODULE_CONFIG, [ $form->getObject()->getTitle() ]), true);

		self::dic()->ctrl()->setParameter($this, self::GET_PARAM_RULE_ID, $form->getObject()->getRuleId());

		self::dic()->ctrl()->redirect($this, self::CMD_EDIT_RULE);
	}


	/**
	 *
	 */
	protected function editRule()/*: void*/ {
		self::dic()->tabs()->activateTab(self::TAB_RULES);

		$rule_id = intval(filter_input(INPUT_GET, self::GET_PARAM_RULE_ID));
		$rule = self::rules()->getRuleById($rule_id);

		$form = $this->getRuleForm($rule);

		self::output()->output($form);
	}


	/**
	 *
	 */
	protected function updateRule()/*: void*/ {
		self::dic()->tabs()->activateTab(self::TAB_RULES);

		$rule_id = intval(filter_input(INPUT_GET, self::GET_PARAM_RULE_ID));
		$rule = self::rules()->getRuleById($rule_id);

		$form = $this->getRuleForm($rule);

		if (!$form->storeForm()) {
			self::output()->output($form);

			return;
		}

		ilUtil::sendSuccess(self::plugin()->translate("saved_rule", self::LANG_MODULE_CONFIG, [ $rule->getTitle() ]), true);

		$this->redirectToTab(self::TAB_RULES);
	}


	/**
	 *
	 */
	protected function removeRuleConfirm()/*: void*/ {
		self::dic()->tabs()->activateTab(self::TAB_RULES);

		$rule_id = intval(filter_input(INPUT_GET, self::GET_PARAM_RULE_ID));
		$rule = self::rules()->getRuleById($rule_id);

		$confirmation = new ilConfirmationGUI();

		self::dic()->ctrl()->setParameter($this, self::GET_PARAM_RULE_ID, $rule->getRuleId());
		$confirmation->setFormAction(self::dic()->ctrl()->getFormAction($this));
		self::dic()->ctrl()->setParameter($this, self::GET_PARAM_RULE_ID, null);

		$confirmation->setHeaderText(self::plugin()->translate("remove_rule_confirm", self::LANG_MODULE_CONFIG, [ $rule->getTitle() ]));

		$confirmation->addItem(self::GET_PARAM_RULE_ID, $rule->getRuleId(), $rule->getTitle());

		$confirmation->setConfirm($this->txt("remove"), self::CMD_REMOVE_RULE);
		$confirmation->setCancel($this->txt("cancel"), $this->getCmdForTab(self::TAB_RULES));

		self::output()->output($confirmation);
	}


	/**
	 *
	 */
	protected function removeRule()/*: void*/ {
		$rule_id = intval(filter_input(INPUT_GET, self::GET_PARAM_RULE_ID));
		$rule = self::rules()->getRuleById($rule_id);

		self::rules()->deleteRule($rule);

		ilUtil::sendSuccess(self::plugin()->translate("removed_rule", self::LANG_MODULE_CONFIG, [ $rule->getTitle() ]), true);

		$this->redirectToTab(self::TAB_RULES);
	}


	/**
	 *
	 */
	protected function enableRules()/*: void*/ {
		$rule_ids = filter_input(INPUT_POST, self::GET_PARAM_RULE_ID, FILTER_DEFAULT, FILTER_FORCE_ARRAY);

		/**
		 * @var Rule[] $rules
		 */
		$rules = array_map(function (int $rule_id)/*: ?Rule*/ {
			return self::rules()->getRuleById($rule_id);
		}, $rule_ids);

		foreach ($rules as $rule) {
			$rule->setEnabled(true);

			self::rules()->storeRule($rule);
		}

		ilUtil::sendSuccess($this->txt("enabled_rules"), true);

		$this->redirectToTab(self::TAB_RULES);
	}


	/**
	 *
	 */
	protected function disableRules()/*: void*/ {
		$rule_ids = filter_input(INPUT_POST, self::GET_PARAM_RULE_ID, FILTER_DEFAULT, FILTER_FORCE_ARRAY);

		/**
		 * @var Rule[] $rules
		 */
		$rules = array_map(function (int $rule_id)/*: ?Rule*/ {
			return self::rules()->getRuleById($rule_id);
		}, $rule_ids);

		foreach ($rules as $rule) {
			$rule->setEnabled(false);

			self::rules()->storeRule($rule);
		}

		ilUtil::sendSuccess($this->txt("disabled_rules"), true);

		$this->redirectToTab(self::TAB_RULES);
	}


	/**
	 *
	 */
	protected function removeRulesConfirm()/*: void*/ {
		self::dic()->tabs()->activateTab(self::TAB_RULES);

		$rule_ids = filter_input(INPUT_POST, self::GET_PARAM_RULE_ID, FILTER_DEFAULT, FILTER_FORCE_ARRAY);

		/**
		 * @var Rule[] $rules
		 */
		$rules = array_map(function (int $rule_id)/*: ?Rule*/ {
			return self::rules()->getRuleById($rule_id);
		}, $rule_ids);

		$confirmation = new ilConfirmationGUI();

		$confirmation->setFormAction(self::dic()->ctrl()->getFormAction($this));

		$confirmation->setHeaderText($this->txt("remove_rules_confirm"));

		foreach ($rules as $rule) {
			$confirmation->addItem("rule_id[]", $rule->getRuleId(), $rule->getTitle());
		}

		$confirmation->setConfirm($this->txt("remove"), self::CMD_REMOVE_RULES);
		$confirmation->setCancel($this->txt("cancel"), $this->getCmdForTab(self::TAB_RULES));

		self::output()->output($confirmation);
	}


	/**
	 *
	 */
	protected function removeRules()/*: void*/ {
		$rule_ids = filter_input(INPUT_POST, self::GET_PARAM_RULE_ID, FILTER_DEFAULT, FILTER_FORCE_ARRAY);

		/**
		 * @var Rule[] $rules
		 */
		$rules = array_map(function (int $rule_id)/*: ?Rule*/ {
			return self::rules()->getRuleById($rule_id);
		}, $rule_ids);

		foreach ($rules as $rule) {
			self::rules()->deleteRule($rule);
		}

		ilUtil::sendSuccess($this->txt("removed_rules"), true);

		$this->redirectToTab(self::TAB_RULES);
	}
}
