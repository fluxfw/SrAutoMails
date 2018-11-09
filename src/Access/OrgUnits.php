<?php

namespace srag\Plugins\SrAutoMails\Access;

use ilSrAutoMailsPlugin;
use srag\DIC\DICTrait;
use srag\Plugins\SrAutoMails\Utils\SrAutoMailsTrait;

/**
 * Class OrgUnits
 *
 * @package srag\Plugins\SrAutoMails\Access
 *
 * @author  studer + raimann ag - Team Custom 1 <support-custom1@studer-raimann.ch>
 */
final class OrgUnits {

	use DICTrait;
	use SrAutoMailsTrait;
	const PLUGIN_CLASS_NAME = ilSrAutoMailsPlugin::class;
	/**
	 * @var self
	 */
	protected static $instance = NULL;


	/**
	 * @return self
	 */
	public static function getInstance(): self {
		if (self::$instance === NULL) {
			self::$instance = new self();
		}

		return self::$instance;
	}


	/**
	 * OrgUnits constructor
	 */
	private function __construct() {

	}


	/**
	 * @param int $user_id
	 *
	 * @return int[]
	 */
	public function getSuperiorsOfUser(int $user_id): array {
		$users = [];

		// TODO: Fill superiors

		return $users;
	}


	/**
	 * @param int[] $users
	 *
	 * @return int[]
	 */
	public function getSuperiorsOfUsers(array $users): array {
		$array = [];

		foreach ($users as $user_id) {
			$array = array_merge($array, $this->getSuperiorsOfUser($user_id));
		}

		return $array;
	}
}
