<?php

namespace srag\Plugins\SrAutoMails\Access;

use ilDBConstants;
use ilObjOrgUnit;
use ilOrgUnitOperationContext;
use ilOrgUnitPermission;
use ilOrgUnitPosition;
use ilOrgUnitUserAssignment;
use ilSrAutoMailsPlugin;
use srag\DIC\SrAutoMails\DICTrait;
use srag\Plugins\SrAutoMails\Utils\SrAutoMailsTrait;

/**
 * Class OrgUnits
 *
 * @package srag\Plugins\SrAutoMails\Access
 *
 * @author  studer + raimann ag - Team Custom 1 <support-custom1@studer-raimann.ch>
 */
final class OrgUnits
{

    use DICTrait;
    use SrAutoMailsTrait;
    const PLUGIN_CLASS_NAME = ilSrAutoMailsPlugin::class;
    /**
     * @var self
     */
    protected static $instance = null;


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
     * OrgUnits constructor
     */
    private function __construct()
    {

    }


    /**
     * @param int $user_id
     *
     * @return int[]
     */
    public function getMemberOrgIdsOfUser(int $user_id) : array
    {
        $result = self::dic()->database()->queryF('SELECT orgu_id FROM ' . ilOrgUnitPermission::TABLE_NAME . '
				INNER JOIN ' . ilOrgUnitUserAssignment::returnDbTableName() . ' ON ' . ilOrgUnitUserAssignment::returnDbTableName() . '.position_id=' . ilOrgUnitPermission::TABLE_NAME . '.position_id
				INNER JOIN ' . ilOrgUnitOperationContext::returnDbTableName() . ' ON ' . ilOrgUnitOperationContext::returnDbTableName() . '.id=' . ilOrgUnitPermission::TABLE_NAME . '.context_id AND '
            . ilOrgUnitOperationContext::returnDbTableName() . '.context IS NOT NULL
				WHERE ' . ilOrgUnitUserAssignment::returnDbTableName()
            . '.user_id=%s AND ' . ilOrgUnitPermission::TABLE_NAME . '.operations IS NOT NULL AND ' . ilOrgUnitPermission::TABLE_NAME . '.parent_id=%s AND ' . ilOrgUnitPermission::TABLE_NAME
            . '.position_id=%s', [
            ilDBConstants::T_INTEGER,
            ilDBConstants::T_INTEGER,
            ilDBConstants::T_INTEGER
        ], [
            $user_id,
            -1,
            ilOrgUnitPosition::getCorePositionId(ilOrgUnitPosition::CORE_POSITION_EMPLOYEE)
        ]);

        $org_units = [];

        while (($row = $result->fetchAssoc()) !== false) {
            $org_units[] = $row["orgu_id"];
        }

        return $org_units;
    }


    /**
     * @return ilObjOrgUnit[]
     */
    public function getOrgUnits() : array
    {
        $result = self::dic()->database()->queryF('SELECT obj_id FROM object_data WHERE type=%s', [ilDBConstants::T_TEXT], ["orgu"]);

        $array = [];

        while (($row = $result->fetchAssoc()) !== false) {
            $array[] = new ilObjOrgUnit($row["obj_id"], false);
        }

        return $array;
    }


    /**
     * @param int $org_unit_ref_id
     *
     * @return int[]
     */
    public function getSuperiorsOfOrgUnit(int $org_unit_ref_id) : array
    {
        $result = self::dic()->database()->queryF('SELECT user_id FROM ' . ilOrgUnitUserAssignment::returnDbTableName() . ' WHERE ' . ilOrgUnitUserAssignment::returnDbTableName() . '.orgu_id=%s AND '
            . ilOrgUnitUserAssignment::returnDbTableName() . '.position_id=%s', [
            ilDBConstants::T_INTEGER,
            ilDBConstants::T_INTEGER
        ], [
            $org_unit_ref_id,
            ilOrgUnitPosition::getCorePositionId(ilOrgUnitPosition::CORE_POSITION_SUPERIOR)
        ]);

        $users = [];

        while (($row = $result->fetchAssoc()) !== false) {
            $users[] = $row["user_id"];
        }

        return $users;
    }


    /**
     * @param int[] $users
     *
     * @return int[]
     */
    public function getSuperiorsOfUsers(array $users) : array
    {
        $users = array_reduce($users, function (array $users, int $user_id) : array {
            $org_units = $this->getMemberOrgIdsOfUser($user_id);

            foreach ($org_units as $org_unit_ref_id) {
                foreach ($this->getSuperiorsOfOrgUnit($org_unit_ref_id) as $user_id) {
                    $users[] = $user_id;
                }
            }

            return $users;
        }, []);

        return $users;
    }
}
