<?php

namespace srag\Plugins\SrAutoMails\Utils;

use srag\Plugins\SrAutoMails\Access\Access;
use srag\Plugins\SrAutoMails\Access\Ilias;
use srag\Plugins\SrAutoMails\ObjectType\Repository as ObjectTypeRepository;
use srag\Plugins\SrAutoMails\Rule\Repository as RuleRepository;
use srag\Plugins\SrAutoMails\Sent\Repository as SentRepository;

/**
 * Trait SrAutoMailsTrait
 *
 * @package srag\Plugins\SrAutoMails\Utils
 *
 * @author  studer + raimann ag - Team Custom 1 <support-custom1@studer-raimann.ch>
 */
trait SrAutoMailsTrait
{

    /**
     * @return Access
     */
    protected static function access() : Access
    {
        return Access::getInstance();
    }


    /**
     * @return Ilias
     */
    protected static function ilias() : Ilias
    {
        return Ilias::getInstance();
    }


    /**
     * @return ObjectTypeRepository
     */
    protected static function objectTypes() : ObjectTypeRepository
    {
        return ObjectTypeRepository::getInstance();
    }


    /**
     * @return RuleRepository
     */
    protected static function rules() : RuleRepository
    {
        return RuleRepository::getInstance();
    }


    /**
     * @return SentRepository
     */
    protected static function sents() : SentRepository
    {
        return SentRepository::getInstance();
    }
}
