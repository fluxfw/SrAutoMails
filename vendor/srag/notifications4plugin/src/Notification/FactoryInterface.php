<?php

namespace srag\Notifications4Plugin\SrAutoMails\Notification;

use stdClass;

/**
 * Interface FactoryInterface
 *
 * @package srag\Notifications4Plugin\SrAutoMails\Notification
 *
 * @author  studer + raimann ag - Team Custom 1 <support-custom1@studer-raimann.ch>
 */
interface FactoryInterface
{

    /**
     * @param stdClass $data
     *
     * @return NotificationInterface
     */
    public function fromDB(stdClass $data) : NotificationInterface;


    /**
     * @return NotificationInterface
     */
    public function newInstance() : NotificationInterface;


    /**
     * @param AbstractNotificationsCtrl $parent
     * @param string                    $parent_cmd
     *
     * @return NotificationsTableGUI
     */
    public function newTableInstance(AbstractNotificationsCtrl $parent, string $parent_cmd = AbstractNotificationsCtrl::CMD_LIST_NOTIFICATIONS) : NotificationsTableGUI;


    /**
     * @param AbstractNotificationCtrl $parent
     * @param NotificationInterface    $notification
     *
     * @return NotificationFormGUI
     */
    public function newFormInstance(AbstractNotificationCtrl $parent, NotificationInterface $notification) : NotificationFormGUI;
}
