<?php
session_start();
$erreur="";
# Verif. de Session
# if($_COOKIE['mdp']== NULL){  exit ("<a href='Frontpage.php'> Vous n'etes pas connecté</a>"); }
require_once("BibliothequeFonctions.php");
verificationConnexion();

# S e s s i o n s

teteDePage("Noodle : fil d'actualités");
?>

<h2>Bonjour 
    <?php 
    if(!empty($_SESSION['pseudo'])) {
        echo $_SESSION['pseudo'];
    } else {
        if(!empty($_SESSION['pseudo'])) {
            echo $_SESSION['pseudo'];
        }
    }
    
    ?> !
</h2>

<p>
<form action='Recherche.php' method='POST'>
    <input type='search' name='pseudo' size='75' placeholder='Rechercher un compte'>
    <input type='submit' value='Rechercher' size='20'>
</form>
</p>

<p>
<form action='Publier.php' method='get'>
    <input type='submit' name='Publier' size='20' value='Page de publication'>
</form>
</p>

<p>
<form action='Frontpage.php' method='get'>
    <input type='submit' name='deconnexion' size='20' value='Se déconnecter'>
</form>
</p>

<p>
<form action='Profil.php' method='POST'>
    <input type='hidden' name='self' value='TRUE'>
    <input type='hidden' name='pseudo' value='<?php echo $_SESSION['pseudo']; ?>'>
    <input type='submit' name='profil' size='20' value='Voir votre profil'>
</form>
</p>



<?php

 //Supprimer une publication
    


//Affichage des publications des abonnés :
$connexion = mysqli_connect('localhost','root','','IO_TEST');
if ( !$connexion ) {
	$erreur = $erreur."<br>Erreur : impossible de se connecter au SQL.";
} else {

    if(isset($_POST['supprimer'])){
        $requeteSupprimer="DELETE FROM Publications WHERE (id=".$_POST['supprimer'].");";
        $resultatSupprimer=mysqli_query($connexion,$requeteSupprimer);
        if(!$resultatSupprimer){ $erreur = $erreur."<br>Erreur : requête invalide : ".mysqli_error($connexion); }
        else { $filename=$cheminPublications.$_POST['supprimer'];
            unlink($filename.$_POST['supprimerType']);unlink ($filename."A".$_POST['supprimerType']);}
    }

    //D'abord on cherche à qui est abonné l'utilisateur
    $requeteRechercheAbonnements="SELECT Abonnement FROM ABONNEMENTS WHERE (ABONNE=".$_SESSION['userid'].");";
    $resultatRechercheAbonnements=mysqli_query($connexion, $requeteRechercheAbonnements);
    if ( !$resultatRechercheAbonnements ) {
         $erreur = $erreur."<br>Erreur : requête invalide : ".mysqli_error($connexion);
     } else {
        $ligneResultatRechercheAbonnements=mysqli_fetch_assoc($resultatRechercheAbonnements);
     
        //Ensuite on parcourt chaque abonnement de l'utilisateur
        while($ligneResultatRechercheAbonnements){
            //Puis on affiche les publications de l'abonnement
            $requeteRecherchePubli="SELECT nom, description, type, id, auteur FROM Publications WHERE auteur=".$ligneResultatRechercheAbonnements['Abonnement']." ORDER BY date DESC;";
            $resultatRecherchePubli=mysqli_query($connexion,$requeteRecherchePubli);
            if ( !$resultatRecherchePubli ) {
                $erreur = $erreur."<br>Erreur : requête invalide : ".mysqli_error($connexion);
            } else {
                $ligneResultatRecherchePubli=mysqli_fetch_assoc($resultatRecherchePubli);
                afficherPublications($ligneResultatRecherchePubli,$resultatRecherchePubli,"FilActualite.php");
            }
            //Passage à l'abonnement suivant
            $ligneResultatRechercheAbonnements=mysqli_fetch_assoc($resultatRechercheAbonnements);
        }
    }

   
}
echo $erreur;


    


piedDePage();
?> 