<#1>
<?php
\srag\Plugins\SrAutoMails\Config\Config::updateDB();
\srag\Plugins\SrAutoMails\Rule\Rule::updateDB();
try {
	\srag\Plugins\SrAutoMails\Sent\Sent::updateDB();
} catch (Throwable $ex) {
    // Fix Call to a member function getName() on null (Because not use ILIAS primary key)
}
?>
<#2>
<?php
\srag\Plugins\SrAutoMails\Rule\Rule::updateDB();
?>
<#3>
<?php
\srag\Plugins\SrAutoMails\Rule\Rule::updateDB();

foreach (\srag\Plugins\SrAutoMails\Rule\Rule::where([ "interval_type" => 0 ])->get() as $rule) {
	/**
	 * @var \srag\Plugins\SrAutoMails\Rule\Rule $rule
	 */
	$rule->setIntervalType(!empty($rule->getInterval()) ? \srag\Plugins\SrAutoMails\Rule\Rule::INTERVAL_TYPE_NUMBER : \srag\Plugins\SrAutoMails\Rule\Rule::INTERVAL_TYPE_ONCE);
	$rule->store();
}

foreach (\srag\Plugins\SrAutoMails\Rule\Rule::where([ "match_type" => 0 ])->get() as $rule) {
	/**
	 * @var \srag\Plugins\SrAutoMails\Rule\Rule $rule
	 */
	$rule->setMatchType(\srag\Plugins\SrAutoMails\Rule\Rule::MATCH_TYPE_MATCH);
	$rule->store();
}
?>
<#4>
<?php
\srag\Plugins\SrAutoMails\Notification\Notification\Notification::updateDB_();
\srag\Plugins\SrAutoMails\Notification\Notification\Language\NotificationLanguage::updateDB_();

foreach (\srag\Plugins\SrAutoMails\Rule\Rule::get() as $rule) {
	/**
	 * @var \srag\Plugins\SrAutoMails\Rule\Rule $rule
	 */

	\srag\Notifications4Plugin\SrAutoMails\Notification\Repository::getInstance(\srag\Plugins\SrAutoMails\Notification\Notification\Notification::class, \srag\Plugins\SrAutoMails\Notification\Notification\Language\NotificationLanguage::class)
		->migrateFromOldGlobalPlugin($rule->getMailTemplateName());
}
?>
<#5>
<?php
\srag\Plugins\SrAutoMails\Notification\Notification\Notification::updateDB_();
\srag\Plugins\SrAutoMails\Notification\Notification\Language\NotificationLanguage::updateDB_();
?>
<#6>
<?php
if (\srag\DIC\SrAutoMails\DICStatic::dic()->database()->tableColumnExists(\srag\Plugins\SrAutoMails\Sent\Sent::TABLE_NAME, "id")) {
	\srag\DIC\SrAutoMails\DICStatic::dic()->database()->dropTableColumn(\srag\Plugins\SrAutoMails\Sent\Sent::TABLE_NAME, "id");
}
?>
