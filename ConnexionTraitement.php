<?php
    session_start();
    require_once("BibliothequeFonctions.php");
    teteDePage("Noodle : connexion");

    
    if(count(probleme($_POST))==0) {
        #Nom de DB: IO_TEST
        $connex=mysqli_connect('localhost','root','','IO_TEST');

        if(!$connex){
            echo "<h1>Erreur SQL, merci de bien vouloir réessayer</h1>";
            session_destroy();
        } else {
            #verif de securité
            $pseudoSQL=mysqli_real_escape_string($connex,$_POST['pseudo']);

            # + hachage du MDP
            $reqMDP="SELECT mdp, userid FROM Users WHERE Pseudo ="."'".$pseudoSQL."'".";";
            $mdpSQL=mysqli_fetch_row(mysqli_query($connex,$reqMDP));
            

            #Vérif du mot de passe
            if(password_verify($_POST['mdp'],$mdpSQL[0])) {
                $_POST['pseudo']=preTraitement($_POST['pseudo']);  
                
                $_SESSION['pseudo']=$_POST['pseudo'];
                $_SESSION['userid']=$mdpSQL[1];

                #Vérif état administrateur
                $requeteVerifAdmin="SELECT * FROM admin WHERE (id=".$mdpSQL[1].");";
                $resultatVerifAdmin=mysqli_query($connex,$requeteVerifAdmin);
                if(!$resultatVerifAdmin) {
                    echo "Problème SQL, merci de bien vouloir réessayer";
                    session_destroy();
                    exit("Problème administrateur");
                } else {
                    $resultatVerifAdmin=mysqli_fetch_row($resultatVerifAdmin);
                    if($resultatVerifAdmin[0]==$_SESSION['userid']) {
                        $_SESSION['admin']=1;
                    } else {
                        $_SESSION['admin']=0;
                    }
                }

                header('Location: FilActualite.php');
                exit();
            } else {
                session_destroy();
                echo "<h1>Mauvais mot de passe</h1>";
                ?>
                <form action='Connexion.php'>
                    <input type='submit' value='Retour'>
                </form>
                <?php
            }
        }
       
    } else {
        reponse(probleme($_POST));
        ?>
        <form action='Connexion.php'> <input type='submit' value='Retour'> </form>
        <?php 
    }

    piedDePage();

?>