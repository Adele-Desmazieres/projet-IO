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
echo "<form action='inscription.html'> <input type='submit' size='20' value='Retour'> </form>"
?>
<form action='FilActualite.php' method='post'> <input type='submit' size='20' value="Accéder à la page d'accueil"> <input type=hidden name='pseudo' value="<?php echo $_REQUEST['pseudo']; ?>"> </form>
