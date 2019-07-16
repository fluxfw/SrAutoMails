<?php

namespace srag\Notifications4Plugin\SrAutoMails\UI;

use ilConfirmationGUI;
use srag\DIC\SrAutoMails\Plugin\Pluginable;
use srag\Notifications4Plugin\SrAutoMails\Ctrl\CtrlInterface;
use srag\Notifications4Plugin\SrAutoMails\Notification\Notification;

/**
 * Interface UIInterface
 *
 * @package srag\Notifications4Plugin\SrAutoMails\UI
 *
 * @author  studer + raimann ag - Team Custom 1 <support-custom1@studer-raimann.ch>
 */
interface UIInterface extends Pluginable {

	/**
	 * @param CtrlInterface $ctrl_class
	 *
	 * @return self
	 */
	public function withCtrlClass(CtrlInterface $ctrl_class): self;


	/**
	 * @param Notification $notification
	 *
	 * @return ilConfirmationGUI
	 */
	public function notificationDeleteConfirmation(Notification $notification): ilConfirmationGUI;


	/**
	 * @param Notification $notification
	 *
	 * @return NotificationFormGUI
	 */
	public function notificationForm(Notification $notification): NotificationFormGUI;


	/**
	 * @param string   $parent_cmd
	 * @param callable $getNotifications
	 * @param callable $getNotificationsCount
	 *
	 * @return NotificationsTableGUI
	 */
	public function notificationTable(string $parent_cmd, callable $getNotifications, callable $getNotificationsCount): NotificationsTableGUI;


	/**
	 * @param array  $notifications
	 * @param string $post_key
	 *
	 * @return array
	 */
	public function templateSelection(array $notifications, string $post_key): array;
}
