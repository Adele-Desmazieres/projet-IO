<?php

require_once("BibliothequeFonctions.php");


//Il manque encore le code pour ajouter l'aperçu, je voudrais le mettre dans le même tableau SQL en tant qu'attribut
//et dans le même dossier que le fichier lié

  $auteur=$_COOKIE['pseudo'];
  $dossier = 'Publications/';
  // le nom du fichier (basename donne le nom de la derniere composante)
  $fichier = basename($_FILES['contenu']['name']);
  $apercuFichier = basename($_FILES['apercu']['name']);
  $taille_maxi = 100000;
  $tailleAper=filesize($_FILES['apercu']['tmp_name']);
  $taille = filesize($_FILES['contenu']['tmp_name']);
  $tailleSQL=$taille/1000;
  $extensions = array('.odt', '.pdf', '.jpg', '.jpeg', '.png');
  $extension = strrchr($_FILES['contenu']['name'], '.'); 
  $extensionsAper = array('.bmp', '.gif', '.jpg', '.jpeg', '.png');
  $typeAper= strrchr($_FILES['apercu']['name'], '.'); 
  $erreur = "";
  $ID="Salut";

  // début des vérifications de sécurité...

  // si l'extension n'est pas dans le tableau
  if(!in_array($extension, $extensions) || !in_array($typeAper, $extensionsAper)) { 
      $erreur = $erreur."<br>Erreur : les formats de fichier acceptés sont odt, png, pdf, jpg, jpeg";
  }

  // si le fichier est de mauvaise taille
  if($taille>$taille_maxi || $tailleAper>$taille_maxi) {
      $erreur = $erreur."<br>Erreur : le fichier est trop gros";
  }

  // s'il n'y a pas d'erreur, on upload
 
              
      // on formate le nom du fichier ici...
      $fichier = strtr($fichier, 
            'ÀÁÂÃÄÅÇÈÉÊËÌÍÎÏÒÓÔÕÖÙÚÛÜÝàáâãäåçèéêëìíîïðòóôõöùúûüýÿ', 
            'AAAAAACEEEEIIIIOOOOOUUUUYaaaaaaceeeeiiiioooooouuuuyy');
      $fichier = preg_replace('/([^.a-z0-9]+)/i', '-', $fichier);

      $connex = mysqli_connect('localhost','root','','IO_TEST');
      $description = mysqli_real_escape_string($connex,$_POST['description']);

      // si on n'arrive pas à se connecter à la base de données
      if (!$connex) { 
          $erreur = $erreur."<br>Erreur : impossible de se connecter à la base de données";
      } else {
        
          // chercher l'ID maximum
          $requeteIDmax="SELECT MAX(id) FROM Publications";
          $resultIDmax= mysqli_query($connex,$requeteIDmax);
          if(!$resultIDmax) { $erreur=$erreur."<br>Problème d'ID"; }
          $ligne=mysqli_fetch_row($resultIDmax); $ID=$ligne[0]+1;
          

          // insérer ici le restant de code pour ajout des stats du fichier dans le tableau Publications de la DB
          $requeteInserFichier = "INSERT INTO Publications (nom, description, type, size, auteur, date, id) VALUES ("."'".$fichier."'".", "."'".$description."'".", "."'".$extension."'".", ".$tailleSQL.", "."'".$auteur."'".", "."'".date('Y-m-d H:i:s')."'".", ".$ID.");"; // une chaine de caractères correspondant à la requête SQL qui va modifier le tableau
          $resultInserFichier = mysqli_query($connex,$requeteInserFichier);

          // si la requête est fausse
          if (!$resultInserFichier) {
              $erreur = $erreur."<br>Erreur : requête SQL fausse : ";
              $erreur = $erreur.mysqli_error($connex);
          } else {
              
              //echo $_FILES['contenu']['tmp_name'];
              //echo '<pre>'; print_r($_FILES); echo '</pre>';
              
              // déplace le fichier uploadé dans le dossier Publications
              if (move_uploaded_file($_FILES['contenu']['tmp_name'], $dossier.strval($ID).$extension) && move_uploaded_file($_FILES['apercu']['tmp_name'], $dossier.strval($ID)."A".$extension)) { // si la fonction renvoie TRUE, c'est que ça a fonctionné...
                  echo 'Upload effectué avec succès !'; 
                            ?>

                  <form action='FilActualite.php'>
                  <input type='submit' value='Retour à la page principale'>
                  </form>

                  <?php   
              } else { //Sinon (la fonction renvoie FALSE).   
                  $erreur = $erreur."<br>Echec de l'upload !";
                  ?>

                  <form action='Publier.html'>
                  <input type='submit' value='Retour'>
                  </form>

                  <?php
              }
          }
      }
    mysqli_close($connex);
  
   
  
    echo $erreur;
  

?>
