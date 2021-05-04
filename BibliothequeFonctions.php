<?php

    
// gère le header de toutes les pages du site
function teteDePage() { 
    ?>
    <!DOCTYPE html>
    <html lang="fr">
    <head>
        <meta charset="utf-8">
        <title>Noodle</title>
        <link rel="stylesheet" href="style0.css">
    </head>
    <?php
}

function piedDePage() { 
    ?>
    </html> 
    <?php
}

// prétraite un String de manière à empêcher l'injection de code malveillante
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


function afficherPublications($arr) {
    foreach($arr as $var) {
        echo $var."<br>";
    }
}

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

// fait une requête ??? Inutile
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

    function SQLinsert($request,$messageSucces){
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
        mysqli_close($SQLconnection);
    }

  
?>


