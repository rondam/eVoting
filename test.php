<?php

require_once('DatabaseHandler.php');

$dbHandler = new DatabaseHandler();

\var_dump($dbHandler->getVoter('pepe'));