<?php

require ('common.php');
require ('checkUser.php');

if ($dni !== PRESIDENT) {
	die();
}
$strata = $dbHandler->getStrata();

if (isset($_POST['start']))
{
	$start = filter_var($_POST['start'], FILTER_SANITIZE_SPECIAL_CHARS);
	$end = filter_var($_POST['end'], FILTER_SANITIZE_SPECIAL_CHARS);
	$chosen = array();
	foreach ($strata as $stratum) {
		$chosen[$stratum['id']] =
		filter_var($_POST['stratum' . $stratum['id']],
				FILTER_SANITIZE_SPECIAL_CHARS);
	}
	$dbHandler->setCurrentElection($start, $end, $chosen);
	$saved = true;
}

$current = $dbHandler->getCurrentElection();
require('editElectionView.php');