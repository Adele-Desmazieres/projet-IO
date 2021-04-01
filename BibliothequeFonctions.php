<?php

function preTraitement($champ) {
    if(!empty($champ)) {
        $champ=trim($champ);
        $champ=htmlspecialchars($champ);
    }
    return $champ;
}

    function SQLconnectTest($host,$pseudo,$psw) {
        $connect_result = mysqli_connect($host,$pseudo,$psw);
        if (!$connect_result) {
            echo("Impossible de se connecter au serveur de bases de données.\n");
        } else {
            echo("Connexion réussie!\n");

            mysql_close($connect_result); 
        }
    }


    function afficherPublications($arr) {
        foreach($arr as $var) {
            echo $var."<br>";
        }
    }

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