<?php
require_once("BibliothequeFonctions.php");
verificationConnexion();
teteDePage("Noodle : profil");
$DejaAbo=-1;
$Qui="";
$erreur="";

//A qui appartient cette page de profil
if(isset($_POST['self'])){
	$Qui=$_SESSION['userid'];
} else {
	$Qui=$_POST["id"];
}

//Déclaration des requêtes SQL
$requeteDesabo="DELETE FROM Abonnements WHERE (Abonne=".$_SESSION['userid'].",".$Qui.");";
$requeteInfos="SELECT * FROM Users WHERE (userid=".$Qui.");";
$requeteAbonnement="INSERT INTO Abonnements VALUES (".$_SESSION['userid'].",".$Qui.");";
$requetePublications = "SELECT nom, description, type, id FROM Publications WHERE (auteur=".$Qui.");";
$requeteAbonnes= "SELECT count(*) FROM Abonnements WHERE (ABONNEMENT='".$Qui."');";
$requeteNmbAbonnement="SELECT count(*) FROM Abonnements WHERE (ABONNE='".$Qui."');";
$requeteVerif="SELECT * FROM Abonnements WHERE (ABONNE=".$_SESSION['userid']." AND ABONNEMENT=".$Qui.");";
$connexion = mysqli_connect('localhost','root','','IO_TEST'); 
//$description = mysqli_real_escape_string($connex,$_POST['description']);

if ( !$connexion ) {
	$erreur = $erreur."<br>Erreur : impossible de se connecter au SQL.";
} else {

	//Recherche des infos du profil
	$resultatInfos = mysqli_query($connexion, $requeteInfos);
	if ( !$resultatInfos ) { $erreur = $erreur."<br>Erreur : requête invalide : ".mysqli_error($connexion); }
	$resultatInfos=mysqli_fetch_assoc($resultatInfos);
	
	// donne un booléen qui dit si la requête a fonctionnée ou pas
	$resultat = mysqli_query($connexion, $requetePublications);
	if ( !$resultat ) { $erreur = $erreur."<br>Erreur : requête invalide : ".mysqli_error($connexion); }
	
	//On vérifie ici si on est déjà abonné au profil
	$resultatVerif = mysqli_query($connexion, $requeteVerif);
	if ( !$resultatVerif ) { $erreur = $erreur."<br>Erreur : requête invalide : ".mysqli_error($connexion); }

	//Ajout/Retrait d'un abonné
	if(isset($POST_['AbPlus'])){
		if($POST_['AbPlus']==2) {
			//Query de désabonnement
			$resultatDesabo=mysqli_query($connexion,$requeteDesabo);
			if ( !$resultatDesabo ) { $erreur = $erreur."<br>Erreur : requête invalide : ".mysqli_error($connexion); }
		} else {
			//Query d'abonnement
			$resultatAbonnement = mysqli_query($connexion, $requeteAbonnement);
			if ( !$resultatAbonnement ) { $erreur = $erreur."<br>Erreur : requête invalide : ".mysqli_error($connexion); }
		}
	}

	//Recherche du nombre d'abonnés et d'abonnements
	$resultatAbonnes=mysqli_query($connexion, $requeteAbonnes);
	if ( !$resultatAbonnes ) { $erreur = $erreur."<br>Erreur : requête invalide : ".mysqli_error($connexion); }
	$resultatNmbAbonnement=mysqli_query($connexion, $requeteNmbAbonnement);
	if ( !$resultatNmbAbonnement ) { $erreur = $erreur."<br>Erreur : requête invalide : ".mysqli_error($connexion); }

	//Affichage des infos du profil
	?>
	<h1>Page de profil de <?php echo $resultatInfos['pseudo'];?></h1><br>
	<p>Date de naissance : <?php echo $resultatInfos['birthdate'];?></p>
	<p>Adresse mail de contact : <?php echo $resultatInfos['mail'];?></p>
	<?php

	//Affichage des publications
	$ligneDePubli=mysqli_fetch_assoc($resultat);
	afficherPublications($ligneDePubli,$resultat);

	//Vérification du "déjà abonné"
	$resultatVerif=mysqli_fetch_row($resultatVerif);
	if(isset($resultatVerif[0])){ $DejaAbo=FALSE; }
	else { $DejaAbo=TRUE; }

	//Affichage du nombre d'abonnés + d'abonnements
	$resultatAbonnes=mysqli_fetch_row($resultatAbonnes);
	$resultatNmbAbonnement=mysqli_fetch_row($resultatNmbAbonnement);
	echo "<p>Nombre d'abonnés : ".$resultatAbonnes[0].",  Nombre d'abonnements : ".$resultatNmbAbonnement[0]."</p>";
}

//Bouton d'abonnement (apparaît seulement si on est pas abonné OU si on est pas sur notre propre profil)
if($Qui!=$_SESSION['userid'] && !$DejaAbo){
	?>

	<p>
<form action='Profil.php' method='POST'>
    <input type='hidden' name='AbPlus' value=1>
    <input type='submit' name='profil' size='20' value="S'abonner à ce profil">
</form>
</p>

<?php
}

//Bouton de désabonnement (apparaît seulement si on est déjà abonné au profil correspondant et si c'est pas notre profil)
if($Qui!=$_SESSION['userid'] && $DejaAbo){
	?>

	<p>
<form action='Profil.php' method='POST'>
    <input type='hidden' name='AbPlus' value=2>
    <input type='submit' name='profil' size='20' value="Se désabonner de ce profil">
</form>
</p>

<?php
}

piedDePage();
?>

