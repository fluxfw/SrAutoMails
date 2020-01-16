<?php

namespace srag\Plugins\SrAutoMails\Rule;

use ilCheckboxInputGUI;
use ilNumberInputGUI;
use ilRadioGroupInputGUI;
use ilRadioOption;
use ilSelectInputGUI;
use ilSrAutoMailsPlugin;
use ilTextInputGUI;
use srag\CustomInputGUIs\SrAutoMails\MultiSelectSearchInputGUI\MultiSelectSearchInputGUI;
use srag\CustomInputGUIs\SrAutoMails\PropertyFormGUI\Items\Items;
use srag\CustomInputGUIs\SrAutoMails\PropertyFormGUI\PropertyFormGUI;
use srag\Plugins\SrAutoMails\Utils\SrAutoMailsTrait;

/**
 * Class RuleFormGUI
 *
 * @package srag\Plugins\SrAutoMails\Rule
 *
 * @author  studer + raimann ag - Team Custom 1 <support-custom1@studer-raimann.ch>
 */
class RuleFormGUI extends PropertyFormGUI
{

    use SrAutoMailsTrait;
    const PLUGIN_CLASS_NAME = ilSrAutoMailsPlugin::class;
    const LANG_MODULE = RulesMailConfigGUI::LANG_MODULE;
    /**
     * @var Rule
     */
    protected $rule;


    /**
     * RuleFormGUI constructor
     *
     * @param RuleMailConfigGUI $parent
     * @param Rule              $rule
     */
    public function __construct(RuleMailConfigGUI $parent, Rule $rule)
    {
        $this->rule = $rule;

        parent::__construct($parent);
    }


    /**
     * @inheritDoc
     */
    protected function getValue(/*string*/ $key)
    {
        switch ($key) {
            case "operator_value_text":
                if ($this->rule->getOperatorValueType() === Rule::OPERATOR_VALUE_TYPE_TEXT) {
                    return Items::getter($this->rule, "operator_value");
                }
                break;

            case "operator_value_object_property":
                if ($this->rule->getOperatorValueType() === Rule::OPERATOR_VALUE_TYPE_OBJECT_PROPERTY) {
                    return Items::getter($this->rule, "operator_value");
                }
                break;

            case "receiver":
                return Items::getter($this->rule, "receiver_type");

            case "receiver_object":
                if ($this->rule->getReceiverType() === Rule::RECEIVER_TYPE_OBJECT) {
                    return Items::getter($this->rule, "receiver");
                }
                break;

            case "receiver_users":
                if ($this->rule->getReceiverType() === Rule::RECEIVER_TYPE_USERS) {
                    return Items::getter($this->rule, "receiver");
                }
                break;

            default:
                return Items::getter($this->rule, $key);
        }

        return null;
    }


    /**
     * @inheritDoc
     */
    protected function initCommands()/*: void*/
    {
        if (!empty($this->rule->getRuleId())) {
            $this->addCommandButton(RuleMailConfigGUI::CMD_UPDATE_RULE, $this->txt("save"));
        } else {
            $this->addCommandButton(RuleMailConfigGUI::CMD_CREATE_RULE, $this->txt("add"));
        }
        $this->addCommandButton(RuleMailConfigGUI::CMD_BACK, $this->txt("cancel"));
    }


