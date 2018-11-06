<?php

namespace srag\Plugins\SrAutoMails\Rule;

use ilCheckboxInputGUI;
use ilSelectInputGUI;
use ilSrAutoMailsConfigGUI;
use ilSrAutoMailsPlugin;
use ilTextInputGUI;
use srag\ActiveRecordConfig\ActiveRecordConfigFormGUI;
use srag\ActiveRecordConfig\ActiveRecordConfigGUI;
use srag\Plugins\SrAutoMails\Utils\SrAutoMailsTrait;

/**
 * Class RuleFormGUI
 *
 * @package srag\Plugins\SrAutoMails\Rule
 *
 * @author  studer + raimann ag - Team Custom 1 <support-custom1@studer-raimann.ch>
 */
class RuleFormGUI extends ActiveRecordConfigFormGUI {

	use SrAutoMailsTrait;
	const PLUGIN_CLASS_NAME = ilSrAutoMailsPlugin::class;
	/**
	 * @var Rule|null
	 */
	protected $rule;


	/**
	 * RuleFormGUI constructor
	 *
	 * @param ActiveRecordConfigGUI $parent
	 * @param string                $tab_id
	 * @param Rule|null             $rule
	 */
	public function __construct(ActiveRecordConfigGUI $parent, string $tab_id, /*?*/
		/*?*/
		Rule $rule = NULL) {

		$this->rule = $rule;

		parent::__construct($parent, $tab_id);
	}


	/**
	 *
	 */
	protected function initForm()/*: void*/ {
		if ($this->rule !== NULL) {
			self::dic()->ctrl()->setParameter($this->parent, "srauma_rule_id", $this->rule->getRuleId());
		}
		$this->setFormAction(self::dic()->ctrl()->getFormAction($this->parent));
		self::dic()->ctrl()->setParameter($this->parent, "srauma_rule_id", NULL);

		$this->setTitle($this->txt($this->rule !== NULL ? "edit_rule" : "add_rule"));

		if ($this->rule !== NULL) {
			$this->addCommandButton(ilSrAutoMailsConfigGUI::CMD_UPDATE_RULE, $this->txt("save"));
		} else {
			$this->addCommandButton(ilSrAutoMailsConfigGUI::CMD_CREATE_RULE, $this->txt("add"));
		}
		$this->addCommandButton($this->parent->getCmdForTab(ilSrAutoMailsConfigGUI::TAB_RULES), $this->txt("cancel"));

		$title = new ilTextInputGUI($this->txt("title"), "srauma_title");
		$title->setRequired(true);
		if ($this->rule !== NULL) {
			$title->setValue($this->rule->getTitle());
		}
		$this->addItem($title);

		$description = new ilTextInputGUI($this->txt("description"), "srauma_description");
		$description->setRequired(true);
		if ($this->rule !== NULL) {
			$description->setValue($this->rule->getDescription());
		}
		$this->addItem($description);

		$object_type = new ilSelectInputGUI($this->txt("object_type"), "srauma_object_type");
		$object_type->setRequired(true);
		$object_type->setOptions(self::rules()->getObjectTypes());
		if ($this->rule !== NULL) {
			$object_type->setValue($this->rule->getObjectType());
			$object_type->setDisabled(true);
		}
		$this->addItem($object_type);

		$enabled = new ilCheckboxInputGUI($this->txt("enabled"), "srauma_enabled");
		if ($this->rule !== NULL) {
			$enabled->setChecked($this->rule->isEnabled());
		}
		$this->addItem($enabled);
	}


	/**
	 * @inheritdoc
	 */
	public function updateConfig()/*: void*/ {
		if ($this->rule === NULL) {
			$this->rule = new Rule();

			$object_type = $this->getInput("srauma_object_type");
			$this->rule->setObjectType($object_type);
		}

		$title = $this->getInput("srauma_title");
		$this->rule->setTitle($title);

		$description = $this->getInput("srauma_description");
		$this->rule->setDescription($description);

		$enabled = boolval($this->getInput("srauma_enabled"));
		$this->rule->setEnabled($enabled);

		$this->rule->store();
	}


	/**
	 * @return Rule
	 */
	public function getRule(): Rule {
		return $this->rule;
	}
}
