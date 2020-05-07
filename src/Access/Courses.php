<?php

namespace srag\Plugins\SrAutoMails\Access;

use ilDBConstants;
use ilLPStatusWrapper;
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
final class Courses
{

    use DICTrait;
    use SrAutoMailsTrait;

    const PLUGIN_CLASS_NAME = ilSrAutoMailsPlugin::class;
    /**
     * @var self|null
     */
    protected static $instance = null;


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
     * Courses constructor
     */
    private function __construct()
    {

    }


    /**
     * @return ilObjCourse[]
     */
    public function getCourses() : array
    {
        $result = self::dic()->database()->queryF('SELECT obj_id FROM object_data WHERE type=%s', [ilDBConstants::T_TEXT], ["crs"]);

        $array = [];

        while (($row = $result->fetchAssoc()) !== false) {
            $array[] = new ilObjCourse($row["obj_id"], false);
        }

        return $array;
    }


    /**
     * @param int $obj_id
     *
     * @return array
     */
    public function getCompletedUsers(int $obj_id) : array
    {
        return ilLPStatusWrapper::_lookupCompletedForObject($obj_id);
    }
}
