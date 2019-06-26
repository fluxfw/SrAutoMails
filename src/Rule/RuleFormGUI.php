<?php

namespace srag\Plugins\SrAutoMails\Rule;

use ilCheckboxInputGUI;
use ilNumberInputGUI;
use ilRadioGroupInputGUI;
use ilRadioOption;
use ilSelectInputGUI;
use ilSrAutoMailsConfigGUI;
use ilSrAutoMailsPlugin;
use ilTextInputGUI;
use srag\CustomInputGUIs\SrAutoMails\MultiSelectSearchInputGUI\MultiSelectSearchInputGUI;
use srag\CustomInputGUIs\SrAutoMails\PropertyFormGUI\ObjectPropertyFormGUI;
use srag\Plugins\SrAutoMails\Utils\SrAutoMailsTrait;

/**
 * Class RuleFormGUI
 *
 * @package srag\Plugins\SrAutoMails\Rule
 *
 * @author  studer + raimann ag - Team Custom 1 <support-custom1@studer-raimann.ch>
 */
class RuleFormGUI extends ObjectPropertyFormGUI {

	use SrAutoMailsTrait;
	const PLUGIN_CLASS_NAME = ilSrAutoMailsPlugin::class;
	const LANG_MODULE = ilSrAutoMailsConfigGUI::LANG_MODULE_CONFIG;
	/**
	 * @var Rule
	 */
	protected $object;


	/**
	 * RuleFormGUI constructor
	 *
	 * @param ilSrAutoMailsConfigGUI $parent
	 * @param Rule                   $object
	 */
	public function __construct(ilSrAutoMailsConfigGUI $parent, Rule $object) {
		parent::__construct($parent, $object);
	}


	/**
	 * @inheritdoc
	 */
	protected function getValue(/*string*/ $key) {
		switch ($key) {
			case "operator_value_text":
				if ($this->object->getOperatorValueType() === Rule::OPERATOR_VALUE_TYPE_TEXT) {
					return parent::getValue("operator_value");
				}
				break;

			case "operator_value_object_property":
				if ($this->object->getOperatorValueType() === Rule::OPERATOR_VALUE_TYPE_OBJECT_PROPERTY) {
					return parent::getValue("operator_value");
				}
				break;

			case "receiver":
				return parent::getValue("receiver_type");

			case "receiver_object":
				if ($this->object->getReceiverType() === Rule::RECEIVER_TYPE_OBJECT) {
					return parent::getValue("receiver");
				}
				break;

			case "receiver_users":
				if ($this->object->getReceiverType() === Rule::RECEIVER_TYPE_USERS) {
					return parent::getValue("receiver");
				}
				break;

			default:
				return parent::getValue($key);
		}

		return null;
	}


	/**
	 * @inheritdoc
	 */
	protected function initCommands()/*: void*/ {
		if (!empty($this->object->getRuleId())) {
			$this->addCommandButton(ilSrAutoMailsConfigGUI::CMD_UPDATE_RULE, $this->txt("save"));
		} else {
			$this->addCommandButton(ilSrAutoMailsConfigGUI::CMD_CREATE_RULE, $this->txt("add"));
		}
		$this->addCommandButton($this->parent->getCmdForTab(ilSrAutoMailsConfigGUI::TAB_RULES), $this->txt("cancel"));
	}


