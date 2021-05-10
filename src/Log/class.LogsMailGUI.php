<?php

namespace srag\Plugins\SrAutoMails\Log;

require_once __DIR__ . "/../../vendor/autoload.php";

use ilSrAutoMailsPlugin;
use srag\CustomInputGUIs\SrAutoMails\MultiSelectSearchNewInputGUI\ObjectsAjaxAutoCompleteCtrl;
use srag\DIC\SrAutoMails\DICTrait;
use srag\Plugins\SrAutoMails\ObjectType\Object\ObjObjectType;
use srag\Plugins\SrAutoMails\Utils\SrAutoMailsTrait;

/**
 * Class LogsMailGUI
 *
 * @package           srag\Plugins\SrAutoMails\Log
 *
 * @author            studer + raimann ag - Team Custom 1 <support-custom1@studer-raimann.ch>
 *
 * @ilCtrl_isCalledBy srag\Plugins\SrAutoMails\Log\LogsMailGUI: ilSrAutoMailsConfigGUI
 * @ilCtrl_isCalledBy srag\CustomInputGUIs\SrAutoMails\MultiSelectSearchNewInputGUI\ObjectsAjaxAutoCompleteCtrl: srag\Plugins\SrAutoMails\Log\LogsMailGUI
 */
class LogsMailGUI
{

    use DICTrait;
    use SrAutoMailsTrait;

    const CMD_APPLY_FILTER = "applyFilter";
    const CMD_LIST_LOGS = "listLogs";
    const CMD_RESET_FILTER = "resetFilter";
    const LANG_MODULE = "logs";
    const PLUGIN_CLASS_NAME = ilSrAutoMailsPlugin::class;
    const TAB_LOGS = "logs";


    /**
     * LogsMailGUI constructor
     */
    public function __construct()
    {

    }


    /**
     *
     */
    public static function addTabs()/*:void*/
    {
        self::dic()->tabs()->addTab(self::TAB_LOGS, self::plugin()->translate("logs", LogsMailGUI::LANG_MODULE), self::dic()->ctrl()
            ->getLinkTargetByClass(self::class, self::CMD_LIST_LOGS));
    }


    /**
     *
     */
    public function executeCommand()/*: void*/
    {
        $this->setTabs();

        $next_class = self::dic()->ctrl()->getNextClass($this);

        switch (strtolower($next_class)) {
            case strtolower(ObjectsAjaxAutoCompleteCtrl::class):
                self::dic()->ctrl()->forwardCommand(new ObjectsAjaxAutoCompleteCtrl(ObjObjectType::TYPES[0]));
                break;

            default:
                $cmd = self::dic()->ctrl()->getCmd();

                switch ($cmd) {
                    case self::CMD_APPLY_FILTER:
                    case self::CMD_LIST_LOGS:
                    case self::CMD_RESET_FILTER:
                        $this->{$cmd}();
                        break;

                    default:
                        break;
                }
                break;
        }
    }


    /**
     *
     */
    protected function applyFilter()/*: void*/
    {
        $table = self::srAutoMails()->logs()->factory()->newTableInstance($this, self::CMD_APPLY_FILTER);

        $table->writeFilterToSession();

        $table->resetOffset();

        //self::dic()->ctrl()->redirect($this, self::CMD_LIST_LOGS);
        $this->listLogs(); // Fix reset offset
    }


    /**
     *
     */
    protected function listLogs()/*: void*/
    {
        self::dic()->tabs()->activateTab(self::TAB_LOGS);

        $table = self::srAutoMails()->logs()->factory()->newTableInstance($this);

        self::output()->output($table);
    }


    /**
     *
     */
    protected function resetFilter()/*: void*/
    {
        $table = self::srAutoMails()->logs()->factory()->newTableInstance($this, self::CMD_RESET_FILTER);

        $table->resetFilter();

        $table->resetOffset();

        //self::dic()->ctrl()->redirect($this, self::CMD_LIST_LOGS);
        $this->listLogs(); // Fix reset offset
    }


    /**
     *
     */
    protected function setTabs()/*:void*/
    {

    }
}
