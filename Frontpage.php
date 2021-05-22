<?php
if (session_status() == 2) {
	session_destroy();
}

require_once("BibliothequeFonctions.php");
teteDePage("Noodle : accueil");
?>

<h1>Bienvenue sur Noodle !</h1>

<main>
<div id='Liens'>

<a class='button' href="Inscription.php">S'inscrire</a>
<a class='button' href="Connexion.php">Se connecter</a>

</div>

<div class='cadre'>
<div id='presentation'>
	<p>Noodle est un site de partage de cours libres de droits.</p>
	<p>Si tu veux trouver les cours postés par tes camarades, ou partager les tiens avec d'autres élèves, tu es au bon endroit. Clique sur un des liens ci-dessus, pour te connecter ou t'inscrire sur le site.</p>
</div>
</div>
</main>

<?php
piedDePage();
?>