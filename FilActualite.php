<?php
session_start();
# Verif. de Session
# if($_COOKIE['mdp']== NULL){  exit ("<a href='Frontpage.php'> Vous n'etes pas connecté</a>"); }
require_once("BibliothequeFonctions.php");
verificationConnexion();

#S e s s i o n s

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
<form action='Recherche.php' method='get'>
    <input type='search' name='recherche' size='75' placeholder='Vous cherchez quelque chose?'>
    <input type='submit' value='Rechercher' size='20'>
</form>
</p>

<p>
<form action='Publier.php' method='get'>
    <input type='submit' name='publier' size='20' value='Publier'>
</form>
</p>

<p>
<form action='Frontpage.php' method='get'>
    <input type='submit' name='publier' size='20' value='Se déconnecter'>
</form>
</p>



<?php
afficherPublications();

piedDePage();
?> 