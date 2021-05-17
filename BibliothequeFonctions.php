<?php

// variables globales
$cheminPublications = "../Publications/";
$masterkey="AeMstadl26nMMXX%";

//gère l'affichage des liens pour télécharger les publications et l'affichage de leur aperçu
//Entrées : Resultat de requête (déjà fetch), Résultat de requête (brut), nom du fichier de la page actuelle
function afficherPublications($TabDePubli, $resultatFonc, $pageActuelle){
    global $cheminPublications, $Qui;
    while($TabDePubli) {?>

        <p>Télécharger 
            <a href=<?php echo "\"".$cheminPublications.$TabDePubli['id'].$TabDePubli['type']."\"";?>><?php echo $TabDePubli['nom'];?></a>
          <img src='<?php echo $cheminPublications.$TabDePubli['id']."A".$TabDePubli['type']; ?>' height="64px">
        </p>
        <p><?php echo $TabDePubli['description']; ?></p>
        <?php if($_SESSION['admin']==1 || isset($_POST['self'])){
            ?><form action='<?php echo $pageActuelle; ?>' method='POST'>
                <?php if(isset($Qui)) { ?> <input type='hidden' name='id' value=<?php echo $Qui; } else { ?>>
                <input type='hidden' name='id' value=<?php echo $TabDePubli['auteur']; } ?> >
                <input type='hidden' name='supprimer' value='<?php echo $TabDePubli['id']; ?>'>
                <input type='hidden' name='supprimerType' value='<?php echo $TabDePubli['type']; ?>' >
                <input type='submit' value='Supprimer la publication'>
            </form>
            <?php
        }
        ?>
        
        <?php
        echo isset($Qui);
        $TabDePubli = mysqli_fetch_assoc($resultatFonc);
    }
}

function afficherPublication($donneesPubli) { 
    global $cheminPublications;
    ?>
    <p>Télécharger 
    <a href=<?php echo "\"".$cheminPublications.$donneesPubli['id'].$donneesPubli['type']."\"";?>>
        <?php echo $donneesPubli['nom'];?>
    </a></p>
    <p>
        <img src='<?php echo $cheminPublications.$donneesPubli['id']."A".$donneesPubli['typeA'];?>' alt="Impossible d'afficher l'image <?php echo $donneesPubli['nomA'];?>" height="64px">
    </p>
    <p><?php echo "Description : ".$donneesPubli['description']; ?></p>
    <?php
}


// gère la tete de chaque page affichée sur le site (infos meta et bandeau)
// prend en argument un String qui sera le nom de l'onglet
function teteDePage($nomOnglet) { 
    ?>
    <!DOCTYPE html>
    <html lang="fr">
    <head>
        <meta charset="utf-8">
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


// Verifie la connexion pour les comptes
function verificationConnexion(){
    session_start();
    if($_SESSION['userid']== NULL){  
        exit ("<a href='Frontpage.php'> Vous n'etes pas connecté</a>"); 
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


