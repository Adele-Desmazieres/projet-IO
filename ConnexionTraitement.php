<?php

    require_once("BibliothequeFonctions.php");

    if(count(probleme($_POST))==0) {
        $_POST['pseudo']=preTraitement($_POST['pseudo']);
        $_POST['mdp']=htmlspecialchars($_POST['mdp']);
            
        setcookie('pseudo',$_POST['pseudo'],time()+3600);
        setcookie('mdp',$_POST['mdp'],time()+3600);
            
        header('Location: FilActualite.php');
        exit();
    } else {
        reponse(probleme($_POST));
        ?>
        <form action='Connexion.html'> <input type='submit' value='Retour'> </form>
        <?php 
    }
        ?>