    /**
     * @inheritDoc
     */
    protected function initFields()/*: void*/
    {
        $this->fields = [
            "object_type" => [
                self::PROPERTY_CLASS    => ilSelectInputGUI::class,
                self::PROPERTY_REQUIRED => true,
                self::PROPERTY_OPTIONS  => ["" => ""] + self::srAutoMails()->objectTypes()->getObjectTypesText(),
                self::PROPERTY_DISABLED => (!empty($this->rule->getRuleId()))
            ]
        ];

        if (!empty($this->rule->getRuleId())) {
            $object_type_definiton = self::srAutoMails()->objectTypes()->factory()->getByObjectType($this->rule->getObjectType());
            $object = $this->fields["object_type"][self::PROPERTY_OPTIONS][$this->rule->getObjectType()];

            $this->fields = array_merge($this->fields, [
                "enabled"       => [
                    self::PROPERTY_CLASS => ilCheckboxInputGUI::class
                ],
                "interval_type" => [
                    self::PROPERTY_CLASS    => ilRadioGroupInputGUI::class,
                    self::PROPERTY_REQUIRED => true,
                    self::PROPERTY_SUBITEMS => [
                        Rule::INTERVAL_TYPE_ONCE   => [
                            self::PROPERTY_CLASS    => ilRadioOption::class,
                            self::PROPERTY_SUBITEMS => [],
                            "setTitle"              => $this->txt("interval_type_once")
                        ],
                        Rule::INTERVAL_TYPE_NUMBER => [
                            self::PROPERTY_CLASS    => ilRadioOption::class,
                            self::PROPERTY_SUBITEMS => [
                                "interval" => [
                                    self::PROPERTY_CLASS => ilNumberInputGUI::class,
                                    "setMinValue"        => 0,
                                    "setSuffix"          => $this->txt("interval_days")
                                ]
                            ],
                            "setTitle"              => $this->txt("interval_type_number")
                        ]
                    ]
                ],
                "title"         => [
                    self::PROPERTY_CLASS    => ilTextInputGUI::class,
                    self::PROPERTY_REQUIRED => true
                ],
                "description"   => [
                    self::PROPERTY_CLASS => ilTextInputGUI::class
                ],
                "match_type"    => [
                    self::PROPERTY_CLASS    => ilRadioGroupInputGUI::class,
                    self::PROPERTY_REQUIRED => true,
                    self::PROPERTY_SUBITEMS => [
                        Rule::MATCH_TYPE_ALWAYS => [
                            self::PROPERTY_CLASS    => ilRadioOption::class,
                            self::PROPERTY_SUBITEMS => [],
                            "setTitle"              => $this->txt("match_type_always")
                        ],
                        Rule::MATCH_TYPE_MATCH  => [
                            self::PROPERTY_CLASS    => ilRadioOption::class,
                            self::PROPERTY_SUBITEMS => [
                                "metadata"                => [
                                    self::PROPERTY_CLASS    => ilSelectInputGUI::class,
                                    self::PROPERTY_REQUIRED => true,
                                    self::PROPERTY_OPTIONS  => ["" => ""] + self::srAutoMails()->ilias()->metadata()->getMetadata()
                                ],
                                "operator"                => [
                                    self::PROPERTY_CLASS    => ilSelectInputGUI::class,
                                    self::PROPERTY_REQUIRED => true,
                                    self::PROPERTY_OPTIONS  => ["" => ""] + self::srAutoMails()->rules()->getOperatorsText()
                                ],
                                "operator_negated"        => [
                                    self::PROPERTY_CLASS => ilCheckboxInputGUI::class
                                ],
                                "operator_case_sensitive" => [
                                    self::PROPERTY_CLASS => ilCheckboxInputGUI::class
                                ],
                                "operator_value_type"     => [
                                    self::PROPERTY_CLASS    => ilRadioGroupInputGUI::class,
                                    self::PROPERTY_REQUIRED => true,
                                    self::PROPERTY_SUBITEMS => [
                                        Rule::OPERATOR_VALUE_TYPE_TEXT            => [
                                            self::PROPERTY_CLASS    => ilRadioOption::class,
                                            self::PROPERTY_SUBITEMS => [
                                                "operator_value_text" => [
                                                    self::PROPERTY_CLASS => ilTextInputGUI::class
                                                ]
                                            ],
                                            "setTitle"              => $this->txt("operator_value_text")
                                        ],
                                        Rule::OPERATOR_VALUE_TYPE_OBJECT_PROPERTY => [
                                            self::PROPERTY_CLASS    => ilRadioOption::class,
                                            self::PROPERTY_SUBITEMS => [
                                                "operator_value_object_property" => [
                                                    self::PROPERTY_CLASS   => ilSelectInputGUI::class,
                                                    self::PROPERTY_OPTIONS => ["" => ""] + $object_type_definiton->getObjectPropertiesText(),
                                                    "setTitle"             => self::plugin()
                                                        ->translate("operator_value_object_property", self::LANG_MODULE, [$object])
                                                ]
                                            ],
                                            "setTitle"              => self::plugin()
                                                ->translate("operator_value_object_property", self::LANG_MODULE, [$object])
                                        ]
                                    ]
                                ]
                            ],
                            "setTitle"              => $this->txt("match_type_match")
                        ]
                    ]
                ],
                "receiver"      => [
                    self::PROPERTY_CLASS    => ilRadioGroupInputGUI::class,
                    self::PROPERTY_REQUIRED => true,
                    self::PROPERTY_SUBITEMS => [
                        Rule::RECEIVER_TYPE_OBJECT => [
                            self::PROPERTY_CLASS    => ilRadioOption::class,
                            self::PROPERTY_SUBITEMS => [
                                "receiver_object" => [
                                    self::PROPERTY_CLASS    => MultiSelectSearchInputGUI::class,
                                    self::PROPERTY_REQUIRED => true,
                                    self::PROPERTY_OPTIONS  => $object_type_definiton->getReceiverPropertiesText(),
                                    "setTitle"              => $object
                                ]
                            ],
                            "setTitle"              => $object
                        ],
                        Rule::RECEIVER_TYPE_USERS  => [
                            self::PROPERTY_CLASS    => ilRadioOption::class,
                            self::PROPERTY_SUBITEMS => [
                                "receiver_users" => [
                                    self::PROPERTY_CLASS    => MultiSelectSearchInputGUI::class,
                                    self::PROPERTY_REQUIRED => true,
                                    self::PROPERTY_OPTIONS  => self::srAutoMails()->ilias()->users()->getUsers()
                                ]
                            ],
                            "setTitle"              => $this->txt("receiver_users")
                        ]
                    ]
                ]
            ]);
        }
    }


