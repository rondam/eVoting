<html>
<head>
	<meta charset="utf-8">
	<title>Votación electrónica</title>
</head>
<body>
	<h1>Votación electrónica</h1>
	<h3>Votar</h3>
	<p><?php 
		if (isset($error) && $error === true) {
			echo 'Su voto no es válido. ';
		}
		if (isset($current['chosen'][$voter['stratum']]) &&
				$current['chosen'][$voter['stratum']] > 0) {
			echo 'Puede votar por hasta ' . $current['chosen'][$voter['stratum']] .
				' candidatos. ';
		} else {
			echo 'Puede votar por cualquier número de candidatos. ';
		}
		if ($current['blankBallots']) {
			echo 'Puede votar en blanco.';
		} else {
			echo 'No puede votar en blanco.';
		}
	?></p>
	<form action="vote.php" method="post">
	<input type="hidden" name="voting" value="voting">
	<?php 
		foreach ($candidateList as $candidate) {
			echo '<input type="checkbox" name="' . $candidate['id'] . '">';
			echo '<label for="' . $candidate['id'] . '">' .
				$candidate['name'] . ' ' . $candidate['surname'] . '</label>';
			echo '<br>';
		}
	?>
	<br>
	<input type="submit">
	</form>
	<a href="census.php">Volver y votar más tarde</a>
</body>
</html>