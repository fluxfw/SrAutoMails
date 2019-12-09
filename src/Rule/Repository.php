<?php

namespace srag\Plugins\SrAutoMails\Rule;

use ilSrAutoMailsPlugin;
use srag\DIC\SrAutoMails\DICTrait;
use srag\Plugins\SrAutoMails\Notification\Notification\Language\NotificationLanguage;
use srag\Plugins\SrAutoMails\Notification\Notification\Notification;
use srag\Plugins\SrAutoMails\Utils\SrAutoMailsTrait;

/**
 * Class Repository
 *
 * @package srag\Plugins\SrAutoMails\Rule
 *
 * @author  studer + raimann ag - Team Custom 1 <support-custom1@studer-raimann.ch>
 */
final class Repository
{

    use DICTrait;
    use SrAutoMailsTrait;
    const PLUGIN_CLASS_NAME = ilSrAutoMailsPlugin::class;
    /**
     * @var self
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
     * Repository constructor
     */
    private function __construct()
    {

    }


    /**
     * @param Rule $rule
     */
    public function deleteRule(Rule $rule)/*: void*/
    {
        $rule->delete();
    }


    /**
     * @internal
     */
    public function dropTables()/*: void*/
    {

    }


    /**
     * @return Factory
     */
    public function factory() : Factory
    {
        return Factory::getInstance();
    }


    /**
     * @return array
     */
    public function getOperatorsText() : array
    {
        return array_map(function (string $operator) : string {
            return self::plugin()->translate("operator_" . $operator, RulesMailConfigGUI::LANG_MODULE);
        }, Rule::$operators);
    }


    /**
     * @param bool        $interval_check
     * @param string|null $object_type
     * @param bool|null   $enabled
     * @param string|null $title
     * @param string|null $description
     *
     * @return Rule[]
     */
    public function getRules(bool $interval_check,/*?*/ string $object_type = null, /*?*/ bool $enabled = null,/*?*/ string $title = null, /*?*/ string $description = null) : array
    {
        $where = Rule::where([]);

        if (!empty($object_type)) {
            $where = $where->where(["object_type" => $object_type]);
        }

        if ($enabled !== null) {
            $where = $where->where(["enabled" => $enabled]);
        }

        if (!empty($title)) {
            $where = $where->where(["title" => '%' . $title . '%'], "LIKE");
        }

        if (!empty($description)) {
            $where = $where->where(["description" => '%' . $description . '%'], "LIKE");
        }

        /**
         * @var Rule[] $rules
         */
        $rules = $where->orderBy("title", "ASC")->get();

        if ($interval_check) {
            $time = time();

            $rules = array_filter($rules, function (Rule $rule) use ($time): bool {
                if ($rule->getLastCheck() === null) {
                    return true;
                }

                if ($rule->getIntervalType() !== Rule::INTERVAL_TYPE_NUMBER) {
                    return true;
                }

                return ((($time - $rule->getLastCheck()->getUnixTime()) / (60 * 60 * 24)) >= $rule->getInterval());
            });
        }

        return $rules;
    }


    /**
     * @param int $rule_id
     *
     * @return Rule|null
     */
    public function getRuleById(int $rule_id)/*: ?Rule*/
    {
        /**
         * @var Rule|null $rule
         */

        $rule = Rule::where(["rule_id" => $rule_id])->first();

        return $rule;
    }


    /**
     * @internal
     */
    public function installTables()/*: void*/
    {
        Rule::updateDB();

        foreach (Rule::where(["interval_type" => 0])->get() as $rule) {
            /**
             * @var Rule $rule
             */
            $rule->setIntervalType(!empty($rule->getInterval()) ? Rule::INTERVAL_TYPE_NUMBER : Rule::INTERVAL_TYPE_ONCE);
            $rule->store();
        }

        foreach (Rule::where(["match_type" => 0])->get() as $rule) {
            /**
             * @var Rule $rule
             */
            $rule->setMatchType(Rule::MATCH_TYPE_MATCH);
            $rule->store();
        }

        foreach (Rule::get() as $rule) {
            /**
             * @var Rule $rule
             */

            self::srAutoMails()->notifications4plugin()->notifications()->migrateFromOldGlobalPlugin($rule->getMailTemplateName());
        }
    }


    /**
     * @param Rule $rule
     */
    public function storeRule(Rule $rule)/*: void*/
    {
        $is_new = (empty($rule->getRuleId()));

        $rule->store();

        if ($is_new) {
            $rule->setMailTemplateName("rule_" . $rule->getRuleId());

            $rule->store();

            $notification = self::srAutoMails()->notifications4plugin()->notifications()->getNotificationByName($rule->getMailTemplateName());

            if ($notification === null) {
                $notification = self::srAutoMails()->notifications4plugin()->notifications()->factory()->newInstance();

                $notification->setName($rule->getMailTemplateName());

                self::srAutoMails()->notifications4plugin()->notifications()->storeNotification($notification);
            }
        }
    }
}
