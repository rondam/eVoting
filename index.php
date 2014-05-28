<?php

require_once('common.php');

if (!isset($_POST['dni'])) {
	session_start();
	session_destroy();
	require('indexView.php');
} else {	
	$dni = filter_var($_POST['dni'], FILTER_SANITIZE_SPECIAL_CHARS);
	$password = $_POST['password'];
	$person = $dbHandler->getPerson($dni);
	if ($person == null || !password_verify($password, $person['password'])) {
		die('DNI y/o contraseña incorrectos. <a href="index.php">Volver</a>');
	} else {
		session_start();
		$_SESSION['dni'] = $dni;
		$_SESSION['password'] = $person['password'];
		require('census.php');
	}
}

if ($afterElection) {
   echo '<p><a href="results.php">Ver resultados</a></p>';
}
