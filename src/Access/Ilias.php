<?php

namespace srag\Plugins\SrAutoMails\Access;

use ilSrAutoMailsPlugin;
use srag\DIC\DICTrait;
use srag\Plugins\SrAutoMails\Utils\SrAutoMailsTrait;

/**
 * Class Ilias
 *
 * @package srag\Plugins\SrAutoMails\Access
 *
 * @author  studer + raimann ag - Team Custom 1 <support-custom1@studer-raimann.ch>
 */
final class Ilias {

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
	 * Ilias constructor
	 */
	private function __construct() {

	}


	/**
	 * @return Metadata
	 */
	public function metadata(): Metadata {
		return Metadata::getInstance();
	}


	/**
	 * @return OrgUnits
	 */
	public function orgUnits(): OrgUnits {
		return OrgUnits::getInstance();
	}


	/**
	 * @return Users
	 */
	public function users(): Users {
		return Users::getInstance();
	}
}
