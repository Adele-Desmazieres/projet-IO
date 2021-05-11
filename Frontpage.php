<?php
session_destroy();
require_once("BibliothequeFonctions.php");
teteDePage("Noodle");
?>

<h1>Bienvenue sur Noodle !</h1>
<p>Noodle est un site de partage de cours libres de droits</p>

<form action='Connexion.php' id='conn'>
    <p>
        <input type='submit' value='Connexion' size='5'>
    </p>
</form>
<form action='Inscription.php' id='insc'>
    <p>
        <input type='submit' value='Inscription' size='5'>
    </p>
</form>

<?php
piedDePage();
?>