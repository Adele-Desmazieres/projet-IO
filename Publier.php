<?php
require_once("BibliothequeFonctions.php");
verificationConnexion();
teteDePage("Noodle : publication");
?>
    <h1>Publier un cours</h1>

    <main>
    <form enctype="multipart/form-data" action="PublierTraitement.php" method='POST'>
        <input type="hidden" name="MAX_FILE_SIZE" value="2000000000">
        <p>Votre cours<br><input class="button" type="file" name="contenu"></p>
        <p>Votre aperçu<br><input class="button" type="file" name="apercu"></p>
        <p>Votre description<br><textarea name="description" rows="10" cols="50"></textarea></p>
        <p><input type="checkbox" name="droits" value="ok" checked required> Je certifie avoir les droits d'auteur nécessaires à la publication des documents ci-dessus. </p>
        <p class="alignement">Visibilité<br>
            <input type="radio" id="prive" name="visibilite" value="prive" <?php if($_SESSION['visibilite']==0) { echo "checked";} ?> > Privée <br>
            <input type="radio" id="publique" name="visibilite" value="publique" <?php if($_SESSION['visibilite']==1) { echo "checked";} ?>> Publique<br>
        </p>
        <p><input class="button" type="submit" value="Publier"></p>
    </form>
</main>
    
<?php
piedDePage();
?>