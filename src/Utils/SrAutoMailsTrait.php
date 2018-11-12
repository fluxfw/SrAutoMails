<?php

namespace srag\Plugins\SrAutoMails\Utils;

use srag\Plugins\SrAutoMails\Access\Ilias;
use srag\Plugins\SrAutoMails\ObjectType\ObjectTypes;
use srag\Plugins\SrAutoMails\Rule\Rules;
use srag\Plugins\SrAutoMails\Sent\Sents;

/**
 * Trait SrAutoMailsTrait
 *
 * @package srag\Plugins\SrAutoMails\Utils
 *
 * @author  studer + raimann ag - Team Custom 1 <support-custom1@studer-raimann.ch>
 */
trait SrAutoMailsTrait {

	/**
	 * @return Ilias ias
	 */
	protected static function ilias(): Ilias {
		return Ilias::getInstance();
	}


	/**
	 * @return ObjectTypes
	 */
	protected static function objectTypes(): ObjectTypes {
		return ObjectTypes::getInstance();
	}


	/**
	 * @return Rules
	 */
	protected static function rules(): Rules {
		return Rules::getInstance();
	}


	/**
	 * @return Sents
	 */
	protected static function sents(): Sents {
		return Sents::getInstance();
	}
}
