<?php

namespace srag\Plugins\SrAutoMails\Rule;

use ilAdvancedSelectionListGUI;
use ilLinkButton;
use ilSelectInputGUI;
use ilSrAutoMailsConfigGUI;
use ilSrAutoMailsPlugin;
use ilTextInputGUI;
use ilUtil;
use srag\ActiveRecordConfig\ActiveRecordConfigTableGUI;
use srag\Plugins\SrAutoMails\Utils\SrAutoMailsTrait;

/**
 * Class RulesTableGUI
 *
 * @package srag\Plugins\SrAutoMails\Rule
 *
 * @author  studer + raimann ag - Team Custom 1 <support-custom1@studer-raimann.ch>
 */
class RulesTableGUI extends ActiveRecordConfigTableGUI {

	use SrAutoMailsTrait;
	const PLUGIN_CLASS_NAME = ilSrAutoMailsPlugin::class;
	/**
	 * @var ilTextInputGUI
	 */
	protected $filter_title;
	/**
	 * @var ilTextInputGUI
	 */
	protected $filter_description;
	/**
	 * @var ilSelectInputGUI
	 */
	protected $filter_object_type;
	/**
	 * @var ilSelectInputGUI
	 */
	protected $filter_enabled;


	/**
	 *
	 */
	protected function initTable()/*: void*/ {
		parent::initTable();

		$parent = $this->getParentObject();

		$add_rule = ilLinkButton::getInstance();
		$add_rule->setCaption($this->txt("add_rule"), false);
		$add_rule->setUrl(self::dic()->ctrl()->getLinkTarget($parent, ilSrAutoMailsConfigGUI::CMD_ADD_RULE));
		self::dic()->toolbar()->addButtonInstance($add_rule);

		$this->setRowTemplate("rules_table_row.html", self::plugin()->directory());

		$this->setSelectAllCheckbox("srauma_rule_id");
		$this->addMultiCommand(ilSrAutoMailsConfigGUI::CMD_ENABLE_RULES, $this->txt("enable_rules"));
		$this->addMultiCommand(ilSrAutoMailsConfigGUI::CMD_DISABLE_RULES, $this->txt("disable_rules"));
		$this->addMultiCommand(ilSrAutoMailsConfigGUI::CMD_REMOVE_RULES_CONFIRM, $this->txt("remove_rules"));
	}


	/**
	 *
	 */
	public function initFilter()/*: void*/ {
		parent::initFilter();

		$this->filter_title = new ilTextInputGUI($this->txt("title"), "srauma_title");
		$this->addFilterItem($this->filter_title);
		$this->filter_title->readFromSession();

		$this->filter_description = new ilTextInputGUI($this->txt("description"), "srauma_description");
		$this->addFilterItem($this->filter_description);
		$this->filter_description->readFromSession();

		$this->filter_object_type = new ilSelectInputGUI($this->txt("object_type"), "srauma_object_type");
		$this->filter_object_type->setOptions([ "" => "" ] + self::objectTypes()->getObjectTypes());
		$this->addFilterItem($this->filter_object_type);
		$this->filter_object_type->readFromSession();

		$this->filter_enabled = new ilSelectInputGUI($this->txt("enabled"), "srauma_enabled");
		$this->filter_enabled->setOptions([ "" => "", "yes" => $this->txt("yes"), "no" => $this->txt("no") ]);
		$this->addFilterItem($this->filter_enabled);
		$this->filter_enabled->readFromSession();

		$this->setDisableFilterHiding(true);
	}


	/**
	 *
	 */
	protected function initData()/*: void*/ {
		$title = $this->filter_title->getValue();
		if ($title === false) {
			$title = "";
		}
		$description = $this->filter_description->getValue();
		if ($description === false) {
			$description = "";
		}
		$object_type = $this->filter_object_type->getValue();
		if ($object_type === false) {
			$object_type = "";
		}
		$enabled = $this->filter_enabled->getValue();
		if (!empty($enabled)) {
			$enabled = ($enabled === "yes");
		} else {
			$enabled = NULL;
		}

		$this->setData(self::rules()->getRulesArray($title, $description, $object_type, $enabled));
	}


	/**
	 *
	 */
	protected function initColumns()/*: void*/ {
		$this->addColumn("");
		$this->addColumn("");
		$this->addColumn($this->txt("title"), true);
		$this->addColumn($this->txt("description"));
		$this->addColumn($this->txt("object_type"), true);
		$this->addColumn($this->txt("actions"));
	}


	/**
	 * @param array $rule
	 */
	protected function fillRow(/*array*/
		$rule) {
		$parent = $this->getParentObject();

		self::dic()->ctrl()->setParameter($parent, "srauma_rule_id", $rule["rule_id"]);
		$edit_rule_link = self::dic()->ctrl()->getLinkTarget($parent, ilSrAutoMailsConfigGUI::CMD_EDIT_RULE);
		$remove_rule_link = self::dic()->ctrl()->getLinkTarget($parent, ilSrAutoMailsConfigGUI::CMD_REMOVE_RULE_CONFIRM);
		self::dic()->ctrl()->setParameter($parent, "srauma_rule_id", NULL);

		$this->tpl->setVariable("RULE_ID", $rule["rule_id"]);

		if ($rule["enabled"]) {
			$enabled = ilUtil::getImagePath("icon_ok.svg");
		} else {
			$enabled = ilUtil::getImagePath("icon_not_ok.svg");
		}
		$this->tpl->setVariable("RULE_ENABLED", self::dic()->ui()->renderer()->render(self::dic()->ui()->factory()->image()->standard($enabled, "")));

		$this->tpl->setVariable("RULE_TITLE", $rule["title"]);

		$this->tpl->setVariable("RULE_DESCRIPTION", $rule["description"]);

		$this->tpl->setVariable("RULE_OBJECT_TYPE", self::objectTypes()->getObjectTypes()[$rule["object_type"]]);

		$actions = new ilAdvancedSelectionListGUI();
		$actions->setListTitle($this->txt("actions"));

		$actions->addItem($this->txt("edit_rule"), "", $edit_rule_link);
		$actions->addItem($this->txt("remove_rule"), "", $remove_rule_link);

		$this->tpl->setVariable("ACTIONS", $actions->getHTML());
	}
}
