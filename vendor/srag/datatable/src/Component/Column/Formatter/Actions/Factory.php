<?php

namespace srag\DataTableUI\SrAutoMails\Component\Column\Formatter\Actions;

/**
 * Interface Factory
 *
 * @package srag\DataTableUI\SrAutoMails\Component\Column\Formatter\Actions
 */
interface Factory
{

    /**
     * @return ActionsFormatter
     */
    public function actionsDropdown() : ActionsFormatter;


    /**
     * @return ActionsFormatter
     */
    public function sort() : ActionsFormatter;
}
