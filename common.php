<?php

define('CURRENT_ELECTION', 1);
define('PRESIDENT', '44738593M');

require('DatabaseHandler.php');

$dbHandler = new DatabaseHandler();
function checkElection($dbHandler) {
	$current = $dbHandler->getCurrentElection();
	$election = $dbHandler->getElection(CURRENT_ELECTION);
	if ($election == null) {
		return false;
	}
	$date = $election['date'];
	$tz = new DateTimeZone('Atlantic/Canary');
	$start = new DateTime($current['start'], $tz);
	$end = new DateTime($current['end'], $tz);
	$now = new DateTime('now', $tz);
	if ($now < $start) {
		return 'before';
	} elseif ($now < $end) {
		return 'current';
	} else {
		return 'after';
	}
}

$val = checkElection($dbHandler);
$currentElection = ($val === 'current');
$afterElection = ($val === 'after');