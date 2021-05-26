<?php

namespace srag\Plugins\SrAutoMails\Log;

use ilDateTime;
use ilDBConstants;
use ilSrAutoMailsPlugin;
use srag\DIC\SrAutoMails\DICTrait;
use srag\Plugins\SrAutoMails\Utils\SrAutoMailsTrait;
use Throwable;

/**
 * Class Repository
 *
 * @package srag\Plugins\SrAutoMails\Log
 */
final class Repository
{

    use DICTrait;
    use SrAutoMailsTrait;

    const PLUGIN_CLASS_NAME = ilSrAutoMailsPlugin::class;
    /**
     * @var self|null
     */
    protected static $instance = null;
    /**
     * @var Log[][]
     */
    protected $kept_logs = [];


    /**
     * Repository constructor
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
     * @param Log $log
     */
    public function deleteLog(Log $log)/*: void*/
    {
        self::dic()->database()->manipulateF('DELETE FROM ' . self::dic()->database()->quoteIdentifier(Log::TABLE_NAME)
            . " WHERE log_id=%s", [ilDBConstants::T_INTEGER], [$log->getLogId()]);
    }


    /**
     * @param int $keep_old_logs_time
     *
     * @return int
     */
    public function deleteOldLogs(int $keep_old_logs_time) : int
    {
        if (empty($keep_old_logs_time)) {
            return 0;
        }

        $time = time();
        $keep_old_logs_time_timestamp = ($time - ($keep_old_logs_time * 24 * 60 * 60));
        $keep_old_logs_time_date = new ilDateTime($keep_old_logs_time_timestamp, IL_CAL_UNIX);

        $count = self::dic()->database()->manipulateF('DELETE FROM ' . self::dic()->database()->quoteIdentifier(Log::TABLE_NAME) . ' WHERE date<%s', [ilDBConstants::T_TEXT],
            [$keep_old_logs_time_date->get(IL_CAL_DATETIME)]);

        self::dic()->database()->resetAutoIncrement(Log::TABLE_NAME, "log_id");

        return $count;
    }


    /**
     * @internal
     */
    public function dropTables()/*: void*/
    {
        self::dic()->database()->dropTable(Log::TABLE_NAME, false);
        self::dic()->database()->dropAutoIncrementTable(Log::TABLE_NAME);
    }


    /**
     * @return Factory
     */
    public function factory() : Factory
    {
        return Factory::getInstance();
    }


    /**
     * @param int $status
     *
     * @return Log[]
     */
    public function getKeptLogs(int $status) : array
    {
        if (isset($this->kept_logs[$status])) {
            return $this->kept_logs[$status];
        } else {
            return [];
        }
    }


    /**
     * @param int $log_id
     *
     * @return Log|null
     */
    public function getLogById(int $log_id)/*: ?Log*/
    {
        /**
         * @var Log|null $log
         */
        $log = self::dic()->database()->fetchObjectCallback(self::dic()->database()->queryF('SELECT * FROM ' . self::dic()->database()
                ->quoteIdentifier(Log::TABLE_NAME) . ' WHERE log_id=%s', [ilDBConstants::T_INTEGER], [$log_id]), [$this->factory(), "fromDB"]);

        return $log;
    }


    /**
     * @param array|null      $object_ids
     * @param string|null     $sort_by
     * @param string|null     $sort_by_direction
     * @param int|null        $limit_start
     * @param int|null        $limit_end
     * @param string|null     $message
     * @param ilDateTime|null $date_start
     * @param ilDateTime|null $date_end
     * @param int|null        $status
     * @param int|null        $user_id
     * @param int|null        $execute_user_id
     * @param array|null      $rule_ids
     *
     * @return Log[]
     */
    public function getLogs(/*?*/ array $object_ids = null, /*?*/ string $sort_by = null, /*?*/ string $sort_by_direction = null, /*?*/ int $limit_start = null, /*?*/ int $limit_end = null, /*?*/
        string $message = null, /*?*/ ilDateTime $date_start = null, /*?*/ ilDateTime $date_end = null, /*?*/ int $status = null, /*?*/ int $user_id = null, /*?*/ int $execute_user_id = null, /*?*/
        array $rule_ids = null
    ) : array {

        $sql = 'SELECT *';

        $sql .= $this->getLogsQuery($object_ids, $sort_by, $sort_by_direction, $limit_start, $limit_end, $message, $date_start, $date_end, $status, $user_id, $execute_user_id, $rule_ids);

        /**
         * @var Log[] $logs
         */
        $logs = self::dic()->database()->fetchAllCallback(self::dic()->database()->query($sql), [$this->factory(), "fromDB"]);

        return $logs;
    }


    /**
     * @param array|null      $object_ids
     * @param string|null     $message
     * @param ilDateTime|null $date_start
     * @param ilDateTime|null $date_end
     * @param int|null        $status
     * @param int|null        $user_id
     * @param int|null        $execute_user_id
     * @param array|null      $rule_ids
     *
     * @return int
     */
    public function getLogsCount(/*?*/ array $object_ids = null, /*?*/ string $message = null, /*?*/ ilDateTime $date_start = null, /*?*/ ilDateTime $date_end = null, /*?*/ int $status = null, /*?*/
        int $user_id = null, /*?*/ int $execute_user_id = null, /*?*/ array $rule_ids = null
    ) : int {

        $sql = 'SELECT COUNT(log_id) AS count';

        $sql .= $this->getLogsQuery($object_ids, null, null, null, null, $message, $date_start, $date_end, $status, $user_id, $execute_user_id, $rule_ids);

        $result = self::dic()->database()->query($sql);

        if (($row = $result->fetchAssoc()) !== false) {
            return intval($row["count"]);
        }

        return 0;
    }


