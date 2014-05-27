<html>
<head>
	<meta charset="utf-8">
	<title>Votación electrónica</title>
</head>
<body>
	<h1>Votación electrónica</h1>
	<h3>Sus datos censales</h3>
	<p><b>DNI: </b> <?php echo $dni ?></p>
	<p><b>Nombre: </b> <?php echo $person['surname'] . ', ' . $person['name'] ?></p>
	<?php 
		if($voter != null) {
			echo '<p><b>Rol:</b> ';
			if ($voter['role'] == 'voter') echo 'votante';
			elseif ($voter['role'] == 'inspector') echo 'interventor';
			elseif ($voter['role'] == 'commitee') echo 'miembro de Junta Electoral';
			echo '</p>';
			echo '<p><b>Estamento:</b> ' . $stratumName . '</p>';
			if ($voter['hasVoted'] == true) {
				echo '<p>Usted ya ha votado.</p>';
			} else {
				echo '<p><a href="vote.php?dni=' . $voter['person'] . '">Votar</a></p>';
			}
		}
	?>
	<p><a href="participation.php?dni=<?php echo $dni ?>">Ver participación</a></p>
	<p><a href="index.php">Cerrar sesión</a></p>
</body>
</html>