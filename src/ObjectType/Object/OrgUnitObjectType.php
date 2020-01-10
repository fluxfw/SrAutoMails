<?php

namespace srag\Plugins\SrAutoMails\ObjectType\Object;

use ilObjCourse;
use ilObjOrgUnit;
use srag\Plugins\SrAutoMails\ObjectType\Repository;

/**
 * Class OrgUnitObjectType
 *
 * @package srag\Plugins\SrAutoMails\ObjectType\Object
 *
 * @author  studer + raimann ag - Team Custom 1 <support-custom1@studer-raimann.ch>
 */
class OrgUnitObjectType extends ObjObjectType
{

    const OBJECT_TYPE = Repository::OBJECT_TYPE_ORG_UNIT;


    /**
     * @param ilObjCourse $object
     * @param array       $placeholders
     */
    protected function applyMailPlaceholders($object, array &$placeholders)/*: void*/
    {
        $placeholders = array_merge($placeholders, [

        ]);
    }


    /**
     * @inheritDoc
     */
    public function getMailPlaceholderKeyTypes() : array
    {
        return array_merge(parent::getMailPlaceholderKeyTypes(), [
            "object" => "object " . ilObjOrgUnit::class
        ]);
    }


    /**
     * @inheritDoc
     */
    protected function getObjectProperties() : array
    {
        return [

        ];
    }


    /**
     * @param ilObjCourse $object
     * @param string      $object_property
     *
     * @return string|int
     */
    protected function getObjectProperty($object, string $object_property)
    {
        switch ($object_property) {
            default:
                return null;
        }
    }


    /**
     * @inheritDoc
     */
    public function getObjects() : array
    {
        return self::srAutoMails()->ilias()->orgUnits()->getOrgUnits();
    }


    /**
     * @inheritDoc
     */
    protected function getReceiverProperties() : array
    {
        return [

        ];
    }


    /**
     * @param array       $receivers
     * @param ilObjCourse $object
     *
     * @return int[]
     */
    protected function getReceiverForObject(array $receivers, $object) : array
    {
        $array = [];

        return $array;
    }
}
