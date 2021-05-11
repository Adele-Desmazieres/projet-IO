<?php
//phpinfo();
require_once("BibliothequeFonctions.php");
verificationConnexion();
teteDePage("Noodle : publication");

// Cette page enregistre les 2 documents (le cours et son aperçu) dans le dossier Publications,
// et elle note les infos relatives au document dans le tableau SQL

// Dans le dossier Publications, nom du fichier enregistré et de son aperçu :
// "id.type"
// "idA.type"


  $pseudo=$_SESSION['pseudo']; // récupère le pseudo de l'auteur
  //$cheminPublications = '../Publications/'; // initiée variable globale dans BibliothequeFonctions.php

  // le nom du fichier (basename donne le nom de la derniere composante du chemin)
  $fichier = basename($_FILES['contenu']['name']);
  $apercuFichier = basename($_FILES['apercu']['name']);
  $taille_maxi = 2000000; // taille max 2 Mo
  $tailleAper = filesize($_FILES['apercu']['tmp_name']);
  $taille = filesize($_FILES['contenu']['tmp_name']);
  $tailleSQL = $taille/1000;

  $extensions = array('.odt', '.pdf', '.jpg', '.jpeg', '.png');
  $extensionsAper = array('.bmp', '.gif', '.jpg', '.jpeg', '.png');

  $extension = strrchr($_FILES['contenu']['name'], '.'); 
  $extensionAper = strrchr($_FILES['apercu']['name'], '.'); 

  $erreur = "";
  $id = 0;

  // début des vérifications de sécurité...

  // si l'extension n'est pas dans le tableau
  if(!in_array($extension, $extensions) || !in_array($extensionAper, $extensionsAper)) { 
      $erreur = $erreur."<br>Erreur : les formats de fichier acceptés sont odt, png, pdf, jpg, jpeg pour le fichier principal, et bmp, gif, jpg, jpeg, png pour l'apercu.";
  }

  // si le fichier est de mauvaise taille
  if($taille>$taille_maxi || $tailleAper>$taille_maxi) {
      $erreur = $erreur."<br>Erreur : le fichier est trop gros.";
  }

  // s'il n'y a pas d'erreur, on upload
              
      // on formate le nom du fichier et celui de son aperçu ici
      $apercuFichier = strtr($apercuFichier, 
            'ÀÁÂÃÄÅÇÈÉÊËÌÍÎÏÒÓÔÕÖÙÚÛÜÝàáâãäåçèéêëìíîïðòóôõöùúûüýÿ', 
            'AAAAAACEEEEIIIIOOOOOUUUUYaaaaaaceeeeiiiioooooouuuuyy');
      $apercuFichier = preg_replace('/([^.a-z0-9]+)/i', '-', $apercuFichier);
      $fichier = strtr($fichier, 
            'ÀÁÂÃÄÅÇÈÉÊËÌÍÎÏÒÓÔÕÖÙÚÛÜÝàáâãäåçèéêëìíîïðòóôõöùúûüýÿ', 
            'AAAAAACEEEEIIIIOOOOOUUUUYaaaaaaceeeeiiiioooooouuuuyy');
      $fichier = preg_replace('/([^.a-z0-9]+)/i', '-', $fichier);

      // fonction qui connecte au SQL, renvoie un booléen qui dit si ca fonctionne
      // mysqli_connect(serveur, utilisateur, mdp, bdd)
      $connex = mysqli_connect('localhost','root','','IO_TEST'); 
      
      // récupère la description du document
      $description = mysqli_real_escape_string($connex, $_POST['description']);
      // récupère l'identifiant de l'utilisateur dans la variable $auteur
      $auteur = mysqli_query($connex, "SELECT userid FROM Users WHERE pseudo=\"".$pseudo."\";");
      $auteur = mysqli_fetch_row($auteur);
      echo $auteur[0];

      if ($auteur == NULL) {
      	$erreur = $erreur."<br>Erreur : auteur non reconnu.";
      }

      // si on n'arrive pas à se connecter à la base de données, erreur
      if (!$connex) { 
          $erreur = $erreur."<br>Erreur : impossible de se connecter à la base de données";

          //echo "ICI";

      // sinon chercher l'id maximum
      } else {
      	  //echo "LA";

          $requeteIDmax = "SELECT MAX(id) FROM Publications";
          $resultIDmax = mysqli_query($connex, $requeteIDmax);
          if(!$resultIDmax) { 
          	$erreur = $erreur."<br>Erreur : impossible d'associer un nouvel id à cette publication"; 
          }
          $ligne = mysqli_fetch_row($resultIDmax); 

          if ($ligne[0] == NULL) {
          	$id = 1;
          } else {
          	$id = $ligne[0]+1;
          }
          
          //echo "LA BAS";

          // insérer ici le restant de code pour ajout des stats du fichier dans le tableau Publications de la DB
          $requeteInserFichier = "INSERT INTO Publications (nom, description, type, size, auteur, date, id) VALUES ("."'".$fichier."'".", "."'".$description."'".", "."'".$extension."'".", ".$tailleSQL.", "."'".$auteur[0]."'".", "."'".date('Y-m-d H:i:s')."'".", ".$id.");"; // une chaine de caractères correspondant à la requête SQL qui va modifier le tableau
          echo "MIA";
          echo $requeteInserFichier;
          $resultInserFichier = mysqli_query($connex,$requeteInserFichier);

          // si la requête est fausse
          if (!$resultInserFichier) {
              $erreur = $erreur."<br>Erreur : requête SQL fausse : ";
              $erreur = $erreur.mysqli_error($connex);

          } else {
              //echo $_FILES['contenu']['tmp_name'];
              echo '<pre>'; 
              print_r($_FILES); 
              echo '</pre>';
              echo $cheminPublications.strval($id).$extension;

              // déplace le fichier uploadé dans le dossier Publications
              if (move_uploaded_file($_FILES['contenu']['tmp_name'], $cheminPublications.strval($id).$extension) && move_uploaded_file($_FILES['apercu']['tmp_name'], $cheminPublications.strval($id)."A".$extensionAper)) { // si la fonction renvoie TRUE, c'est que ça a fonctionné...
                  echo 'Upload effectué avec succès !'; 
                  ?>

                  <form action='FilActualite.php'>
                  <input type='submit' value='Retour à la page principale'>
                  </form>

                  <?php   
              } else { //Sinon (la fonction renvoie FALSE).   
                  $erreur = $erreur."<br>Echec de l'upload !";
                  ?>

                  <form action='Publier.php'>
                  <input type='submit' value='Retour'>
                  </form>

                  <?php
              }
          }
      }
    mysqli_close($connex);
  
   
  
    echo $erreur;
    echo error_reporting(E_ALL);

piedDePage();

?>