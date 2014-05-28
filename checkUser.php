<?php

if (!isset($_SESSION)) {
	session_start();
}
if (!isset($dni) && !isset($_SESSION['dni'])) {
	die();
}

if (!isset($dni)) {
	$dni = $_SESSION['dni'];
	$person = $dbHandler->getPerson($dni);
	if ($person == null || $_SESSION['password'] !== $person['password']) {
		die();
	}
} else {
	$person = $dbHandler->getPerson($dni);
	if ($person == null) {
		die();
	}
}