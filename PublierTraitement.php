<?php

//Il manque encore le code pour ajouter l'aperçu, je voudrais le mettre dans le même tableau SQL en tant qu'attribut
//et dans le même dossier que le fichier lié

  $auteur=$_COOKIE['pseudo'];
  $dossier = 'Publications/';
  $fichier = basename($_FILES['contenu']['name']);
  $taille_maxi = 100000;
  $taille = filesize($_FILES['contenu']['tmp_name']);
  $tailleSQL=$taille/1000;
  $extensions = array('.odt', '.pdf', '.jpg', '.jpeg');
  $extension = strrchr($_FILES['contenu']['name'], '.'); 
  //Début des vérifications de sécurité...
  if(!in_array($extension, $extensions)) { //Si l'extension n'est pas dans le tableau
       $erreur = 'Vous devez uploader un fichier de type png, gif, jpg, jpeg, txt ou doc...';
  } else {
     if($taille>$taille_maxi) {
          $erreur = 'Le fichier est trop gros...';
     } else {
          if(!isset($erreur)) { //S'il n'y a pas d'erreur, on upload
                         
               //On formate le nom du fichier ici...
               $fichier = strtr($fichier, 
                    'ÀÁÂÃÄÅÇÈÉÊËÌÍÎÏÒÓÔÕÖÙÚÛÜÝàáâãäåçèéêëìíîïðòóôõöùúûüýÿ', 
                    'AAAAAACEEEEIIIIOOOOOUUUUYaaaaaaceeeeiiiioooooouuuuyy');
               $fichier = preg_replace('/([^.a-z0-9]+)/i', '-', $fichier);
               $connex=mysqli_connect('localhost','root','','IO_TEST');
               $description=mysqli_real_escape_string($connex,$_POST['description']);
               if(!$connex) { echo "<h1>Erreur SQL, merci de bien vouloir réessayer</h1>"; }
               else {
                    //Insérer ici le restant de code pour ajout des stats du fichier dans le tableau Publications de la DB
                    $requeteInserFichier="INSERT INTO Publications (nom, description, type, size, auteur, date) VALUES ("."'".$fichier."'".", "."'".$description."'".", "."'".$extension."'".", ".$tailleSQL.", "."'".$auteur."'".", ".date("Y-m-d").");";
                    $resultInserFichier=mysqli_query($connex,$requeteInserFichier);
                    if(!$resultInserFichier){
                         echo "<h1>Erreur SQL, le fichier n'a pas été publié, merci de bien vouloir réessayer</h1>";
                         echo mysqli_error($connex);
                    } else {
                         if(move_uploaded_file($_FILES['contenu']['tmp_name'], $dossier . $fichier)) { //Si la fonction renvoie TRUE, c'est que ça a fonctionné...
                              echo 'Upload effectué avec succès !'; 
                              ?>
                <form action='FilActualite.php'>
                    <input type='submit' value='Retour à la page principale'>
                </form>
                <?php   
                         } else { //Sinon (la fonction renvoie FALSE).   
                              echo 'Echec de l\'upload !';
                              ?>
                <form action='Publier.html'>
                    <input type='submit' value='Retour'>
                </form>
                <?php
                         }
                    }
               }
          } else {
               echo $erreur;
               ?>
               <form action='Publier.html'>
                   <input type='submit' value='Retour'>
               </form>
               <?php
          }
     }
}
  

  ?>