<?php

namespace srag\Plugins\SrAutoMails\Utils;

use srag\Plugins\SrAutoMails\Repository;

/**
 * Trait SrAutoMailsTrait
 *
 * @package srag\Plugins\SrAutoMails\Utils
 */
trait SrAutoMailsTrait
{

    /**
     * @return Repository
     */
    protected static function srAutoMails() : Repository
    {
        return Repository::getInstance();
    }
}
