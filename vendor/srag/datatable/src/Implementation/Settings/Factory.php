<?php

namespace srag\DataTableUI\SrAutoMails\Implementation\Settings;

use ILIAS\UI\Component\ViewControl\Pagination;
use srag\DataTableUI\SrAutoMails\Component\Settings\Factory as FactoryInterface;
use srag\DataTableUI\SrAutoMails\Component\Settings\Settings as SettingsInterface;
use srag\DataTableUI\SrAutoMails\Component\Settings\Sort\Factory as SortFactoryInterface;
use srag\DataTableUI\SrAutoMails\Component\Settings\Storage\Factory as StorageFactoryInterface;
use srag\DataTableUI\SrAutoMails\Implementation\Settings\Sort\Factory as SortFactory;
use srag\DataTableUI\SrAutoMails\Implementation\Settings\Storage\Factory as StorageFactory;
use srag\DataTableUI\SrAutoMails\Implementation\Utils\DataTableUITrait;
use srag\DIC\SrAutoMails\DICTrait;

/**
 * Class Factory
 *
 * @package srag\DataTableUI\SrAutoMails\Implementation\Settings
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
    public function settings(Pagination $pagination) : SettingsInterface
    {
        return new Settings($pagination);
    }


    /**
     * @inheritDoc
     */
    public function sort() : SortFactoryInterface
    {
        return SortFactory::getInstance();
    }


    /**
     * @inheritDoc
     */
    public function storage() : StorageFactoryInterface
    {
        return StorageFactory::getInstance();
    }
}