    /**
     * @internal
     */
    public function installTables()/*: void*/
    {
        try {
            Log::updateDB();
        } catch (Throwable $ex) {
            // Fix Call to a member function getName() on null (Because not use ILIAS sequence)
        }

        self::dic()->database()->createAutoIncrement(Log::TABLE_NAME, "log_id"); // Using MySQL native autoincrement for performance
    }


    /**
     * @param Log $log
     */
    public function keepLog(Log $log)/*:void*/
    {
        if (!isset($this->kept_logs[$log->getStatus()])) {
            $this->kept_logs[$log->getStatus()] = [];
        }

        $this->kept_logs[$log->getStatus()][] = $log;
    }


    /**
     * @param Log $log
     */
    public function storeLog(Log $log)/*: void*/
    {
        $date = new ilDateTime(time(), IL_CAL_UNIX);

        if (empty($log->getLogId())) {
            $log->withDate($date);
            $log->withExecuteUserId(self::dic()->user()->getId());
        }

        $log->withLogId(self::dic()->database()->store(Log::TABLE_NAME, [
            "object_id"       => [ilDBConstants::T_INTEGER, $log->getObjectId()],
            "rule_id"         => [ilDBConstants::T_INTEGER, $log->getRuleId()],
            "user_id"         => [ilDBConstants::T_INTEGER, $log->getUserId()],
            "execute_user_id" => [ilDBConstants::T_INTEGER, $log->getExecuteUserId()],
            "date"            => [ilDBConstants::T_TEXT, $log->getDate()->get(IL_CAL_DATETIME)],
            "status"          => [ilDBConstants::T_INTEGER, $log->getStatus()],
            "message"         => [ilDBConstants::T_TEXT, $log->getMessage()]
        ], "log_id", $log->getLogId()));

        $this->keepLog($log);
    }


    /**
     * @param array|null      $object_ids
     * @param string|null     $sort_by
     * @param string|null     $sort_by_direction
     * @param int|null        $limit_start
     * @param int|null        $limit_end
     * @param string|null     $message
     * @param ilDateTime|null $date_start
     * @param ilDateTime|null $date_end
     * @param int|null        $status
     * @param int|null        $user_id
     * @param int|null        $execute_user_id
     * @param array|null      $rule_ids
     *
     * @return string
     */
    private function getLogsQuery(/*?*/ array $object_ids = null, /*?*/ string $sort_by = null, /*?*/ string $sort_by_direction = null, /*?*/ int $limit_start = null, /*?*/ int $limit_end = null,
        /*?*/ string $message = null, /*?*/ ilDateTime $date_start = null, /*?*/ ilDateTime $date_end = null, /*?*/ int $status = null, /*?*/ int $user_id = null, /*?*/ int $execute_user_id = null,
        /*?*/ array $rule_ids = null
    ) : string {

        $sql = ' FROM ' . self::dic()->database()->quoteIdentifier(Log::TABLE_NAME);

        $wheres = [];

        if (!empty($object_ids)) {
            $wheres[] = self::dic()->database()->in("object_id", $object_ids, false, ilDBConstants::T_INTEGER);
        }

        if (!empty($message)) {
            $wheres[] = self::dic()->database()->like("message", ilDBConstants::T_TEXT, '%' . $message . '%');
        }

        if (!empty($date_start)) {
            $wheres[] = 'date>=' . self::dic()->database()->quote($date_start->get(IL_CAL_DATETIME), ilDBConstants::T_TEXT);
        }

        if (!empty($date_end)) {
            $wheres[] = 'date<=' . self::dic()->database()->quote($date_end->get(IL_CAL_DATETIME), ilDBConstants::T_TEXT);
        }

        if (!empty($status)) {
            $wheres[] = 'status=' . self::dic()->database()->quote($status, ilDBConstants::T_INTEGER);
        }

        if (!empty($user_id)) {
            $wheres[] = 'user_id=' . self::dic()->database()->quote($user_id, ilDBConstants::T_INTEGER);
        }

        if (!empty($execute_user_id)) {
            $wheres[] = 'execute_user_id=' . self::dic()->database()->quote($execute_user_id, ilDBConstants::T_INTEGER);
        }

        if (!empty($rule_ids)) {
            $wheres[] = self::dic()->database()->in("rule_id", $rule_ids, false, ilDBConstants::T_INTEGER);
        }

        if (count($wheres) > 0) {
            $sql .= ' WHERE ' . implode(" AND ", $wheres);
        }

        if ($sort_by !== null && $sort_by_direction !== null) {
            $sql .= ' ORDER BY ' . self::dic()->database()->quoteIdentifier($sort_by) . ' ' . $sort_by_direction;
        }

        if ($limit_start !== null && $limit_end !== null) {
            $sql .= ' LIMIT ' . self::dic()->database()->quote($limit_start, ilDBConstants::T_INTEGER) . ',' . self::dic()->database()
                    ->quote($limit_end, ilDBConstants::T_INTEGER);
        }

        return $sql;
    }
}
