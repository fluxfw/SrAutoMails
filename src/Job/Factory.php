<?php

namespace srag\Plugins\SrAutoMails\Job;

use ilCronJob;
use ilSrAutoMailsPlugin;
use srag\DIC\SrAutoMails\DICTrait;
use srag\Plugins\SrAutoMails\Log\DeleteOldLogsJob;
use srag\Plugins\SrAutoMails\Rule\RulesJob;
use srag\Plugins\SrAutoMails\Utils\SrAutoMailsTrait;

/**
 * Class Factory
 *
 * @package srag\Plugins\SrAutoMails\Job
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
     * @param string $job_id
     *
     * @return ilCronJob|null
     */
    public function newInstanceById(string $job_id) : ?ilCronJob
    {
        switch ($job_id) {
            case RulesJob::CRON_JOB_ID:
                return self::srAutoMails()->rules()->factory()->newJobInstance();

            case DeleteOldLogsJob::CRON_JOB_ID:
                return self::srAutoMails()->logs()->factory()->newDeleteOldLogsJobInstance();

            default:
                return null;
        }
    }


    /**
     * @return ilCronJob[]
     */
    public function newInstances() : array
    {
        return [
            self::srAutoMails()->rules()->factory()->newJobInstance(),
            self::srAutoMails()->logs()->factory()->newDeleteOldLogsJobInstance()
        ];
    }
}
