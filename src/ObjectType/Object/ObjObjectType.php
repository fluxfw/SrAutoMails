<?php

namespace srag\Plugins\SrAutoMails\ObjectType\Object;

use ilObject;
use srag\Plugins\SrAutoMails\ObjectType\ObjectType;

/**
 * Class ObjObjectType
 *
 * @package srag\Plugins\SrAutoMails\ObjectType\Object
 *
 * @author  studer + raimann ag - Team Custom 1 <support-custom1@studer-raimann.ch>
 */
abstract class ObjObjectType extends ObjectType {

	/**
	 * @inheritdoc
	 */
	public function getMailPlaceholderKeyTypes(): array {
		return array_merge(parent::getMailPlaceholderKeyTypes(), [
			"object" => "object " . ilObject::class
		]);
	}


	/**
	 * @param ilObject $object
	 *
	 * @return int
	 */
	public final function getObjectId($object): int {
		return intval($object->getId());
	}
}
