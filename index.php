<?php

require_once('common.php');

if (!isset($_GET['dni'])) {
	require('indexView.php');
} else {	
	$dni = filter_var($_GET['dni'], FILTER_SANITIZE_SPECIAL_CHARS);
	$person = $dbHandler->getPerson($dni);
	if ($person == null) {
		die('DNI no encontrado. <a href="index.php">Volver</a>');
	} else {
		require('census.php');
	}
}