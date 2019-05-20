<?php

namespace srag\Notifications4Plugin\SrAutoMails\Notification;

use srag\DIC\SrAutoMails\Plugin\PluginInterface;

/**
 * Interface RepositoryInterface
 *
 * @package srag\Notifications4Plugin\SrAutoMails\Notification
 *
 * @author  studer + raimann ag - Team Custom 1 <support-custom1@studer-raimann.ch>
 */
interface RepositoryInterface {

	/**
	 * @param Notification $notification
	 */
	public function deleteNotification(Notification $notification)/*: void*/ ;


	/**
	 * @param Notification    $notification
	 * @param PluginInterface $plugin
	 *
	 * @return Notification
	 */
	public function duplicateNotification(Notification $notification, PluginInterface $plugin): Notification;


	/**
	 * @return FactoryInterface
	 */
	public function factory(): FactoryInterface;


	/**
	 * @param Notification[] $notifications
	 *
	 * @return array
	 */
	public function getArrayForSelection(array $notifications): array;


	/**
	 * @param int $id
	 *
	 * @return Notification|null
	 */
	public function getNotificationById(int $id)/*: ?Notification*/ ;


	/**
	 * @param string $name
	 *
	 * @return Notification|null
	 */
	public function getNotificationByName(string $name)/*: ?Notification*/ ;


	/**
	 * @return Notification[]
	 */
	public function getNotifications(): array;


	/**
	 * @param string $name |null
	 *
	 * @return Notification|null
	 *
	 * @deprecated
	 */
	public function migrateFromOldGlobalPlugin(string $name = null)/*: ?Notification*/ ;


	/**
	 * @param Notification $notification
	 */
	public function storeInstance(Notification $notification)/*: void*/ ;
}
