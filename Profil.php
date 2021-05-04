<?php
require_once("BibliothequeFonctions.php");
teteDePage("Noodle : profil");

$id = $_POST["id"];
$requete = "SELECT "
$connexion = mysqli_connect('localhost','root','','IO_TEST'); 
//$description = mysqli_real_escape_string($connex,$_POST['description']);

if ( !connexion ) {
	$erreur = $erreur."<br>Erreur : impossible de se connecter au SQL.";
} else {
	// donne un booléen qui dit si la requête a fonctionnée ou pas
	$resultat = mysqli_query($connexion, $requete);
	if ( !$resultat ) { $erreur = $erreur."<br>Erreur : requête invalide : ".mysqli_error($connexion); }
}

// REFAIRE ICI POUR TRAITER LE RESULTAT ET LE TRANSFROMER EN TABLEAU
$resultat = mysqli_fetch_

?>

<h1>Page de profil de <?php echo $resultat["Pseudo"];?></h1>




<?php
piedDePage();
?>

