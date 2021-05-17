<?php
require_once("BibliothequeFonctions.php");
teteDePage("Requete SQL");

// tente de se connecter à la BDD, et renvoie une erreur sinon
function sqlConnexion() {
	// renvoie un objet de connexion, ou false si echec
	// prend en argument : serveur, utilisateur, mdp, nom de la BDD
	$connex = mysqli_connect('localhost', 'root', '', 'IO_TEST');
	if (!$connex) {
		echo "<br>Erreur : impossible de se connecter à la BDD (".$mysqli_connect_error().").";
	}
	return $connex;
}

// renvoie un String correspondant à cette requete :
// SELECT $attributs FROM $table WHERE $clef1 LIKE "%$val1%" AND $clef2=$val2 AND...
// fonctionne uniquement avec une tableau contenant String et int
// le tableau $conditions peut contenir un tableau indicé, ce qui donnera :
// ... AND ($clef=$val1 OR $clef=$val2)
function creationRequete($attributs, $table, $conditions) {
	$sqlRequete = "SELECT";
	foreach ($attributs as $att) {
		$sqlRequete = $sqlRequete." $att,"; // SELECT "att1", "att2",
	}
	// on retire la virgule en trop
	$sqlRequete = substr($sqlRequete, 0, strlen($sqlRequete)-1);
	$sqlRequete = $sqlRequete." FROM ".$table.", Apercus WHERE Apercus.id=Publications.id AND";

	// on ajoute les conditions séparées par AND
	foreach ($conditions as $clef => $val) {
		//$clef = $cl;

		// si c'est un string : clef LIKE "%val%" AND
		if (is_string($val)) {
			$sqlRequete = $sqlRequete." ".$clef." LIKE \"%".$val."%\" AND";

		// si c'est un tableau : ( clef=valInt1 OR clef LIKE "%valInt2%" ) AND
		} else if (is_array($val)) {
			$sqlRequete = $sqlRequete." (";
			foreach ($val as $valInterne) {
				//echo $valInterne;
				if (is_string($valInterne)) {
					$sqlRequete = $sqlRequete." ".$clef." LIKE \"%".$valInterne."%\" OR";
				} else {
					$sqlRequete = $sqlRequete." ".$clef."=".$valInterne." OR";
				}
			}
			$sqlRequete = substr($sqlRequete, 0, strlen($sqlRequete)-2);
			$sqlRequete = $sqlRequete.") AND";

		// sinon c'est un entier : clef="val" AND
		} else {
			$sqlRequete = $sqlRequete." ".$clef."=".$val." AND";
		}
	}
	// on retire le AND de trop et on met le point-virgule final
	$sqlRequete = substr($sqlRequete, 0, strlen($sqlRequete)-4);
	$sqlRequete = $sqlRequete.";";
	return $sqlRequete;
}





/*
function afficherApercuCompte($donneesCompte) { 
	$nbrAbonnés = sqli_query();

	?>
	<div>
		<p><?php echo $donneesCompte["pseudo"];?></p>
		<p>Abonnés <?php echo $donneesCompte[];?></p>
	</div>
	<?php
}
*/

/*
$userid = array(1, 3);
$conditions = array("pseudo" => "delta", "userid" => $userid);
$attributs = array("userid", "pseudo");
$table = "Users";
echo "<br><u>inputs :</u><pre>";
print_r($attributs);
echo $table.", <br>";
print_r($conditions);
echo "</pre>";
echo "<br><u>output :</u> ".creationRequete($attributs, $table, $conditions);

*/
 
piedDePage();
?>