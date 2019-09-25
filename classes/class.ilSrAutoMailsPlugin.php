<?php

require_once __DIR__ . "/../vendor/autoload.php";

use ILIAS\GlobalScreen\Scope\MainMenu\Provider\AbstractStaticPluginMainMenuProvider;
use srag\DIC\SrAutoMails\Util\LibraryLanguageInstaller;
use srag\Plugins\SrAutoMails\Config\Config;
use srag\Plugins\SrAutoMails\Job\Job;
use srag\Plugins\SrAutoMails\Menu\Menu;
use srag\Plugins\SrAutoMails\Notification\Notification\Language\NotificationLanguage;
use srag\Plugins\SrAutoMails\Notification\Notification\Notification;
use srag\Plugins\SrAutoMails\Rule\Rule;
use srag\Plugins\SrAutoMails\Sent\Sent;
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
    const REMOVE_PLUGIN_DATA_CONFIRM_CLASS_NAME = SrAutoMailsRemoveDataConfirm::class;
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
     * @return string
     */
    public function getPluginName() : string
    {
        return self::PLUGIN_NAME;
    }


    /**
     * @return ilCronJob[]
     */
    public function getCronJobInstances() : array
    {
        return [new Job()];
    }


    /**
     * @param string $a_job_id
     *
     * @return ilCronJob|null
     */
    public function getCronJobInstance(/*string*/ $a_job_id)/*: ?ilCronJob*/
    {
        switch ($a_job_id) {
            case Job::CRON_JOB_ID:
                return new Job();

            default:
                return null;
        }
    }


    /**
     * @inheritdoc
     */
    public function updateLanguages($a_lang_keys = null)
    {
        parent::updateLanguages($a_lang_keys);

        LibraryLanguageInstaller::getInstance()->withPlugin(self::plugin())->withLibraryLanguageDirectory(__DIR__
            . "/../vendor/srag/removeplugindataconfirm/lang")->updateLanguages();

        LibraryLanguageInstaller::getInstance()->withPlugin(self::plugin())->withLibraryLanguageDirectory(__DIR__
            . "/../vendor/srag/notifications4plugin/lang")->updateLanguages();
    }


    /**
     * @inheritdoc
     */
    public function promoteGlobalScreenProvider() : AbstractStaticPluginMainMenuProvider
    {
        return new Menu(self::dic()->dic(), $this);
    }


    /**
     * @inheritdoc
     */
    protected function deleteData()/*: void*/
    {
        self::dic()->database()->dropTable(Config::TABLE_NAME, false);
        Notification::dropDB_();
        NotificationLanguage::dropDB_();
        self::dic()->database()->dropTable(Rule::TABLE_NAME, false);
        self::dic()->database()->dropTable(Sent::TABLE_NAME, false);
    }
}
