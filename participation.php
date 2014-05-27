<?php

require('common.php');
require('checkUser.php');

if($currentElection || $afterElection) {
	$election = $dbHandler->getElection(CURRENT_ELECTION);
	if ($election != null) {
		$voters = $dbHandler->getVoters(CURRENT_ELECTION);
	
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
}

require('participationView.php');