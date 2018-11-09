<?php

namespace srag\Plugins\SrAutoMails\Sent;

use ActiveRecord;
use arConnector;
use ilSrAutoMailsPlugin;
use srag\DIC\DICTrait;

/**
 * Class Sent
 *
 * @package srag\Plugins\SrAutoMails\Sent
 *
 * @author  studer + raimann ag - Team Custom 1 <support-custom1@studer-raimann.ch>
 */
class Sent extends ActiveRecord {

	use DICTrait;
	const TABLE_NAME = "srauma_sent";
	const PLUGIN_CLASS_NAME = ilSrAutoMailsPlugin::class;


	/**
	 * @return string
	 */
	public function getConnectorContainerName(): string {
		return self::TABLE_NAME;
	}


	/**
	 * @return string
	 *
	 * @deprecated
	 */
	public static function returnDbTableName(): string {
		return self::TABLE_NAME;
	}


	/**
	 * @param int $rule_id
	 * @param int $object_id
	 * @param int $user_id
	 *
	 * @return self|null
	 */
	public static function getSent(int $rule_id, int $object_id, int $user_id)/*: ?self*/ {
		/**
		 * @var self|null $sent
		 */

		$sent = self::where([
			"rule_id" => $rule_id,
			"object_id" => $object_id,
			"user_id" => $user_id
		])->first();

		return $sent;
	}


	/**
	 * @param int $rule_id
	 * @param int $object_id
	 * @param int $user_id
	 *
	 * @return bool
	 */
	public static function hasSent(int $rule_id, int $object_id, int $user_id): bool {
		$sent = self::getSent($rule_id, $object_id, $user_id);

		return ($sent !== NULL);
	}


	/**
	 * @param int $rule_id
	 * @param int $object_id
	 * @param int $user_id
	 */
	public static function sent(int $rule_id, int $object_id, int $user_id)/*: void*/ {
		$sent = self::getSent($rule_id, $object_id, $user_id);

		if ($sent === NULL) {
			$sent = new self();
			$sent->setRuleId($rule_id);
			$sent->setObjectId($object_id);
			$sent->setUserId($user_id);
			$sent->store();
		}
	}


	/**
	 * @var int
	 *
	 * @con_has_field   true
	 * @con_fieldtype   integer
	 * @con_length      8
	 * @con_is_notnull  true
	 * @con_is_primary  true
	 */
	protected $id;
	/**
	 * @var int
	 *
	 * @con_has_field   true
	 * @con_fieldtype   integer
	 * @con_length      8
	 * @con_is_notnull  true
	 */
	protected $rule_id;
	/**
	 * @var int
	 *
	 * @con_has_field   true
	 * @con_fieldtype   integer
	 * @con_length      8
	 * @con_is_notnull  true
	 */
	protected $object_id;
	/**
	 * @var int
	 *
	 * @con_has_field   true
	 * @con_fieldtype   integer
	 * @con_length      8
	 * @con_is_notnull  true
	 */
	protected $user_id;


	/**
	 * Sent constructor
	 *
	 * @param int              $primary_key_value
	 * @param arConnector|null $connector
	 */
	public function __construct(/*int*/
		$primary_key_value = 0, /*?*/
		arConnector $connector = NULL) {
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
			case "id":
			case "rule_id":
			case "object_id":
			case "user_id":
				return intval($field_value);
				break;

			default:
				return NULL;
		}
	}


	/**
	 * @return int
	 */
	public function getId(): int {
		return $this->id;
	}


	/**
	 * @param int $id
	 */
	public function setId(int $id)/*: void*/ {
		$this->id = $id;
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
	 * @return int
	 */
	public function getObjectId(): int {
		return $this->object_id;
	}


	/**
	 * @param int $object_id
	 */
	public function setObjectId(int $object_id)/*: void*/ {
		$this->object_id = $object_id;
	}


	/**
	 * @return int
	 */
	public function getUserId(): int {
		return $this->user_id;
	}


	/**
	 * @param int $user_id
	 */
	public function setUserId(int $user_id)/*: void*/ {
		$this->user_id = $user_id;
	}
}
