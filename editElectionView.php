<html>
<head>
	<meta charset="utf-8">
	<title>Votación electrónica</title>
</head>
<body>
	<h1>Votación electrónica</h1>
	<h3>Editar elección</h3>
	<?php 
		if (isset($saved) && $saved === true) {
			echo '<p>Votación editada correctamente</p>';
		}
	?>
	<form action="editElection.php" method="post">
		<label for="start">Hora de comienzo:</label>
		<input type="text" name="start" maxlength="5" value="<?php
			if($current['start'] !== null) {
				echo substr($current['start'], 11, 5);
			}
		?>">
		<br><br>
		<label for="end">Hora de finalización:</label>
		<input type="text" name="end" maxlength="5" value="<?php
			if($current['end'] !== null) {
				echo substr($current['end'], 11, 5);
			}
		?>">
		<br><br>
		<?php 
			foreach ($strata as $stratum) {
				echo '<label for="stratum' . $stratum['id'] . '">Candidatos '
					. $stratum['name'] . '</label> ';
				echo '<input type="number" name="stratum' . $stratum['id'] . '" maxlength="2" value="';
				echo isset($current['chosen'][$stratum['id']]) ? $current['chosen'][$stratum['id']] : 0;
				echo '"><br><br>';
			}
		?>
		<input type="submit">
	</form>
</body>
</html>