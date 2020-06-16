<?php

namespace srag\DataTableUI\SrAutoMails\Implementation\Utils;

use srag\DataTableUI\SrAutoMails\Component\Factory as FactoryInterface;
use srag\DataTableUI\SrAutoMails\Implementation\Factory;

/**
 * Trait DataTableUITrait
 *
 * @package srag\DataTableUI\SrAutoMails\Implementation\Utils
 *
 * @author  studer + raimann ag - Team Custom 1 <support-custom1@studer-raimann.ch>
 */
trait DataTableUITrait
{

    /**
     * @return FactoryInterface
     */
    protected static function dataTableUI() : FactoryInterface
    {
        return Factory::getInstance();
    }
}
