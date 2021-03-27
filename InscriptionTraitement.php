<?php

    function preTraitement($champ) {
        if(!empty($champ)) {
            $champ=trim($champ);
            $champ=htmlspecialchars($champ);
        }
        return $champ;
    }

    function probleme() {
        if(empty($_REQUEST['pseudo']) && empty($_REQUEST['mdp'])){
            $ret=array('pseudo','mot de passe');
        }
        if(empty($_REQUEST['mdp']) && !empty($_REQUEST['pseudo'])) {
            $ret=array('mot de passe');
        } 
        if(empty($_REQUEST['pseudo']) && !empty($_REQUEST['mdp'])){
            $ret=array('pseudo');
        }
        if(!empty($_REQUEST['pseudo']) && !empty($_REQUEST['mdp'])){
            $ret=array();
        }
        return $ret;
    }

    function reponse($arr){
        foreach($arr as $val) {
            echo "<p>Le ".$val." est incorrect</p>";
        }
    }

    if(count(probleme())==0) {
        echo "<h1>Recapitulatif</h1><br>";
        echo "<p>Pseudo : ".$_REQUEST["pseudo"]."</p>";
        echo "<p>Mot de passe : ".$_REQUEST["mdp"]."</p>";
    } else {
        reponse(probleme());
    }
    echo "<form action='inscription.html'> <input type='submit' size='20' value='Retour'> </form>"



    ?>
