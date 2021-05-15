<?php

// variables globales
$cheminPublications = "../Publications/";

//gère l'affichage des liens pour télécharger les publications et l'affichage de leur aperçu
//Entrées : Resultat de requête (déjà fetch), Résultat de requête (brut)
function afficherPublications($TabDePubli, $resultatFonc){
    global $cheminPublications;
    while($TabDePubli) {?>

        <p>Télécharger 
            <a href=<?php echo "\"".$cheminPublications.$TabDePubli['id'].$TabDePubli['type']."\"";?>><?php echo $TabDePubli['nom'];?></a>
          <img src='<?php echo $cheminPublications.$TabDePubli['id']."A".$TabDePubli['type']; ?>' height="64px">
        </p>
        <p><?php echo $TabDePubli['description']; ?></p>
        
        <?php
        $TabDePubli = mysqli_fetch_assoc($resultatFonc);
    }
}

// gère la tete de chaque page affichée sur le site (infos meta et bandeau)
// prend en argument un String qui sera le nom de l'onglet
function teteDePage($nomOnglet) { 
    ?>
    <!DOCTYPE html>
    <html lang="fr">
    <head>
        <meta charset="utf-8">
        <title><?php echo $nomOnglet;?></title>
        <?php
        //<link rel="stylesheet" href="style0.css">
        ?>
    </head>
    <body>
    <?php
}

// gère le bas de chaque page affichée sur le site
function piedDePage() { 
    ?>
    </body>
    </html> 
    <?php
}


// Verifie la connexion pour les comptes
function verificationConnexion(){
    session_start();
    if($_SESSION['userid']== NULL){  exit ("<a href='Frontpage.php'> Vous n'etes pas connecté</a>"); }
}


// prétraite un String de manière à empêcher l'injection de code malveillant
function preTraitement($champ) {
    if (!empty($champ)) {
        $champ=trim($champ);
        $champ=htmlspecialchars($champ);
        return $champ;
    } else {
        return "";
    }
}

function SQLconnectTest($host, $pseudo, $psw) {
    $connect_result = mysqli_connect($host,$pseudo,$psw);
    if (!$connect_result) {
        echo("Impossible de se connecter au serveur de bases de données.\n");
    } else {
        echo("Connexion réussie!\n");
        mysql_close($connect_result); 
    }
}

/*
function afficherPublications($arr) {
    foreach($arr as $var) {
        echo $var."<br>";
    }
} */

// renvoie un tableau contenant les champs qui sont vides après validation d'un formulaire
function probleme($arr) {
    $ret = array();
    foreach ($arr as $clef => $val) {
        if (empty($val)) {
            $ret[] = $clef;
        }
    }
    return $ret;
}

// fonction qui fait une requête ? Fonction inutilisée. 
// faire plutot une fonction qui renvoie si la connexion réussie ou échoue
// et une 2e fonction qui renvoie le résultat de la requête
function SQLinsert($request, $messageSucces){
    $SQLconnection=mysqli_connect('localhost','root','','IO_TEST');
    if(!$SQLconnection){
        echo "<h1>Erreur SQL, merci de bien vouloir réessayer</h1>";
    } else {
        $resultat=mysqli_query($SQLconnection,$request);
        if(!$resultat){
            echo "<h1>Erreur SQL 2, merci de bien vouloir réessayer</h1>";
            echo "<p>".mysqli_error($SQLconnection)."</p>";
        } else {
            echo "<h1>".$messageSucces."</h1>";
        }
    }
    //mysqli_close($SQLconnection);
}

  
?>


