<?php

require_once __DIR__ . "/../vendor/autoload.php";

use ILIAS\GlobalScreen\Scope\MainMenu\Provider\AbstractStaticPluginMainMenuProvider;
use srag\Plugins\SrAutoMails\Utils\SrAutoMailsTrait;
use srag\RemovePluginDataConfirm\SrAutoMails\PluginUninstallTrait;

/**
 * Class ilSrAutoMailsPlugin
 *
 * @author studer + raimann ag - Team Custom 1 <support-custom1@studer-raimann.ch>
 */
class ilSrAutoMailsPlugin extends ilCronHookPlugin
{

    use PluginUninstallTrait;
    use SrAutoMailsTrait;
    const PLUGIN_ID = "srauma";
    const PLUGIN_NAME = "SrAutoMails";
    const PLUGIN_CLASS_NAME = self::class;
    /**
     * @var self|null
     */
    protected static $instance = null;


    /**
     * @return self
     */
    public static function getInstance() : self
    {
        if (self::$instance === null) {
            self::$instance = new self();
        }

        return self::$instance;
    }


    /**
     * ilSrAutoMailsPlugin constructor
     */
    public function __construct()
    {
        parent::__construct();
    }


    /**
     * @inheritDoc
     */
    public function getPluginName() : string
    {
        return self::PLUGIN_NAME;
    }


    /**
     * @inheritDoc
     */
    public function getCronJobInstances() : array
    {
        return self::srAutoMails()->jobs()->factory()->newInstances();
    }


    /**
     * @inheritDoc
     */
    public function getCronJobInstance(/*string*/ $a_job_id)/*: ?ilCronJob*/
    {
        return self::srAutoMails()->jobs()->factory()->newInstanceById($a_job_id);
    }


    /**
     * @inheritDoc
     */
    public function updateLanguages(/*?array*/ $a_lang_keys = null)/*:void*/
    {
        parent::updateLanguages($a_lang_keys);

        $this->installRemovePluginDataConfirmLanguages();

        self::srAutoMails()->notifications4plugin()->installLanguages();
    }


    /**
     * @inheritDoc
     */
    public function promoteGlobalScreenProvider() : AbstractStaticPluginMainMenuProvider
    {
        return self::srAutoMails()->menu();
    }


    /**
     * @inheritDoc
     */
    protected function deleteData()/*: void*/
    {
        self::srAutoMails()->dropTables();
    }
}
