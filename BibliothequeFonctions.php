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

    <div>
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
        <input type='hidden' name='id' value=<?php echo $ligneDePubli['userid']; ?> >
        <input type='hidden' name='supprimer' value='<?php echo $ligneDePubli['id']; ?>'>
        <input type='hidden' name='supprimerType' value='<?php echo $ligneDePubli['extensionArticle']; ?>' >
		<input type='hidden' name='supprimerAperType' value='<?php echo $ligneDePubli['extensionApercu']; ?>' >
        <input type='submit' value='Supprimer la publication'>
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
    
}


// gère la tete de chaque page affichée sur le site (infos meta et bandeau)
// prend en argument un String qui sera le nom de l'onglet
function teteDePage($nomOnglet) { 
    ?>
    <!DOCTYPE html>
    <html lang="fr">
    <head>
        <meta charset="utf-8">
        <link rel='stylesheet' href='style.css'>
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


// Verifie la connexion de celui qui tente d'accéder au site
function verificationConnexion(){
    session_start();
    if($_SESSION['userid']== NULL){  
        exit ("<p class='erreur'>Vous n'etes pas connecté, vous pouvez retourner à la <a href='Frontpage.php'>page d'accueil</a> ou à celle de <a href='Connexion.php'>connexion.</a></p>"); 
    }
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


  
?>


