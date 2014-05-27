<?php

if (!isset($dni) && !isset($_GET['dni'])) {
	die();
}

if (!isset($dni)) {
	$dni = filter_var($_GET['dni'], FILTER_SANITIZE_SPECIAL_CHARS);
}
$person = $dbHandler->getPerson($dni);
if ($person == null) {
	die();
}