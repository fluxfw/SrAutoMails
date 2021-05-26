<?php

namespace srag\DataTableUI\SrAutoMails\Implementation\Column\Formatter;

use srag\DataTableUI\SrAutoMails\Component\Column\Formatter\Formatter;
use srag\DataTableUI\SrAutoMails\Implementation\Utils\DataTableUITrait;
use srag\DIC\SrAutoMails\DICTrait;

/**
 * Class AbstractFormatter
 *
 * @package srag\DataTableUI\SrAutoMails\Implementation\Column\Formatter
 */
abstract class AbstractFormatter implements Formatter
{

    use DICTrait;
    use DataTableUITrait;

    /**
     * AbstractFormatter constructor
     */
    public function __construct()
    {

    }
}
