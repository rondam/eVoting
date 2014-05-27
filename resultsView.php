<html>
<head>
	<meta charset="utf-8">
	<title>Votación electrónica</title>
</head>
<body>
	<h1>Votación electrónica</h1>
	<h3>Resultados</h3>
	<?php 
		if ($election == null || (!$afterElection)) {
			echo '<p>No hay elecciones activas.</p>';
		} else {
			foreach ($strata as $stratum) {
				echo '<p><b>' . $stratum['name'] . ':</b> ';
				foreach ($votes[$stratum['id']] as $person => $vote) {
					echo '<p>' . $names[$stratum['id']][$person] .
						": " . $vote . '</p>';
				}
			}
		}			
	?>
	<p><a href="census.php?dni=<?php echo $dni ?>">Volver</a></p>
</body>
</html>