<?php

namespace srag\Plugins\SrAutoMails\Sent;

use ilDBConstants;
use ilSrAutoMailsPlugin;
use srag\DIC\SrAutoMails\DICTrait;
use srag\Plugins\SrAutoMails\Utils\SrAutoMailsTrait;
use Throwable;

/**
 * Class Repository
 *
 * @package srag\Plugins\SrAutoMails\Sent
 *
 * @author  studer + raimann ag - Team Custom 1 <support-custom1@studer-raimann.ch>
 */
final class Repository
{

    use DICTrait;
    use SrAutoMailsTrait;

    const PLUGIN_CLASS_NAME = ilSrAutoMailsPlugin::class;
    /**
     * @var self|null
     */
    protected static $instance = null;


    /**
     * Repository constructor
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
     * @internal
     */
    public function dropTables()/*: void*/
    {

    }


    /**
     * @param int $rule_id
     * @param int $object_id
     * @param int $user_id
     *
     * @return bool
     */
    public function hasSent(int $rule_id, int $object_id, int $user_id) : bool
    {
        $sent = $this->getSent($rule_id, $object_id, $user_id);

        return ($sent !== null);
    }


    /**
     * @internal
     */
    public function installTables()/*: void*/
    {
        try {
            Sent::updateDB();
        } catch (Throwable $ex) {
            // Fix Call to a member function getName() on null (Because not use ILIAS sequence)
        }

        if (self::dic()->database()->tableColumnExists(Sent::TABLE_NAME, "id")) {
            self::dic()->database()->dropTableColumn(Sent::TABLE_NAME, "id");
        }
    }


    /**
     * @param int $rule_id
     * @param int $object_id
     * @param int $user_id
     */
    public function sent(int $rule_id, int $object_id, int $user_id)/*: void*/
    {
        $sent = $this->getSent($rule_id, $object_id, $user_id);

        if ($sent === null) {
            $sent = $this->factory()->newInstance();
            $sent->setRuleId($rule_id);
            $sent->setObjectId($object_id);
            $sent->setUserId($user_id);
            $this->store($sent);
        }
    }


    /**
     * @param int $rule_id
     * @param int $object_id
     * @param int $user_id
     */
    public function unsent(int $rule_id, int $object_id, int $user_id)/*: void*/
    {
        $sent = $this->getSent($rule_id, $object_id, $user_id);

        if ($sent !== null) {
            $this->delete($sent);
        }
    }


    /**
     * @param Sent $sent
     */
    protected function delete(Sent $sent)/*: void*/
    {
        self::dic()->database()->manipulateF('DELETE FROM ' . self::dic()->database()->quoteIdentifier(Sent::TABLE_NAME)
            . " WHERE rule_id=%s AND object_id=%s AND user_id=%s", [
            ilDBConstants::T_INTEGER,
            ilDBConstants::T_INTEGER,
            ilDBConstants::T_INTEGER
        ], [$sent->getRuleId(), $sent->getObjectId(), $sent->getUserId()]);
    }


    /**
     * @return Factory
     */
    protected function factory() : Factory
    {
        return Factory::getInstance();
    }


    /**
     * @param int $rule_id
     * @param int $object_id
     * @param int $user_id
     *
     * @return Sent|null
     */
    protected function getSent(int $rule_id, int $object_id, int $user_id)/*: ?Sent*/
    {
        /**
         * @var Sent|null $sent
         */
        $sent = self::dic()->database()->fetchObjectCallback(self::dic()->database()->queryF('SELECT * FROM ' . self::dic()->database()
                ->quoteIdentifier(Sent::TABLE_NAME) . " WHERE rule_id=%s AND object_id=%s AND user_id=%s", [
            ilDBConstants::T_INTEGER,
            ilDBConstants::T_INTEGER,
            ilDBConstants::T_INTEGER
        ], [$rule_id, $object_id, $user_id]), [$this->factory(), "fromDB"]);

        return $sent;
    }


    /**
     * @param Sent $sent
     */
    protected function store(Sent $sent)/*: void*/
    {
        self::dic()->database()->insert(Sent::TABLE_NAME, [
            "rule_id"   => [ilDBConstants::T_INTEGER, $sent->getRuleId()],
            "object_id" => [ilDBConstants::T_INTEGER, $sent->getObjectId()],
            "user_id"   => [ilDBConstants::T_INTEGER, $sent->getUserId()]
        ]);
    }
}
