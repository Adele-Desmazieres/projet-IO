<?php
require_once("BibliothequeFonctions.php");
verificationConnexion();
teteDePage("Noodle : profil");


$DejaAbo=-1;
$Qui="";
$erreur="";
$isAdmin=FALSE;
$isPrive=FALSE;
$pageActuelle="Profil.php";


//A qui appartient cette page de profil
if(isset($_POST['self'])){
	// soi meme
	$Qui=$_SESSION['userid'];
} else {
	// un autre compte
	$Qui=$_POST["id"];
}


//Déclaration des requêtes SQL
$requeteDesabo="DELETE FROM Abonnements WHERE (Abonne=".$_SESSION['userid']." AND Abonnement=".$Qui.");";
$requeteInfos="SELECT * FROM Users WHERE (userid=".$Qui.");";
$requetePublications="SELECT * FROM Publications WHERE userid=".$Qui.";";
$requeteAbonnement="INSERT INTO Abonnements VALUES (".$_SESSION['userid'].",".$Qui.");";
$requeteAbonnes= "SELECT count(*) FROM Abonnements WHERE (ABONNEMENT='".$Qui."');";
$requeteNmbAbonnement="SELECT count(*) FROM Abonnements WHERE (ABONNE='".$Qui."');";
$requeteVerif="SELECT Abonne FROM Abonnements WHERE (ABONNE=".$_SESSION['userid']." AND ABONNEMENT=".$Qui.");";
if(isset($_POST['supprimer'])) { $requeteSupprimer="DELETE FROM Publications WHERE (id=".$_POST['supprimer'].");"; }
$connexion = mysqli_connect('localhost','root',$mdpBDD,$nomBDD); 

//$description = mysqli_real_escape_string($connex,$_POST['description']);

