<?php

namespace srag\Plugins\SrAutoMails\Rule;

use ilSelectInputGUI;
use ilSrAutoMailsPlugin;
use ilTextInputGUI;
use ilUtil;
use srag\CustomInputGUIs\SrAutoMails\PropertyFormGUI\Items\Items;
use srag\CustomInputGUIs\SrAutoMails\PropertyFormGUI\PropertyFormGUI;
use srag\CustomInputGUIs\SrAutoMails\TableGUI\TableGUI;
use srag\Plugins\SrAutoMails\Utils\SrAutoMailsTrait;

/**
 * Class RulesTableGUI
 *
 * @package srag\Plugins\SrAutoMails\Rule
 *
 * @author  studer + raimann ag - Team Custom 1 <support-custom1@studer-raimann.ch>
 */
class RulesTableGUI extends TableGUI
{

    use SrAutoMailsTrait;
    const PLUGIN_CLASS_NAME = ilSrAutoMailsPlugin::class;
    const LANG_MODULE = RulesMailConfigGUI::LANG_MODULE;


    /**
     * RulesTableGUI constructor
     *
     * @param RulesMailConfigGUI $parent
     * @param string             $parent_cmd
     */
    public function __construct(RulesMailConfigGUI $parent, string $parent_cmd)
    {
        parent::__construct($parent, $parent_cmd);
    }


    /**
     * @inheritdoc
     *
     * @param Rule $rule
     */
    protected function getColumnValue(/*string*/ $column, /*Rule*/ $rule, /*int*/ $format = self::DEFAULT_FORMAT) : string
    {
        switch ($column) {
            case "enabled":
                if ($rule->isEnabled()) {
                    $column = ilUtil::getImagePath("icon_ok.svg");
                } else {
                    $column = ilUtil::getImagePath("icon_not_ok.svg");
                }
                $column = self::output()->getHTML(self::dic()->ui()->factory()->image()->standard($column, ""));
                break;

            case "object_type":
                $column = self::srAutoMails()->objectTypes()->getObjectTypesText()[$rule->getObjectType()];
                break;

            default:
                $column = Items::getter($rule, $column);
                break;
        }

        return strval($column);
    }


    /**
     * @inheritdoc
     */
    public function getSelectableColumns2() : array
    {
        $columns = [
            "enabled"     => [
                "id"      => "enabled",
                "default" => true,
                "sort"    => false
            ],
            "title"       => [
                "id"      => "title",
                "default" => true,
                "sort"    => false
            ],
            "description" => [
                "id"      => "description",
                "default" => true,
                "sort"    => false
            ],
            "object_type" => [
                "id"      => "object_type",
                "default" => true,
                "sort"    => false
            ]
        ];

        return $columns;
    }


    /**
     * @inheritdoc
     */
    protected function initColumns()/*: void*/
    {
        $this->addColumn("");

        parent::initColumns();

        $this->addColumn($this->txt("actions"));
    }


    /**
     * @inheritdoc
     */
    protected function initCommands()/*: void*/
    {
        self::dic()->toolbar()->addComponent(self::dic()->ui()->factory()->button()->standard($this->txt("add_rule"), self::dic()->ctrl()
            ->getLinkTargetByClass(RuleMailConfigGUI::class, RuleMailConfigGUI::CMD_ADD_RULE)));

        $this->setSelectAllCheckbox(RuleMailConfigGUI::GET_PARAM_RULE_ID);
        $this->addMultiCommand(RulesMailConfigGUI::CMD_ENABLE_RULES, $this->txt("enable_rules"));
        $this->addMultiCommand(RulesMailConfigGUI::CMD_DISABLE_RULES, $this->txt("disable_rules"));
        $this->addMultiCommand(RulesMailConfigGUI::CMD_REMOVE_RULES_CONFIRM, $this->txt("remove_rules"));
    }


    /**
     * @inheritdoc
     */
    protected function initData()/*: void*/
    {
        $this->setExternalSegmentation(true);
        $this->setExternalSorting(true);

        $filter = $this->getFilterValues();

        $title = $filter["title"];
        $description = $filter["description"];
        $object_type = $filter["object_type"];
        $enabled = $filter["enabled"];
        if (!empty($enabled)) {
            $enabled = ($enabled === "yes");
        } else {
            $enabled = null;
        }

        $this->setData(self::srAutoMails()->rules()->getRules(false, $object_type, $enabled, $title, $description));
    }


    /**
     * @inheritdoc
     */
    protected function initFilterFields()/*: void*/
    {
        $this->filter_fields = [
            "title"       => [
                PropertyFormGUI::PROPERTY_CLASS => ilTextInputGUI::class
            ],
            "description" => [
                PropertyFormGUI::PROPERTY_CLASS => ilTextInputGUI::class
            ],
            "object_type" => [
                PropertyFormGUI::PROPERTY_CLASS   => ilSelectInputGUI::class,
                PropertyFormGUI::PROPERTY_OPTIONS => ["" => ""] + self::srAutoMails()->objectTypes()->getObjectTypesText()
            ],
            "enabled"     => [
                PropertyFormGUI::PROPERTY_CLASS   => ilSelectInputGUI::class,
                PropertyFormGUI::PROPERTY_OPTIONS => ["" => "", "yes" => $this->txt("yes"), "no" => $this->txt("no")]
            ]
        ];
    }


    /**
     * @inheritdoc
     */
    protected function initId()/*: void*/
    {
        $this->setId("srauma_rules");
    }


    /**
     * @inheritdoc
     */
    protected function initTitle()/*: void*/
    {
        $this->setTitle($this->txt("rules"));
    }


    /**
     * @param Rule $rule
     */
    protected function fillRow(/*Rule*/ $rule)/*: void*/
    {
        self::dic()->ctrl()->setParameterByClass(RuleMailConfigGUI::class, RuleMailConfigGUI::GET_PARAM_RULE_ID, $rule->getRuleId());

        $this->tpl->setCurrentBlock("checkbox");
        $this->tpl->setVariable("CHECKBOX_POST_VAR", RuleMailConfigGUI::GET_PARAM_RULE_ID);
        $this->tpl->setVariable("ID", $rule->getRuleId());
        $this->tpl->parseCurrentBlock();

        parent::fillRow($rule);

        $this->tpl->setVariable("COLUMN", self::output()->getHTML(self::dic()->ui()->factory()->dropdown()->standard([
            self::dic()->ui()->factory()->link()->standard($this->txt("edit_rule"), self::dic()->ctrl()
                ->getLinkTargetByClass(RuleMailConfigGUI::class, RuleMailConfigGUI::CMD_EDIT_RULE)),
            self::dic()->ui()->factory()->link()->standard($this->txt("remove_rule"), self::dic()->ctrl()
                ->getLinkTargetByClass(RuleMailConfigGUI::class, RuleMailConfigGUI::CMD_REMOVE_RULE_CONFIRM))
        ])->withLabel($this->txt("actions"))));
    }
}
