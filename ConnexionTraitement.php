<?php
    session_start();
    require_once("BibliothequeFonctions.php");
    teteDePage("Noodle : connexion");

    // on évite les injections de code malveillant
    foreach ($_POST as $clef => $val) {
        $_POST[$clef] = preTraitement($val);
    }
    
    if(count(probleme($_POST))==0) {
        # Nom de DB: NoodleBDD
        $connex=mysqli_connect('localhost' ,'root' ,$mdpBDD ,$nomBDD);

        if(!$connex){
            echo "<p class='erreur'>Erreur : impossible de se connecter à la base de données.</p>";
            session_destroy();
        } else {
            # verif de securité
            $pseudoSQL=mysqli_real_escape_string($connex, $_POST['pseudo']);

            # + hachage du MDP
            $reqMDP="SELECT * FROM Users WHERE pseudo ='".$pseudoSQL."';";
            $resultatSQL=mysqli_fetch_assoc(mysqli_query($connex, $reqMDP));

            # Vérif du mot de passe
            if(password_verify($_POST['mdp'], $resultatSQL["mdp"])) {
                //$_POST['pseudo']=preTraitement($_POST['pseudo']);  
                
                $_SESSION['pseudo']=$_POST['pseudo'];
                $_SESSION['userid']=$resultatSQL["userid"];

                # retient si état administrateur
                $_SESSION['admin'] = $resultatSQL["admin"];

                # retient si état privé
                $_SESSION['visibilite']=$resultatSQL['visibilite'];

                header('Location: FilActualite.php');
                exit();

            } else {
                session_destroy();
                echo "<p class=\"erreur\">Mot de passe incorrect</p>";
                ?>

                <form action='Connexion.php' method="POST">
                    <input type='hidden' name='pseudo' value="<?php echo $_POST["pseudo"];?>" >
                    <input type='submit' value='Retour'>
                </form>
                <?php
            }
        }
       
    } else {
        echo "<div>";
        foreach (probleme($_POST) as $clef => $val) {
            echo "<p class=\"erreur\">Champ vide qui doit être rempli : ".$clef.".";
        }
        ?>
        <form action='Connexion.php'>
            <input type='submit' value='Retour'>
        </form>
        </div>
    <?php 
    
    }
    piedDePage();

?>