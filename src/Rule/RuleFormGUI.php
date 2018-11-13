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

		$object_type = new ilSelectInputGUI($this->txt("object_type"), "srauma_object_type");
		$object_type->setRequired(true);
		$object_type->setOptions([ "" => "" ] + self::objectTypes()->getObjectTypesText());
		if ($this->rule !== NULL) {
			$object_type->setValue($this->rule->getObjectType());
			$object_type->setDisabled(true);
		}
		$this->addItem($object_type);

		if ($this->rule !== NULL) {
			$object_type_definiton = self::objectTypes()->factory($this->rule->getObjectType());
			$object = $object_type->getOptions()[$object_type->getValue()];

			$enabled = new ilCheckboxInputGUI($this->txt("enabled"), "srauma_enabled");
			$enabled->setChecked($this->rule->isEnabled());
			$this->addItem($enabled);

			$title = new ilTextInputGUI($this->txt("title"), "srauma_title");
			$title->setRequired(true);
			$title->setValue($this->rule->getTitle());
			$this->addItem($title);

			$description = new ilTextInputGUI($this->txt("description"), "srauma_description");
			$description->setValue($this->rule->getDescription());
			$this->addItem($description);

			$metadata = new ilSelectInputGUI($this->txt("metadata"), "srauma_metadata");
			$metadata->setRequired(true);
			$metadata->setOptions([ "" => "" ] + self::ilias()->metadata()->getMetadata());
			$metadata->setValue($this->rule->getMetadata());
			$this->addItem($metadata);

			$operator = new ilSelectInputGUI($this->txt("operator"), "srauma_operator");
			$operator->setInfo($this->txt("operator_reg_ex_info"));
			$operator->setRequired(true);
			$operator->setOptions([ "" => "" ] + self::rules()->getOperatorsText());
			$operator->setValue($this->rule->getOperator());
			$this->addItem($operator);

			$operator_negated = new ilCheckboxInputGUI($this->txt("operator_negated"), "srauma_operator_negated");
			$operator_negated->setChecked($this->rule->isOperatorNegated());
			$this->addItem($operator_negated);

			$operator_case_sensitive = new ilCheckboxInputGUI($this->txt("operator_case_sensitive"), "srauma_operator_case_sensitive");
			$operator_case_sensitive->setChecked($this->rule->isOperatorCaseSensitive());
			$this->addItem($operator_case_sensitive);

			$operator_value_type = new ilRadioGroupInputGUI($this->txt("operator_value_type"), "srauma_operator_value_type");
			$operator_value_type->setRequired(true);
			$operator_value_type->setValue($this->rule->getOperatorValueType());
			$this->addItem($operator_value_type);

			$operator_value_type_text = new ilRadioOption($this->txt("operator_value_text"), Rule::OPERATOR_VALUE_TYPE_TEXT);
			$operator_value_type->addOption($operator_value_type_text);

			$operator_value_type_text_text = new ilTextInputGUI($this->txt("operator_value_text"), "srauma_operator_value_type_text");
			if ($this->rule->getOperatorValueType() === Rule::OPERATOR_VALUE_TYPE_TEXT) {
				$operator_value_type_text_text->setValue($this->rule->getOperatorValue());
			}
			$operator_value_type_text->addSubItem($operator_value_type_text_text);

			$operator_value_type_object_property = new ilRadioOption(self::plugin()
				->translate("operator_value_object_property", ilSrAutoMailsConfigGUI::LANG_MODULE_CONFIG, [ $object ]), Rule::OPERATOR_VALUE_TYPE_OBJECT_PROPERTY);
			$operator_value_type->addOption($operator_value_type_object_property);

			$operator_value_type_object_property_select = new ilSelectInputGUI(self::plugin()
				->translate("operator_value_object_property", ilSrAutoMailsConfigGUI::LANG_MODULE_CONFIG, [ $object ]), "srauma_operator_value_type_object_property");
			$operator_value_type_object_property_select->setOptions([ "" => "" ] + $object_type_definiton->getObjectPropertiesText());
			if ($this->rule->getOperatorValueType() === Rule::OPERATOR_VALUE_TYPE_OBJECT_PROPERTY) {
				$operator_value_type_object_property_select->setValue($this->rule->getOperatorValue());
			}
			$operator_value_type_object_property->addSubItem($operator_value_type_object_property_select);

			$mail_template_name = new ilSelectInputGUI($this->txt("mail_template_name"), "srauma_mail_template_name");
			$mail_template_name->setInfo(ilNotifications4PluginsPlugin::PLUGIN_NAME);
			$mail_template_name->setRequired(true);
			$mail_template_name->setOptions([ "" => "" ] + self::rules()->getMailTemplatesText());
			$mail_template_name->setValue($this->rule->getMailTemplateName());
			$this->addItem($mail_template_name);

			$receiver = new ilRadioGroupInputGUI($this->txt("receiver"), "srauma_receiver");
			$receiver->setRequired(true);
			$receiver->setValue($this->rule->getReceiverType());
			$this->addItem($receiver);

			$receiver_object = new ilRadioOption($object, Rule::RECEIVER_TYPE_OBJECT);
			$receiver->addOption($receiver_object);

			$receiver_object_select = new MultiSelectSearchInputGUI($object, "srauma_receiver_object");
			$receiver_object_select->setRequired(true);
			$receiver_object_select->setOptions($object_type_definiton->getReceiverPropertiesText());
			if ($this->rule->getReceiverType() === Rule::RECEIVER_TYPE_OBJECT) {
				$receiver_object_select->setValue($this->rule->getReceiver());
			}
			$receiver_object->addSubItem($receiver_object_select);

			$receiver_users = new ilRadioOption($this->txt("receiver_users"), Rule::RECEIVER_TYPE_USERS);
			$receiver->addOption($receiver_users);

			$receiver_users_select = new MultiSelectSearchInputGUI($this->txt("receiver_users"), "srauma_receiver_users");
			$receiver_users_select->setRequired(true);
			$receiver_users_select->setOptions(self::ilias()->users()->getUsers());
			if ($this->rule->getReceiverType() === Rule::RECEIVER_TYPE_USERS) {
				$receiver_users_select->setValue($this->rule->getReceiver());
			}
			$receiver_users->addSubItem($receiver_users_select);
		}
	}


	/**
	 * @inheritdoc
	 */
	public function updateConfig()/*: void*/ {
		if ($this->rule === NULL) {
			$this->rule = new Rule();

			$object_type = intval($this->getInput("srauma_object_type"));
			$this->rule->setObjectType($object_type);
		}

		$enabled = boolval($this->getInput("srauma_enabled"));
		$this->rule->setEnabled($enabled);

		$title = strval($this->getInput("srauma_title"));
		$this->rule->setTitle($title);

		$description = strval($this->getInput("srauma_description"));
		$this->rule->setDescription($description);

		$metadata = intval($this->getInput("srauma_metadata"));
		$this->rule->setMetadata($metadata);

		$operator = intval($this->getInput("srauma_operator"));
		$this->rule->setOperator($operator);

		$operator_negated = boolval($this->getInput("srauma_operator_negated"));
		$this->rule->setOperatorNegated($operator_negated);

		$operator_case_sensitive = boolval($this->getInput("srauma_operator_case_sensitive"));
		$this->rule->setOperatorCaseSensitive($operator_case_sensitive);

		$operator_value_type = intval($this->getInput("srauma_operator_value_type"));
		$this->rule->setOperatorValueType($operator_value_type);

		switch ($operator_value_type) {
			case Rule::OPERATOR_VALUE_TYPE_TEXT:
				$operator_value = strval($this->getInput("srauma_operator_value_type_text"));
				$this->rule->setOperatorValue($operator_value);
				break;

			case Rule::OPERATOR_VALUE_TYPE_OBJECT_PROPERTY:
				$operator_value = strval($this->getInput("srauma_operator_value_type_object_property"));
				$this->rule->setOperatorValue($operator_value);
				break;

			default:
				break;
		}

		$mail_template_name = strval($this->getInput("srauma_mail_template_name"));
		$this->rule->setMailTemplateName($mail_template_name);

		$receiver = intval($this->getInput("srauma_receiver"));
		$this->rule->setReceiverType($receiver);

		switch ($receiver) {
			case Rule::RECEIVER_TYPE_OBJECT:
				$receiver = $this->getInput("srauma_receiver_object");
				$this->rule->setReceiver($receiver);
				break;

			case Rule::RECEIVER_TYPE_USERS:
				$receiver = $this->getInput("srauma_receiver_users");
				$this->rule->setReceiver($receiver);
				break;

			default:
				break;
		}

		$this->rule->store();
	}


	/**
	 * @return Rule
	 */
	public function getRule(): Rule {
		return $this->rule;
	}
}
