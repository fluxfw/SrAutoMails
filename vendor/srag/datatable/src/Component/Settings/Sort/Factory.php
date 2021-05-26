<?php

namespace srag\DataTableUI\SrAutoMails\Component\Settings\Sort;

/**
 * Interface Factory
 *
 * @package srag\DataTableUI\SrAutoMails\Component\Settings\Sort
 */
interface Factory
{

    /**
     * @param string $sort_field
     * @param int    $sort_field_direction
     *
     * @return SortField
     */
    public function sortField(string $sort_field, int $sort_field_direction) : SortField;
}
