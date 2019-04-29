<?php

namespace srag\Plugins\SrAutoMails\Notification\Notification\Language;

use srag\Notifications4Plugin\SrAutoMails\Notification\Language\AbstractNotificationLanguage;
use srag\Plugins\SrAutoMails\Utils\SrAutoMailsTrait;

/**
 * Class NotificationLanguage
 *
 * @package srag\Plugins\SrAutoMails\Notification\Notification\Language
 *
 * @author  studer + raimann ag - Team Custom 1 <support-custom1@studer-raimann.ch>
 */
class NotificationLanguage extends AbstractNotificationLanguage {

	use SrAutoMailsTrait;
	const TABLE_NAME = "srauma_not_lang";
}
