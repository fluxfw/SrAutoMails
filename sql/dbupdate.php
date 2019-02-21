<#1>
<?php
\srag\Plugins\SrAutoMails\Config\Config::updateDB();
\srag\Plugins\SrAutoMails\Rule\Rule::updateDB();
\srag\Plugins\SrAutoMails\Sent\Sent::updateDB();
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
