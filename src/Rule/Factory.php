<?php

namespace srag\Plugins\SrAutoMails\Rule;

use ilSrAutoMailsPlugin;
use srag\DIC\SrAutoMails\DICTrait;
use srag\Plugins\SrAutoMails\Utils\SrAutoMailsTrait;

/**
 * Class Factory
 *
 * @package srag\Plugins\SrAutoMails\Rule
 *
 * @author  studer + raimann ag - Team Custom 1 <support-custom1@studer-raimann.ch>
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
     * @param RuleMailConfigGUI $parent
     * @param Rule              $rule
     *
     * @return RuleFormGUI
     */
    public function newFormInstance(RuleMailConfigGUI $parent, Rule $rule) : RuleFormGUI
    {
        $form = new RuleFormGUI($parent, $rule);

        return $form;
    }


    /**
     * @return Rule
     */
    public function newInstance() : Rule
    {
        $rule = new Rule();

        return $rule;
    }


    /**
     * @return RulesJob
     */
    public function newJobInstance() : RulesJob
    {
        $job = new RulesJob();

        return $job;
    }


    /**
     * @param RulesMailConfigGUI $parent
     * @param string             $cmd
     *
     * @return RulesTableGUI
     */
    public function newTableInstance(RulesMailConfigGUI $parent, string $cmd = RulesMailConfigGUI::CMD_LIST_RULES) : RulesTableGUI
    {
        $table = new RulesTableGUI($parent, $cmd);

        return $table;
    }
}
