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
if ($elections !== null) {
	$election = $elections[0];
	$voters = $dbHandler->getVoters($election['id']);
	
	$strata = $dbHandler->getStrata();
	$stratumVotes = array();
	$stratumVoters = array();
	foreach ($strata as $stratum) {
		$stratumVotes[$stratum['id']] = 0;
		$stratumVoters[$stratum['id']] = 0;
	}
	
	foreach ($voters as $voter) {
		$stratumVoters[$voter['stratum']]++;
		if ($voter['hasVoted']) {
			$stratumVotes[$voter['stratum']]++;
		}
	}
	
	$totalVotes = 0;
	$totalVoters = sizeof($voters);
	foreach ($strata as $stratum) {
		$totalVotes += $stratumVotes[$stratum['id']];
	}
}

require('participationView.php');