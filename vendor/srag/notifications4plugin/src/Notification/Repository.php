<?php

namespace srag\Notifications4Plugin\SrAutoMails\Notification;

use ilDateTime;
use ilDBConstants;
use srag\DIC\SrAutoMails\DICTrait;
use srag\DIC\SrAutoMails\Plugin\PluginInterface;
use srag\Notifications4Plugin\SrAutoMails\Ctrl\CtrlInterface;
use srag\Notifications4Plugin\SrAutoMails\Parser\twigParser;
use srag\Notifications4Plugin\SrAutoMails\Utils\Notifications4PluginTrait;

/**
 * Class Repository
 *
 * @package srag\Notifications4Plugin\SrAutoMails\Notification
 *
 * @author  studer + raimann ag - Team Custom 1 <support-custom1@studer-raimann.ch>
 */
final class Repository implements RepositoryInterface {

	use DICTrait;
	use Notifications4PluginTrait;
	/**
	 * @var RepositoryInterface[]
	 */
	protected static $instances = [];


	/**
	 * @param string $notification_class
	 * @param string $language_class
	 *
	 * @return RepositoryInterface
	 */
	public static function getInstance(string $notification_class, string $language_class): RepositoryInterface {
		if (!isset(self::$instances[$notification_class . "_" . $language_class])) {
			self::$instances[$notification_class . "_" . $language_class] = new self($notification_class, $language_class);
		}

		return self::$instances[$notification_class . "_" . $language_class];
	}


	/**
	 * @var string|Notification
	 */
	protected $notification_class;
	/**
	 * @var string
	 */
	protected $language_class;


	/**
	 * Repository constructor
	 *
	 * @param string $notification_class
	 * @param string $language_class
	 */
	private function __construct(string $notification_class, string $language_class) {
		$this->notification_class = $notification_class;
		$this->language_class = $language_class;
	}


	/**
	 * @inheritdoc
	 */
	public function deleteNotification(Notification $notification)/*: void*/ {
		self::dic()->database()->manipulate('DELETE FROM ' . self::dic()->database()->quoteIdentifier($this->notification_class::TABLE_NAME)
			. " WHERE id=%s", [ ilDBConstants::T_INTEGER ], [ $notification->getId() ]);

		foreach (self::notificationLanguage($this->language_class)->getLanguagesForNotification($notification->getId()) as $language) {
			self::notificationLanguage($this->language_class)->deleteLanguage($language);
		}
	}


	/**
	 * @inheritdoc
	 */
	public function duplicateNotification(Notification $notification, PluginInterface $plugin): Notification {
		$duplicated_notification = clone $notification;

		$duplicated_notification->setId(0);

		$duplicated_notification->setTitle($duplicated_notification->getTitle() . " ("
			. $plugin->translate("duplicated", CtrlInterface::LANG_MODULE_NOTIFICATIONS4PLUGIN) . ")");

		$languages = [];
		foreach (self::notificationLanguage($this->language_class)->getLanguagesForNotification($notification->getId()) as $language) {
			$languages[$language->getLanguage()] = self::notificationLanguage($this->language_class)->duplicateLanguage($language);
		}
		$duplicated_notification->setLanguages($languages);

		return $duplicated_notification;
	}


	/**
	 * @inheritdoc
	 */
	public function factory(): FactoryInterface {
		return Factory::getInstance($this->notification_class);
	}


	/**
	 * @inheritdoc
	 */
	public function getArrayForSelection(array $notifications): array {
		$array = [];

		foreach ($notifications as $notification) {
			$array[$notification->getName()] = $notification->getTitle() . " (" . $notification->getName() . ")";
		}

		return $array;
	}


	/**
	 * @inheritdoc
	 */
	public function getNotificationById(int $id)/*: ?Notification*/ {
		/**
		 * @var Notification|null $notification
		 */
		$notification = self::dic()->database()->fetchObjectCallback(self::dic()->database()->queryF("SELECT * FROM " . self::dic()->database()
				->quoteIdentifier($this->notification_class::TABLE_NAME) . " WHERE id=%s", [ ilDBConstants::T_INTEGER ], [ $id ]), [
			$this->factory(),
			"fromDB"
		]);

		return $notification;
	}


