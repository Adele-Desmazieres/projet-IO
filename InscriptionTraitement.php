<?php
require_once("BibliothequeFonctions.php");
teteDePage("Noodle : inscription");
session_start();
$requeteAdmin=21;

//vérifie qu'on a pas affaire à un petit chenapan
if(isset($_POST['admincode']) && $_POST['admincode']!=NULL){
    if($_POST['admincode']!=$masterkey){
        echo "<h1>Vous n'êtes pas un administrateur, échec critique</h1>";
        ?>
        <form action="Inscription.php" method='POST'>
            <input type="submit" value="Retour à l'inscription">
        </form>
        <br>
        <?php
        session_destroy();
        exit();
    } else {
        $requeteAdmin="INSERT INTO admin VALUES (";
    }
}

// affiche le contenu de $arr
function reponseRecap($arr) {
    foreach ($arr as $clef => $val) {
        if ($clef != "mdp") {
            echo "<p>".$clef." : ".$val."</p>";
        }
    }
}

if(count(probleme($_POST))==0) {
    reponseRecap($_POST);
}

$id="";

# nom de DB: IO_TEST
$connex=mysqli_connect('localhost','root','','IO_TEST');

if(!$connex){
    echo "<h1>Erreur SQL, merci de bien vouloir réessayer</h1>";
    session_destroy();
} else {
    #verif de securité
    $pseudoSQL=mysqli_real_escape_string($connex,$_POST['pseudo']);
    $mailSQL=mysqli_real_escape_string($connex,$_POST['mail']);
    # + hachage du MDP
    $mdpSQL=password_hash(mysqli_real_escape_string($connex,$_POST['mdp']),PASSWORD_DEFAULT);

    $requeteIDmax = "SELECT MAX(userid) FROM Users";
          $resultIDmax = mysqli_query($connex, $requeteIDmax);
          if(!$resultIDmax) { 
            $erreur = $erreur."<br>Erreur : impossible d'associer un nouvel id à cet utilisateur"; 
            session_destroy();
          }
          $ligne = mysqli_fetch_row($resultIDmax); 

          if ($ligne[0] == NULL) {
            $id = 1;
          } else {
            $id = $ligne[0]+1;
          }
    

    }
    #Requete d'insertion dans la table Users
    $req="INSERT INTO Users (pseudo, mdp, mail, birthdate, userid) VALUES("."'".$pseudoSQL."'".", "."'".$mdpSQL."'".", "."'".$mailSQL."'"." , "."'".$_POST['naissance']."'".",".$id.");";

    #Vérif de disponibilité du pseudo
    $reqVerif="SELECT * FROM Users WHERE Pseudo ="."'".$pseudoSQL."'".";";
    $resultVerif=mysqli_query($connex,$reqVerif);
    $ligneDeRes=mysqli_fetch_row($resultVerif);
    if($ligneDeRes[0]==$pseudoSQL) {
        echo "<h1>Nom d'utilisateur déjà utilisé, merci de bien vouloir en choisir un autre</h1>";
        session_destroy();
    } else {
        $result=mysqli_query($connex,$req);
        if(!$result){
            echo "<h1>Erreur SQL 2, merci de bien vouloir réessayer.</h1>";
            echo "<p>".mysqli_error($connex)."</p>";
            session_destroy();
        } else {
            //Insertion dans la table prive si nécessaire
            if( isset($_POST["prive"]) && $_POST["prive"]=="Oui") {
                $requetePrive="INSERT INTO prive VALUES (".$id.");";
                $resultatPrive=mysqli_query($connex,$requetePrive);
                if(!$resultatPrive) { $erreur=$erreur."<br>Erreur: Insertion prive mal faite"; }
            }
    
            //fin de la requete d'insertion dans la table admin
            if($requeteAdmin!=21){
                $requeteAdmin=$requeteAdmin.$id.");";
                $resultatAdmin=mysqli_query($connex,$requeteAdmin);
                if(!$resultatAdmin){
                    echo "<h1 class='error'>Erreur SQL 2, merci de bien vouloir réessayer.</h1>";
                    echo "<p>".mysqli_error($connex)."</p>";
                    session_destroy();
                } else {
                    $_SESSION['admin']=1;
                }
            }
            
            echo "<h1>Vous êtes bien inscrit dans la base de données.</h1>";
            $_SESSION['pseudo']=$_POST['pseudo'];
            $_SESSION['userid']=$mdpSQL[1];
            
        }
    }

?>

<form action='Frontpage.php'>
    <input type='submit' size='20' value='Retour'> 
</form>
<form action='FilActualite.php' method='post'> 
    <input type='submit' size='20' value="Accéder à la page d'accueil"> 
    <input type=hidden name='pseudo' value="<?php echo $_REQUEST['pseudo']; ?>"> 
</form>

<?php
piedDePage();
?>
