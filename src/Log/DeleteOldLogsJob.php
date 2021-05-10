<?php

namespace srag\Plugins\SrAutoMails\Log;

use ilCronJob;
use ilCronJobResult;
use ilNumberInputGUI;
use ilPropertyFormGUI;
use ilSrAutoMailsPlugin;
use srag\DIC\SrAutoMails\DICTrait;
use srag\Plugins\SrAutoMails\Utils\SrAutoMailsTrait;

/**
 * Class DeleteOldLogsJob
 *
 * @package srag\Plugins\SrAutoMails\Log
 *
 * @author  studer + raimann ag - Team Custom 1 <support-custom1@studer-raimann.ch>
 */
class DeleteOldLogsJob extends ilCronJob
{

    use DICTrait;
    use SrAutoMailsTrait;

    const CRON_JOB_ID = ilSrAutoMailsPlugin::PLUGIN_ID . "_delete_old_logs";
    const KEY_KEEP_OLD_LOGS_TIME = "keep_old_logs_time";
    const PLUGIN_CLASS_NAME = ilSrAutoMailsPlugin::class;


    /**
     * DeleteOldLogsJob constructor
     */
    public function __construct()
    {

    }


    /**
     * @inheritDoc
     */
    public function addCustomSettingsToForm(ilPropertyFormGUI $a_form)/*:void*/
    {
        $keep_old_logs_time = new ilNumberInputGUI(self::plugin()->translate(self::KEY_KEEP_OLD_LOGS_TIME, LogsMailGUI::LANG_MODULE), self::KEY_KEEP_OLD_LOGS_TIME);
        $keep_old_logs_time->setInfo(nl2br(self::plugin()->translate(self::KEY_KEEP_OLD_LOGS_TIME . "_info", LogsMailGUI::LANG_MODULE), false));
        $keep_old_logs_time->setSuffix(self::plugin()->translate("days", LogsMailGUI::LANG_MODULE));
        $keep_old_logs_time->setValue(self::srAutoMails()->config()->getValue(self::KEY_KEEP_OLD_LOGS_TIME));
        $a_form->addItem($keep_old_logs_time);
    }


    /**
     * @inheritDoc
     */
    public function getDefaultScheduleType() : int
    {
        return self::SCHEDULE_TYPE_DAILY;
    }


    /**
     * @inheritDoc
     */
    public function getDefaultScheduleValue()/*:?int*/
    {
        return null;
    }


    /**
     * @inheritDoc
     */
    public function getDescription() : string
    {
        return self::plugin()->translate("delete_old_logs_description", LogsMailGUI::LANG_MODULE);
    }


    /**
     * @inheritDoc
     */
    public function getId() : string
    {
        return self::CRON_JOB_ID;
    }


    /**
     * @inheritDoc
     */
    public function getTitle() : string
    {
        return ilSrAutoMailsPlugin::PLUGIN_NAME . ": " . self::plugin()->translate("delete_old_logs", LogsMailGUI::LANG_MODULE);
    }


    /**
     * @inheritDoc
     */
    public function hasAutoActivation() : bool
    {
        return true;
    }


    /**
     * @inheritDoc
     */
    public function hasCustomSettings() : bool
    {
        return true;
    }


    /**
     * @inheritDoc
     */
    public function hasFlexibleSchedule() : bool
    {
        return true;
    }


    /**
     * @inheritDoc
     */
    public function run() : ilCronJobResult
    {
        $result = new ilCronJobResult();

        $keep_old_logs_time = self::srAutoMails()->config()->getValue(self::KEY_KEEP_OLD_LOGS_TIME);

        if (empty($keep_old_logs_time)) {
            $result->setStatus(ilCronJobResult::STATUS_NO_ACTION);

            return $result;
        }

        $count = self::srAutoMails()->logs()->deleteOldLogs($keep_old_logs_time);

        $result->setStatus(ilCronJobResult::STATUS_OK);

        $result->setMessage(self::plugin()->translate("delete_old_logs_status", LogsMailGUI::LANG_MODULE, [$count]));

        return $result;
    }


    /**
     * @inheritDoc
     */
    public function saveCustomSettings(ilPropertyFormGUI $a_form) : bool
    {
        self::srAutoMails()->config()->setValue(self::KEY_KEEP_OLD_LOGS_TIME, intval($a_form->getInput(self::KEY_KEEP_OLD_LOGS_TIME)));

        return true;
    }
}
