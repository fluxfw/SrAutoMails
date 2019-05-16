<?php

namespace srag\Plugins\SrAutoMails\ObjectType;

use ilSrAutoMailsConfigGUI;
use ilSrAutoMailsPlugin;
use srag\DIC\SrAutoMails\DICTrait;
use srag\Plugins\SrAutoMails\Utils\SrAutoMailsTrait;

/**
 * Class Repository
 *
 * @package srag\Plugins\SrAutoMails\ObjectType
 *
 * @author  studer + raimann ag - Team Custom 1 <support-custom1@studer-raimann.ch>
 */
final class Repository {

	use DICTrait;
	use SrAutoMailsTrait;
	const PLUGIN_CLASS_NAME = ilSrAutoMailsPlugin::class;
	const OBJECT_TYPE_COURSE = 1;
	/**
	 * @var array
	 */
	protected static $object_types = [
		self::OBJECT_TYPE_COURSE => "course"
	];
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
	 * @return Factory
	 */
	public function factory(): Factory {
		return Factory::getInstance();
	}


	/**
	 * @return ObjectType[]
	 */
	public function getObjectTypes(): array {
		return array_map(function (string $object_type): ObjectType {
			return $this->factory()->getByObjectType($object_type);
		}, array_keys(self::$object_types));
	}


	/**
	 * @return array
	 */
	public function getObjectTypesText(): array {
		return array_map(function (string $object_type): string {
			return self::plugin()->translate("object_" . $object_type, ilSrAutoMailsConfigGUI::LANG_MODULE_CONFIG);
		}, self::$object_types);
	}
}
