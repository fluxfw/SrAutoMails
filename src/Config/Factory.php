<?php

namespace srag\Plugins\SrAutoMails\Config;

use ilSrAutoMailsPlugin;
use srag\ActiveRecordConfig\SrAutoMails\Config\AbstractFactory;
use srag\Plugins\SrAutoMails\Utils\SrAutoMailsTrait;

/**
 * Class Factory
 *
 * @package srag\Plugins\SrAutoMails\Config
 */
final class Factory extends AbstractFactory
{

    use SrAutoMailsTrait;

    const PLUGIN_CLASS_NAME = ilSrAutoMailsPlugin::class;
    /**
     * @var self|null
     */
    protected static $instance = null;


    /**
     * Factory constructor
     */
    protected function __construct()
    {
        parent::__construct();
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
}
