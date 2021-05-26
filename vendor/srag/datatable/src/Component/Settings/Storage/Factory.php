<?php

namespace srag\DataTableUI\SrAutoMails\Component\Settings\Storage;

/**
 * Interface Factory
 *
 * @package srag\DataTableUI\SrAutoMails\Component\Settings\Storage
 */
interface Factory
{

    /**
     * @return SettingsStorage
     */
    public function default() : SettingsStorage;
}
