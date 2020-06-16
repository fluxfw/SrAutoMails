<?php

namespace srag\DataTableUI\SrAutoMails\Implementation\Column\Formatter;

use ilLearningProgressBaseGUI;
use srag\DataTableUI\SrAutoMails\Component\Column\Column;
use srag\DataTableUI\SrAutoMails\Component\Data\Row\RowData;
use srag\DataTableUI\SrAutoMails\Component\Format\Format;

/**
 * Class LearningProgressFormatter
 *
 * @package srag\DataTableUI\SrAutoMails\Implementation\Column\Formatter
 *
 * @author  studer + raimann ag - Team Custom 1 <support-custom1@studer-raimann.ch>
 */
class LearningProgressFormatter extends DefaultFormatter
{

    /**
     * @inheritDoc
     */
    public function formatRowCell(Format $format, $status, Column $column, RowData $row, string $table_id) : string
    {
        $img = ilLearningProgressBaseGUI::_getImagePathForStatus($status);
        $text = ilLearningProgressBaseGUI::_getStatusText($status);

        return self::output()->getHTML([
            self::dic()->ui()
                ->factory()
                ->icon()
                ->custom($img, $text),
            self::dic()->ui()->factory()->legacy($text)
        ]);
    }
}
