<?php

namespace srag\Plugins\SrAutoMails\Rule;

use ilSrAutoMailsConfigGUI;
use ilSrAutoMailsPlugin;
use srag\DIC\DICTrait;
use srag\Plugins\SrAutoMails\Utils\SrAutoMailsTrait;

/**
 * Class Rules
 *
 * @package srag\Plugins\SrAutoMails\Rule
 *
 * @author  studer + raimann ag - Team Custom 1 <support-custom1@studer-raimann.ch>
 */
class Rules {

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
	 * Rules constructor
	 */
	private function __construct() {

	}


	/**
	 * @return array
	 */
	public function getObjectTypes(): array {
		return array_map(function (string $object_type): string {
			return self::plugin()->translate("object_type_" . $object_type, ilSrAutoMailsConfigGUI::LANG_MODULE_CONFIG);
		}, Rule::$object_types);
	}


	/**
	 * @param string    $title
	 * @param string    $description
	 * @param string    $object_type
	 * @param bool|null $enabled
	 *
	 * @return array
	 */
	public function getRulesArray(string $title = "", string $description = "", string $object_type = "", /*?*/
		bool $enabled = NULL): array {
		$where = Rule::where([]);

		if (!empty($title)) {
			$where = $where->where([ "title" => '%' . $title . '%' ], "LIKE");
		}

		if (!empty($description)) {
			$where = $where->where([ "description" => '%' . $description . '%' ], "LIKE");
		}

		if (!empty($object_type)) {
			$where = $where->where([ "object_type" => '%' . $object_type . '%' ], "LIKE");
		}

		if ($enabled !== NULL) {
			$where = $where->where([ "enabled" => $enabled ]);
		}

		return $where->getArray();
	}


	/**
	 * @param int $rule_id
	 *
	 * @return Rule|null
	 */
	public function getRuleById(int $rule_id)/*: ?Rule*/ {
		/**
		 * @var Rule|null $log
		 */

		$log = Rule::where([ "rule_id" => $rule_id ])->first();

		return $log;
	}
}
