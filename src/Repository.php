<?php

namespace srag\Plugins\SrAutoMails;

use ilSrAutoMailsPlugin;
use srag\DIC\SrAutoMails\DICTrait;
use srag\Notifications4Plugin\SrAutoMails\RepositoryInterface as Notifications4PluginRepositoryInterface;
use srag\Notifications4Plugin\SrAutoMails\Utils\Notifications4PluginTrait;
use srag\Plugins\SrAutoMails\Access\Ilias;
use srag\Plugins\SrAutoMails\Config\Repository as ConfigRepository;
use srag\Plugins\SrAutoMails\Job\Repository as JobsRepository;
use srag\Plugins\SrAutoMails\Log\Repository as LogsRepository;
use srag\Plugins\SrAutoMails\Menu\Menu;
use srag\Plugins\SrAutoMails\ObjectType\Repository as ObjectTypesRepository;
use srag\Plugins\SrAutoMails\Rule\Repository as RulesRepository;
use srag\Plugins\SrAutoMails\Sent\Repository as SentsRepository;
use srag\Plugins\SrAutoMails\Utils\SrAutoMailsTrait;

/**
 * Class Repository
 *
 * @package srag\Plugins\SrAutoMails
 */
final class Repository
{

    use DICTrait;
    use SrAutoMailsTrait;
    use Notifications4PluginTrait {
        notifications4plugin as protected _notifications4plugin;
    }

    const PLUGIN_CLASS_NAME = ilSrAutoMailsPlugin::class;
    /**
     * @var self|null
     */
    protected static $instance = null;


    /**
     * Repository constructor
     */
    private function __construct()
    {
        $this->notifications4plugin()->withTableNamePrefix(ilSrAutoMailsPlugin::PLUGIN_ID)->withPlugin(self::plugin());
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
     * @return ConfigRepository
     */
    public function config() : ConfigRepository
    {
        return ConfigRepository::getInstance();
    }


    /**
     *
     */
    public function dropTables() : void
    {
        $this->config()->dropTables();
        $this->jobs()->dropTables();
        $this->logs()->dropTables();
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
    public function installTables() : void
    {
        $this->config()->installTables();
        $this->jobs()->installTables();
        $this->logs()->installTables();
        $this->notifications4plugin()->installTables();
        $this->objectTypes()->installTables();
        $this->rules()->installTables();
        $this->sents()->installTables();
    }


    /**
     * @return JobsRepository
     */
    public function jobs() : JobsRepository
    {
        return JobsRepository::getInstance();
    }


    /**
     * @return LogsRepository
     */
    public function logs() : LogsRepository
    {
        return LogsRepository::getInstance();
    }


    /**
     * @return Menu
     */
    public function menu() : Menu
    {
        return new Menu(self::dic()->dic(), self::plugin()->getPluginObject());
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
