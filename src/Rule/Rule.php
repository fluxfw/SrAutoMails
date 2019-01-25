<?php

namespace srag\Plugins\SrAutoMails\Rule;

use ActiveRecord;
use arConnector;
use ilDateTime;
use ilSrAutoMailsPlugin;
use srag\DIC\SrAutoMails\DICTrait;
use srag\Plugins\SrAutoMails\Utils\SrAutoMailsTrait;

/**
 * Class Rule
 *
 * @package srag\Plugins\SrAutoMails\Rule
 *
 * @author  studer + raimann ag - Team Custom 1 <support-custom1@studer-raimann.ch>
 */
class Rule extends ActiveRecord {

	use DICTrait;
	use SrAutoMailsTrait;
	const TABLE_NAME = "srauma_rule";
	const PLUGIN_CLASS_NAME = ilSrAutoMailsPlugin::class;
	const OPERATOR_EQUALS = 1;
	const OPERATOR_STARTS_WITH = 2;
	const OPERATOR_CONTAINS = 3;
	const OPERATOR_ENDS_WITH = 4;
	const OPERATOR_IS_EMPTY = 5;
	const OPERATOR_REG_EX = 6;
	const OPERATOR_LESS = 7;
	const OPERATOR_LESS_EQUALS = 8;
	const OPERATOR_BIGGER = 9;
	const OPERATOR_BIGGER_EQUALS = 10;
	const OPERATOR_X_DAYS_BEFORE = 11;
	const OPERATOR_X_DAYS_AFTER = 12;
	const OPERATOR_VALUE_TYPE_TEXT = 1;
	const OPERATOR_VALUE_TYPE_OBJECT_PROPERTY = 2;
	const RECEIVER_TYPE_OBJECT = 1;
	const RECEIVER_TYPE_USERS = 2;
	/**
	 * @var array
	 */
	public static $operators = [
		self::OPERATOR_EQUALS => "equals",
		self::OPERATOR_STARTS_WITH => "starts_with",
		self::OPERATOR_CONTAINS => "contains",
		self::OPERATOR_ENDS_WITH => "ends_with",
		self::OPERATOR_IS_EMPTY => "is_empty",
		self::OPERATOR_REG_EX => "reg_ex",
		self::OPERATOR_LESS => "less",
		self::OPERATOR_LESS_EQUALS => "less_equals",
		self::OPERATOR_BIGGER => "bigger",
		self::OPERATOR_BIGGER_EQUALS => "bigger_equals",
		self::OPERATOR_X_DAYS_BEFORE => "x_days_before",
		self::OPERATOR_X_DAYS_AFTER => "x_days_after"
	];


	/**
	 * @return string
	 */
	public function getConnectorContainerName() {
		return self::TABLE_NAME;
	}


	/**
	 * @return string
	 *
	 * @deprecated
	 */
	public static function returnDbTableName() {
		return self::TABLE_NAME;
	}


