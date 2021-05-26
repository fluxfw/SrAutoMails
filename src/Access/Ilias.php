<?php

namespace srag\Plugins\SrAutoMails\Access;

use ilSrAutoMailsPlugin;
use srag\DIC\SrAutoMails\DICTrait;
use srag\Plugins\SrAutoMails\Utils\SrAutoMailsTrait;

/**
 * Class Ilias
 *
 * @package srag\Plugins\SrAutoMails\Access
 */
final class Ilias
{

    use DICTrait;
    use SrAutoMailsTrait;

    const PLUGIN_CLASS_NAME = ilSrAutoMailsPlugin::class;
    /**
     * @var self|null
     */
    protected static $instance = null;


    /**
     * Ilias constructor
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
     * @return Courses
     */
    public function courses() : Courses
    {
        return Courses::getInstance();
    }


    /**
     * @return Metadata
     */
    public function metadata() : Metadata
    {
        return Metadata::getInstance();
    }


    /**
     * @return OrgUnits
     */
    public function orgUnits() : OrgUnits
    {
        return OrgUnits::getInstance();
    }


    /**
     * @return Users
     */
    public function users() : Users
    {
        return Users::getInstance();
    }
}
