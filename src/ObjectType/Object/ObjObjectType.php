<?php

namespace srag\Plugins\SrAutoMails\ObjectType\Object;

use ilObject;
use srag\Plugins\SrAutoMails\ObjectType\ObjectType;

/**
 * Class ObjObjectType
 *
 * @package srag\Plugins\SrAutoMails\ObjectType\Object
 */
abstract class ObjObjectType extends ObjectType
{

    const TYPES = ["crs"];


    /**
     * @inheritDoc
     */
    public function getMailPlaceholderKeyTypes() : array
    {
        return array_merge(parent::getMailPlaceholderKeyTypes(), [
            "object" => "object " . ilObject::class
        ]);
    }


    /**
     * @param ilObject $object
     *
     * @return int
     */
    public final function getObjectId($object) : int
    {
        return intval($object->getId());
    }
}
