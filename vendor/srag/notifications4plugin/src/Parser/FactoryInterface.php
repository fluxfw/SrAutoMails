<?php

namespace srag\Notifications4Plugin\SrAutoMails\Parser;

/**
 * Interface FactoryInterface
 *
 * @package srag\Notifications4Plugin\SrAutoMails\Parser
 */
interface FactoryInterface
{

    /**
     * @return twigParser
     */
    public function twig() : twigParser;
}
