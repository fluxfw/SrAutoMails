<?php

namespace srag\DIC\SrAutoMails\DIC;

use ILIAS\DI\Container;
use srag\DIC\SrAutoMails\Database\DatabaseDetector;
use srag\DIC\SrAutoMails\Database\DatabaseInterface;

/**
 * Class AbstractDIC
 *
 * @package srag\DIC\SrAutoMails\DIC
 */
abstract class AbstractDIC implements DICInterface
{

    /**
     * @var Container
     */
    protected $dic;


    /**
     * @inheritDoc
     */
    public function __construct(Container &$dic)
    {
        $this->dic = &$dic;
    }


    /**
     * @inheritDoc
     */
    public function database() : DatabaseInterface
    {
        return DatabaseDetector::getInstance($this->databaseCore());
    }
}
