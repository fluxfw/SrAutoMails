<?php

namespace srag\Plugins\SrAutoMails\Log;

use ilDateTime;
use ilSrAutoMailsPlugin;
use srag\DIC\SrAutoMails\DICTrait;
use srag\Plugins\SrAutoMails\Utils\SrAutoMailsTrait;
use stdClass;
use Throwable;

/**
 * Class Factory
 *
 * @package srag\Plugins\SrAutoMails\Log
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
     * @param stdClass $data
     *
     * @return Log
     */
    public function fromDB(stdClass $data) : Log
    {
        $log = $this->newInstance()->withLogId($data->log_id)->withObjectId($data->object_id)->withRuleId($data->rule_id)->withUserId($data->user_id)->withExecuteUserId($data->execute_user_id)
            ->withDate(new ilDateTime($data->date, IL_CAL_DATETIME))->withStatus($data->status)->withMessage($data->message);

        return $log;
    }


    /**
     * @return DeleteOldLogsJob
     */
    public function newDeleteOldLogsJobInstance() : DeleteOldLogsJob
    {
        $job = new DeleteOldLogsJob();

        return $job;
    }


    /**
     * @param Throwable $ex
     * @param int|null  $object_id
     * @param int|null  $rule_id
     * @param int|null  $user_id
     *
     * @return Log
     */
    public function newExceptionInstance(Throwable $ex, /*?*/ int $object_id = null, /*?*/ int $rule_id = null, /*?*/ int $user_id = null) : Log
    {
        $log = $this->newObjectRuleUserInstance($object_id, $rule_id, $user_id)->withMessage($ex->getMessage());

        return $log;
    }


    /**
     * @return Log
     */
    public function newInstance() : Log
    {
        $log = new Log();

        return $log;
    }


    /**
     * @param int|null $object_id
     * @param int|null $rule_id
     * @param int|null $user_id
     *
     * @return Log
     */
    public function newObjectRuleUserInstance(/*?*/ int $object_id = null, /*?*/ int $rule_id = null, /*?*/ int $user_id = null) : Log
    {
        $log = $this->newInstance()->withObjectId($object_id)->withRuleId($rule_id)->withUserId($user_id);

        return $log;
    }


    /**
     * @param LogsMailGUI $parent
     * @param string      $cmd
     *
     * @return LogsTableGUI
     */
    public function newTableInstance(LogsMailGUI $parent, string $cmd = LogsMailGUI::CMD_LIST_LOGS) : LogsTableGUI
    {
        $table = new LogsTableGUI($parent, $cmd);

        return $table;
    }
}
