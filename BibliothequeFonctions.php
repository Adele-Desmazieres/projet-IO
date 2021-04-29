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

    function SQLinsert($request,$messageSucces){
        $SQLconnection=mysqli_connect('localhost','root','','IO_TEST');
        if(!$SQLconnection){
            echo "<h1>Erreur SQL, merci de bien vouloir réessayer</h1>";
        } else {
            $resultat=mysqli_query($SQLconnection,$request);
            if(!$resultat){
                echo "<h1>Erreur SQL 2, merci de bien vouloir réessayer</h1>";
                echo "<p>".mysqli_error($SQLconnection)."</p>";
            } else {
                echo "<h1>".$messageSucces."</h1>";
            }
        }
        mysqli_close($SQLconnection);
    }

  
?>