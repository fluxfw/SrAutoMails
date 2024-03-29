<?php

namespace srag\Plugins\SrAutoMails\ObjectType;

use ilSrAutoMailsPlugin;
use srag\DIC\SrAutoMails\DICTrait;
use srag\Plugins\SrAutoMails\ObjectType\Object\CourseObjectType;
use srag\Plugins\SrAutoMails\Utils\SrAutoMailsTrait;

/**
 * Class Factory
 *
 * @package srag\Plugins\SrAutoMails\ObjectType
 */
final class Factory
{

    use DICTrait;
    use SrAutoMailsTrait;

    const PLUGIN_CLASS_NAME = ilSrAutoMailsPlugin::class;
    /**
     * @var self|null
     */
    protected static $instance = null;


    /**
     * Factory constructor
     */
    private function __construct()
    {

    }


    /**
     * @return self
     */
    public static function getInstance() : self
    {
        if (self::$instance === null) {
            self::$instance = new self();
        }

        return self::$instance;
    }


    /**
     * @return CourseObjectType
     */
    public function course() : CourseObjectType
    {
        return new CourseObjectType();
    }


    /**
     * @param string $object_type
     *
     * @return ObjectType|null
     */
    public function getByObjectType(string $object_type) : ?ObjectType
    {
        switch ($object_type) {
            case Repository::OBJECT_TYPE_COURSE:
                return $this->course();

            default:
                return null;
        }
    }
}
