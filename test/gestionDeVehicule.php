<?php
include "ini.php";
include "en_tete.php"; 


if($_SESSION['statut']==1 && isConnected()){
    echo "<h3><a href='gestionDeVehicule.php'>DASHBORD: Gestion des Véhicules</a></h3>";
    
    
         // Bouton de modification
    if(isset($_POST['submitMod'])){
 
    extract($_POST);
    
   
        
     echo "Véhicule modifié!</h3>";
        
    $sql = "REPLACE INTO vehicule (id_vehicule,id_agence,titre,marque,modele,description,photo,prix_journalier)
    VALUES (:id_vehicule,:id_agence,:titre,:marque,:modele,:description,:photo,:prix_journalier)";
    execRequete($sql,array('id_vehicule'=>$id_vehicule,'id_agence'=>$id_agence,'titre'=>$titre,'marque'=>$marque,'modele'=>$modele,'description'=>$description,'photo'=>$_FILES['photo']['name'],'prix_journalier'=>$prix_journalier));
    
    

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
//s'il n'y a pas d'erreur alors $_FILES['nom_du_fichier']['error'] 
//vaut 0 
   extract($_POST); 
   if ((isset($_FILES['photo']['name'])&&($_FILES['photo']['error'] == UPLOAD_ERR_OK)) && $villeLocation>0) { 
      
       $sql = "INSERT INTO vehicule (id_agence,titre,marque,modele,description,photo,prix_journalier)
    VALUES (:id_agence,:titre,:marque,:modele,:description,:photo,:prix_journalier)";
       
    execRequete($sql,array('id_agence'=>$villeLocation,'titre'=>$titre,'marque'=>$marque,'modele'=>$modele,'description'=>$description,'photo'=>$_FILES['photo']['name'],'prix_journalier'=>$prix_journalier));
       
       $chemin_destination = 'inc/img/'; 
      //déplacement du fichier du répertoire temporaire (stocké 
      //par défaut) dans le répertoire de destination 
      move_uploaded_file($_FILES['photo']['tmp_name'], $chemin_destination.$_FILES['photo']['name']); 
      echo "Le fichier ".$_FILES['photo']['name']." a été copié dans le répertoire img"; 
   } 
   else { 
      echo "<p>Le fichier n'a pas pu être copié dans le répertoire img.</p>";
      echo "<p>Choisir agence!</p>";
   } 
}
 
}
    
    
       if(isset($_GET['Supid_vehicule'])){
//        
//        $verif_suppression="SELECT "   
           
           
           
        $supprimer="DELETE FROM vehicule WHERE id_vehicule=:id_vehicule AND NOT EXISTS(SELECT commande.id_vehicule FROM commande WHERE commande.id_vehicule=:id_vehicule)";
        $resultat=execRequete($supprimer,array('id_vehicule'=>$_GET['Supid_vehicule']));
           if($resultat->rowCount()==0){
           echo "<p>Véhicule en cours de location</p>";
       }
    }

 
        /* Affichage des vehicules*/
    
    $sql="SELECT vehicule.*,agences.titre AS titreAgence FROM vehicule INNER JOIN agences ON agences.id_agence=vehicule.id_agence";
    
    $resultat=execRequete($sql);
    if($resultat->rowCount()!=0){
        
        echo "<table  align='center'>

   <tr>
       <th>Véhicule</th>
       <th>Agence</th>
       <th>Titre</th>
       <th>Marque</th>
       <th>Modele</th>
       <th>Description</th>
       <th>Photo</th>
       <th>Prix</th>
       <th>Actions</th>
   </tr>";
            
        while($vehicule=$resultat->fetch(PDO::FETCH_ASSOC)) {
            
            echo" <tr>
                   <td>".$vehicule['id_vehicule']."</td>
                   <td>".$vehicule['titreAgence']."</td>
                   <td>".$vehicule['titre']."</td>
                   <td>".$vehicule['marque']."</td>
                   <td>".$vehicule['modele']."</td>
                   <td>".$vehicule['description']."</td>
                   <td><img src='inc/img/".$vehicule['photo']."' style ='width:200px'/></td>
                   <td>".$vehicule['prix_journalier']."</td>
                   <td><a href='gestionDeVehicule.php?id_vehicule=".$vehicule['id_vehicule']."'>Modifier</a>
                       <a href='gestionDeVehicule.php?Supid_vehicule=".$vehicule['id_vehicule']."'>Supprimer</a>
                   </td>
                </tr>";
        }
        
        echo "</table><br>";
}
   
    
    
    
    
    
    
    
    
    
    
    
    
    $sql="SELECT * FROM agences";
    $resultat_ville=execRequete($sql);
    
    if(!isset($_GET['id_vehicule'])){
    
    echo '<form action="gestionDeVehicule.php" method="POST" enctype="multipart/form-data">
     <fieldset><legend>Ajouter véhicule: </legend>
         <select id="ville" name="villeLocation">
         <option value="0">Choisir agence: </option>';
   
    while($agence=$resultat_ville->fetch(PDO::FETCH_ASSOC)){  
        echo'<option value="'.$agence["id_agence"].'">'.$agence["titre"].'</option>';
    }
    
    echo'</select>  
    <br><br>
      <input type="text" name="titre" placeholder="Titre véhicule" /><br><br>
      <input type="text" name="marque" placeholder="Marque véhicule" /><br><br>
      <input type="text" name="modele" placeholder="Modele véhicule" /><br><br>
      <input type="text" name="prix_journalier" placeholder="Prix Journalier" /><br>
      <p>Description: <br /><textarea name="description" rows="10" cols="50"></textarea></p> 
      <input type="hidden" name="MAX_FILE_SIZE" value="2097152"> 
      <p>Choisissez une photo avec une taille inférieure à 2 Mo.</p> 
      <input type="file" name="photo"> 
      <br /><br /> 
      <input type="submit" name="submit" value="Enregistrer"> 
      </fieldset>
    </form>'; 
    }
    
    else{
        // Préremplir le formulaire
        $sql="SELECT * FROM vehicule WHERE id_vehicule=:id_vehicule";
        $resultat=execRequete($sql,array('id_vehicule'=>$_GET['id_vehicule']));
        $vehicule=$resultat->fetch(PDO::FETCH_ASSOC);
         
        echo '<form action="gestionDeVehicule.php" method="POST" enctype="multipart/form-data"> 
         <select id="ville" name="villeLocation">
         <option value="0">Choisir agence: </option>';
   
    while($agence=$resultat_ville->fetch(PDO::FETCH_ASSOC)){  
        echo'<option value="'.$agence["id_agence"].'">'.$agence["titre"].'</option>';
    }
    
    echo'</select>  
    <br><br>
      <input type="text" name="titre" placeholder="Titre véhicule"   value="'.$vehicule["titre"].'" /><br><br>
      <input type="text" name="marque" placeholder="Marque véhicule" value="'.$vehicule["marque"].'"/><br><br>
      <input type="text" name="modele" placeholder="Modele véhicule" value="'.$vehicule["modele"].'" /><br><br>
      <input type="text" name="prix_journalier" placeholder="Prix Journalier" value="'.$vehicule["prix_journalier"].'"/><br>
      <p>Description: <br /><textarea name="description" rows="10" cols="50">'.$vehicule["description"].'</textarea></p> 
      <input type="hidden" name="MAX_FILE_SIZE" value="2097152"> 
      <p>Choisissez une photo avec une taille inférieure à 2 Mo.</p> 
      <input type="file" name="photo"> 
      <br /><br /> 
        <input type="hidden" name="id_agence"  value="'.$vehicule["id_agence"].'" >
       <input type="hidden" name="id_vehicule"  value="'.$vehicule["id_vehicule"].'" > 
      <input type="submit" name="submitMod" value="Enregistrer"> 
    </form>';
        
    }
  
    
    
    
    
    
    
    
}
?>


<?php
include "footer.php";
?>