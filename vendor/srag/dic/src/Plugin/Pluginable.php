<?php

namespace srag\DIC\SrAutoMails\Plugin;

/**
 * Interface Pluginable
 *
 * @package srag\DIC\SrAutoMails\Plugin
 */
interface Pluginable
{

    /**
     * @return PluginInterface
     */
    public function getPlugin() : PluginInterface;


    /**
     * @param PluginInterface $plugin
     *
     * @return static
     */
    public function withPlugin(PluginInterface $plugin)/*: static*/ ;
}
