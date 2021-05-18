<?php
require_once("BibliothequeFonctions.php");
verificationConnexion();
teteDePage("Noodle : publication");
?>
    <h1>Publier un cours</h1>
    <form enctype="multipart/form-data" action="PublierTraitement.php" method='POST'>
        <input type="hidden" name="MAX_FILE_SIZE" value="2000000000">
        <p><input type="file" name="contenu"><br>Votre cours</p>
        <p><input type="file" name="apercu"><br>Votre aperçu</p>
        <p><textarea name="description" rows="10" cols="50"></textarea><br>Votre description</p>
        <p><input type="checkbox" name="droits" value="ok" checked required> Je certifie avoir les droits d'auteur nécessaires à la publication des documents ci-dessus. </p>
        <p><input type="radio" id="prive" name="visibilite" value="prive" <?php if($_SESSION['visibilite']==0) { echo "checked";} ?> > Privée </p>
        <p><input type="radio" id="publique" name="visibilite" value="publique" <?php if($_SESSION['visibilite']==1) { echo "checked";} ?>> Publique </p>
        <p><input type="submit" value="Publier"></p>
    </form>
    
<?php
piedDePage();
?>