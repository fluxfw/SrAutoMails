<?php

namespace srag\Plugins\SrAutoMails\Rule;

use ilCheckboxInputGUI;
use ilNotifications4PluginsPlugin;
use ilRadioGroupInputGUI;
use ilRadioOption;
use ilSelectInputGUI;
use ilSrAutoMailsConfigGUI;
use ilSrAutoMailsPlugin;
use ilTextInputGUI;
use srag\ActiveRecordConfig\SrAutoMails\ActiveRecordConfigFormGUI;
use srag\ActiveRecordConfig\SrAutoMails\ActiveRecordConfigGUI;
use srag\CustomInputGUIs\SrAutoMails\MultiSelectSearchInputGUI\MultiSelectSearchInputGUI;
use srag\Plugins\SrAutoMails\Config\Config;
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
	const CONFIG_CLASS_NAME = Config::class;
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
		Rule $rule = NULL) {

		$this->rule = $rule;

		parent::__construct($parent, $tab_id);
	}


	/**
	 * @inheritdoc
	 */
	protected function getValue(/*string*/
		$key) {
		if ($this->rule !== NULL) {
			switch ($key) {
				case "object_type":
					return $this->rule->getObjectType();

				case "enabled":
					return $this->rule->isEnabled();

				case "title":
					return $this->rule->getTitle();

				case "description":
					return $this->rule->getDescription();

				case "metadata":
					return $this->rule->getMetadata();

				case "operator":
					return $this->rule->getOperator();

				case "operator_negated":
					return $this->rule->isOperatorNegated();

				case "operator_case_sensitive":
					return $this->rule->isOperatorCaseSensitive();

				case "operator_value_type":
					return $this->rule->getOperatorValueType();

				case "operator_value_text":
					if ($this->rule->getOperatorValueType() === Rule::OPERATOR_VALUE_TYPE_TEXT) {
						return $this->rule->getOperatorValue();
					}
					break;

				case "operator_value_object_property":
					if ($this->rule->getOperatorValueType() === Rule::OPERATOR_VALUE_TYPE_OBJECT_PROPERTY) {
						return $this->rule->getOperatorValue();
					}
					break;

				case "mail_template_name":
					return $this->rule->getMailTemplateName();

				case "receiver":
					return $this->rule->getReceiverType();

				case "receiver_object":
					if ($this->rule->getReceiverType() === Rule::RECEIVER_TYPE_OBJECT) {
						return $this->rule->getReceiver();
					}
					break;

				case "receiver_users":
					if ($this->rule->getReceiverType() === Rule::RECEIVER_TYPE_USERS) {
						return $this->rule->getReceiver();
					}
					break;

				default:
					break;
			}
		}

		return NULL;
	}


	/**
	 * @inheritdoc
	 */
	protected function initAction()/*: void*/ {
		if ($this->rule !== NULL) {
			self::dic()->ctrl()->setParameter($this->parent, "srauma_rule_id", $this->rule->getRuleId());
		}

		parent::initAction();

		self::dic()->ctrl()->setParameter($this->parent, "srauma_rule_id", NULL);
	}


	/**
	 * @inheritdoc
	 */
	protected function initCommands()/*: void*/ {
		if ($this->rule !== NULL) {
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
				self::PROPERTY_DISABLED => ($this->rule !== NULL)
			]
		];

		if ($this->rule !== NULL) {
			$object_type_definiton = self::objectTypes()->factory($this->rule->getObjectType());
			$object = $this->fields["object_type"][self::PROPERTY_OPTIONS][$this->rule->getObjectType()];

			$this->fields = array_merge($this->fields, [
				"enabled" => [
					self::PROPERTY_CLASS => ilCheckboxInputGUI::class
				],
				"title" => [
					self::PROPERTY_CLASS => ilTextInputGUI::class,
					self::PROPERTY_REQUIRED => true
				],
				"description" => [
					self::PROPERTY_CLASS => ilTextInputGUI::class
				],
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
				],
				"mail_template_name" => [
					self::PROPERTY_CLASS => ilSelectInputGUI::class,
					self::PROPERTY_REQUIRED => true,
					self::PROPERTY_OPTIONS => [ "" => "" ] + self::rules()->getMailTemplatesText(),
					"setInfo" => ilNotifications4PluginsPlugin::PLUGIN_NAME
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
	protected function initTile()/*: void*/ {
		$this->setTitle($this->txt($this->rule !== NULL ? "edit_rule" : "add_rule"));
	}


	/**
	 * @inheritdoc
	 */
	protected function setValue(/*string*/
		$key, $value)/*: void*/ {
		switch ($key) {
			case "enabled":
				$this->rule->setEnabled($value);
				break;

			case "title":
				$this->rule->setTitle(strval($value));
				break;

			case "description":
				$this->rule->setDescription(strval($value));
				break;

			case "metadata":
				$this->rule->setMetadata(intval($value));
				break;

			case "operator":
				$this->rule->setOperator(intval($value));
				break;

			case "operator_negated":
				$this->rule->setOperatorNegated($value);
				break;

			case "operator_case_sensitive":
				$this->rule->setOperatorCaseSensitive($value);
				break;

			case "operator_value_type":
				$this->rule->setOperatorValueType(intval($value));
				break;

			case "operator_value_text":
				if ($this->rule->getOperatorValueType() === Rule::OPERATOR_VALUE_TYPE_TEXT) {
					$this->rule->setOperatorValue($value);
				}
				break;

			case "operator_value_object_property":
				if ($this->rule->getOperatorValueType() === Rule::OPERATOR_VALUE_TYPE_OBJECT_PROPERTY) {
					$this->rule->setOperatorValue($value);
				}
				break;

			case "mail_template_name":
				$this->rule->setMailTemplateName(strval($value));
				break;

			case "receiver":
				$this->rule->setReceiverType(intval($value));
				break;

			case "receiver_object":
				if ($this->rule->getReceiverType() === Rule::RECEIVER_TYPE_OBJECT) {
					$this->rule->setReceiver($value);
				}
				break;

			case "receiver_users":
				if ($this->rule->getReceiverType() === Rule::RECEIVER_TYPE_USERS) {
					$this->rule->setReceiver($value);
				}
				break;

			default:
				break;
		}
	}


	/**
	 * @inheritdoc
	 */
	public function updateForm()/*: void*/ {
		if ($this->rule === NULL) {
			$this->rule = new Rule();

			$object_type = intval($this->getInput("object_type"));
			$this->rule->setObjectType($object_type);
		}

		parent::updateForm();

		$this->rule->store();
	}


	/**
	 * @return Rule
	 */
	public function getRule(): Rule {
		return $this->rule;
	}
}
