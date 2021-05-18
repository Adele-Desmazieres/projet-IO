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
	<p>Auteur <input type="search" name="pseudo" placeholder="Pseudo de l'auteur" value=<?php 
		if (isset($_POST["pseudo"])) {
			echo "\"".$_POST["pseudo"]."\"";
		}
	?>>
	</p>
	<p>Type de l'article principal
		<ul>
		<?php
		$extensions = array('.odt', '.pdf', '.jpg', '.jpeg', '.png', '.txt');
		foreach ($extensions as $ext) { ?>
			<li><input 
					type="checkbox" 
					name="extensionArticle[]" 
					value=<?php echo "\"".$ext."\""; // une valeur dans le tableau des extensions
					// qu'on pré-coche si précédemment cochée
					if (isset($_POST["extensionArticle"])) {
						foreach ($_POST["extensionArticle"] as $precedenteCochee) {
							if ($precedenteCochee == $ext) {
								echo " checked";
							}
						}
					// sauf si non initialisé : on l'initialise avec toutes de cochées
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
// crée la connexion sql
$connex = sqlConnexion();

// on prétraite le tableau POST pour obtenir
// $attributs : tableau indicé des attributs sélectionnés
// $table : le string de la table dans laquelle on fera la requete
// $conditions : tableau associatif des conditions nécessaires à la requête 
if (isset($_POST["table"])) {
	$table = $_POST["table"];
	if ($_POST["table"] == "Users") {
		$attributs = array("pseudo, mail, visibilite, admin");
	} else {
		$attributs = array("*"); 
	}

	$conditions = array();
	foreach ($_POST as $clef => $val) {
		if ($clef == "pseudo") {
			$useridSQL = mysqli_query($connex, "SELECT userid FROM Users WHERE pseudo LIKE '%".$val."%';");
    		$userid = mysqli_fetch_assoc($useridSQL);
    		while ($userid) {
				$conditions["userid"][] = $userid["userid"];
				$userid = mysqli_fetch_assoc($useridSQL);
			}
		} else if ($clef != "table" AND $val != NULL) {
			$conditions[$clef] = $val;
		}
	}
}

// crée et affiche la requete sql
$requete = creationRequete($attributs, $table, $conditions);
echo $requete;

if ($connex) {
	//echo "<br>connexion réussie";
	$resultat = mysqli_query($connex, $requete);
	//echo mysqli_error($connex);
	$ligneResultat = mysqli_fetch_assoc($resultat);

	// parcourt et affiche toutes les publications
	if ($table == "Publications") {
		while($ligneResultat) {
			// sauf celles qui sont en privé (visibilité 0)
			//echo "<br>visibilité : ".$ligneResultat["visibilite"]; 
			if ($ligneResultat["visibilite"] == 1) {
				afficherPublication($connex, $ligneResultat);
			}
			$ligneResultat = mysqli_fetch_assoc($resultat);
		}
	// parcourt et affiche tous les comptes
	} else {
		while($ligneResultat) {
			afficherApercuCompte($connex, $ligneResultat);
			$ligneResultat = mysqli_fetch_assoc($resultat);
		}
	}
}


?>




<?php
piedDePage();
?>