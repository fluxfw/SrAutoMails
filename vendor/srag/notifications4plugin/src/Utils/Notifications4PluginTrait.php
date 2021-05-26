<?php

namespace srag\Notifications4Plugin\SrAutoMails\Utils;

use srag\Notifications4Plugin\SrAutoMails\Repository as Notifications4PluginRepository;
use srag\Notifications4Plugin\SrAutoMails\RepositoryInterface as Notifications4PluginRepositoryInterface;

/**
 * Trait Notifications4PluginTrait
 *
 * @package srag\Notifications4Plugin\SrAutoMails\Utils
 */
trait Notifications4PluginTrait
{

    /**
     * @return Notifications4PluginRepositoryInterface
     */
    protected static function notifications4plugin() : Notifications4PluginRepositoryInterface
    {
        return Notifications4PluginRepository::getInstance();
    }
}