	/**
	 * @var int
	 *
	 * @con_has_field    true
	 * @con_fieldtype    integer
	 * @con_length       8
	 * @con_is_notnull   true
	 * @con_is_primary   true
	 * @con_sequence     true
	 */
	protected $rule_id;
	/**
	 * @var bool
	 *
	 * @con_has_field    true
	 * @con_fieldtype    integer
	 * @con_length       1
	 * @con_is_notnull   true
	 */
	protected $enabled = false;
	/**
	 * @var string
	 *
	 * @con_has_field    true
	 * @con_fieldtype    text
	 * @con_is_notnull   true
	 */
	protected $title = "";
	/**
	 * @var string
	 *
	 * @con_has_field    true
	 * @con_fieldtype    text
	 * @con_is_notnull   true
	 */
	protected $description = "";
	/**
	 * @var int
	 *
	 * @con_has_field    true
	 * @con_fieldtype    integer
	 * @con_length       2
	 * @con_is_notnull   true
	 */
	protected $object_type = 0;
	/**
	 * @var int
	 *
	 * @con_has_field    true
	 * @con_fieldtype    integer
	 * @con_length       8
	 * @con_is_notnull   true
	 */
	protected $metadata = 0;
	/**
	 * @var int
	 *
	 * @con_has_field    true
	 * @con_fieldtype    integer
	 * @con_length       2
	 * @con_is_notnull   true
	 */
	protected $operator = 0;
	/**
	 * @var bool
	 *
	 * @con_has_field    true
	 * @con_fieldtype    integer
	 * @con_length       1
	 * @con_is_notnull   true
	 */
	protected $operator_negated = false;
	/**
	 * @var bool
	 *
	 * @con_has_field    true
	 * @con_fieldtype    integer
	 * @con_length       1
	 * @con_is_notnull   true
	 */
	protected $operator_case_sensitive = false;
	/**
	 * @var int
	 *
	 * @con_has_field    true
	 * @con_fieldtype    integer
	 * @con_length       1
	 * @con_is_notnull   true
	 */
	protected $operator_value_type = 0;
	/**
	 * @var string
	 *
	 * @con_has_field    true
	 * @con_fieldtype    text
	 * @con_is_notnull   true
	 */
	protected $operator_value = "";
	/**
	 * @var string
	 *
	 * @con_has_field    true
	 * @con_fieldtype    text
	 * @con_is_notnull   true
	 */
	protected $mail_template_name = "";
	/**
	 * @var int
	 *
	 * @con_has_field    true
	 * @con_fieldtype    integer
	 * @con_length       1
	 * @con_is_notnull   true
	 */
	protected $receiver_type = 0;
	/**
	 * @var array
	 *
	 * @con_has_field    true
	 * @con_fieldtype    text
	 * @con_is_notnull   true
	 */
	protected $receiver = [];
	/**
	 * @var int
	 *
	 * @con_has_field    true
	 * @con_fieldtype    integer
	 * @con_length       1
	 * @con_is_notnull   true
	 */
	protected $interval = 0;
	/**
	 * @var ilDateTime|null
	 *
	 * @con_has_field    true
	 * @con_fieldtype    timestamp
	 * @con_is_notnull   false
	 */
	protected $last_check = NULL;


	/**
	 * Rule constructor
	 *
	 * @param int              $primary_key_value
	 * @param arConnector|null $connector
	 */
	public function __construct(/*int*/
		$primary_key_value = 0, arConnector $connector = NULL) {
		parent::__construct($primary_key_value, $connector);
	}


	/**
	 * @param string $field_name
	 *
	 * @return mixed|null
	 */
	public function sleep(/*string*/
		$field_name) {
		$field_value = $this->{$field_name};

		switch ($field_name) {
			case "enabled":
			case "operator_negated":
			case "operator_case_sensitive":
				return ($field_value ? 1 : 0);

			case "receiver":
				return json_encode($field_value);

			case "last_check":
				if ($field_value !== NULL) {
					return $field_value->get(IL_CAL_DATETIME);
				} else {
					return NULL;
				}

			default:
				return NULL;
		}
	}


	/**
	 * @param string $field_name
	 * @param mixed  $field_value
	 *
	 * @return mixed|null
	 */
	public function wakeUp(/*string*/
		$field_name, $field_value) {
		switch ($field_name) {
			case "rule_id":
			case "object_type":
			case "metadata":
			case "operator":
			case "operator_value_type":
			case "receiver_type":
			case "interval":
				return intval($field_value);

			case "enabled":
			case "operator_negated":
			case "operator_case_sensitive":
				return boolval($field_value);

			case "receiver":
				return json_decode($field_value);

			case "last_check":
				if ($field_value !== NULL) {
					return new ilDateTime($field_value, IL_CAL_DATETIME);
				} else {
					return NULL;
				}

			default:
				return NULL;
		}
	}


	/**
	 * @return int
	 */
	public function getRuleId(): int {
		return $this->rule_id;
	}


	/**
	 * @param int $rule_id
	 */
	public function setRuleId(int $rule_id)/*: void*/ {
		$this->rule_id = $rule_id;
	}


	/**
	 * @return bool
	 */
	public function isEnabled(): bool {
		return $this->enabled;
	}


