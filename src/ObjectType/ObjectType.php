<?php

namespace srag\Plugins\SrAutoMails\ObjectType;

use ilADT;
use ilADTInteger;
use ilADTText;
use ilAdvancedMDValues;
use ilObjUser;
use ilSrAutoMailsConfigGUI;
use ilSrAutoMailsPlugin;
use srag\DIC\DICTrait;
use srag\Plugins\SrAutoMails\Rule\Rule;
use srag\Plugins\SrAutoMails\Utils\SrAutoMailsTrait;

/**
 * Class ObjectType
 *
 * @package srag\Plugins\SrAutoMails\ObjectType
 *
 * @author  studer + raimann ag - Team Custom 1 <support-custom1@studer-raimann.ch>
 */
abstract class ObjectType {

	use DICTrait;
	use SrAutoMailsTrait;
	const PLUGIN_CLASS_NAME = ilSrAutoMailsPlugin::class;


	/**
	 * @param Rule   $rule
	 * @param object $object
	 *
	 * @return bool
	 */
	public final function checkRuleForObject(Rule $rule, $object): bool {
		$metadata = $this->getMetadataForObject($object, $rule->getMetadata());

		if (empty($metadata)) {
			return false;
		}

		$text = "";
		switch ($rule->getOperatorValueType()) {
			case Rule::OPERATOR_VALUE_TYPE_TEXT:
				$text = $rule->getOperatorValue();
				break;

			case Rule::OPERATOR_VALUE_TYPE_OBJECT_PROPERTY:
				$text = $this->getObjectProperty($object, $rule->getOperatorValue());
				break;

			default:
				return false;
		}

		if (!$rule->isOperatorCaseSensitive()) {
			$metadata = strtolower($metadata);
			$text = strtolower($text);
		}

		$check = false;
		switch ($rule->getOperator()) {
			case Rule::OPERATOR_EQUALS:
				$check = ($metadata == $text);
				break;

			case Rule::OPERATOR_STARTS_WITH:
				$check = (strpos($metadata, $text) === 0);
				break;

			case Rule::OPERATOR_CONTAINS:
				$check = (strpos($metadata, $text) !== false);
				break;

			case Rule::OPERATOR_ENDS_WITH:
				$check = (strrpos($metadata, $text) === (strlen($metadata) - strlen($text)));
				break;

			case Rule::OPERATOR_IS_EMPTY:
				$check = empty($metadata);
				break;

			case Rule::OPERATOR_REG_EX:
				// Fix RegExp
				if ($text[0] !== "/" && $text[strlen($text) - 1] !== "/") {
					$text = "/$text/";
				}
				$check = (preg_match($text, $metadata) === 1);
				break;

			case Rule::OPERATOR_LESS:
				$check = ($metadata < $text);
				break;

			case Rule::OPERATOR_LESS_EQUALS:
				$check = ($metadata <= $text);
				break;

			case Rule::OPERATOR_BIGGER:
				$check = ($metadata > $text);
				break;

			case Rule::OPERATOR_BIGGER_EQUALS:
				$check = ($metadata >= $text);
				break;

			default:
				return false;
		}

		if ($rule->isOperatorNegated()) {
			$check = (!$check);
		}

		return $check;
	}


	/**
	 * @param object $object
	 * @param int    $metadata_id
	 *
	 * @return mixed
	 */
	protected final function getMetadataForObject($object, int $metadata_id) {
		$values = new ilAdvancedMDValues(self::ilias()->metadata()->getRecordOfField($metadata_id), $this->getObjectId($object), "", "");

		$values->read();

		/**
		 * @var ilADT|null $metadata
		 */
		$metadata = $values->getADTGroup()->getElement($metadata_id);

		switch (true) {
			case ($metadata instanceof ilADTText):
				return $metadata->getText();

			case ($metadata instanceof ilADTInteger):
				return $metadata->getNumber();

			default:
				return NULL;
		}
	}


	/**
	 * @return array
	 */
	public final function getObjectPropertiesText() {
		return array_map(function (string $object_property): string {
			return self::plugin()->translate("object_property_" . $object_property, ilSrAutoMailsConfigGUI::LANG_MODULE_CONFIG);
		}, $this->getObjectProperties());
	}


	/**
	 * @return string
	 */
	public final function getObjectType(): string {
		return static::OBJECT_TYPE;
	}


	/**
	 * @param object $object
	 * @param int    $user_id
	 * @param Rule   $rule
	 *
	 * @return array
	 */
	public final function getPlaceholdersForMail($object, int $user_id, Rule $rule): array {
		$placeholders = [
			"user" => new ilObjUser($user_id),
			"superiors" => self::ilias()->orgUnits()->getSuperiorsOfUser($user_id),
			"object" => $object,
			"rule_id" => $rule->getRuleId()
		];

		$this->applyMailPlaceholders($object, $placeholders);

		return $placeholders;
	}


	/**
	 * @param Rule   $rule
	 * @param object $object
	 *
	 * @return int[]
	 */
	public final function getReceivers(Rule $rule, $object): array {
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

		$receivers = array_unique(array_map(function ($user_id): int { return intval($user_id); }, $receivers));

		return $receivers;
	}


	/**
	 * @return array
	 */
	public final function getReceiverPropertiesText() {
		return array_map(function (string $object_property): string {
			return self::plugin()->translate("receiver_" . $object_property, ilSrAutoMailsConfigGUI::LANG_MODULE_CONFIG);
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
	protected abstract function applyMailPlaceholders($object, array &$placeholders)/*: void*/
	;


	/**
	 * @return string[]
	 */
	protected abstract function getObjectProperties(): array;


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
	public abstract function getObjects(): array;


	/**
	 * @param object $object
	 *
	 * @return int
	 */
	public abstract function getObjectId($object): int;


	/**
	 * @return string[]
	 */
	protected abstract function getReceiverProperties(): array;


	/**
	 * @param array  $receivers
	 * @param object $object
	 *
	 * @return int[]
	 */
	protected abstract function getReceiverForObject(array $receivers, $object): array;
}
