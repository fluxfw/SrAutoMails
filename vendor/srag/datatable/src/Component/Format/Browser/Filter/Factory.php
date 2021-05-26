<?php

namespace srag\DataTableUI\SrAutoMails\Component\Format\Browser\Filter;

use srag\CustomInputGUIs\SrAutoMails\FormBuilder\FormBuilder;
use srag\DataTableUI\SrAutoMails\Component\Format\Browser\BrowserFormat;
use srag\DataTableUI\SrAutoMails\Component\Settings\Settings;
use srag\DataTableUI\SrAutoMails\Component\Table;

/**
 * Interface Factory
 *
 * @package srag\DataTableUI\SrAutoMails\Component\Format\Browser\Filter
 */
interface Factory
{

    /**
     * @param BrowserFormat $parent
     * @param Table         $component
     * @param Settings      $settings
     *
     * @return FormBuilder
     */
    public function formBuilder(BrowserFormat $parent, Table $component, Settings $settings) : FormBuilder;
}
