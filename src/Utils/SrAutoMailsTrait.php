<?php

namespace srag\Plugins\SrAutoMails\Utils;

use srag\Plugins\SrAutoMails\Rule\Rules;

/**
 * Trait SrAutoMailsTrait
 *
 * @package srag\Plugins\SrAutoMails\Utils
 *
 * @author  studer + raimann ag - Team Custom 1 <support-custom1@studer-raimann.ch>
 */
trait SrAutoMailsTrait {

	/**
	 * @return Rules
	 */
	protected static function rules(): Rules {
		return Rules::getInstance();
	}
}
