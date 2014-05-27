<html>
<head>
	<meta charset="utf-8">
	<title>Votación electrónica</title>
</head>
<body>
	<h1>Votación electrónica</h1>
	<h3>Participación</h3>
	<?php 
		if ($election == null || (!$currentElection && !$afterElection)) {
			echo '<p>No hay elecciones activas.</p>';
		} else {
			foreach ($strata as $stratum) {
				echo '<p><b>' . $stratum['name'] . ':</b> ';
				echo $stratumVotes[$stratum['id']] == 0 ?
					0 : round(100 * $stratumVotes[$stratum['id']] / $stratumVoters[$stratum['id']], 2);
				echo ' % de ' . $stratumVoters[$stratum['id']];
				echo '</p>';
			}
		}
		echo '<p><b>Total:</b> ';
		echo $totalVotes == 0 ? 0 : round(100 * $totalVotes / $totalVoters, 2);
		echo ' % de ' . $totalVoters . '</p>';
			
	?>
	<a href="census.php?dni=<?php echo $dni ?>">Volver</a>
</body>
</html>