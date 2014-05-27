<?php
require ('common.php');
require ('checkUser.php');

if ($afterElection) {
	$election = $dbHandler->getElection ( CURRENT_ELECTION );
	$voter = $dbHandler->getVoter ( $person ['id'], CURRENT_ELECTION );
	
	if ($election == null || $voter ['role'] !== 'commitee') {
		die();
	} else {
		$strata = $dbHandler->getStrata ();
		$candidates = array();
		$votes = array();
		$names = array();
		foreach ($strata as $stratum) {
			$candidates =
				$dbHandler->getCandidates(CURRENT_ELECTION, $stratum['id']);
			foreach ($candidates as $candidate) {
				$votes[$stratum['id']][$candidate['person']] =
					$dbHandler->getVotes($candidate['person'], CURRENT_ELECTION);
				$curPerson = $dbHandler->getPerson($candidate['person']);
				$names[$stratum['id']][$candidate['person']] =
					$curPerson['name'] . ' ' . $curPerson['surname'];
			}
			array_multisort($votes[$stratum['id']], SORT_DESC, $names[$stratum['id']]);
		}
		require('resultsView.php');
	}
} else {
	die ();
}