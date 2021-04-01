<?php
    require_once("BibliothequeFonctions.php");
    ?>
<!DOCTYPE html>
<html>
    <head>
        <meta charset='UTF-8'>
        <title>Fil d'actualité</title>
    </head>
    <body>
        <h2>Bonjour <?php 
        if(!empty($_REQUEST['pseudo'])) {
            echo $_REQUEST['pseudo'];
        } else {
            if(!empty($_COOKIE['pseudo'])) {
                echo $_COOKIE['pseudo'];
            }
        }
         ?> !</h2>
        <form action='Recherche.php' method='get'>
            <input type='search' name='recherche' size='75' placeholder='Vous cherchez quelque chose?'>
            <input type='submit' value='Rechercher' size='20'>
        </form>
        <br>
        <form action='Publier.html' method='get'>
            <input type='submit' name='publier' size='20' value='Publier'>
        </form>
        <br>
        <?php
            afficherPublications();
            ?>
    </body>
</html>    