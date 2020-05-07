<?php

namespace srag\Plugins\SrAutoMails\Sent;

use ilSrAutoMailsPlugin;
use srag\DIC\SrAutoMails\DICTrait;
use srag\Plugins\SrAutoMails\Utils\SrAutoMailsTrait;
use stdClass;

/**
 * Class Factory
 *
 * @package srag\Plugins\SrAutoMails\Sent
 *
 * @author  studer + raimann ag - Team Custom 1 <support-custom1@studer-raimann.ch>
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
     * Factory constructor
     */
    private function __construct()
    {

    }


    /**
     * @param stdClass $data
     *
     * @return Sent
     */
    public function fromDB(stdClass $data) : Sent
    {
        $sent = $this->newInstance();

        $sent->setRuleId($data->rule_id);
        $sent->setObjectId($data->object_id);
        $sent->setUserId($data->user_id);

        return $sent;
    }


    /**
     * @return Sent
     */
    public function newInstance() : Sent
    {
        $rule = new Sent();

        return $rule;
    }
}
