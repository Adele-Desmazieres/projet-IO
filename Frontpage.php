<?php
session_destroy();
require_once("BibliothequeFonctions.php");
teteDePage("Noodle : accueil");
?>

<h1>Bienvenue sur Noodle !</h1>

<div><a href="Inscription.php">S'inscrire</a></div>
<div><a href="Connexion.php">Se connecter</a></div>

<div>
	<p>Noodle est un site de partage de cours libres de droits.</p>
	<p>Si tu veux trouver les cours postés par tes camarades, ou partager les tiens avec d'autres élèves, tu es au bon endroit. Clique sur un des liens ci-dessus, pour te connecter ou t'inscrire sur le site.</p>
</div>


<?php
piedDePage();
?>