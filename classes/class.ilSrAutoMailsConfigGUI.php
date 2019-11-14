<?php

require_once __DIR__ . "/../vendor/autoload.php";

use srag\ActiveRecordConfig\SrAutoMails\ActiveRecordConfigGUI;
use srag\Plugins\SrAutoMails\Rule\RulesMailConfigGUI;
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
            RulesMailConfigGUI::TAB_RULES => [
                RulesMailConfigGUI::class,
                RulesMailConfigGUI::CMD_LIST_RULES
            ]
        ];
}
