<?php

namespace srag\Plugins\SrAutoMails\Access;

use ilObjCourse;
use ilSrAutoMailsPlugin;
use srag\DIC\SrAutoMails\DICTrait;
use srag\Plugins\SrAutoMails\Utils\SrAutoMailsTrait;

/**
 * Class Courses
 *
 * @package srag\Plugins\SrAutoMails\Access
 *
 * @author  studer + raimann ag - Team Custom 1 <support-custom1@studer-raimann.ch>
 */
final class Courses {

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
	 * Courses constructor
	 */
	private function __construct() {

	}


	/**
	 * @return ilObjCourse[]
	 */
	public function getCourses(): array {
		$result = self::dic()->database()->queryF('SELECT obj_id FROM object_data WHERE type=%s', [ "text" ], [ "crs" ]);

		$array = [];

		while (($row = $result->fetchAssoc()) !== false) {
			$array[] = new ilObjCourse($row["obj_id"], false);
		}

		return $array;
	}
}
