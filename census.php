<?php

require_once('common.php');
require('checkUser.php');

$voter = $dbHandler->getVoter($dni, CURRENT_ELECTION);
if ($currentElection) {
	$strata = $dbHandler->getStrata();
	foreach ($strata as $stratum) {
		if ($stratum['id'] == $voter['stratum']) {
			$stratumName = $stratum['name'];
		}
	}
}

require('censusView.php');