	/**
	 * @inheritdoc
	 */
	public function getNotificationByName(string $name)/*: ?Notification*/ {
		/**
		 * @var Notification|null $notification
		 */
		$notification = self::dic()->database()->fetchObjectCallback(self::dic()->database()->queryF("SELECT * FROM " . self::dic()->database()
				->quoteIdentifier($this->notification_class::TABLE_NAME)
			. " WHERE name=%s", [ ilDBConstants::T_TEXT ], [ $name ]), [ $this->factory(), "fromDB" ]);

		return $notification;
	}


	/**
	 * @inheritdoc
	 */
	public function getNotifications(): array {
		/**
		 * @var Notification[] $notifications
		 */
		$notifications = self::dic()->database()->fetchAllCallback(self::dic()->database()->query("SELECT * FROM " . self::dic()->database()
				->quoteIdentifier($this->notification_class::TABLE_NAME) . " ORDER BY title ASC"), [ $this->factory(), "fromDB" ]);

		return $notifications;
	}


	/**
	 * @inheritdoc
	 *
	 * @deprecated
	 */
	public function migrateFromOldGlobalPlugin(string $name = null)/*: ?Notification*/ {
		$global_plugin_notification_table_name = "sr_notification";
		$global_plugin_notification_language_table_name = "sr_notification_lang";
		$global_plugin_twig_parser_class = implode("\\", [
			"srag",
			"Notifications4Plugin",
			"Notifications4Plugins",
			"Parser",
			"twigParser"
		]); // (Prevents LibraryNamespaceChanger)

		if (!empty($name)) {
			if (self::dic()->database()->tableExists($global_plugin_notification_table_name)
				&& self::dic()->database()->tableExists($global_plugin_notification_language_table_name)) {
				$result = self::dic()->database()->queryF("SELECT * FROM " . self::dic()->database()
						->quoteIdentifier($global_plugin_notification_table_name) . " WHERE name=%s", [ ilDBConstants::T_TEXT ], [ $name ]);

				if (($row = $result->fetchAssoc()) !== false) {

					$notification = $this->getNotificationByName($name);
					if ($notification !== null) {
						return $notification;
					}

					$notification = $this->factory()->newInstance();

					$notification->setName($row["name"]);
					$notification->setTitle($row["title"]);
					$notification->setDescription($row["description"]);
					$notification->setDefaultLanguage($row["default_language"]);

					if ($row["parser"] === $global_plugin_twig_parser_class) {
						$notification->setParser(twigParser::class);
					} else {
						$notification->setParser($row["parser"]);
					}

					$result2 = self::dic()->database()->queryF("SELECT * FROM " . self::dic()->database()
							->quoteIdentifier($global_plugin_notification_language_table_name)
						. " WHERE notification_id=%s", [ ilDBConstants::T_INTEGER ], [ $row["id"] ]);

					while (($row2 = $result2->fetchAssoc()) !== false) {
						$notification->setSubject($row2["subject"], $row2["language"]);
						$notification->setText($row2["text"], $row2["language"]);
					}

					$this->storeInstance($notification);

					return $notification;
				}
			}
		}

		return null;
	}


	/**
	 * @inheritdoc
	 */
	public function storeInstance(Notification $notification)/*: void*/ {
		$date = new ilDateTime(time(), IL_CAL_UNIX);

		if (empty($notification->getId())) {
			$notification->setCreatedAt($date);
		}

		$notification->setUpdatedAt($date);

		$notification->setId(self::dic()->database()->store($this->notification_class::TABLE_NAME, [
			"name" => [ ilDBConstants::T_TEXT, $notification->getName() ],
			"title" => [ ilDBConstants::T_TEXT, $notification->getTitle() ],
			"description" => [ ilDBConstants::T_TEXT, $notification->getDescription() ],
			"default_language" => [ ilDBConstants::T_TEXT, $notification->getDefaultLanguage() ],
			"parser" => [ ilDBConstants::T_TEXT, $notification->getParser() ],
			"created_at" => [ ilDBConstants::T_TEXT, $notification->getCreatedAt()->get(IL_CAL_DATETIME) ],
			"updated_at" => [ ilDBConstants::T_TEXT, $notification->getUpdatedAt()->get(IL_CAL_DATETIME) ]
		], "id", $notification->getId()));

		foreach ($notification->getLanguages() as $language) {
			$language->setNotificationId($notification->getId());

			self::notificationLanguage($this->language_class)->storeInstance($language);
		}
	}
}
