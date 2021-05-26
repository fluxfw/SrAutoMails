<?php

namespace srag\Plugins\SrAutoMails\Access;

use ilADT;
use ilADTInteger;
use ilADTText;
use ilAdvancedMDValues;
use ilDBConstants;
use ilSrAutoMailsPlugin;
use srag\DIC\SrAutoMails\DICTrait;
use srag\Plugins\SrAutoMails\Utils\SrAutoMailsTrait;

/**
 * Class Metadata
 *
 * @package srag\Plugins\SrAutoMails\Access
 */
final class Metadata
{

    use DICTrait;
    use SrAutoMailsTrait;

    const PLUGIN_CLASS_NAME = ilSrAutoMailsPlugin::class;
    /**
     * @var self|null
     */
    protected static $instance = null;


    /**
     * Metadata constructor
     */
    private function __construct()
    {

    }


    /**
     * @return self
     */
    public static function getInstance() : self
    {
        if (self::$instance === null) {
            self::$instance = new self();
        }

        return self::$instance;
    }


    /**
     * @return array
     */
    public function getMetadata() : array
    {
        $result = self::dic()->database()->queryF('SELECT field_id, title FROM adv_mdf_definition', [], []);

        $array = [];

        while (($row = $result->fetchAssoc()) !== false) {
            $array[$row["field_id"]] = $row["title"];
        }

        return $array;
    }


    /**
     * @param int $obj_id
     * @param int $metadata_id
     *
     * @return mixed
     */
    public function getMetadataForObject(int $obj_id, int $metadata_id)
    {
        $values = new ilAdvancedMDValues($this->getRecordOfField($metadata_id), $obj_id, "", "");

        $values->read();

        /**
         * @var ilADT|null $metadata
         */
        $metadata = $values->getADTGroup()->getElement($metadata_id);

        switch (true) {
            case ($metadata instanceof ilADTText):
                return $metadata->getText();

            case ($metadata instanceof ilADTInteger):
                return $metadata->getNumber();

            default:
                return null;
        }
    }


    /**
     * @param int $field_id
     *
     * @return int
     */
    protected function getRecordOfField(int $field_id) : int
    {
        $result = self::dic()->database()
            ->queryF('SELECT record_id FROM adv_mdf_definition WHERE field_id=%s', [ilDBConstants::T_INTEGER], [$field_id]);

        $record_id = intval($result->fetchAssoc()["record_id"]);

        return $record_id;
    }
}
