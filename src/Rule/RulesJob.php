<?php

namespace srag\Plugins\SrAutoMails\Rule;

use ilCronJob;
use ilCronJobResult;
use ilCronManager;
use ilDateTime;
use ilSrAutoMailsPlugin;
use srag\DIC\SrAutoMails\DICTrait;
use srag\Notifications4Plugin\SrAutoMails\Exception\Notifications4PluginException;
use srag\Plugins\SrAutoMails\EnrolmentWorkflow\Rule\RulesGUI;
use srag\Plugins\SrAutoMails\Log\Log;
use srag\Plugins\SrAutoMails\Log\LogsMailGUI;
use srag\Plugins\SrAutoMails\ObjectType\ObjectType;
use srag\Plugins\SrAutoMails\Utils\SrAutoMailsTrait;
use Throwable;

/**
 * Class RulesJob
 *
 * @package srag\Plugins\SrAutoMails\Rule
 */
class RulesJob extends ilCronJob
{

    use DICTrait;
    use SrAutoMailsTrait;

    const CRON_JOB_ID = ilSrAutoMailsPlugin::PLUGIN_ID;
    const PLUGIN_CLASS_NAME = ilSrAutoMailsPlugin::class;


    /**
     * RulesJob constructor
     */
    public function __construct()
    {

    }


    /**
     * @inheritDoc
     */
    public function getDefaultScheduleType() : int
    {
        return self::SCHEDULE_TYPE_IN_HOURS;
    }


    /**
     * @inheritDoc
     */
    public function getDefaultScheduleValue() : ?int
    {
        return 1;
    }


    /**
     * @inheritDoc
     */
    public function getDescription() : string
    {
        return "";
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
        return ilSrAutoMailsPlugin::PLUGIN_NAME;
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
    public function hasFlexibleSchedule() : bool
    {
        return true;
    }


    /**
     * @inheritDoc
     */
    public function run() : ilCronJobResult
    {
        $time = time();

        $result = new ilCronJobResult();

        /**
         * @var Rule[] $checked_rules
         */
        $checked_rules = [];

        try {
            $object_types = self::srAutoMails()->objectTypes()->getObjectTypes();

            foreach ($object_types as $object_type) {
                try {
                    $objects = $object_type->getObjects();

                    $rules = self::srAutoMails()->rules()->getRules(true, $object_type->getObjectType(), true, null, null);

                    foreach ($objects as $object) {
                        try {

                            foreach ($rules as $rule) {
                                try {

                                    if ($object_type->checkRuleForObject($rule, $object)) {

                                        $receivers = $object_type->getReceivers($rule, $object);

                                        foreach ($receivers as $user_id) {
                                            try {

                                                if ($rule->getIntervalType() === Rule::INTERVAL_TYPE_NUMBER
                                                    || !self::srAutoMails()->sents()->hasSent($rule->getRuleId(), $object_type->getObjectId($object), $user_id)
                                                ) {

                                                    $this->sendNotification($rule, $object_type, $object, $user_id);

                                                    self::srAutoMails()->sents()->sent($rule->getRuleId(), $object_type->getObjectId($object), $user_id);

                                                    self::srAutoMails()->logs()->storeLog(self::srAutoMails()->logs()->factory()
                                                        ->newObjectRuleUserInstance($object->getId(), $rule->getRuleId(), $user_id)->withStatus(Log::STATUS_MAIL_SENT));
                                                } else {
                                                    self::srAutoMails()->logs()->storeLog(self::srAutoMails()->logs()->factory()
                                                        ->newObjectRuleUserInstance($object->getId(), $rule->getRuleId(), $user_id)->withStatus(Log::STATUS_MAIL_SKIPPED));
                                                }
                                            } catch (Throwable $ex) {
                                                self::srAutoMails()->logs()->storeLog(self::srAutoMails()->logs()->factory()
                                                    ->newExceptionInstance($ex, $object->getId(), $rule->getRuleId(), $user_id)->withStatus(Log::STATUS_MAIL_FAILED));
                                            }

                                            ilCronManager::ping($this->getId());
                                        }
                                    } else {
                                        self::srAutoMails()->logs()->storeLog(self::srAutoMails()->logs()->factory()
                                            ->newObjectRuleUserInstance($object->getId(), $rule->getRuleId())->withStatus(Log::STATUS_RULE_SKIPPED));
                                    }

                                    if (!isset($checked_rules[$rule->getRuleId()])) {
                                        $checked_rules[$rule->getRuleId()] = $rule;
                                    }
                                } catch (Throwable $ex) {
                                    self::srAutoMails()->logs()->storeLog(self::srAutoMails()->logs()->factory()
                                        ->newExceptionInstance($ex, $object->getId(), $rule->getRuleId())->withStatus(Log::STATUS_RULE_FAILED));
                                }

                                ilCronManager::ping($this->getId());
                            }
                        } catch (Throwable $ex) {
                            self::srAutoMails()->logs()->storeLog(self::srAutoMails()->logs()->factory()
                                ->newExceptionInstance($ex, $object->getId())->withStatus(Log::STATUS_OBJECT_TYPE_FAILED));
                        }

                        ilCronManager::ping($this->getId());
                    }
                } catch (Throwable $ex) {
                    self::srAutoMails()->logs()->storeLog(self::srAutoMails()->logs()->factory()
                        ->newExceptionInstance($ex)->withStatus(Log::STATUS_OBJECT_TYPE_FAILED));
                }

                ilCronManager::ping($this->getId());
            }
        } catch (Throwable $ex) {
            self::srAutoMails()->logs()->storeLog(self::srAutoMails()->logs()->factory()
                ->newExceptionInstance($ex)->withStatus(Log::STATUS_OBJECT_TYPE_FAILED));
        }

        foreach ($checked_rules as $rule) {
            try {
                $rule->setLastCheck(new ilDateTime($time, IL_CAL_UNIX));

                self::srAutoMails()->rules()->storeRule($rule);
            } catch (Throwable $ex) {
                self::srAutoMails()->logs()->storeLog(self::srAutoMails()->logs()->factory()
                    ->newExceptionInstance($ex, null, $rule->getRuleId())->withStatus(Log::STATUS_RULE_FAILED));
            }

            ilCronManager::ping($this->getId());
        }

        $logs = array_reduce(array_keys(Log::$status_all), function (array $logs, int $status) : array {
            $logs[] = self::plugin()->translate("status_" . Log::$status_all[$status], LogsMailGUI::LANG_MODULE) . ": " . count(self::srAutoMails()->logs()->getKeptLogs($status));

            return $logs;
        }, []);

        $result_count = nl2br(implode("\n", $logs), false);

        $result->setStatus(ilCronJobResult::STATUS_OK);

        $result->setMessage($result_count);

        return $result;
    }


    /**
     * @param Rule       $rule
     * @param ObjectType $object_type
     * @param object     $object
     * @param int        $user_id
     *
     * @throws Notifications4PluginException
     */
    protected function sendNotification(Rule $rule, ObjectType $object_type, $object, int $user_id) : void
    {
        $notification = self::srAutoMails()->notifications4plugin()->notifications()->getNotificationByName($rule->getMailTemplateName());

        $sender = self::srAutoMails()->notifications4plugin()->sender()->factory()->internalMail(ANONYMOUS_USER_ID, $user_id);

        $placeholders = $object_type->getPlaceholdersForMail($object, $user_id, $rule);

        self::srAutoMails()->notifications4plugin()->sender()->send($sender, $notification, $placeholders, $placeholders["receiver"]->getLanguage());
    }
}