    /**
     * @inheritDoc
     */
    protected function initId()/*: void*/
    {

    }


    /**
     * @inheritDoc
     */
    protected function initTitle()/*: void*/
    {
        $this->setTitle($this->txt(!empty($this->rule->getRuleId()) ? "edit_rule" : "add_rule"));
    }


    /**
     * @inheritDoc
     */
    protected function storeValue(/*string*/ $key, $value)/*: void*/
    {
        switch ($key) {
            case "object_type":
                if (empty($this->rule->getRuleId())) {
                    Items::setter($this->rule, "object_type", $value);
                }
                break;

            case "operator_value_text":
                if ($this->rule->getOperatorValueType() === Rule::OPERATOR_VALUE_TYPE_TEXT) {
                    Items::setter($this->rule, "operator_value", $value);
                }
                break;

            case "operator_value_object_property":
                if ($this->rule->getOperatorValueType() === Rule::OPERATOR_VALUE_TYPE_OBJECT_PROPERTY) {
                    Items::setter($this->rule, "operator_value", $value);
                }
                break;

            case "receiver":
                Items::setter($this->rule, "receiver_type", $value);
                break;

            case "receiver_object":
                if ($this->rule->getReceiverType() === Rule::RECEIVER_TYPE_OBJECT) {
                    Items::setter($this->rule, "receiver", $value);
                }
                break;

            case "receiver_users":
                if ($this->rule->getReceiverType() === Rule::RECEIVER_TYPE_USERS) {
                    Items::setter($this->rule, "receiver", $value);
                }
                break;

            default:
                Items::setter($this->rule, $key, $value);
                break;
        }
    }


    /**
     * @inheritDoc
     */
    public function storeForm() : bool
    {
        if (!parent::storeForm()) {
            return false;
        }

        self::srAutoMails()->rules()->storeRule($this->rule);

        return true;
    }
}
