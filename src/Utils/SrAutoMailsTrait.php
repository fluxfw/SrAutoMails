<?php

namespace srag\Plugins\SrAutoMails\Utils;

use srag\Plugins\SrAutoMails\Repository;

/**
 * Trait SrAutoMailsTrait
 *
 * @package srag\Plugins\SrAutoMails\Utils
 *
 * @author  studer + raimann ag - Team Custom 1 <support-custom1@studer-raimann.ch>
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
