<?php

namespace srag\Plugins\SrAutoMails\Rule;

use ActiveRecord;
use arConnector;
use ilSrAutoMailsPlugin;
use srag\DIC\DICTrait;
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
	const OBJECT_TYPE_COURSE = "crs";
	/**
	 * @var array
	 */
	public static $object_types = [ self::OBJECT_TYPE_COURSE => self::OBJECT_TYPE_COURSE ];


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
	 * @var string
	 *
	 * @con_has_field    true
	 * @con_fieldtype    text
	 * @con_is_notnull   true
	 */
	protected $object_type = "";
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
	public function sleep($field_name) {
		$field_value = $this->{$field_name};

		switch ($field_name) {
			case "enabled":
				return ($field_value ? 1 : 0);
				break;

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
	public function wakeUp($field_name, $field_value) {
		switch ($field_name) {
			case "rule_id":
				return intval($field_value);
				break;

			case "enabled":
				return boolval($field_value);
				break;

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
	 * @return string
	 */
	public function getObjectType(): string {
		return $this->object_type;
	}


	/**
	 * @param string $object_type
	 */
	public function setObjectType(string $object_type)/*: void*/ {
		$this->object_type = $object_type;
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
}
