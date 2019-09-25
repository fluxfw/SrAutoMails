<?php

namespace srag\Plugins\SrAutoMails\Notification\Notification;

use srag\Notifications4Plugin\SrAutoMails\Notification\AbstractNotification;
use srag\Plugins\SrAutoMails\Notification\Notification\Language\NotificationLanguage;
use srag\Plugins\SrAutoMails\Utils\SrAutoMailsTrait;

/**
 * Class Notification
 *
 * @package srag\Plugins\SrAutoMails\Notification\Notification
 *
 * @author  studer + raimann ag - Team Custom 1 <support-custom1@studer-raimann.ch>
 */
class Notification extends AbstractNotification
{

    use SrAutoMailsTrait;
    const TABLE_NAME = "srauma_not";
    const LANGUAGE_CLASS_NAME = NotificationLanguage::class;
}
