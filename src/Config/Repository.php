<?php

namespace srag\Plugins\SrAutoMails\Config;

use ilSrAutoMailsPlugin;
use srag\ActiveRecordConfig\SrAutoMails\Config\AbstractFactory;
use srag\ActiveRecordConfig\SrAutoMails\Config\AbstractRepository;
use srag\ActiveRecordConfig\SrAutoMails\Config\Config;
use srag\Plugins\SrAutoMails\Log\DeleteOldLogsJob;
use srag\Plugins\SrAutoMails\Utils\SrAutoMailsTrait;

/**
 * Class Repository
 *
 * @package srag\Plugins\SrAutoMails\Config
 */
final class Repository extends AbstractRepository
{

    use SrAutoMailsTrait;

    const PLUGIN_CLASS_NAME = ilSrAutoMailsPlugin::class;
    /**
     * @var self|null
     */
    protected static $instance = null;


    /**
     * Repository constructor
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


    /**
     * @inheritDoc
     *
     * @return Factory
     */
    public function factory() : AbstractFactory
    {
        return Factory::getInstance();
    }


    /**
     * @inheritDoc
     */
    protected function getFields() : array
    {
        return [
            DeleteOldLogsJob::KEY_KEEP_OLD_LOGS_TIME => [Config::TYPE_INTEGER, 0],
        ];
    }


    /**
     * @inheritDoc
     */
    protected function getTableName() : string
    {
        return ilSrAutoMailsPlugin::PLUGIN_ID . "_config";
    }
}
