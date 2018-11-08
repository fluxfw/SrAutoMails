<?php

namespace srag\Plugins\SrAutoMails\Access;

use ilOrgUnitAssistantPlugin;
use srag\AVL\Plugins\OrgUnitAssistant\Utils\OrgUnitAssistantTrait;
use srag\DIC\DICTrait;

/**
 * Class OrgUnits
 *
 * @package srag\Plugins\SrAutoMails\Access
 *
 * @author  studer + raimann ag - Team Custom 1 <support-custom1@studer-raimann.ch>
 */
final class OrgUnits {

	use DICTrait;
	use OrgUnitAssistantTrait;
	const PLUGIN_CLASS_NAME = ilOrgUnitAssistantPlugin::class;
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
}
