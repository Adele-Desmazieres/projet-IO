<?php

// variables globales
$cheminPublications = "../Publications/"; // chemin dossier de Publications
$masterkey = "AeMstadl26nMMXX%"; // code pour etre admin
$nomBDD = "NoodleBDD";
$mdpBDD = "";


// affiche une seule publication, avec le pseudo de son auteur
function afficherPublication($connex, $donneesPubli) { 
    global $cheminPublications;
    $pseudo = pseudoDeId($connex, $donneesPubli["userid"]);
    ?>

    <div class="cadre">
    <p><strong><?php echo $pseudo;?></strong></p>
    <p>Télécharger 
        <a href=<?php echo "\"".$cheminPublications.$donneesPubli['id'].$donneesPubli['extensionArticle']."\"";?>>
        <?php echo $donneesPubli['nomArticle'];?>
        </a>
    </p>
    <p>
        <a href=<?php echo "\"".$cheminPublications.$donneesPubli['id']."A".$donneesPubli['extensionApercu']."\"";?>>
        <img src="<?php echo $cheminPublications.$donneesPubli['id'].'A'.$donneesPubli['extensionApercu'];?>" alt="Impossible d'afficher l'aperçu <?php echo $donneesPubli['nomApercu'];?>" height="200px">
        </a>
    </p>
    <p><?php echo "Description : ".$donneesPubli['description']; ?></p>
    </div>
    <?php
}

//Fonction qui affiche le bouton supprimer
//Prend en entrée un tableau associatif de résultat SQL dans Publications
function afficheSupprimer($ligneDePubli,$pageActuelle) {
	?>
	<div>
	<form action='<?php echo $pageActuelle; ?>' method='POST'>
        <?php if(isset($_POST['self']) && $_POST['self']=='TRUE') { ?> <input type='hidden' name='self' value='TRUE'> <?php } ?>
        <input type='hidden' name='id' value=<?php echo $ligneDePubli['userid']; ?> >
        <input type='hidden' name='supprimer' value='<?php echo $ligneDePubli['id']; ?>'>
        <input type='hidden' name='supprimerType' value='<?php echo $ligneDePubli['extensionArticle']; ?>' >
		<input type='hidden' name='supprimerAperType' value='<?php echo $ligneDePubli['extensionApercu']; ?>' >
        <input class="buttonRed" type='submit' value='Supprimer la publication'>
    </form></div>
	<?php
}

// renvoie le pseudo correspondant à un userid
function pseudoDeId($connex, $userid) {
    $pseudoSQL = mysqli_fetch_assoc(mysqli_query($connex, "SELECT pseudo FROM Users WHERE Users.userid="."'".$userid."';"));
    $pseudo = $pseudoSQL["pseudo"];
    return $pseudo;
}


// affiche l'apercu d'un compte, prend en arguement sa ligne sql, et une connexion sql
function afficherApercuCompte($connex, $donneesCompte) { 
    $nbrAbonnes = mysqli_fetch_row(mysqli_query($connex, "SELECT count(*) FROM Abonnements WHERE abonnement="."'".$donneesCompte["userid"]."';"))[0];
    $nbrAbonnements = mysqli_fetch_row(mysqli_query($connex, "SELECT count(*) FROM Abonnements WHERE abonne="."'".$donneesCompte["userid"]."';"))[0];    
    ?>
    <div>
        <form action="Profil.php" method="POST">
            <input type="hidden" name="id" value="<?php echo $donneesCompte["userid"];?>">
            <button class='buttonProfil' type="submit">
                <?php echo "<strong>".$donneesCompte["pseudo"].'</strong><br> Abonnés : '.$nbrAbonnes." <br> Abonnements : ".$nbrAbonnements;?>
            </button>
        </form>
    </div>
    <?php    
}


// gère la tete de chaque page affichée sur le site (infos meta et bandeau)
// prend en argument un String qui sera le nom de l'onglet
function teteDePage($nomOnglet) { 
    ?>
    <!DOCTYPE html>
    <html id="top" lang="fr">
    <head>
        <meta charset="utf-8">
        <link rel='stylesheet' href='style.css'>
        <title><?php echo $nomOnglet;?></title>
        <?php
        //<link rel="stylesheet" href="style0.css">
        ?>
    </head>
    <body>
        <p class="bandeau">Noodle</p>
    <?php
}

// gère le bas de chaque page affichée sur le site
function piedDePage() { 
    ?>
    </body>
    </html> 
    <?php
}


