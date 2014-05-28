<?php

require ('common.php');
require ('checkUser.php');

$president=null;

$presidente = $dbHandler->getVoter ( $dni, CURRENT_ELECTION );
\var_dump($dbHandler->getVoter ( $dni, CURRENT_ELECTION ));
if (!$voter['person'] == PRESIDENT) {
	die ( 'No tiene permisos. <a href="census.php?dni=' . $person ['id'] . '">Volver</a>' );
}
$dbHandler->setCurrentElection('09:00', '18:00', [1 => 5, 2 => 4, 3 => 6]);
\var_dump($dbHandler->getCurrentElection());
