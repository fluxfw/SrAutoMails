<?php

namespace srag\Plugins\SrAutoMails\Access;

use ilSrAutoMailsPlugin;
use srag\DIC\DICTrait;
use srag\Plugins\SrAutoMails\Utils\SrAutoMailsTrait;

/**
 * Class Metadata
 *
 * @package srag\Plugins\SrAutoMails\Access
 *
 * @author  studer + raimann ag - Team Custom 1 <support-custom1@studer-raimann.ch>
 */
final class Metadata {

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
	 * Metadata constructor
	 */
	private function __construct() {

	}


	/**
	 * @return array
	 */
	public function getMetadata(): array {
		$result = self::dic()->database()->queryF('SELECT field_id, title FROM adv_mdf_definition', [], []);

		$array = [];

		while (($row = $result->fetchAssoc()) !== false) {
			$array[$row["field_id"]] = $row["title"];
		}

		return $array;
	}
}
