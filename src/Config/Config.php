<?php

namespace srag\Plugins\SrAutoMails\Config;

use ilSrAutoMailsPlugin;
use OrgUnitAssistantRemoveDataConfirm;
use srag\ActiveRecordConfig\ActiveRecordConfig;
use srag\Plugins\SrAutoMails\Utils\SrAutoMailsTrait;

/**
 * Class Config
 *
 * @package srag\Plugins\SrAutoMails\Config
 *
 * @author  studer + raimann ag - Team Custom 1 <support-custom1@studer-raimann.ch>
 */
class Config extends ActiveRecordConfig {

	use SrAutoMailsTrait;
	const TABLE_NAME = "srauma_config";
	const PLUGIN_CLASS_NAME = ilSrAutoMailsPlugin::class;


	/**
	 * @return bool|null
	 */
	public static function getUninstallRemovesData()/*: ?bool*/ {
		return self::getXValue(OrgUnitAssistantRemoveDataConfirm::KEY_UNINSTALL_REMOVES_DATA, OrgUnitAssistantRemoveDataConfirm::DEFAULT_UNINSTALL_REMOVES_DATA);
	}


	/**
	 * @param bool $uninstall_removes_data
	 */
	public static function setUninstallRemovesData(bool $uninstall_removes_data)/*: void*/ {
		self::setBooleanValue(OrgUnitAssistantRemoveDataConfirm::KEY_UNINSTALL_REMOVES_DATA, $uninstall_removes_data);
	}


	/**
	 *
	 */
	public static function removeUninstallRemovesData()/*: void*/ {
		self::removeName(OrgUnitAssistantRemoveDataConfirm::KEY_UNINSTALL_REMOVES_DATA);
	}
}
