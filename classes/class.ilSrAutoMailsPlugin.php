<?php

require_once __DIR__ . "/../vendor/autoload.php";

use ILIAS\DI\Container;
use ILIAS\GlobalScreen\Scope\MainMenu\Provider\AbstractStaticPluginMainMenuProvider;
use srag\CustomInputGUIs\SrAutoMails\Loader\CustomInputGUIsLoaderDetector;
use srag\DevTools\SrAutoMails\DevToolsCtrl;
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

    const PLUGIN_CLASS_NAME = self::class;
    const PLUGIN_ID = "srauma";
    const PLUGIN_NAME = "SrAutoMails";
    /**
     * @var self|null
     */
    protected static $instance = null;


    /**
     * ilSrAutoMailsPlugin constructor
     */
    public function __construct()
    {
        parent::__construct();
    }


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
     * @inheritDoc
     */
    public function exchangeUIRendererAfterInitialization(Container $dic) : Closure
    {
        return CustomInputGUIsLoaderDetector::exchangeUIRendererAfterInitialization();
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
    public function getCronJobInstances() : array
    {
        return self::srAutoMails()->jobs()->factory()->newInstances();
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
    public function promoteGlobalScreenProvider() : AbstractStaticPluginMainMenuProvider
    {
        return self::srAutoMails()->menu();
    }


    /**
     * @inheritDoc
     */
    public function updateLanguages(/*?array*/ $a_lang_keys = null)/*:void*/
    {
        parent::updateLanguages($a_lang_keys);

        $this->installRemovePluginDataConfirmLanguages();

        self::srAutoMails()->notifications4plugin()->installLanguages();

        DevToolsCtrl::installLanguages(self::plugin());
    }


    /**
     * @inheritDoc
     */
    protected function deleteData()/*: void*/
    {
        self::srAutoMails()->dropTables();
    }


    /**
     * @inheritDoc
     */
    protected function shouldUseOneUpdateStepOnly() : bool
    {
        return true;
    }
}