	/**
	 * @inheritdoc
	 */
	protected function initFields()/*: void*/ {
		$this->fields = [
			"object_type" => [
				self::PROPERTY_CLASS => ilSelectInputGUI::class,
				self::PROPERTY_REQUIRED => true,
				self::PROPERTY_OPTIONS => [ "" => "" ] + self::objectTypes()->getObjectTypesText(),
				self::PROPERTY_DISABLED => (!empty($this->object->getRuleId()))
			]
		];

		if (!empty($this->object->getRuleId())) {
			$object_type_definiton = self::objectTypes()->factory()->getByObjectType($this->object->getObjectType());
			$object = $this->fields["object_type"][self::PROPERTY_OPTIONS][$this->object->getObjectType()];

			$this->fields = array_merge($this->fields, [
				"enabled" => [
					self::PROPERTY_CLASS => ilCheckboxInputGUI::class
				],
				"interval_type" => [
					self::PROPERTY_CLASS => ilRadioGroupInputGUI::class,
					self::PROPERTY_REQUIRED => true,
					self::PROPERTY_SUBITEMS => [
						Rule::INTERVAL_TYPE_ONCE => [
							self::PROPERTY_CLASS => ilRadioOption::class,
							self::PROPERTY_SUBITEMS => [],
							"setTitle" => $this->txt("interval_type_once")
						],
						Rule::INTERVAL_TYPE_NUMBER => [
							self::PROPERTY_CLASS => ilRadioOption::class,
							self::PROPERTY_SUBITEMS => [
								"interval" => [
									self::PROPERTY_CLASS => ilNumberInputGUI::class,
									"setMinValue" => 0,
									"setSuffix" => $this->txt("interval_days")
								]
							],
							"setTitle" => $this->txt("interval_type_number")
						]
					]
				],
				"title" => [
					self::PROPERTY_CLASS => ilTextInputGUI::class,
					self::PROPERTY_REQUIRED => true
				],
				"description" => [
					self::PROPERTY_CLASS => ilTextInputGUI::class
				],
				"match_type" => [
					self::PROPERTY_CLASS => ilRadioGroupInputGUI::class,
					self::PROPERTY_REQUIRED => true,
					self::PROPERTY_SUBITEMS => [
						Rule::MATCH_TYPE_ALWAYS => [
							self::PROPERTY_CLASS => ilRadioOption::class,
							self::PROPERTY_SUBITEMS => [],
							"setTitle" => $this->txt("match_type_always")
						],
						Rule::MATCH_TYPE_MATCH => [
							self::PROPERTY_CLASS => ilRadioOption::class,
							self::PROPERTY_SUBITEMS => [
								"metadata" => [
									self::PROPERTY_CLASS => ilSelectInputGUI::class,
									self::PROPERTY_REQUIRED => true,
									self::PROPERTY_OPTIONS => [ "" => "" ] + self::ilias()->metadata()->getMetadata()
								],
								"operator" => [
									self::PROPERTY_CLASS => ilSelectInputGUI::class,
									self::PROPERTY_REQUIRED => true,
									self::PROPERTY_OPTIONS => [ "" => "" ] + self::rules()->getOperatorsText()
								],
								"operator_negated" => [
									self::PROPERTY_CLASS => ilCheckboxInputGUI::class
								],
								"operator_case_sensitive" => [
									self::PROPERTY_CLASS => ilCheckboxInputGUI::class
								],
								"operator_value_type" => [
									self::PROPERTY_CLASS => ilRadioGroupInputGUI::class,
									self::PROPERTY_REQUIRED => true,
									self::PROPERTY_SUBITEMS => [
										Rule::OPERATOR_VALUE_TYPE_TEXT => [
											self::PROPERTY_CLASS => ilRadioOption::class,
											self::PROPERTY_SUBITEMS => [
												"operator_value_text" => [
													self::PROPERTY_CLASS => ilTextInputGUI::class
												]
											],
											"setTitle" => $this->txt("operator_value_text")
										],
										Rule::OPERATOR_VALUE_TYPE_OBJECT_PROPERTY => [
											self::PROPERTY_CLASS => ilRadioOption::class,
											self::PROPERTY_SUBITEMS => [
												"operator_value_object_property" => [
													self::PROPERTY_CLASS => ilSelectInputGUI::class,
													self::PROPERTY_OPTIONS => [ "" => "" ] + $object_type_definiton->getObjectPropertiesText(),
													"setTitle" => self::plugin()
														->translate("operator_value_object_property", ilSrAutoMailsConfigGUI::LANG_MODULE_CONFIG, [ $object ])
												]
											],
											"setTitle" => self::plugin()
												->translate("operator_value_object_property", ilSrAutoMailsConfigGUI::LANG_MODULE_CONFIG, [ $object ])
										]
									]
								]
							],
							"setTitle" => $this->txt("match_type_match")
						]
					]
				],
				"receiver" => [
					self::PROPERTY_CLASS => ilRadioGroupInputGUI::class,
					self::PROPERTY_REQUIRED => true,
					self::PROPERTY_SUBITEMS => [
						Rule::RECEIVER_TYPE_OBJECT => [
							self::PROPERTY_CLASS => ilRadioOption::class,
							self::PROPERTY_SUBITEMS => [
								"receiver_object" => [
									self::PROPERTY_CLASS => MultiSelectSearchInputGUI::class,
									self::PROPERTY_REQUIRED => true,
									self::PROPERTY_OPTIONS => $object_type_definiton->getReceiverPropertiesText(),
									"setTitle" => $object
								]
							],
							"setTitle" => $object
						],
						Rule::RECEIVER_TYPE_USERS => [
							self::PROPERTY_CLASS => ilRadioOption::class,
							self::PROPERTY_SUBITEMS => [
								"receiver_users" => [
									self::PROPERTY_CLASS => MultiSelectSearchInputGUI::class,
									self::PROPERTY_REQUIRED => true,
									self::PROPERTY_OPTIONS => self::ilias()->users()->getUsers()
								]
							],
							"setTitle" => $this->txt("receiver_users")
						]
					]
				]
			]);
		}
	}


	/**
	 * @inheritdoc
	 */
	protected function initId()/*: void*/ {

	}


	/**
	 * @inheritdoc
	 */
	protected function initTitle()/*: void*/ {
		$this->setTitle($this->txt(!empty($this->object->getRuleId()) ? "edit_rule" : "add_rule"));
	}


	/**
	 * @inheritdoc
	 */
	protected function storeValue(/*string*/ $key, $value)/*: void*/ {
		switch ($key) {
			case "object_type":
				if (empty($this->object->getRuleId())) {
					parent::storeValue("object_type", $value);
				}
				break;

			case "operator_value_text":
				if ($this->object->getOperatorValueType() === Rule::OPERATOR_VALUE_TYPE_TEXT) {
					parent::storeValue("operator_value", $value);
				}
				break;

			case "operator_value_object_property":
				if ($this->object->getOperatorValueType() === Rule::OPERATOR_VALUE_TYPE_OBJECT_PROPERTY) {
					parent::storeValue("operator_value", $value);
				}
				break;

			case "receiver":
				parent::storeValue("receiver_type", $value);
				break;

			case "receiver_object":
				if ($this->object->getReceiverType() === Rule::RECEIVER_TYPE_OBJECT) {
					parent::storeValue("receiver", $value);
				}
				break;

			case "receiver_users":
				if ($this->object->getReceiverType() === Rule::RECEIVER_TYPE_USERS) {
					parent::storeValue("receiver", $value);
				}
				break;

			default:
				parent::storeValue($key, $value);
				break;
		}
	}
}
