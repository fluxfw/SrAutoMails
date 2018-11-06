<?php

require_once __DIR__ . "/../../vendor/autoload.php";

use srag\Plugins\SrAutoMails\Config\Config;
use srag\Plugins\SrAutoMails\Utils\SrAutoMailsTrait;
use srag\RemovePluginDataConfirm\AbstractRemovePluginDataConfirm;

/**
 * Class SrAutoMailsRemoveDataConfirm
 *
 * @author            studer + raimann ag - Team Custom 1 <support-custom1@studer-raimann.ch>
 *
 * @ilCtrl_isCalledBy SrAutoMailsRemoveDataConfirm: ilUIPluginRouterGUI
 */
class SrAutoMailsRemoveDataConfirm extends AbstractRemovePluginDataConfirm {

	use SrAutoMailsTrait;
	const PLUGIN_CLASS_NAME = ilSrAutoMailsPlugin::class;


	/**
	 * @inheritdoc
	 */
	public function getUninstallRemovesData()/*: ?bool*/ {
		return Config::getUninstallRemovesData();
	}


	/**
	 * @inheritdoc
	 */
	public function setUninstallRemovesData(/*bool*/
		$uninstall_removes_data)/*: void*/ {
		Config::setUninstallRemovesData($uninstall_removes_data);
	}


	/**
	 * @inheritdoc
	 */
	public function removeUninstallRemovesData()/*: void*/ {
		Config::removeUninstallRemovesData();
	}
}
