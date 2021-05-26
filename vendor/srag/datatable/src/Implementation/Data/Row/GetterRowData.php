<?php

namespace srag\DataTableUI\SrAutoMails\Implementation\Data\Row;

use srag\CustomInputGUIs\SrAutoMails\PropertyFormGUI\Items\Items;

/**
 * Class GetterRowData
 *
 * @package srag\DataTableUI\SrAutoMails\Implementation\Data\Row
 */
class GetterRowData extends AbstractRowData
{

    /**
     * @inheritDoc
     */
    public function __invoke(string $key)
    {
        return Items::getter($this->getOriginalData(), $key);
    }
}
