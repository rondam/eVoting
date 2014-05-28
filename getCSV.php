
<?php
require_once('DatabaseHandler.php');

$dbHandler = new DatabaseHandler();

   $row = 1;
   if (($handle = fopen("csv/eleccion.csv", "r")) !== FALSE) {
      while (($data = fgetcsv($handle, 100, ",")) !== FALSE) {
        
         if ($row > 1){
            //INsertamos eleccion
            $dbHandler->newElection(utf8_encode($data[0]),utf8_encode($data[1]));  
         }
      $row++;
      }
   fclose($handle);
   }

   $row = 1;
   if (($handle = fopen("csv/estamento.csv", "r")) !== FALSE) {
      while (($data = fgetcsv($handle, 100, ",")) !== FALSE) {
        
         if ($row > 1){
            //Insertamos estamento
            $dbHandler->newStratum($data[0]);  
         }
      $row++;
      }
   fclose($handle);
   }
   
   $idelection = array ();
   $strata = array();
	  
   $idelection = $dbHandler->getElections();
   
   $dbStrata = $dbHandler->getStrata();
   foreach ($dbStrata as $stratum) {
      $strata[$stratum['name']] = $stratum['id'];
   }
   

   $row = 1;
   if (($handle = fopen("csv/persona.csv", "r")) !== FALSE) {
      while (($data = fgetcsv($handle, 200, ",")) !== FALSE) {
         if ($row > 1){
            $dbHandler->newPerson(utf8_encode($data[0]), utf8_encode($data[1]), utf8_encode($data[2]), $data[5]);
            $dbHandler->newVoter($data[0],$idelection[0]['id'],$strata[$data[4]],$data[3]);
         }
       $row++;
      }
   fclose($handle);
   }

   $row = 1;
   if (($handle = fopen("csv/candidato.csv", "r")) !== FALSE) {
   	while (($data = fgetcsv($handle, 100, ",")) !== FALSE) {
   		if ($row > 1){
   			$voter = $dbHandler->getVoter($data[0], $idelection[0]['id']);
   			if ($voter != null) {
   				$dbHandler->newCandidate($data[0],$idelection[0]['id'],$voter['stratum']);
   			}
   		}
   		$row++;
   	}
   	fclose($handle);
   }
   
   echo 'Finished loading data.';


?>