if ( !$connexion ) {
	$erreur = $erreur."<br>Erreur : impossible de se connecter au SQL.";
} else {

	//Supprimer une publication
	if(isset($_POST['supprimer'])){
		$filename=$cheminPublications.$_POST['supprimer'];
		$isSupprime=unlink($filename.$_POST['supprimerType']);
		$isAperSupprime=unlink ($filename."A".$_POST['supprimerAperType']);
		if($isSupprime && $isAperSupprime) {
			$resultatSupprimer=mysqli_query($connexion,$requeteSupprimer);
			if(!$resultatSupprimer) { echo mysqli_error($connexion); }
		} else {
			echo "<br>Un des fichiers n'a pas pu être supprimé";
		}
	}

	//On vérifie ici si on est déjà abonné au profil dans la BDD
	$resultatVerif = mysqli_query($connexion, $requeteVerif);
	if ( !$resultatVerif ) { $erreur = $erreur."<br>Erreur : requête invalide : ".mysqli_error($connexion); }
	//Vérification du "déjà abonné"
	$resultatVerif=mysqli_fetch_row($resultatVerif);
	if(isset($resultatVerif) && $resultatVerif[0]==$_SESSION["userid"]){ $DejaAbo=TRUE; }
	else { $DejaAbo=FALSE; }

	//Ajout/Retrait d'un abonné
	if(isset($_POST['AbPlus'])){
		if($_POST['AbPlus']==2) {
			if($DejaAbo) {
				//Query de désabonnement
				$resultatDesabo=mysqli_query($connexion,$requeteDesabo);
				if ( !$resultatDesabo ) { $erreur = $erreur."<br>Erreur : requête invalide : ".mysqli_error($connexion); }
			}	
		} else {
			if(!$DejaAbo) {
				//Query d'abonnement
				$resultatAbonnement = mysqli_query($connexion, $requeteAbonnement);
				if ( !$resultatAbonnement ) { $erreur = $erreur."<br>Erreur : requête invalide : ".mysqli_error($connexion); }
			}
		}
	}

	//On REvérifie ici si on est déjà abonné au profil dans la BDD
	$resultatVerif = mysqli_query($connexion, $requeteVerif);
	if ( !$resultatVerif ) { $erreur = $erreur."<br>Erreur : requête invalide : ".mysqli_error($connexion); }
	//Re-Vérification du "déjà abonné"
	$resultatVerif=mysqli_fetch_row($resultatVerif);
	if(isset($resultatVerif) && $resultatVerif[0]==$_SESSION["userid"]){ $DejaAbo=TRUE; }
	else { $DejaAbo=FALSE; }

	

	//Recherche des infos du profil
	$resultatInfos = mysqli_query($connexion, $requeteInfos);
	if ( !$resultatInfos ) { echo $erreur."<br>Erreur : requête invalide : ".mysqli_error($connexion); }
	$resultatInfos=mysqli_fetch_assoc($resultatInfos);
	
	// donne un booléen qui dit si la requête a fonctionnée ou pas
	$resultat = mysqli_query($connexion, $requetePublications);
	if ( !$resultat ) { echo $erreur."<br>Erreur : requête invalide : ".mysqli_error($connexion);
	echo mysqli_error($connexion); }
	


	//Recherche du nombre d'abonnés et d'abonnements
	$resultatAbonnes=mysqli_query($connexion, $requeteAbonnes);
	if ( !$resultatAbonnes ) { $erreur = $erreur."<br>Erreur : requête invalide : ".mysqli_error($connexion); }
	$resultatNmbAbonnement=mysqli_query($connexion, $requeteNmbAbonnement);
	if ( !$resultatNmbAbonnement ) { $erreur = $erreur."<br>Erreur : requête invalide : ".mysqli_error($connexion); }

	//Verification d'état administrateur du profil
	if($resultatInfos['admin']==1) { $isAdmin=TRUE; }

	
	//Vérification dans la table SQL prive
	if($resultatInfos['visibilite']==0) { $isPrive=1; }

	//Affichage des infos du profil
	?>
	
	<h1>Page de profil de <?php echo $resultatInfos['pseudo']; if($isAdmin){ echo " (Administrateur)"; } ?></h1>
	
	<main>
	<p>Date de naissance : <?php echo $resultatInfos['birthdate'];?></p>
	<p>Adresse mail de contact : <?php if(($isPrive && $DejaAbo) || !$isPrive)echo $resultatInfos['mail'];?></p>
	<?php

	//Affichage des publications si l'utilisateur est abonné ou si c'est lui même
	
	//Conditions de visualisation
	if($isPrive) {
		if($DejaAbo) {
			$ligneDePubli=mysqli_fetch_assoc($resultat);
			while ($ligneDePubli) {
				afficherPublication($connexion,$ligneDePubli);
				if($_SESSION['admin']==1 || isset($_POST['self'])){
					afficheSupprimer($ligneDePubli,$pageActuelle);
        		}
 
				$ligneDePubli=mysqli_fetch_assoc($resultat);
			}

			//afficherPublications($ligneDePubli,$resultat,"Profil.php");
		} else {
			echo "Ce compte est privé, abonnez vous pour regarder ses publications";
		}	
	} else {
		$ligneDePubli=mysqli_fetch_assoc($resultat);
			while ($ligneDePubli) {
				afficherPublication($connexion,$ligneDePubli);
				if($_SESSION['admin']==1 || isset($_POST['self'])){
					afficheSupprimer($ligneDePubli,$pageActuelle);
        		}
 
				$ligneDePubli=mysqli_fetch_assoc($resultat);
			}
	}

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
	<input type='hidden' name='id' value=<?php echo $Qui; ?> >
    <input class='button' type='submit' name='profil' size='20' value="S'abonner à ce profil">
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
	<input type='hidden' name='id' value=<?php echo $Qui; ?> >
    <input class='button' type='submit' name='profil' size='20' value="Se désabonner de ce profil">
</form>
</p>



<?php
}

//Bouton pour revenir au fil d'actualité
?>
<div>
	<a href="#top" class="button">Remonter</a>
</div>

</main>


<?php
piedDePage();
?>

