<?php

namespace srag\Plugins\SrAutoMails\Notification;

use ilSrAutoMailsPlugin;
use srag\Notifications4Plugin\SrAutoMails\Notification\AbstractNotificationsCtrl;
use srag\Plugins\SrAutoMails\Utils\SrAutoMailsTrait;

/**
 * Class NotificationsCtrl
 *
 * @package           srag\Plugins\SrAutoMails\Notification
 *
 * @author            studer + raimann ag - Team Custom 1 <support-custom1@studer-raimann.ch>
 *
 * @ilCtrl_isCalledBy srag\Plugins\SrAutoMails\Notification\NotificationsCtrl: srag\Plugins\SrAutoMails\Rule\RulesMailConfigGUI
 */
class NotificationsCtrl extends AbstractNotificationsCtrl
{

    use SrAutoMailsTrait;
    const PLUGIN_CLASS_NAME = ilSrAutoMailsPlugin::class;


    /**
     * @inheritDoc
     */
    public function getNotificationCtrlClass() : string
    {
        return NotificationCtrl::class;
    }
}
