<?php 
require_once("BibliothequeFonctions.php");
teteDePage("Noddle : recherche");


?>

<h1>Rechercher</h1>

<aside>
<h2>Rechercher un compte</h2>
<div>
<form action="Recherche.php" method="POST">
	<p>Pseudo <input type="search" name="pseudo" placeholder="Pseudo" value=
		<?php 
		if (isset($_POST["pseudo"])) {
			echo "\"".$_POST["pseudo"]."\"";
		}
		?>>
	</p>
	<p>Mail <input type="search" name="mail" placeholder="Mail" value=
		<?php 
		if (isset($_POST["mail"])) {
			echo "\"".$_POST["mail"]."\"";
		}
		?>>
	</p>
	<input type="hidden" name="table" value="Users">
	<p><input type="submit" value="Rechercher"></p>
</form>
</div>

<h2>Rechercher une publication</h2>
<div>
<form action="Recherche.php" method="POST">
	<p>Description <input type="search" name="description" placeholder="Description de la publication" value=<?php // cette injection de php permet de préremplir la recherche actuelle avec la dernière
		if (isset($_POST["description"])) {
			echo "\"".$_POST["description"]."\"";
		}
	?>>
	</p>
	<p>Auteur <input type="search" name="auteur" placeholder="Pseudo de l'auteur" value=<?php 
		if (isset($_POST["auteur"])) {
			echo "\"".$_POST["auteur"]."\"";
		}
	?>>
	</p>
	<p>Type de fichier
		<ul>
		<?php
		$extensions = array('.odt', '.pdf', '.jpg', '.jpeg', '.png', '.txt');
		foreach ($extensions as $ext) { ?>
			<li><input 
					type="checkbox" 
					name="type[]" 
					value=<?php echo "\"".$ext."\""; // une valeur dans le tableau des extensions
					// qu'on pré-coche si précédemment cochée
					if (isset($_POST["type"])) {
						foreach ($_POST["type"] as $precedenteCochee) {
							if ($precedenteCochee == $ext) {
								echo " checked";
							}
						}
					// sauf si on l'initialise avec toutes de cochées
					} else {
						echo " checked";
					}
					?> > 
				<?php echo substr($ext, 1);?> </li>
		<?php
		} ?>
		</ul>
	</p>
	<input type="hidden" name="table" value="Publications">
	<p><input type="submit" value="Rechercher"></p>
</form>
</div>
</aside>

<h2>Résultats de la recherche</h2>

<pre>
<?php print_r($_POST); ?>
</pre>

<?php

require_once("RequeteSQL.php");


// on prétraite le tableau POST pour obtenir
// $attributs : tableau indicé des attributs sélectionnés
// $table : le string de la table dans laquelle on fera la requete
// $conditions : tableau associatif des conditions nécessaires à la requête 
if (isset($_POST["table"])) {
	$table = $_POST["table"];
	if ($_POST["table"] == "Users") {
		$attributs = array("pseudo");
	} else {
		$attributs = array("*"); 
	}

	$conditions = array();
	foreach ($_POST as $clef => $val) {
		if ($clef != "table" AND $val != NULL) {
			$conditions[$clef] = $val;
		}
	}
}

// crée et affiche la requete sql
$requete = creationRequete($attributs, $table, $conditions);
echo $requete;

// crée la connexion sql
$connex = sqlConnexion();
if ($connex) {
	$resultat = mysqli_query($connex, $requete);
	$ligneResultat = mysqli_fetch_assoc($resultat);

	// parcourt et affiche toutes les publications
	if ($table == "Publications") {
		echo "<br>TRUE";
		while($ligneResultat) {
			afficherPublication($ligneResultat);
			$ligneResultat = mysqli_fetch_assoc($resultat);
		}
	// parcourt et affiche tous les comptes
	} else {
		while($ligneResultat) {
			afficherApercuCompte($ligneResultat);
			$ligneResultat = mysqli_fetch_assoc($resultat);
		}
	}
}


?>




<?php
piedDePage();
?>