<?php
require_once("BibliothequeFonctions.php");
teteDePage("Noodle : inscription");
?>

        <h1>Page d'inscription</h1>
        <form action='InscriptionTraitement.php' method="POST">
            <table><tbody>
            <tr>
                <td>Pseudo</td>
                <td><input type="text" name="pseudo" placeholder="Votre pseudonyme" required></td>
            </tr>
            <tr>
                <td>Mot de passe</td>
                <td><input type="password" name="mdp" minlength="8" placeholder="Votre mot de passe" required></td>
            </tr>
            <tr>
                <td>Adresse e-mail</td>
                <td><input type="email" name="mail" placeholder="Votre mail" required></td>
            </tr>
            <tr>
                <td>Date de naissance</td>
                <td><input type="date" name="naissance" required></td>
            </tr>
            <tr>
                <td>Désigner ce compte comme privé</td>
                <td><input type="checkbox" name="prive" value="Oui"></td>
            </tr>
            <tr>
                <td>Code administrateur (merci de ne remplir ce champ que si vous savez ce que vous faites)</td>
                <td><input type="password" name="admincode"></td>
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