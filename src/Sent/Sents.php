<?php

namespace srag\Plugins\SrAutoMails\Sent;

use ilSrAutoMailsPlugin;
use srag\DIC\SrAutoMails\DICTrait;
use srag\Plugins\SrAutoMails\Utils\SrAutoMailsTrait;

/**
 * Class Sents
 *
 * @package srag\Plugins\SrAutoMails\Sent
 *
 * @author  studer + raimann ag - Team Custom 1 <support-custom1@studer-raimann.ch>
 */
final class Sents {

	use DICTrait;
	use SrAutoMailsTrait;
	const PLUGIN_CLASS_NAME = ilSrAutoMailsPlugin::class;
	/**
	 * @var Sents
	 */
	protected static $instance = NULL;


	/**
	 * @return Sents
	 */
	public static function getInstance(): Sents {
		if (self::$instance === NULL) {
			self::$instance = new Sents();
		}

		return self::$instance;
	}


	/**
	 * Sents constructor
	 */
	private function __construct() {

	}


	/**
	 * @param int $rule_id
	 * @param int $object_id
	 * @param int $user_id
	 *
	 * @return Sent|null
	 */
	protected function getSent(int $rule_id, int $object_id, int $user_id)/*: ?Sent*/ {
		/**
		 * @var Sent|null $sent
		 */

		$sent = Sent::where([
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
	public function hasSent(int $rule_id, int $object_id, int $user_id): bool {
		$sent = $this->getSent($rule_id, $object_id, $user_id);

		return ($sent !== NULL);
	}


	/**
	 * @param int $rule_id
	 * @param int $object_id
	 * @param int $user_id
	 */
	public function sent(int $rule_id, int $object_id, int $user_id)/*: void*/ {
		$sent = $this->getSent($rule_id, $object_id, $user_id);

		if ($sent === NULL) {
			$sent = new Sent();
			$sent->setRuleId($rule_id);
			$sent->setObjectId($object_id);
			$sent->setUserId($user_id);
			$sent->store();
		}
	}
}
