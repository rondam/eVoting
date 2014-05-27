<?php

require ('common.php');
require ('checkUser.php');

$voter = null;
if (! $currentElection) {
	die ();
}

$voter = $dbHandler->getVoter ( $dni, CURRENT_ELECTION );
if ($voter ['hasVoted'] == true) {
	die ( 'Usted ya ha votado. <a href="census.php?dni=' . $person ['id'] . '">Volver</a>' );
}

$strata = $dbHandler->getStrata ();
foreach ( $strata as $stratum ) {
	if ($stratum ['id'] == $voter ['stratum']) {
		$stratumId = $stratum ['id'];
	}
}

$candidates = $dbHandler->getCandidates ( CURRENT_ELECTION, $stratumId );

if (! isset ( $_POST ['voting'] )) { // Show vote form
	$candidateList = array ();
	foreach ( $candidates as $candidate ) {
		$candidateList [] = $dbHandler->getPerson ( $candidate ['person'] );
	}
	require ('voteView.php');
} else { // Vote!
	$dbHandler->startTransaction ();
	$voter = $dbHandler->getVoter ( $dni, CURRENT_ELECTION );
	// Verify INSIDE the transaction that the user hasn't voted.
	if ($voter ['hasVoted'] == true) {
		$dbHandler->rollback ();
		die ( 'Usted ya ha votado. <a href="census.php?dni=' . $person ['id'] . '">Volver</a>' );
	}
	$dbHandler->hasVoted ( $person ['id'], CURRENT_ELECTION );
	foreach ( $candidates as $candidate ) {
		if (isset ( $_POST [$candidate ['person']] )) {
			$dbHandler->newVote ( $candidate ['person'], CURRENT_ELECTION );
		}
	}
	$dbHandler->commit ();
	require ('voteFinishView.php');
}