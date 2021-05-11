<?php
require_once("BibliothequeFonctions.php");
teteDePage("Noodle : connexion");
?>

<h1>Page de connexion</h1>
<form action='ConnexionTraitement.php' method="POST">    
    <table><tbody>
        <tr>
            <td>Pseudo</td>
            <td><input type="text" name="pseudo" placeholder="Pseudonyme ou email" required></td>
        </tr>
        <tr>
            <td>Mot de passe</td>
            <td><input type="password" name="mdp" minlength="8" placeholder="Mot de passe" required></td>
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