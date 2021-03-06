<?php

namespace srag\DataTableUI\SrAutoMails\Implementation\Format\Browser;

use srag\DataTableUI\SrAutoMails\Component\Format\Browser\BrowserFormat;
use srag\DataTableUI\SrAutoMails\Component\Format\Browser\Factory as FactoryInterface;
use srag\DataTableUI\SrAutoMails\Component\Format\Browser\Filter\Factory as FilterFactoryInterface;
use srag\DataTableUI\SrAutoMails\Implementation\Format\Browser\Filter\Factory as FilterFactory;
use srag\DataTableUI\SrAutoMails\Implementation\Utils\DataTableUITrait;
use srag\DIC\SrAutoMails\DICTrait;

/**
 * Class Factory
 *
 * @package srag\DataTableUI\SrAutoMails\Implementation\Format\Browser
 */
class Factory implements FactoryInterface
{

    use DICTrait;
    use DataTableUITrait;

    /**
     * @var self|null
     */
    protected static $instance = null;


    /**
     * Factory constructor
     */
    private function __construct()
    {

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
     * @inheritDoc
     */
    public function default() : BrowserFormat
    {
        return new DefaultBrowserFormat();
    }


    /**
     * @inheritDoc
     */
    public function filter() : FilterFactoryInterface
    {
        return FilterFactory::getInstance();
    }
}
