<?php
require_once("BibliothequeFonctions.php");
verificationConnexion();
teteDePage("Noodle : profil");
$DejaAbo=-1;
$Qui="";
$erreur="";
$isAdmin=FALSE;
$isPrive=FALSE;

//A qui appartient cette page de profil
if(isset($_POST['self'])){
	// soi meme
	$Qui=$_SESSION['userid'];
} else {
	// un autre compte
	$Qui=$_POST["id"];
}

//Déclaration des requêtes SQL
$requeteVerifAdmin="SELECT * FROM admin WHERE (id=".$Qui.");";
$requeteDesabo="DELETE FROM Abonnements WHERE (Abonne=".$_SESSION['userid'].",".$Qui.");";
$requeteInfos="SELECT * FROM Users WHERE (userid=".$Qui.");";
$requeteAbonnement="INSERT INTO Abonnements VALUES (".$_SESSION['userid'].",".$Qui.");";
$requetePublications = "SELECT nom, description, type, Publications.id, typeA, nomA FROM Publications, Apercus WHERE (auteur= ".$Qui."AND Publications.id=Apercus.id) ORDER BY date DESC;";
$requeteAbonnes= "SELECT count(*) FROM Abonnements WHERE (ABONNEMENT='".$Qui."');";
$requeteNmbAbonnement="SELECT count(*) FROM Abonnements WHERE (ABONNE='".$Qui."');";
$requeteVerif="SELECT * FROM Abonnements WHERE (ABONNE=".$_SESSION['userid']." AND ABONNEMENT=".$Qui.");";
if(isset($_POST['supprimer'])) { $requeteSupprimer="DELETE FROM Publications WHERE (id=".$_POST['supprimer'].");"; }
$requeteIsPrivé="SELECT * FROM prive WHERE (id=".$Qui.");"; //A finir
$connexion = mysqli_connect('localhost','root','','IO_TEST'); 

//$description = mysqli_real_escape_string($connex,$_POST['description']);

if ( !$connexion ) {
	$erreur = $erreur."<br>Erreur : impossible de se connecter au SQL.";
} else {

	//Supprimer une publication
	if(isset($_POST['supprimer'])){
		$resultatSupprimer=mysqli_query($connexion,$requeteSupprimer);
		if(!$resultatSupprimer){ $erreur = $erreur."<br>Erreur : requête invalide : ".mysqli_error($connexion); }
		else { $filename=$cheminPublications.$_POST['supprimer'];
			unlink($filename.$_POST['supprimerType']);unlink ($filename."A".$_POST['supprimerType']);}
	}

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

	//Verification d'état administrateur du profil
	$resultatVerifAdmin=mysqli_query($connexion,$requeteVerifAdmin);
	if(!$resultatVerifAdmin){ $erreur = $erreur."<br>Erreur : requête invalide : ".mysqli_error($connexion); }
	else { $resultatVerifAdmin=mysqli_fetch_row($resultatVerifAdmin);
			if(isset($resultatVerifAdmin)){
				$isAdmin=TRUE;
			}
	}

	//Vérification du "déjà abonné"
	$resultatVerif=mysqli_fetch_row($resultatVerif);
	if(isset($resultatVerif[0])){ $DejaAbo=FALSE; }
	else { $DejaAbo=TRUE; }

	//Vérification dans la table SQL prive
	$resultatIsPrive=mysqli_query($connexion,$requeteIsPrivé);
	if(!$resultatIsPrive){ $erreur = $erreur."<br>Erreur : requête invalide : ".mysqli_error($connexion);}
	else { $resultatIsPrive=mysqli_fetch_row($resultatIsPrive);
			if($resultatIsPrive!=NULL){
				if($resultatIsPrive[0]==$Qui){ $isPrive=TRUE; } 
			}
	}

	//Affichage des infos du profil
	?>
	<h1>Page de profil de <?php echo $resultatInfos['pseudo']; if($isAdmin){ echo " (Administrateur)"; } ?></h1><br>
	<p>Date de naissance : <?php echo $resultatInfos['birthdate'];?></p>
	<p>Adresse mail de contact : <?php if(($isPrive && $DejaAbo) || !$isPrive)echo $resultatInfos['mail'];?></p>
	<?php

	//Affichage des publications si l'utilisateur est abonné ou si c'est lui même
	
	//Conditions de visualisation
	if($isPrive) {
		if($DejaAbo) {
			$ligneDePubli=mysqli_fetch_assoc($resultat);
			while ($ligneDePubli) {
				afficherPublication($ligneDePubli);
				if($_SESSION['admin']==1 || isset($_POST['self'])){
            ?><form action='<?php echo $pageActuelle; ?>' method='POST'>
                <?php if(isset($Qui)) { ?> <input type='hidden' name='id' value=<?php echo $Qui; } else { ?>>
                <input type='hidden' name='id' value=<?php echo $ligneDePubli['auteur']; } ?> >
                <input type='hidden' name='supprimer' value='<?php echo $ligneDePubli['id']; ?>'>
                <input type='hidden' name='supprimerType' value='<?php echo $ligneDePubli['type']; ?>' >
                <input type='submit' value='Supprimer la publication'>
            </form>
            <?php
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
				afficherPublication($ligneDePubli);
				if($_SESSION['admin']==1 || isset($_POST['self'])){
            ?><form action='<?php echo $pageActuelle; ?>' method='POST'>
                <?php if(isset($Qui)) { ?> <input type='hidden' name='id' value=<?php echo $Qui; } else { ?>>
                <input type='hidden' name='id' value=<?php echo $ligneDePubli['auteur']; } ?> >
                <input type='hidden' name='supprimer' value='<?php echo $ligneDePubli['id']; ?>'>
                <input type='hidden' name='supprimerType' value='<?php echo $ligneDePubli['type']; ?>' >
                <input type='submit' value='Supprimer la publication'>
            </form>
            <?php
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

