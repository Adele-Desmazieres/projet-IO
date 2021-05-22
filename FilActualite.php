<?php
if (session_status() == 0) {
    session_start();
}

$erreur="";
# Verif. de Session
# if($_COOKIE['mdp']== NULL){  exit ("<a href='Frontpage.php'> Vous n'etes pas connecté</a>"); }
require_once("BibliothequeFonctions.php");
verificationConnexion();

# S e s s i o n s

teteDePage("Noodle : fil d'actualités");
?>

<h1>Fil d'actualités</h1>

<main>
<h2>Bonjour 
    <?php 
    if(!empty($_SESSION['pseudo'])) {
        echo $_SESSION['pseudo'];
    } 
    
    ?> !
</h2>


<?php

//Affichage des publications des abonnés :
$connexion = mysqli_connect('localhost','root',$mdpBDD,$nomBDD);
if ( !$connexion ) {
	$erreur = $erreur."<br>Erreur : impossible de se connecter au SQL.";
} else {

    //Traitement de requête de suppression
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
            $requeteRecherchePubli="SELECT * FROM Publications WHERE userid=".$ligneResultatRechercheAbonnements['Abonnement']." ORDER BY date DESC;";
            $resultatRecherchePubli=mysqli_query($connexion,$requeteRecherchePubli);
            if ( !$resultatRecherchePubli ) {
                $erreur = $erreur."<br>Erreur : requête invalide : ".mysqli_error($connexion);
            } else {
                $ligneResultatRecherchePubli=mysqli_fetch_assoc($resultatRecherchePubli);
                if(isset($ligneResultatRecherchePubli)){
                    while ($ligneResultatRecherchePubli){
                        //Si on est administrateur, on affiche le bouton supprimer
                        afficherPublication($connexion,$ligneResultatRecherchePubli);
                        if($_SESSION['admin']==1){
                            afficheSupprimer($ligneResultatRecherchePubli,"FilActualite.php");
                        }
                        $ligneResultatRecherchePubli=mysqli_fetch_assoc($resultatRecherchePubli);
                    }
                } 
            }
            //Passage à l'abonnement suivant
            $ligneResultatRechercheAbonnements=mysqli_fetch_assoc($resultatRechercheAbonnements);
        }
    }

   
}
echo $erreur;


piedDePage();
?> 