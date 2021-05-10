<?php

namespace srag\Plugins\SrAutoMails\Log;

use ilDateTime;
use ilObjUser;
use ilSelectInputGUI;
use ilSrAutoMailsPlugin;
use ilTextInputGUI;
use srag\CustomInputGUIs\SrAutoMails\DateDurationInputGUI\DateDurationInputGUI;
use srag\CustomInputGUIs\SrAutoMails\MultiSelectSearchNewInputGUI\MultiSelectSearchNewInputGUI;
use srag\CustomInputGUIs\SrAutoMails\MultiSelectSearchNewInputGUI\ObjectsAjaxAutoCompleteCtrl;
use srag\CustomInputGUIs\SrAutoMails\PropertyFormGUI\Items\Items;
use srag\CustomInputGUIs\SrAutoMails\PropertyFormGUI\PropertyFormGUI;
use srag\CustomInputGUIs\SrAutoMails\TableGUI\TableGUI;
use srag\Plugins\SrAutoMails\ObjectType\Object\ObjObjectType;
use srag\Plugins\SrAutoMails\Rule\Rule;
use srag\Plugins\SrAutoMails\Utils\SrAutoMailsTrait;

/**
 * Class LogsTableGUI
 *
 * @package srag\Plugins\SrAutoMails\Log
 *
 * @author  studer + raimann ag - Team Custom 1 <support-custom1@studer-raimann.ch>
 */
class LogsTableGUI extends TableGUI
{

    use SrAutoMailsTrait;

    const LANG_MODULE = LogsMailGUI::LANG_MODULE;
    const PLUGIN_CLASS_NAME = ilSrAutoMailsPlugin::class;


    /**
     * LogsTableGUI constructor
     *
     * @param LogsMailGUI $parent
     * @param string      $parent_cmd
     */
    public function __construct(LogsMailGUI $parent, string $parent_cmd)
    {
        parent::__construct($parent, $parent_cmd);
    }


    /**
     * @inheritDoc
     */
    public function getSelectableColumns2() : array
    {
        $columns = [
            "object_id"       => "object_id",
            "rule_id"         => "rule_id",
            "date"            => "date",
            "status"          => "status",
            "message"         => "message",
            "user_id"         => "user_id",
            "execute_user_id" => "execute_user_id"
        ];

        $columns = array_map(function (string $key) : array {
            return [
                "id"      => $key,
                "default" => true,
                "sort"    => true
            ];
        }, $columns);

        return $columns;
    }


    /**
     * @inheritDoc
     *
     * @param Log $row
     */
    protected function getColumnValue(string $column, /*Log*/ $row, int $format = self::DEFAULT_FORMAT) : string
    {
        $value = Items::getter($row, $column);

        switch ($column) {
            case "object_id":
                if (!empty($value)) {
                    $value = htmlspecialchars(self::dic()->objDataCache()->lookupTitle($value));
                } else {
                    $value = "";
                }
                break;

            case "rule_id":
                if (!empty($value) && ($rule = self::srAutoMails()->rules()->getRuleById($value)) !== null) {
                    $value = htmlspecialchars($rule->getTitle());
                } else {
                    $value = "";
                }
                break;

            case "status":
                $value = htmlspecialchars($this->txt("status_" . Log::$status_all[$value]));
                break;

            case "user_id":
            case "execute_user_id":
                $value = htmlspecialchars(ilObjUser::_lookupLogin($value));
                break;

            default:
                break;
        }

        return strval($value);
    }


    /**
     * @inheritDoc
     */
    protected function initCommands()/*: void*/
    {

    }


    /**
     * @inheritDoc
     */
    protected function initData()/*: void*/
    {
        $this->setExternalSegmentation(true);
        $this->setExternalSorting(true);

        $this->setDefaultOrderField("date");
        $this->setDefaultOrderDirection("desc");

        // Fix stupid ilTable2GUI !!! ...
        $this->determineLimit();
        $this->determineOffsetAndOrder();

        $filter = $this->getFilterValues();

        $message = $filter["message"];
        $date_start = $filter["date"]["start"];
        if (!empty($date_start)) {
            $date_start = new ilDateTime(intval($date_start), IL_CAL_UNIX);
        } else {
            $date_start = null;
        }
        $date_end = $filter["date"]["end"];
        if (!empty($date_end)) {
            $date_end = new ilDateTime(intval($date_end), IL_CAL_UNIX);
        } else {
            $date_end = null;
        }
        $status = $filter["status"];
        if (!empty($status)) {
            $status = intval($status);
        } else {
            $status = null;
        }
        $rule_ids = (array) $filter["rule_id"];
        $object_ids = (array) $filter["object_id"];

        $this->setData(self::srAutoMails()
            ->logs()
            ->getLogs($object_ids, $this->getOrderField(), $this->getOrderDirection(), intval($this->getOffset()), intval($this->getLimit()), $message, $date_start, $date_end, $status, null, null,
                $rule_ids));

        $this->setMaxCount(self::srAutoMails()->logs()->getLogsCount($object_ids, $message, $date_start, $date_end, $status, null, null, $rule_ids));
    }


    /**
     * @inheritDoc
     */
    protected function initFilterFields()/*: void*/
    {
        self::dic()->language()->loadLanguageModule("form");

        $this->filter_fields = [
            "object_id" => [
                PropertyFormGUI::PROPERTY_CLASS => MultiSelectSearchNewInputGUI::class,
                "setAjaxAutoCompleteCtrl"       => new ObjectsAjaxAutoCompleteCtrl(ObjObjectType::TYPES[0])
            ],
            "rule_id"   => [
                PropertyFormGUI::PROPERTY_CLASS   => MultiSelectSearchNewInputGUI::class,
                PropertyFormGUI::PROPERTY_OPTIONS => array_map(function (Rule $rule) : string {
                    return $rule->getTitle();
                }, self::srAutoMails()->rules()->getRules(false))
            ],
            "date"      => [
                PropertyFormGUI::PROPERTY_CLASS => DateDurationInputGUI::class,
                "setShowTime"                   => true
            ],
            "message"   => [
                PropertyFormGUI::PROPERTY_CLASS => ilTextInputGUI::class
            ],
            "status"    => [
                PropertyFormGUI::PROPERTY_CLASS   => ilSelectInputGUI::class,
                PropertyFormGUI::PROPERTY_OPTIONS => [
                        "" => ""
                    ] + array_map(function (string $status_lang_key) : string {
                        return $this->txt("status_" . $status_lang_key);
                    }, Log::$status_all)
            ]
        ];
    }


    /**
     * @inheritDoc
     */
    protected function initId()/*: void*/
    {
        $this->setId(ilSrAutoMailsPlugin::PLUGIN_ID . "_logs");
    }


    /**
     * @inheritDoc
     */
    protected function initTitle()/*: void*/
    {
        $this->setTitle($this->txt("logs"));
    }
}
