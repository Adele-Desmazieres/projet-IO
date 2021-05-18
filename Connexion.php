<?php
require_once("BibliothequeFonctions.php");
teteDePage("Noodle : connexion");
?>

<h1>Page de connexion</h1>
<form action='ConnexionTraitement.php' method="POST">
    <div><table><tbody>
        <tr>
            <td>Pseudo</td>
            <td><input type="text" name="pseudo" placeholder="Pseudonyme" required
                <?php 
                if (isset($_POST["pseudo"])) {
                    echo "value=\"".$_POST["pseudo"]."\"";
                }
                ?>>
            </td>
        </tr>
        <tr>
            <td>Mot de passe</td>
            <td><input type="password" name="mdp" placeholder="Mot de passe" required></td>
        </tr>
    </tbody></table></div>

    <div>
        <input type='reset' value='Réinitialiser'>
        <input type='submit' value='Envoyer'>
    </div>
</form>

<?php
piedDePage();
?>