<?php

namespace srag\Plugins\SrAutoMails\Rule;

use ilSelectInputGUI;
use ilSrAutoMailsConfigGUI;
use ilSrAutoMailsPlugin;
use ilTextInputGUI;
use ilUtil;
use srag\ActiveRecordConfig\SrAutoMails\ActiveRecordConfigTableGUI;
use srag\CustomInputGUIs\SrAutoMails\PropertyFormGUI\PropertyFormGUI;
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
	 * @inheritdoc
	 */
	protected function getColumnValue(/*string*/ $column, /*array*/ $row, /*int*/ $format = self::DEFAULT_FORMAT): string {
		switch ($column) {
			case "object_type":
				$column = self::objectTypes()->getObjectTypesText()[$row[$column]];
				break;

			default:
				$column = $row[$column];
				break;
		}

		return strval($column);
	}


	/**
	 * @inheritdoc
	 */
	public function getSelectableColumns2(): array {
		$columns = [
			"enabled" => "enabled",
			"title" => "title",
			"description" => "description",
			"object_type" => "object_type"
		];

		$columns = array_map(function (string $key): array {
			return [
				"id" => $key,
				"default" => true,
				"sort" => ($key !== "enabled" && $key !== "description")
			];
		}, $columns);

		return $columns;
	}


	/**
	 * @inheritdoc
	 */
	protected function initColumns()/*: void*/ {
		$this->addColumn("");

		parent::initColumns();

		$this->addColumn($this->txt("actions"));
	}


	/**
	 * @inheritdoc
	 */
	protected function initCommands()/*: void*/ {
		self::dic()->toolbar()->addComponent(self::dic()->ui()->factory()->button()->standard($this->txt("add_rule"), self::dic()->ctrl()
			->getLinkTarget($this->parent_obj, ilSrAutoMailsConfigGUI::CMD_ADD_RULE)));

		$this->setSelectAllCheckbox(ilSrAutoMailsConfigGUI::GET_PARAM_RULE_ID);
		$this->addMultiCommand(ilSrAutoMailsConfigGUI::CMD_ENABLE_RULES, $this->txt("enable_rules"));
		$this->addMultiCommand(ilSrAutoMailsConfigGUI::CMD_DISABLE_RULES, $this->txt("disable_rules"));
		$this->addMultiCommand(ilSrAutoMailsConfigGUI::CMD_REMOVE_RULES_CONFIRM, $this->txt("remove_rules"));
	}


	/**
	 * @inheritdoc
	 */
	protected function initData()/*: void*/ {
		$filter = $this->getFilterValues();

		$title = $filter["title"];
		$description = $filter["description"];
		$object_type = $filter["object_type"];
		$enabled = $filter["enabled"];
		if (!empty($enabled)) {
			$enabled = ($enabled === "yes");
		} else {
			$enabled = null;
		}

		$this->setData(array_map(function (array &$row): array {
			if ($row["enabled"]) {
				$enabled = ilUtil::getImagePath("icon_ok.svg");
			} else {
				$enabled = ilUtil::getImagePath("icon_not_ok.svg");
			}
			$row["enabled"] = self::output()->getHTML(self::dic()->ui()->factory()->image()->standard($enabled, ""));

			return $row;
		}, self::rules()->getRulesArray($title, $description, $object_type, $enabled)));
	}


	/**
	 * @inheritdoc
	 */
	protected function initFilterFields()/*: void*/ {
		parent::initFilterFields();

		$this->filter_fields = [
			"title" => [
				PropertyFormGUI::PROPERTY_CLASS => ilTextInputGUI::class
			],
			"description" => [
				PropertyFormGUI::PROPERTY_CLASS => ilTextInputGUI::class
			],
			"object_type" => [
				PropertyFormGUI::PROPERTY_CLASS => ilSelectInputGUI::class,
				PropertyFormGUI::PROPERTY_OPTIONS => [ "" => "" ] + self::objectTypes()->getObjectTypesText()
			],
			"enabled" => [
				PropertyFormGUI::PROPERTY_CLASS => ilSelectInputGUI::class,
				PropertyFormGUI::PROPERTY_OPTIONS => [ "" => "", "yes" => $this->txt("yes"), "no" => $this->txt("no") ]
			]
		];
	}


	/**
	 * @inheritdoc
	 */
	protected function initId()/*: void*/ {
		$this->setId("srauma_rules");
	}


	/**
	 * @param array $row
	 */
	protected function fillRow(/*array*/ $row)/*: void*/ {
		self::dic()->ctrl()->setParameter($this->parent_obj, ilSrAutoMailsConfigGUI::GET_PARAM_RULE_ID, $row["rule_id"]);

		$this->tpl->setCurrentBlock("checkbox");
		$this->tpl->setVariable("CHECKBOX_POST_VAR", ilSrAutoMailsConfigGUI::GET_PARAM_RULE_ID);
		$this->tpl->setVariable("ID", $row["rule_id"]);
		$this->tpl->parseCurrentBlock();

		parent::fillRow($row);

		$this->tpl->setVariable("COLUMN", self::output()->getHTML(self::dic()->ui()->factory()->dropdown()->standard([
			self::dic()->ui()->factory()->button()->shy($this->txt("edit_rule"), self::dic()->ctrl()
				->getLinkTarget($this->parent_obj, ilSrAutoMailsConfigGUI::CMD_EDIT_RULE)),
			self::dic()->ui()->factory()->button()->shy($this->txt("remove_rule"), self::dic()->ctrl()
				->getLinkTarget($this->parent_obj, ilSrAutoMailsConfigGUI::CMD_REMOVE_RULE_CONFIRM))
		])->withLabel($this->txt("actions"))));
	}
}
