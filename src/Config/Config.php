<?php

namespace srag\Plugins\SrAutoMails\Config;

use ilSrAutoMailsPlugin;
use srag\ActiveRecordConfig\SrAutoMails\ActiveRecordConfig;
use srag\Plugins\SrAutoMails\Utils\SrAutoMailsTrait;

/**
 * Class Config
 *
 * @package srag\Plugins\SrAutoMails\Config
 *
 * @author  studer + raimann ag - Team Custom 1 <support-custom1@studer-raimann.ch>
 */
class Config extends ActiveRecordConfig
{

    use SrAutoMailsTrait;
    const TABLE_NAME = "srauma_config";
    const PLUGIN_CLASS_NAME = ilSrAutoMailsPlugin::class;
    /**
     * @var array
     */
    protected static $fields
        = [

        ];
}
