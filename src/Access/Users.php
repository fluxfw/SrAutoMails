<?php

namespace srag\Plugins\SrAutoMails\Access;

use ilDBConstants;
use ilSrAutoMailsPlugin;
use srag\DIC\SrAutoMails\DICTrait;
use srag\Plugins\SrAutoMails\Utils\SrAutoMailsTrait;

/**
 * Class Users
 *
 * @package srag\Plugins\SrAutoMails\Access
 */
final class Users
{

    use DICTrait;
    use SrAutoMailsTrait;

    const PLUGIN_CLASS_NAME = ilSrAutoMailsPlugin::class;
    /**
     * @var self|null
     */
    protected static $instance = null;


    /**
     * Users constructor
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
     * @return array
     */
    public function getUsers() : array
    {
        $result = self::dic()->database()
            ->queryF('SELECT usr_id, firstname, lastname FROM usr_data WHERE active=%s', [ilDBConstants::T_INTEGER], [1]);

        $array = [];

        while (($row = $result->fetchAssoc()) !== false) {
            $array[$row["usr_id"]] = $row["lastname"] . ", " . $row["firstname"];
        }

        return $array;
    }
}
