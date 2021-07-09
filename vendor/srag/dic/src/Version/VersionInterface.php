<?php

namespace srag\DIC\SrAutoMails\Version;

/**
 * Interface VersionInterface
 *
 * @package srag\DIC\SrAutoMails\Version
 */
interface VersionInterface
{

    const ILIAS_VERSION_6 = "6.0";
    const ILIAS_VERSION_7 = "7.0";


    /**
     * @return string
     */
    public function getILIASVersion() : string;


    /**
     * @return bool
     */
    public function is6() : bool;


    /**
     * @return bool
     */
    public function is7() : bool;


    /**
     * @param string $version
     *
     * @return bool
     */
    public function isEqual(string $version) : bool;


    /**
     * @param string $version
     *
     * @return bool
     */
    public function isGreater(string $version) : bool;


    /**
     * @param string $version
     *
     * @return bool
     */
    public function isLower(string $version) : bool;


    /**
     * @param string $version
     *
     * @return bool
     */
    public function isMaxVersion(string $version) : bool;


    /**
     * @param string $version
     *
     * @return bool
     */
    public function isMinVersion(string $version) : bool;
}
