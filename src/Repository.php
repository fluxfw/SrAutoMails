<?php

namespace srag\Plugins\SrAutoMails;

use ilSrAutoMailsPlugin;
use srag\ActiveRecordConfig\SrAutoMails\Config\Repository as ConfigRepository;
use srag\ActiveRecordConfig\SrAutoMails\Utils\ConfigTrait;
use srag\DIC\SrAutoMails\DICTrait;
use srag\Notifications4Plugin\SrAutoMails\RepositoryInterface as Notifications4PluginRepositoryInterface;
use srag\Notifications4Plugin\SrAutoMails\Utils\Notifications4PluginTrait;
use srag\Plugins\SrAutoMails\Access\Ilias;
use srag\Plugins\SrAutoMails\ObjectType\Repository as ObjectTypesRepository;
use srag\Plugins\SrAutoMails\Rule\Repository as RulesRepository;
use srag\Plugins\SrAutoMails\Sent\Repository as SentsRepository;
use srag\Plugins\SrAutoMails\Utils\SrAutoMailsTrait;

/**
 * Class Repository
 *
 * @package srag\Plugins\SrAutoMails
 *
 * @author  studer + raimann ag - Team Custom 1 <support-custom1@studer-raimann.ch>
 */
final class Repository
{

    use DICTrait;
    use SrAutoMailsTrait;
    use ConfigTrait {
        config as protected _config;
    }
    use Notifications4PluginTrait {
        notifications4plugin as protected _notifications4plugin;
    }
    const PLUGIN_CLASS_NAME = ilSrAutoMailsPlugin::class;
    /**
     * @var self
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
     * Repository constructor
     */
    private function __construct()
    {
        $this->config()->withTableName(ilSrAutoMailsPlugin::PLUGIN_ID . "_config")->withFields([]);

        $this->notifications4plugin()->withTableNamePrefix(ilSrAutoMailsPlugin::PLUGIN_ID)->withPlugin(self::plugin());
    }


    /**
     * @inheritDoc
     */
    public function config() : ConfigRepository
    {
        return self::_config();
    }


    /**
     *
     */
    public function dropTables()/*: void*/
    {
        $this->config()->dropTables();
        $this->notifications4plugin()->dropTables();
        $this->objectTypes()->dropTables();
        $this->rules()->dropTables();
        $this->sents()->dropTables();
    }


    /**
     * @return Ilias
     */
    public function ilias() : Ilias
    {
        return Ilias::getInstance();
    }


    /**
     *
     */
    public function installTables()/*: void*/
    {
        $this->config()->installTables();
        $this->notifications4plugin()->installTables();
        $this->objectTypes()->installTables();
        $this->rules()->installTables();
        $this->sents()->installTables();
    }


    /**
     * @inheritDoc
     */
    public function notifications4plugin() : Notifications4PluginRepositoryInterface
    {
        return self::_notifications4plugin();
    }


    /**
     * @return ObjectTypesRepository
     */
    public function objectTypes() : ObjectTypesRepository
    {
        return ObjectTypesRepository::getInstance();
    }


    /**
     * @return RulesRepository
     */
    public function rules() : RulesRepository
    {
        return RulesRepository::getInstance();
    }


    /**
     * @return SentsRepository
     */
    public function sents() : SentsRepository
    {
        return SentsRepository::getInstance();
    }
}
