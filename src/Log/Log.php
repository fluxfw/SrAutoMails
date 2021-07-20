<?php

namespace srag\Plugins\SrAutoMails\Log;

use ActiveRecord;
use arConnector;
use ilDateTime;
use ilSrAutoMailsPlugin;
use srag\DIC\SrAutoMails\DICTrait;
use srag\Plugins\SrAutoMails\Utils\SrAutoMailsTrait;

/**
 * Class Log
 *
 * @package srag\Plugins\SrAutoMails\Log
 */
class Log extends ActiveRecord
{

    use DICTrait;
    use SrAutoMailsTrait;

    const PLUGIN_CLASS_NAME = ilSrAutoMailsPlugin::class;
    const STATUS_MAIL_FAILED = 30;
    const STATUS_MAIL_SENT = 10;
    const STATUS_MAIL_SKIPPED = 20;
    const STATUS_OBJECT_TYPE_FAILED = 60;
    const STATUS_RULE_FAILED = 40;
    const STATUS_RULE_SKIPPED = 50;
    const TABLE_NAME = ilSrAutoMailsPlugin::PLUGIN_ID . "_log";
    /**
     * @var array
     */
    public static $status_all
        = [
            self::STATUS_MAIL_SENT          => "mail_sent",
            self::STATUS_MAIL_SKIPPED       => "mail_skipped",
            self::STATUS_MAIL_FAILED        => "mail_failed",
            self::STATUS_RULE_FAILED        => "rule_failed",
            self::STATUS_RULE_SKIPPED       => "rule_skipped",
            self::STATUS_OBJECT_TYPE_FAILED => "object_type_failed"
        ];
    /**
     * @var array
     */

    /**
     * @var ilDateTime
     *
     * @con_has_field    true
     * @con_fieldtype    timestamp
     * @con_is_notnull   true
     */
    protected $date = null;
    /**
     * @var int|null
     *
     * @con_has_field    true
     * @con_fieldtype    integer
     * @con_length       8
     * @con_is_notnull   false
     */
    protected $execute_user_id = null;
    /**
     * @var int
     *
     * @con_has_field    true
     * @con_fieldtype    integer
     * @con_length       8
     * @con_is_notnull   true
     * @con_is_primary   true
     */
    protected $log_id = 0;
    /**
     * @var string
     *
     * @con_has_field    true
     * @con_fieldtype    text
     * @con_is_notnull   true
     */
    protected $message = "";
    /**
     * @var int
     *
     * @con_has_field    true
     * @con_fieldtype    integer
     * @con_length       8
     * @con_is_notnull   false
     */
    protected $object_id = null;
    /**
     * @var int|null
     *
     * @con_has_field    true
     * @con_fieldtype    integer
     * @con_length       8
     * @con_is_notnull   false
     */
    protected $rule_id = null;
    /**
     * @var int
     *
     * @con_has_field    true
     * @con_fieldtype    integer
     * @con_length       8
     * @con_is_notnull   true
     */
    protected $status = 0;
    /**
     * @var int|null
     *
     * @con_has_field    true
     * @con_fieldtype    integer
     * @con_length       8
     * @con_is_notnull   false
     */
    protected $user_id = null;


    /**
     * Log constructor
     *
     * @param int              $primary_key_value
     * @param arConnector|null $connector
     */
    public final function __construct(/*int*/ $primary_key_value = 0, /*?*/ arConnector $connector = null)
    {
        //parent::__construct($primary_key_value, $connector);
    }


    /**
     * @return string
     *
     * @deprecated
     */
    public final static function returnDbTableName() : string
    {
        return static::TABLE_NAME;
    }


    /**
     * @return string
     */
    public final function getConnectorContainerName() : string
    {
        return static::TABLE_NAME;
    }


    /**
     * @return ilDateTime
     */
    public function getDate() : ilDateTime
    {
        return $this->date;
    }


    /**
     * @return int|null
     */
    public function getExecuteUserId() : ?int
    {
        return $this->execute_user_id;
    }


    /**
     * @return int
     */
    public function getLogId() : int
    {
        return $this->log_id;
    }


    /**
     * @return string
     */
    public function getMessage() : string
    {
        return $this->message;
    }


    /**
     * @return int|null
     */
    public function getObjectId() : ?int
    {
        return $this->object_id;
    }


    /**
     * @return int|null
     */
    public function getRuleId() : ?int
    {
        return $this->rule_id;
    }


    /**
     * @return int
     */
    public function getStatus() : int
    {
        return $this->status;
    }


    /**
     * @return int|null
     */
    public function getUserId() : ?int
    {
        return $this->user_id;
    }


    /**
     * @param ilDateTime $date
     *
     * @return self
     */
    public function withDate(ilDateTime $date) : self
    {
        $this->date = $date;

        return $this;
    }


    /**
     * @param int|null $execute_user_id
     *
     * @return self
     */
    public function withExecuteUserId(/*?*/ int $execute_user_id = null) : self
    {
        $this->execute_user_id = $execute_user_id;

        return $this;
    }


    /**
     * @param int $log_id
     *
     * @return self
     */
    public function withLogId(int $log_id) : self
    {
        $this->log_id = $log_id;

        return $this;
    }


    /**
     * @param string $message
     *
     * @return self
     */
    public function withMessage(string $message) : self
    {
        $this->message = $message;

        return $this;
    }


    /**
     * @param int|null $object_id
     *
     * @return self
     */
    public function withObjectId(/*?*/ int $object_id = null) : self
    {
        $this->object_id = $object_id;

        return $this;
    }


    /**
     * @param int|null $rule_id
     *
     * @return self
     */
    public function withRuleId(/*?*/ int $rule_id = null) : self
    {
        $this->rule_id = $rule_id;

        return $this;
    }


    /**
     * @param int $status
     *
     * @return self
     */
    public function withStatus(int $status) : self
    {
        $this->status = $status;

        return $this;
    }


    /**
     * @param int|null $user_id
     *
     * @return self
     */
    public function withUserId(/*?*/ int $user_id = null) : self
    {
        $this->user_id = $user_id;

        return $this;
    }
}
