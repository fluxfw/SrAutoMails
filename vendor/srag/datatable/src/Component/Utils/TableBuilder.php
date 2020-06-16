<?php

namespace srag\DataTableUI\SrAutoMails\Component\Utils;

use srag\DataTableUI\SrAutoMails\Component\Table;

/**
 * Interface TableBuilder
 *
 * @package srag\DataTableUI\SrAutoMails\Component\Utils
 *
 * @author  studer + raimann ag - Team Custom 1 <support-custom1@studer-raimann.ch>
 */
interface TableBuilder
{

    /**
     * @return Table
     */
    public function getTable() : Table;


    /**
     * @return string
     */
    public function render() : string;
}
