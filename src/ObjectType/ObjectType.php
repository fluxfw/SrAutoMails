<?php

namespace srag\Plugins\SrAutoMails\ObjectType;

use ilSrAutoMailsConfigGUI;
use ilSrAutoMailsPlugin;
use srag\DIC\DICTrait;
use srag\Plugins\SrAutoMails\Utils\SrAutoMailsTrait;

/**
 * Class ObjectType
 *
 * @package srag\Plugins\SrAutoMails\ObjectType
 *
 * @author  studer + raimann ag - Team Custom 1 <support-custom1@studer-raimann.ch>
 */
abstract class ObjectType {

	use DICTrait;
	use SrAutoMailsTrait;
	const PLUGIN_CLASS_NAME = ilSrAutoMailsPlugin::class;
	/**
	 * @var int
	 *
	 * @abstract
	 */
	const OBJECT_TYPE = "";


	/**
	 * @return array
	 */
	public final function getObjectPropertiesText() {
		return array_map(function (string $object_property): string {
			return self::plugin()->translate("object_property_" . $object_property, ilSrAutoMailsConfigGUI::LANG_MODULE_CONFIG);
		}, $this->getObjectProperties());
	}


	/**
	 * @return array
	 */
	public abstract function getObjectProperties(): array;


	/**
	 * @return array
	 */
	public final function getReceiverPropertiesText() {
		return array_map(function (string $object_property): string {
			return self::plugin()->translate("receiver_" . $object_property, ilSrAutoMailsConfigGUI::LANG_MODULE_CONFIG);
		}, $this->getReceiverProperties());
	}


	/**
	 * @return array
	 */
	public abstract function getReceiverProperties(): array;
}
