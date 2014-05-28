<html>
<head>
	<meta charset="utf-8">
	<title>Votación electrónica</title>
</head>
<body>
	<h1>Votación electrónica</h1>
	<h3>Votar</h3>
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
</body>
</html>