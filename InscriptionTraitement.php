<?php
require_once("BibliothequeFonctions.php");
teteDePage("Noodle : inscription");

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
          }
          $ligne = mysqli_fetch_row($resultIDmax); 

          if ($ligne[0] == NULL) {
            $id = 1;
          } else {
            $id = $ligne[0]+1;
          }
    

    #Requete d'insertion dans la table Users
    $req="INSERT INTO Users (pseudo, mdp, mail, birthdate, userid) VALUES("."'".$pseudoSQL."'".", "."'".$mdpSQL."'".", "."'".$mailSQL."'"." , "."'".$_POST['naissance']."'".",".$id.");";

    #Vérif de disponibilité du pseudo
    $reqVerif="SELECT * FROM Users WHERE Pseudo ="."'".$pseudoSQL."'".";";
    $resultVerif=mysqli_query($connex,$reqVerif);
    $ligneDeRes=mysqli_fetch_row($resultVerif);
    if($ligneDeRes[0]==$pseudoSQL) {
        echo "<h1>Nom d'utilisateur déjà utilisé, merci de bien vouloir en choisir un autre</h1>";
    } else {
        $result=mysqli_query($connex,$req);
        if(!$result){
            echo "<h1>Erreur SQL 2, merci de bien vouloir réessayer.</h1>";
            echo "<p>".mysqli_error($connex)."</p>";
        } else {
            echo "<h1>Vous êtes bien inscrit dans la base de données.</h1>";
            
        }
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
