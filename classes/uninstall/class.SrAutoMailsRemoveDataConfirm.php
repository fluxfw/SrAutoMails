<?php

require_once __DIR__ . "/../../vendor/autoload.php";

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
}
