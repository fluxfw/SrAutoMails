<?php

namespace srag\Plugins\SrAutoMails\Config;

use ilSrAutoMailsPlugin;
use srag\ActiveRecordConfig\ActiveRecordConfigFormGUI;
use srag\Plugins\SrAutoMails\Utils\SrAutoMailsTrait;

/**
 * Class ConfigFormGUI
 *
 * @package srag\Plugins\SrAutoMails\Config
 *
 * @author  studer + raimann ag - Team Custom 1 <support-custom1@studer-raimann.ch>
 */
class ConfigFormGUI extends ActiveRecordConfigFormGUI {

	use SrAutoMailsTrait;
	const PLUGIN_CLASS_NAME = ilSrAutoMailsPlugin::class;


	/**
	 * @inheritdoc
	 */
	protected function initForm()/*: void*/ {
		parent::initForm();
	}


	/**
	 * @inheritdoc
	 */
	public function updateConfig()/*: void*/ {

	}
}
