<?php
require_once("BibliothequeFonctions.php");
teteDePage("Noodle : inscription");
session_start();

// traitement htmlspecialchars
foreach ($_POST as $clef => $val) {
    $_POST[$clef] = preTraitement($val);
}

// vérifie que le code admin est correct s'il est rentré
// 0 user non-admin, 1 pour user admin
$requeteAdmin = 0;
if(isset($_POST['admincode']) && $_POST['admincode']!=NULL){
    if($_POST['admincode']!=$masterkey){
        echo "<p class='erreur'>Erreur : code administrateur incorrect.</p>";
        ?>
        <div>
        <form action="Inscription.php" method='POST'>
            <input type="submit" value="Retour à l'inscription">
        </form>
        </div>
        <?php
        session_destroy();
        exit();
    } else {
        $_SESSION['admin'] = 1;
        $requeteAdmin = 1;
    }
}

// vérifie la visibilité du compte (0 pour privé, 1 pour publique)
if( isset($_POST["prive"]) && $_POST["prive"]=="Oui") {
    $requetePrive = 0;
} else {
    $requetePrive = 1;
}

// si aucun champ n'est vide, on affiche un récap
foreach($_POST as $clef => $val){
    if($clef!="admincode"){
        $POSTsansAdmin[$clef] = $val;
    }
}
if(count(probleme($POSTsansAdmin))==0) { 
    echo "<h2>Récapitulatif de l'inscription</h2>";
    foreach ($_POST as $clef => $val) {
        if ($clef != "mdp" && $clef!="admincode") {
            echo "<p>".$clef." : ".$val."</p>";
        }
    }
} else { 
    echo "<pre>";
    print_r($_POST); 
    echo "</pre>";
    
    ?>

    <form action='Inscription.php' method='POST'>
        <input type='submit' value='Retour'>
    </form>
    <?php
    exit("<p class='erreur'>Erreur : un des champs est vide.</p>");
}

$id=0;

# connexion à la BDD
$connex=mysqli_connect('localhost', 'root', $mdpBDD ,$nomBDD);

if(!$connex){
    echo "<p class='erreur'>Erreur : impossible de se connecter à la base de données.</p>";
    session_destroy();
} else {
    #verif de securité
    $pseudoSQL=mysqli_real_escape_string($connex,$_POST['pseudo']);
    $mailSQL=mysqli_real_escape_string($connex,$_POST['mail']);
    # + hachage du MDP avec la clef de cryptage PASSWORD_DEFAULT
    $mdpSQL=password_hash(mysqli_real_escape_string($connex, $_POST['mdp']), PASSWORD_DEFAULT);

    # attribuer un id unique au nouvel utilisateur
    $requeteIDmax = "SELECT MAX(userid) FROM Users";
          $resultIDmax = mysqli_query($connex, $requeteIDmax);
          if (!$resultIDmax) { 
            $erreur = $erreur."<p class='erreur'>Erreur : impossible d'associer un nouvel id à cet utilisateur.</p>"; 
            session_destroy();
          }
          $ligne = mysqli_fetch_row($resultIDmax); 

          if ($ligne[0] == NULL) {
            $id = 1;
          } else {
            $id = $ligne[0]+1;
          }
    }

    #Vérif de disponibilité du pseudo
    $reqVerif="SELECT * FROM Users WHERE Pseudo ='".$pseudoSQL."';";
    $resultVerif=mysqli_query($connex, $reqVerif);
    $ligneDeRes=mysqli_fetch_assoc($resultVerif);
    if(isset($ligneDeRes["pseudo"]) && $ligneDeRes["pseudo"]==$pseudoSQL) {
        echo "<p class='erreur'>Nom d'utilisateur déjà utilisé, merci d'en choisir un autre</p>";
        session_destroy();
    } else {
        #Requete d'insertion dans la table Users
        $req="INSERT INTO Users (pseudo, mdp, mail, birthdate, userid, admin, visibilite) VALUES('".$pseudoSQL."', '".$mdpSQL."', '".$mailSQL."', '".$_POST['naissance']."', ".$id.", ".$requeteAdmin.", ".$requetePrive.");";

        $result=mysqli_query($connex, $req);
        if(!$result){
            echo "<p class='erreur>Erreur : requête SQL incorrecte (".mysqli_error($connex).").</p>";
            session_destroy();

        } else {
            echo "<h2>Vous êtes bien inscrit dans la base de données.</h2>";
            $_SESSION['pseudo']=$pseudoSQL;
            $_SESSION['userid']=$id;
            $_SESSION['visibilite']=$requetePrive;
        }
    }

?>

<div>
<form action='Frontpage.php'>
    <input type='submit' value='Retour'> 
</form>
</div>
<div>
<form action='FilActualite.php' > 
    <input type='submit' value="Accéder à son fil d'actualité"> 
</form>
</div>

<?php
piedDePage();
?>
