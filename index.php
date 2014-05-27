<?php

if (!isset($_POST['dni'])) {
	require('indexView.php');
} else {
	require("DatabaseHandler.php");
	$dbHandler = new DatabaseHandler();
	
	$dni = filter_var($_POST['dni'], FILTER_SANITIZE_SPECIAL_CHARS);
	$person = $dbHandler->getPerson($dni);
	if ($person == null) {
		die('DNI no encontrado. <a href="index.php">Volver</a>');
	} else {
		require('census.php');
	}
}