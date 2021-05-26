<?php

namespace srag\DataTableUI\SrAutoMails\Implementation\Utils;

use srag\DataTableUI\SrAutoMails\Component\Factory as FactoryInterface;
use srag\DataTableUI\SrAutoMails\Implementation\Factory;

/**
 * Trait DataTableUITrait
 *
 * @package srag\DataTableUI\SrAutoMails\Implementation\Utils
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
