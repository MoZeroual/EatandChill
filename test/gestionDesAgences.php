<?php
include "ini.php";
include "en_tete.php"; 


if($_SESSION['statut']==1 && isConnected()){
    
    
    echo "<h3><a href='gestionDesAgences.php'>DASHBORD: Gestion des Agences</a></h3>";
   
    



    
    // Bouton de modification
    if(isset($_POST['submitMod'])){
 
    extract($_POST);
    
   
        
     echo "<h3>Agence modifié!</h3>";
        
    $sql = "REPLACE INTO agences (id_agence,titre,adresse,ville,cp,description,photo)
    VALUES (:id_agence,:titre,:adresse,:ville,:cp,:description,:photo)";
    execRequete($sql,array('id_agence'=>$id_agence,'titre'=>$titre,'adresse'=>$adresse,'ville'=>$ville,'cp'=>$cp,'description'=>$description,'photo'=>$_FILES['photo']['name']));
}
    
    
if(isset($_POST['submit'])){
    
    if ($_FILES['photo']['error']) {  
   switch ($_FILES['photo']['error']){  
         case 1: // UPLOAD_ERR_INI_SIZE  
            echo "La taille du fichier est plus grande que la limite autorisée par le serveur (paramètre upload_max_filesize du fichier php.ini).";  
            break;  
         case 2: // UPLOAD_ERR_FORM_SIZE  
            echo "La taille du fichier est plus grande que la limite autorisée par le formulaire (paramètre post_max_size du fichier php.ini)."; 
            break;  
         case 3: // UPLOAD_ERR_PARTIAL  
            echo "L'envoi du fichier a été interrompu pendant le transfert."; 
    
            break;  
         case 4: // UPLOAD_ERR_NO_FILE  
           echo "La taille du fichier que vous avez envoyé est nulle."; 
            break;  
      }  
}  
else {
    
    extract($_POST);
    
    $verif_titreAgence="SELECT * FROM agences WHERE titre=:titre";
    
    $resultat=execRequete($verif_titreAgence,array('titre'=>$titre));
    
    if($resultat->rowCount()==0){
        
      
    
    $sql = "INSERT INTO agences (titre,adresse,ville,cp,description,photo)
    VALUES (:titre,:adresse,:ville,:cp,:description,:photo)";
    
    execRequete($sql,array('titre'=>$titre,'adresse'=>$adresse,'ville'=>$ville,'cp'=>$cp,'description'=>$description,'photo'=>$_FILES['photo']['name']));
    
//s'il n'y a pas d'erreur alors $_FILES['nom_du_fichier']['error'] 
//vaut 0  
   echo "Aucune erreur dans le transfert du fichier.<br />"; 
   if ((isset($_FILES['photo']['name'])&&($_FILES['photo']['error'] == UPLOAD_ERR_OK))) { 
      $chemin_destination = 'inc/img/'; 
      //déplacement du fichier du répertoire temporaire (stocké 
      //par défaut) dans le répertoire de destination 
      move_uploaded_file($_FILES['photo']['tmp_name'], $chemin_destination.$_FILES['photo']['name']); 
      echo "Le fichier ".$_FILES['photo']['name']." a été copié dans le répertoire img"; 
   } 
   else { 
      echo "Le fichier n'a pas pu être copié dans le répertoire img."; 
   } 
}else{
    echo "<p>Titre agence existe déja!</p>";
}
}
    
    
}

     if(isset($_GET['Supid_agence'])){
            // On verifie s'il existe des vehicules dans l'agence avant de supprimer l'agence
          $sql="SELECT * FROM vehicule WHERE id_agence=:id_agence ";
          $resultat=execRequete($sql,array('id_agence'=>$_GET['Supid_agence']));
         
         
         $sql2="SELECT * FROM agences WHERE id_agence=:id_agence";
        $resultat2=execRequete($sql2,array('id_agence'=>$_GET['Supid_agence']));
        $agence=$resultat2->fetch(PDO::FETCH_ASSOC);
         
        if($resultat->rowCount()==0){
         
        $supprimer="DELETE FROM agences WHERE id_agence=:id_agence";
        execRequete($supprimer,array('id_agence'=>$_GET['Supid_agence']));
            
            
    }else{
            echo "<h3>Veuillez d'abord retirez tous les véhicules de ".$agence['titre']."</h3>";
        }
     }
    
    
    // Affichage des agences
    
    $sql="SELECT * FROM agences";
    
    $resultat=execRequete($sql);
    if($resultat->rowCount()!=0){
        
        echo "<table align='center'>

   <tr>
       <th>Agence</th>
       <th>Titre</th>
       <th>Adresse</th>
       <th>Ville</th>
       <th>Cp</th>
       <th>Description</th>
       <th>Photo</th>
       <th>Actions</th>
   </tr>";
            
        while($agence=$resultat->fetch(PDO::FETCH_ASSOC)) {
            
            echo" <tr>
                   <td>".$agence['id_agence']."</td>
                   <td>".$agence['titre']."</td>
                   <td>".$agence['adresse']."</td>
                   <td>".$agence['ville']."</td>
                   <td>".$agence['cp']."</td>
                   <td>".$agence['description']."</td>
                   <td><img src='inc/img/".$agence['photo']."' width='100px'/></td>
                   <td><a href='gestionDesAgences.php?id_agence=".$agence['id_agence']."'>Modifier</a>
                       <a href='gestionDesAgences.php?Supid_agence=".$agence['id_agence']."'>Supprimer</a>
                   </td>
                </tr>";
        }
        
        echo "</table><br>";
} 
 
    
    if(!isset($_GET['id_agence'])){
  echo '<form action="gestionDesAgences.php" method="POST" enctype="multipart/form-data"> 
       <fieldset><legend>Ajouter une agence: </legend>
      <input type="text" name="titre" placeholder="Titre agence" /><br><br>
      <input type="text" name="adresse" placeholder="adresse agence"/><br><br>
      
      <input type="text" name="ville" placeholder="ville" /><br><br>
      
      <input type="text" name="cp" placeholder="Code postal" /><br>
      
      <p>Description: <br /><textarea name="description" rows="10" cols="20"></textarea></p> 
      <input type="hidden" name="MAX_FILE_SIZE" value="2097152"> 
      <p>Choisissez une photo avec une taille inférieure à 2 Mo.</p> 
      <input type="file" name="photo"> 
      <br /><br /> 
      <input type="submit" name="submit" value="Enregistrer"> 
    </fieldset></form>'; 
}else{
    // Présremplir le formulaire
    
    $sql="SELECT * FROM agences WHERE id_agence=:id_agence";
        $resultat=execRequete($sql,array('id_agence'=>$_GET['id_agence']));
        $agence=$resultat->fetch(PDO::FETCH_ASSOC);
    
    echo '
    <form action="gestionDesAgences.php" method="POST" enctype="multipart/form-data"> 
      <fieldset><legend>Modifier une agence: </legend>
      <input type="text" name="titre" placeholder="Titre agence"  value="'.$agence["titre"].'" ><br><br>
      
      <input type="text" name="adresse" placeholder="adresse agence" value="'.$agence["adresse"].'" ><br><br>
      
      <input type="text" name="ville" placeholder="ville" value="'.$agence["ville"].'" ><br><br>
      
      <input type="text" name="cp" placeholder="Code postal" value="'.$agence["cp"].'" ><br>
      
      <p>Description: <br /><textarea name="description" rows="10" cols="20">'.$agence["description"].'</textarea></p> 
      <input type="hidden" name="MAX_FILE_SIZE" value="2097152"> 
      <p>Choisissez une photo avec une taille inférieure à 2 Mo.</p> 
      <input type="file" name="photo" required> 
      <br /><br /> 
      <input type="hidden" name="id_agence"  value="'.$agence["id_agence"].'" >
      <input type="submit" name="submitMod" value="Enregistrer"> 
    </fieldset></form>'; 
}
    
    
}


?>
<?php
include "footer.php";
?>