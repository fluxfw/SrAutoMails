<?php

namespace srag\Plugins\SrAutoMails\ObjectType;

use ilObjUser;
use ilSrAutoMailsPlugin;
use srag\DIC\SrAutoMails\DICTrait;
use srag\Plugins\SrAutoMails\Rule\Rule;
use srag\Plugins\SrAutoMails\Rule\RulesMailConfigGUI;
use srag\Plugins\SrAutoMails\Utils\SrAutoMailsTrait;

/**
 * Class ObjectType
 *
 * @package srag\Plugins\SrAutoMails\ObjectType
 *
 * @author  studer + raimann ag - Team Custom 1 <support-custom1@studer-raimann.ch>
 */
abstract class ObjectType
{

    use DICTrait;
    use SrAutoMailsTrait;
    const PLUGIN_CLASS_NAME = ilSrAutoMailsPlugin::class;


    /**
     * @param Rule   $rule
     * @param object $object
     *
     * @return bool
     */
    public final function checkRuleForObject(Rule $rule, $object) : bool
    {
        $time = time();

        switch ($rule->getMatchType()) {
            case Rule::MATCH_TYPE_ALWAYS:
                return true;

            case Rule::MATCH_TYPE_MATCH:
                $metadata_value = self::ilias()->metadata()->getMetadataForObject($this->getObjectId($object), $rule->getMetadata());

                if (empty($metadata_value)) {
                    return false;
                }

                switch ($rule->getOperatorValueType()) {
                    case Rule::OPERATOR_VALUE_TYPE_TEXT:
                        $object_value = $rule->getOperatorValue();
                        break;

                    case Rule::OPERATOR_VALUE_TYPE_OBJECT_PROPERTY:
                        $object_value = $this->getObjectProperty($object, $rule->getOperatorValue());
                        break;

                    default:
                        return false;
                }

                if (!$rule->isOperatorCaseSensitive()) {
                    if (is_string($metadata_value)) {
                        $metadata_value = strtolower($metadata_value);
                    }
                    if (is_string($object_value)) {
                        $object_value = strtolower($object_value);
                    }
                }

                switch ($rule->getOperator()) {
                    case Rule::OPERATOR_EQUALS:
                        $check = ($metadata_value == $object_value);
                        break;

                    case Rule::OPERATOR_STARTS_WITH:
                        $check = (strpos($metadata_value, $object_value) === 0);
                        break;

                    case Rule::OPERATOR_CONTAINS:
                        $check = (strpos($metadata_value, $object_value) !== false);
                        break;

                    case Rule::OPERATOR_ENDS_WITH:
                        $check = (strrpos($metadata_value, $object_value) === (strlen($metadata_value) - strlen($object_value)));
                        break;

                    case Rule::OPERATOR_IS_EMPTY:
                        $check = empty($metadata_value);
                        break;

                    case Rule::OPERATOR_REG_EX:
                        // Fix RegExp
                        if ($object_value[0] !== "/" && $object_value[strlen($object_value) - 1] !== "/") {
                            $object_value = "/$object_value/";
                        }
                        $check = (preg_match($object_value, $metadata_value) === 1);
                        break;

                    case Rule::OPERATOR_LESS:
                        $check = ($metadata_value < $object_value);
                        break;

                    case Rule::OPERATOR_LESS_EQUALS:
                        $check = ($metadata_value <= $object_value);
                        break;

                    case Rule::OPERATOR_BIGGER:
                        $check = ($metadata_value > $object_value);
                        break;

                    case Rule::OPERATOR_BIGGER_EQUALS:
                        $check = ($metadata_value >= $object_value);
                        break;

                    case Rule::OPERATOR_X_DAYS_BEFORE:
                        $check = ($object_value !== null && (($object_value->getUnixTime() - $time) / (60 * 60 * 24)) <= $metadata_value);

                        break;

                    case Rule::OPERATOR_X_DAYS_AFTER:
                        $check = ($object_value !== null && (($time - $object_value->getUnixTime()) / (60 * 60 * 24)) >= $metadata_value);

                        break;

                    default:
                        return false;
                }

                if ($rule->isOperatorNegated()) {
                    $check = (!$check);
                }

                return $check;

            default:
                return false;
        }
    }


    /**
     * @return array
     */
    public function getMailPlaceholderKeyTypes() : array
    {
        return [
            "receiver" => "object " . ilObjUser::class,
            "object"   => "object",
            "rule_id"  => "int"
        ];
    }


    /**
     * @return array
     */
    public final function getObjectPropertiesText()
    {
        return array_map(function (string $object_property) : string {
            return self::plugin()->translate("object_property_" . $object_property, RulesMailConfigGUI::LANG_MODULE_RULES);
        }, $this->getObjectProperties());
    }


    /**
     * @return string
     */
    public final function getObjectType() : string
    {
        return static::OBJECT_TYPE;
    }


    /**
     * @param object $object
     * @param int    $user_id
     * @param Rule   $rule
     *
     * @return array
     */
    public final function getPlaceholdersForMail($object, int $user_id, Rule $rule) : array
    {
        $placeholders = array_merge($this->getMailPlaceholderKeyTypes(), [
            "receiver" => new ilObjUser($user_id),
            "object"   => $object,
            "rule_id"  => $rule->getRuleId()
        ]);

        $this->applyMailPlaceholders($object, $placeholders);

        return $placeholders;
    }


    /**
     * @param Rule   $rule
     * @param object $object
     *
     * @return int[]
     */
    public final function getReceivers(Rule $rule, $object) : array
    {
        switch ($rule->getReceiverType()) {
            case Rule::RECEIVER_TYPE_OBJECT:
                $receivers = $this->getReceiverForObject($rule->getReceiver(), $object);
                break;

            case Rule::RECEIVER_TYPE_USERS:
                $receivers = $rule->getReceiver();
                break;

            default:
                $receivers = [];
                break;
        }

        $receivers = array_unique(array_map(function ($user_id) : int { return intval($user_id); }, $receivers));

        return $receivers;
    }


    /**
     * @return array
     */
    public final function getReceiverPropertiesText()
    {
        return array_map(function (string $object_property) : string {
            return self::plugin()->translate("receiver_" . $object_property, RulesMailConfigGUI::LANG_MODULE_RULES);
        }, $this->getReceiverProperties());
    }


    /**
     * @var int
     *
     * @abstract
     */
    const OBJECT_TYPE = "";


    /**
     * @param object $object
     * @param array  $placeholders
     *
     * @return mixed
     */
    protected abstract function applyMailPlaceholders($object, array &$placeholders)/*: void*/ ;


    /**
     * @return string[]
     */
    protected abstract function getObjectProperties() : array;


    /**
     * @param object $object
     * @param string $object_property
     *
     * @return string|int
     */
    protected abstract function getObjectProperty($object, string $object_property);


    /**
     * @return object[]
     */
    public abstract function getObjects() : array;


    /**
     * @param object $object
     *
     * @return int
     */
    public abstract function getObjectId($object) : int;


    /**
     * @return string[]
     */
    protected abstract function getReceiverProperties() : array;


    /**
     * @param array  $receivers
     * @param object $object
     *
     * @return int[]
     */
    protected abstract function getReceiverForObject(array $receivers, $object) : array;
}
