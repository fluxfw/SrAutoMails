<?php

namespace srag\Plugins\SrAutoMails\Sent;

use ilSrAutoMailsPlugin;
use srag\DIC\SrAutoMails\DICTrait;
use srag\Plugins\SrAutoMails\Utils\SrAutoMailsTrait;

/**
 * Class Repository
 *
 * @package srag\Plugins\SrAutoMails\Sent
 *
 * @author  studer + raimann ag - Team Custom 1 <support-custom1@studer-raimann.ch>
 */
final class Repository {

	use DICTrait;
	use SrAutoMailsTrait;
	const PLUGIN_CLASS_NAME = ilSrAutoMailsPlugin::class;
	/**
	 * @var self
	 */
	protected static $instance = null;


	/**
	 * @return self
	 */
	public static function getInstance(): self {
		if (self::$instance === null) {
			self::$instance = new self();
		}

		return self::$instance;
	}


	/**
	 * Repository constructor
	 */
	private function __construct() {

	}


	/**
	 * @param Sent $sent
	 */
	protected function delete(Sent $sent)/*: void*/ {
		$sent->delete();
	}


	/**
	 * @return Factory
	 */
	protected function factory(): Factory {
		return Factory::getInstance();
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

		return ($sent !== null);
	}


	/**
	 * @param int $rule_id
	 * @param int $object_id
	 * @param int $user_id
	 */
	public function sent(int $rule_id, int $object_id, int $user_id)/*: void*/ {
		$sent = $this->getSent($rule_id, $object_id, $user_id);

		if ($sent === null) {
			$sent = $this->factory()->newInstance();
			$sent->setRuleId($rule_id);
			$sent->setObjectId($object_id);
			$sent->setUserId($user_id);
			$this->store($sent);
		}
	}


	/**
	 * @param Sent $sent
	 */
	protected function store(Sent $sent)/*: void*/ {
		$sent->store();
	}


	/**
	 * @param int $rule_id
	 * @param int $object_id
	 * @param int $user_id
	 */
	public function unsent(int $rule_id, int $object_id, int $user_id)/*: void*/ {
		$sent = $this->getSent($rule_id, $object_id, $user_id);

		if ($sent !== null) {
			$this->delete($sent);
		}
	}
}
