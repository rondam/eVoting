<?php

require ('common.php');
require ('checkUser.php');

$president = $dbHandler->getVoter ( $dni, CURRENT_ELECTION );
\var_dump($dbHandler->getVoter ( $dni, CURRENT_ELECTION ));
echo PRESIDENT;
if ($dni !== PRESIDENT) {
	die ( 'No tiene permisos.' );
}
$dbHandler->setCurrentElection('09:00', '18:00', [1 => 5, 2 => 4, 3 => 6]);
\var_dump($dbHandler->getCurrentElection());