	/**
	 * @param bool $enabled
	 */
	public function setEnabled(bool $enabled)/*: void*/ {
		$this->enabled = $enabled;
	}


	/**
	 * @return string
	 */
	public function getTitle(): string {
		return $this->title;
	}


	/**
	 * @param string $title
	 */
	public function setTitle(string $title)/*: void*/ {
		$this->title = $title;
	}


	/**
	 * @return string
	 */
	public function getDescription(): string {
		return $this->description;
	}


	/**
	 * @param string $description
	 */
	public function setDescription(string $description)/*: void*/ {
		$this->description = $description;
	}


	/**
	 * @return int
	 */
	public function getObjectType(): int {
		return $this->object_type;
	}


	/**
	 * @param int $object_type
	 */
	public function setObjectType(int $object_type)/*: void*/ {
		$this->object_type = $object_type;
	}


	/**
	 * @return int
	 */
	public function getMetadata(): int {
		return $this->metadata;
	}


	/**
	 * @param int $metadata
	 */
	public function setMetadata(int $metadata)/*: void*/ {
		$this->metadata = $metadata;
	}


	/**
	 * @return int
	 */
	public function getOperator(): int {
		return $this->operator;
	}


	/**
	 * @param int $operator
	 */
	public function setOperator(int $operator)/*: void*/ {
		$this->operator = $operator;
	}


	/**
	 * @return bool
	 */
	public function isOperatorNegated(): bool {
		return $this->operator_negated;
	}


	/**
	 * @param bool $operator_negated
	 */
	public function setOperatorNegated(bool $operator_negated)/*: void*/ {
		$this->operator_negated = $operator_negated;
	}


	/**
	 * @return bool
	 */
	public function isOperatorCaseSensitive(): bool {
		return $this->operator_case_sensitive;
	}


	/**
	 * @param bool $operator_case_sensitive
	 */
	public function setOperatorCaseSensitive(bool $operator_case_sensitive)/*: void*/ {
		$this->operator_case_sensitive = $operator_case_sensitive;
	}


	/**
	 * @return int
	 */
	public function getOperatorValueType(): int {
		return $this->operator_value_type;
	}


	/**
	 * @param int $operator_value_type
	 */
	public function setOperatorValueType(int $operator_value_type)/*: void*/ {
		$this->operator_value_type = $operator_value_type;
	}


	/**
	 * @return string
	 */
	public function getOperatorValue(): string {
		return $this->operator_value;
	}


	/**
	 * @param string $operator_value
	 */
	public function setOperatorValue(string $operator_value)/*: void*/ {
		$this->operator_value = $operator_value;
	}


	/**
	 * @return string
	 */
	public function getMailTemplateName(): string {
		return $this->mail_template_name;
	}


	/**
	 * @param string $mail_template_name
	 */
	public function setMailTemplateName(string $mail_template_name)/*: void*/ {
		$this->mail_template_name = $mail_template_name;
	}


	/**
	 * @return int
	 */
	public function getReceiverType(): int {
		return $this->receiver_type;
	}


	/**
	 * @param int $receiver_type
	 */
	public function setReceiverType(int $receiver_type)/*: void*/ {
		$this->receiver_type = $receiver_type;
	}


	/**
	 * @return array
	 */
	public function getReceiver(): array {
		return $this->receiver;
	}


	/**
	 * @param array $receiver
	 */
	public function setReceiver(array $receiver)/*: void*/ {
		$this->receiver = $receiver;
	}


	/**
	 * @return int
	 */
	public function getInterval(): int {
		return $this->interval;
	}


	/**
	 * @param int $interval
	 */
	public function setInterval(int $interval)/*: void*/ {
		$this->interval = $interval;
	}


	/**
	 * @return ilDateTime|null
	 */
	public function getLastCheck()/*: ?ilDateTime*/ {
		return $this->last_check;
	}


	/**
	 * @param ilDateTime $last_check
	 */
	public function setLastCheck(ilDateTime $last_check)/*: void*/ {
		$this->last_check = $last_check;
	}
}