// Verifie la connexion de celui qui tente d'accéder au site
function verificationConnexion(){
    session_start();
    if($_SESSION['userid']== NULL){  
        exit ("<p class='erreur'>Vous n'etes pas connecté, vous pouvez retourner à la <a href='Frontpage.php'>page d'accueil</a> ou à celle de <a href='Connexion.php'>connexion.</a></p>"); 
    } else {
        affichageMenu();
    }
}


function affichageMenu() { ?>
    
    <div class="menu">
    <h2>Menu</h2>

    <div class="cadre">
    <p>
    <form action='FilActualite.php' method='POST'>
        <input class='button' type='submit' value="Fil d'actualité">
    </form>
    </p>

    <p>
    <form action='Recherche.php' method='POST'>
        <input class='button' type='submit' value='Rechercher'>
    </form>
    </p>

    <p>
    <form action='Publier.php' method='get'>
        <input class='button' type='submit' name='Publier' value='Faire une publication'>
    </form>
    </p>

    <p>
    <form action='Profil.php' method='POST'>
        <input type='hidden' name='self' value='TRUE'>
        <input type='hidden' name='pseudo' value='<?php echo $_SESSION['pseudo']; ?>'>
        <input class='buttonProfil' type='submit' name='profil' value='Voir son profil'>
    </form>
    </p>

    <p>
    <form action='Frontpage.php' method='get'>
        <input class='buttonRed' type='submit' name='deconnexion' value='Se déconnecter'>
    </form>
    </p>

    </div>
    </div>
<?php
}


// prétraite un String de manière à empêcher l'injection de code malveillant
function preTraitement($champ) {
    if (!empty($champ)) {
        $champ = trim($champ);
        $champ = htmlspecialchars($champ);
        return $champ;
    } else {
        return "";
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

// tente de se connecter à la BDD, et renvoie une erreur sinon
function sqlConnexion() {
    global $mdpBDD;
    global $nomBDD;
    // renvoie un objet de connexion, ou false si echec
    // prend en argument : serveur, utilisateur, mdp, nom de la BDD
    $connex = mysqli_connect('localhost', 'root', $mdpBDD, $nomBDD);
    if (!$connex) {
        echo "<br>Erreur : impossible de se connecter à la BDD (".$mysqli_connect_error().").";
    }
    return $connex;
}


// renvoie un String correspondant à cette requete :
// SELECT $attributs FROM $table WHERE $clef1 LIKE "%$val1%" AND $clef2=$val2 AND...
// fonctionne uniquement avec une tableau contenant String et int
// le tableau $conditions peut contenir un tableau indicé, ce qui donnera :
// ... AND ($clef=$val1 OR $clef=$val2)
function creationRequete($attributs, $table, $conditions) {
    $sqlRequete = "SELECT";
    foreach ($attributs as $att) {
        $sqlRequete = $sqlRequete." $att,"; // SELECT "att1", "att2",
    }
    // on retire la virgule en trop
    $sqlRequete = substr($sqlRequete, 0, strlen($sqlRequete)-1);
    $sqlRequete = $sqlRequete." FROM ".$table." WHERE ";

    // on ajoute les conditions séparées par AND
    foreach ($conditions as $clef => $val) {
        //$clef = $cl;

        // si c'est un string : clef LIKE "%val%" AND
        if (is_string($val)) {
            $sqlRequete = $sqlRequete." ".$clef." LIKE \"%".$val."%\" AND";

        // si c'est un tableau : ( clef=valInt1 OR clef LIKE "%valInt2%" ) AND
        } else if (is_array($val)) {
            $sqlRequete = $sqlRequete." (";
            foreach ($val as $valInterne) {
                //echo $valInterne;
                if (is_string($valInterne)) {
                    $sqlRequete = $sqlRequete." ".$clef." LIKE \"%".$valInterne."%\" OR";
                } else {
                    $sqlRequete = $sqlRequete." ".$clef."=".$valInterne." OR";
                }
            }
            $sqlRequete = substr($sqlRequete, 0, strlen($sqlRequete)-2);
            $sqlRequete = $sqlRequete.") AND";

        // sinon c'est un entier : clef="val" AND
        } else {
            $sqlRequete = $sqlRequete." ".$clef."=".$val." AND";
        }
    }
    // on retire le AND de trop et on met le point-virgule final
    $sqlRequete = substr($sqlRequete, 0, strlen($sqlRequete)-4);
    $sqlRequete = $sqlRequete.";";
    return $sqlRequete;
}



  
?>


