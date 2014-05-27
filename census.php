<?php

if (!isset($dni) && !isset($_GET['dni'])) {
	die();
} elseif (!isset($dni)) {
	require("DatabaseHandler.php");
	$dbHandler = new DatabaseHandler();
	$dni = filter_var($_GET['dni'], FILTER_SANITIZE_SPECIAL_CHARS);
	$person = $dbHandler->getPerson($dni);
	if ($person == null) {
		die();
	}
}

$elections = $dbHandler->getNextElections();
$voter = null;
if ($elections !== null) {
	$election = $elections[0];
	$voter = $dbHandler->getVoter($dni, $election['id']);
	$strata = $dbHandler->getStrata();
	foreach ($strata as $stratum) {
		if ($stratum['id'] == $voter['stratum']) {
			$stratumName = $stratum['name'];
		}
	}
}

require('censusView.php');