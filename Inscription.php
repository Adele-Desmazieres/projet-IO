<?php
require_once("BibliothequeFonctions.php");
teteDePage("Noodle : inscription");
?>

        <h1>Page d'inscription</h1>
        <form action='InscriptionTraitement.php' method="POST">
            <table><tbody>
            <tr>
                <td>Pseudo</td>
                <td><input type="text" name="pseudo" placeholder="Votre magnifique pseudonyme ici" required></td>
            </tr>
            <tr>
                <td>Mot de passe</td>
                <td><input type="password" name="mdp" minlength="8" placeholder="Chut ! C'est votre mot de passe" required></td>
            </tr>
            <tr>
                <td>Mail</td>
                <td><input type="email" name="mail" placeholder="arty.dumont@mail.abc" required></td>
            </tr>
            <tr>
                <td>Date de naissance</td>
                <td><input type="date" name="naissance" required></td>
            </tr>
            </tbody></table>

            <p>
                <input type='reset' value='Réinitialiser'>
                <input type='submit' value='Envoyer' size="20">
            </p>
        </form>

<?php
piedDePage();
?>