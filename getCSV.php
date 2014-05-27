
<?php
require_once('DatabaseHandler.php');

$dbHandler = new DatabaseHandler();

//Leer y guardar los datos de elección en la BD
   $row = 1;
   if (($handle = fopen("eleccion.csv", "r")) !== FALSE) {
      while (($data = fgetcsv($handle, 100, ",")) !== FALSE) {
         $num = count($data);
        
         if ($row > 1){
            //INsertamos eleccion
            $dbHandler->newElection($data[0]);  
         }
      $row++;
      }
   fclose($handle);
   }


//Leer y guardar los datos de estamento en la BD
   $row = 1;
   if (($handle = fopen("estamento.csv", "r")) !== FALSE) {
      while (($data = fgetcsv($handle, 100, ",")) !== FALSE) {
         $num = count($data);
        
         if ($row > 1){
            //Insertamos estamento
            $dbHandler->newStratum($data[0]);  
         }
      $row++;
      }
   fclose($handle);
   }


//Creamos un candidato y un votante 
   
   $idelection = array ();
   $strata = array();
	  
   $idelection = $dbHandler->getElections();
   
   $dbStrata = $dbHandler->getStrata();
   foreach ($dbStrata as $stratum) {
      $strata[$stratum['name']] = $stratum['id'];
   }
   

   $row = 1;
   if (($handle = fopen("persona.csv", "r")) !== FALSE) {
      while (($data = fgetcsv($handle, 100, ",")) !== FALSE) {
         $num = count($data);
         
         if ($row > 1){     

            //Insertamos persona (DNI, nombre, apellidos)
            $dbHandler->newPerson($data[0],$data[1],$data[2]);  
 
            //INsertamos Candidato (DNI, id de la eleccion, id del estamento)
            $dbHandler->newCandidate($data[0],$idelection[0]['id'],$strata[$data[4]]);


	    //INsertamos Votante (DNI, id de la eleccion, id del estamento, rol)
            $dbHandler->newVoter($data[0],$idelection[0]['id'],$strata[$data[4]],$data[3]);

         }
        
       $row++;
      
      }
   fclose($handle);
   }


?>
