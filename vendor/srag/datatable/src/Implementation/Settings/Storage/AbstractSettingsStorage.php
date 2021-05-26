<?php

namespace srag\DataTableUI\SrAutoMails\Implementation\Settings\Storage;

use srag\DataTableUI\SrAutoMails\Component\Column\Column;
use srag\DataTableUI\SrAutoMails\Component\Settings\Settings;
use srag\DataTableUI\SrAutoMails\Component\Settings\Sort\SortField;
use srag\DataTableUI\SrAutoMails\Component\Settings\Storage\SettingsStorage;
use srag\DataTableUI\SrAutoMails\Component\Table;
use srag\DataTableUI\SrAutoMails\Implementation\Utils\DataTableUITrait;
use srag\DIC\SrAutoMails\DICTrait;

/**
 * Class AbstractSettingsStorage
 *
 * @package srag\DataTableUI\SrAutoMails\Implementation\Settings\Storage
 */
abstract class AbstractSettingsStorage implements SettingsStorage
{

    use DICTrait;
    use DataTableUITrait;

    /**
     * AbstractSettingsStorage constructor
     */
    public function __construct()
    {

    }


    /**
     * @inheritDoc
     */
    public function handleDefaultSettings(Settings $settings, Table $component) : Settings
    {
        if (!$settings->isFilterSet() && empty($settings->getSortFields())) {
            $settings = $settings->withSortFields(array_map(function (Column $column) use ($component) : SortField {
                return self::dataTableUI()->settings()->sort()->sortField($column->getKey(), $column->getDefaultSortDirection());
            }, array_filter($component->getColumns(), function (Column $column) : bool {
                return ($column->isSortable() && $column->isDefaultSort());
            })));
        }

        if (!$settings->isFilterSet() && empty($settings->getSelectedColumns())) {
            $settings = $settings->withSelectedColumns(array_map(function (Column $column) : string {
                return $column->getKey();
            }, array_filter($component->getColumns(), function (Column $column) : bool {
                return ($column->isSelectable() && $column->isDefaultSelected());
            })));
        }

        return $settings;
    }
}
