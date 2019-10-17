<?php

require_once __DIR__ . "/../vendor/autoload.php";

use srag\ActiveRecordConfig\SrAutoMails\ActiveRecordConfigGUI;
use srag\Plugins\SrAutoMails\Rule\RulesConfigGUI;
use srag\Plugins\SrAutoMails\Utils\SrAutoMailsTrait;

/**
 * Class ilSrAutoMailsConfigGUI
 *
 * @author studer + raimann ag - Team Custom 1 <support-custom1@studer-raimann.ch>
 */
class ilSrAutoMailsConfigGUI extends ActiveRecordConfigGUI
{

    use SrAutoMailsTrait;
    const PLUGIN_CLASS_NAME = ilSrAutoMailsPlugin::class;
    /**
     * @var array
     */
    protected static $tabs
        = [
            RulesConfigGUI::TAB_RULES => [
                RulesConfigGUI::class,
                RulesConfigGUI::CMD_LIST_RULES
            ]
        ];
}
