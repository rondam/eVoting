<?php
if (! isset ( $dni ) && ! isset ( $_GET ['dni'] )) {
	die ();
} elseif (! isset ( $dni )) {
	require ("DatabaseHandler.php");
	$dbHandler = new DatabaseHandler ();
	$dni = filter_var ( $_GET ['dni'], FILTER_SANITIZE_SPECIAL_CHARS );
	$person = $dbHandler->getPerson ( $dni );
	if ($person == null) {
		die ();
	}
}

$elections = $dbHandler->getNextElections ();
$voter = null;
if ($elections !== null) {
	$electionId = $elections [0] ['id'];
	$voter = $dbHandler->getVoter ( $dni, $electionId );
	if ($voter ['hasVoted'] == true) {
		die ( 'Usted ya ha votado. <a href="census.php?dni=' . $person ['id'] . '">Volver</a>' );
	}
	
	$strata = $dbHandler->getStrata ();
	foreach ( $strata as $stratum ) {
		if ($stratum ['id'] == $voter ['stratum']) {
			$stratumId = $stratum ['id'];
		}
	}
	
	$candidates = $dbHandler->getCandidates ( $electionId, $stratumId );
	
	if (! isset ( $_POST ['voting'] )) { // Show vote form
		$candidateList = array ();
		foreach ( $candidates as $candidate ) {
			$candidateList [] = $dbHandler->getPerson ( $candidate ['person'] );
		}
		require ('voteView.php');
		
	} else { // Vote!
		$dbHandler->startTransaction ();
		$voter = $dbHandler->getVoter ( $dni, $electionId );
		// Verify INSIDE the transaction that the user hasn't voted.
		if ($voter ['hasVoted'] == true) {
			$dbHandler->rollback();
			die ( 'Usted ya ha votado. <a href="census.php?dni=' . $person ['id'] . '">Volver</a>' );
		}
		$dbHandler->hasVoted ( $person ['id'], $electionId );
		foreach ( $candidates as $candidate ) {
			if (isset ( $_POST [$candidate ['person']] )) {
				$dbHandler->newVote ( $candidate ['person'], $electionId );
			}
		}
		$dbHandler->commit();
		require ('voteFinishView.php');
	}
	
} else {
	die ();
}