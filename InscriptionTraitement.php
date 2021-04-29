<?php
 require_once("BibliothequeFonctions.php");

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

#Nom de DB: IO_TEST
$connex=mysqli_connect('localhost','root','','IO_TEST');

if(!$connex){
    echo "<h1>Erreur SQL, merci de bien vouloir réessayer</h1>";
} else {
    #verif de securité
    $pseudoSQL=mysqli_real_escape_string($connex,$_POST['pseudo']);
    $mailSQL=mysqli_real_escape_string($connex,$_POST['mail']);
    # + hachage du MDP
    $mdpSQL=password_hash(mysqli_real_escape_string($connex,$_POST['mdp']),PASSWORD_DEFAULT);
    

    #Requete d'insertion dans la table Users
    $req="INSERT INTO Users (Pseudo, mdp, mail, birthdate) VALUES("."'".$pseudoSQL."'".", "."'".$mdpSQL."'".", "."'".$mailSQL."'"." , "."'".$_POST['naissance']."'".");";

    #Vérif de disponibilité du pseudo
    $reqVerif="SELECT * FROM Users WHERE Pseudo ="."'".$pseudoSQL."'".";";
    $resultVerif=mysqli_query($connex,$reqVerif);
    $ligneDeRes=mysqli_fetch_row($resultVerif);
    if($ligneDeRes[0]==$pseudoSQL) {
        echo "<h1>Nom d'utilisateur déjà utilisé, merci de bien vouloir en choisir un autre</h1>";
    } else {
        $result=mysqli_query($connex,$req);
        if(!$result){
            echo "<h1>Erreur SQL 2, merci de bien vouloir réessayer</h1>";
            echo "<p>".mysqli_error($connex)."</p>";
        } else {
            echo "<h1>Vous êtes bien inscrit dans la base de donnees!</h1>";
            
        }
    }
}
echo "<form action='inscription.html'> <input type='submit' size='20' value='Retour'> </form>"
?>
<meta charset='UTF-8'>
<form action='FilActualite.php' method='post'> <input type='submit' size='20' value="Accéder à la page d'accueil"> <input type=hidden name='pseudo' value="<?php echo $_REQUEST['pseudo']; ?>"> </form>
