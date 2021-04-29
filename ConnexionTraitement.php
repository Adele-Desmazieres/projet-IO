<?php

    require_once("BibliothequeFonctions.php");

    if(count(probleme($_POST))==0) {
        #Nom de DB: IO_TEST
        $connex=mysqli_connect('localhost','root','','IO_TEST');

        if(!$connex){
            echo "<h1>Erreur SQL, merci de bien vouloir réessayer</h1>";
        } else {
            #verif de securité
            $pseudoSQL=mysqli_real_escape_string($connex,$_POST['pseudo']);

            # + hachage du MDP
            $reqMDP="SELECT mdp FROM Users WHERE Pseudo ="."'".$pseudoSQL."'".";";
            $mdpSQL=mysqli_fetch_row(mysqli_query($connex,$reqMDP));
            

            #Vérif du mot de passe
            if(password_verify($_POST['mdp'],$mdpSQL[0])) {
            $_POST['pseudo']=preTraitement($_POST['pseudo']);
            $_POST['mdp']=htmlspecialchars($_POST['mdp']);                    
            setcookie('pseudo',$_POST['pseudo'],time()+3600);
            setcookie('mdp',$_POST['mdp'],time()+3600);        
            header('Location: FilActualite.php');
            exit();
            } else {
                echo "<h1>Mauvais mot de passe</h1>";
                ?>
                <form action='Connexion.html'>
                    <input type='submit' value='Retour'>
                </form>
                <?php
            }
        }
       
    } else {
        reponse(probleme($_POST));
        ?>
        <form action='Connexion.html'> <input type='submit' value='Retour'> </form>
        <?php 
    }
        ?>