<?php

namespace srag\Notifications4Plugin\SrAutoMails\Notification;

use srag\DIC\SrAutoMails\DICTrait;
use srag\Notifications4Plugin\SrAutoMails\Utils\Notifications4PluginTrait;

/**
 * Class AbstractNotificationsCtrl
 *
 * @package srag\Notifications4Plugin\SrAutoMails\Notification
 *
 * @author  studer + raimann ag - Team Custom 1 <support-custom1@studer-raimann.ch>
 */
abstract class AbstractNotificationsCtrl
{

    use DICTrait;
    use Notifications4PluginTrait;
    const CMD_LIST_NOTIFICATIONS = "listNotifications";
    const LANG_MODULE = "notifications4plugin";
    const TAB_NOTIFICATIONS = "notifications";


    /**
     * AbstractNotificationsCtrl constructor
     */
    public function __construct()
    {

    }


    /**
     *
     */
    public function executeCommand()/*: void*/
    {
        $this->setTabs();

        $next_class = self::dic()->ctrl()->getNextClass($this);

        switch (strtolower($next_class)) {
            case strtolower($this->getNotificationCtrlClass());
                $class = $this->getNotificationCtrlClass();
                self::dic()->ctrl()->forwardCommand(new $class($this));
                break;

            default:
                $cmd = self::dic()->ctrl()->getCmd();

                switch ($cmd) {
                    case self::CMD_LIST_NOTIFICATIONS:
                        $this->{$cmd}();
                        break;

                    default:
                        break;
                }
                break;
        }
    }


    /**
     *
     */
    protected function setTabs()/*: void*/
    {
        self::dic()->tabs()->activateTab(self::TAB_NOTIFICATIONS);
    }


    /**
     *
     */
    protected function listNotifications()/*: void*/
    {
        $table = self::notifications4plugin()->notifications()->factory()->newTableInstance($this);

        self::output()->output($table);
    }


    /**
     * @return string
     */
    public abstract function getNotificationCtrlClass() : string;
}
