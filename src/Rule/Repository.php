<?php

namespace srag\Plugins\SrAutoMails\Rule;

use ilSrAutoMailsPlugin;
use srag\DIC\SrAutoMails\DICTrait;
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
            return self::plugin()->translate("operator_" . $operator, RulesConfigGUI::LANG_MODULE_RULES);
        }, Rule::$operators);
    }


    /**
     * @param string    $title
     * @param string    $description
     * @param string    $object_type
     * @param bool|null $enabled
     *
     * @return array
     */
    public function getRulesArray(string $title = "", string $description = "", string $object_type = "", /*?*/ bool $enabled = null) : array
    {
        $where = Rule::where([]);

        if (!empty($title)) {
            $where = $where->where(["title" => '%' . $title . '%'], "LIKE");
        }

        if (!empty($description)) {
            $where = $where->where(["description" => '%' . $description . '%'], "LIKE");
        }

        if (!empty($object_type)) {
            $where = $where->where(["object_type" => '%' . $object_type . '%'], "LIKE");
        }

        if ($enabled !== null) {
            $where = $where->where(["enabled" => $enabled]);
        }

        return $where->getArray();
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
     * @param string $object_type
     * @param bool   $only_enabled
     * @param bool   $interval_check
     *
     * @return Rule[]
     */
    public function getRules(string $object_type = "", bool $only_enabled = true, bool $interval_check = true) : array
    {
        $time = time();

        $where = Rule::where([]);
        if (!empty($object_type)) {
            $where = $where->where(["object_type" => $object_type]);
        }
        if ($only_enabled) {
            $where = $where->where(["enabled" => $only_enabled]);
        }

        /**
         * @var Rule[] $rules
         */
        $rules = $where->get();

        if ($interval_check) {
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
     * @param Rule $rule
     */
    public function storeRule(Rule $rule)/*: void*/
    {
        $rule->store();
    }
}
