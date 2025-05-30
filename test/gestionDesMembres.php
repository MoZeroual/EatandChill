<?php
include "ini.php";
include "en_tete.php"; 
/*
<?= $_POST['pseudo']?? '' ?>
*/

if($_SESSION['statut']==1 && isConnected()){
    echo "<h3><a href='gestionDesMembres.php'>DASHBORD: Gestion des Membres</a></h3>";

    

    
if(isset($_POST['submitMod'])){
 
    extract($_POST);
        
     echo "<h3>Membre modifié!</h3>";
        
    $sql = "REPLACE INTO membre (id_membre,pseudo,mdp,nom,prenom,email,civilite,statut,date_enregistrement )
    VALUES (:id_membre,:pseudo,:mdp,:nom,:prenom,:email,:civilite,:statut,NOW())";
    execRequete($sql,array('id_membre'=>$id_membre,'pseudo'=>$pseudo,'mdp'=>md5($mdp),'nom'=>$nom,'prenom'=>$prenom,'email'=>$email,'civilite'=>$civilite,'statut'=>$statut));
    
   

}
    
    
if(isset($_POST['submit'])){
 
    extract($_POST);
    
    $verif_pseudo="SELECT * FROM membre WHERE pseudo=:pseudo";
    
    $resultat=execRequete($verif_pseudo,array('pseudo'=>$pseudo));
    
    if($resultat->rowCount()==0){
        
     echo "Membre ajouté!</h3>";
        
    $sql = "INSERT INTO membre (pseudo,mdp,nom,prenom,email,civilite,statut,date_enregistrement )
    VALUES (:pseudo,:mdp,:nom,:prenom,:email,:civilite,:statut,NOW())";
    execRequete($sql,array('pseudo'=>$pseudo,'mdp'=>md5($mdp),'nom'=>$nom,'prenom'=>$prenom,'email'=>$email,'civilite'=>$civilite,'statut'=>$statut));
    
    }else{
        echo "<h3>Pseudo indisponible,Veuillez choisir un autre!</h3>";
    }

   
}

    if(isset($_GET['Supid_membre'])){
        
        $supprimer="DELETE FROM membre WHERE id_membre=:id_membre AND NOT EXISTS(SELECT commande.id_membre FROM commande WHERE commande.id_membre=:id_membre)";
       $resultat=execRequete($supprimer,array('id_membre'=>$_GET['Supid_membre']));
        if($resultat->rowCount()==0){
           echo "<p>Ce membre à une location en cours</p>";
       }
    }
              $sql="SELECT * FROM membre";
    
    $resultat=execRequete($sql);
    if($resultat->rowCount()!=0){
        
        echo "<table align='center'>

   <tr>
       <th>Id Membre</th>
       <th>Pseudo</th>
       <th>Nom</th>
       <th>Prenom</th>
       <th>Email</th>
       <th>Civilite</th>
       <th>Statut</th>
       <th>Date_enregistrement</th>
       <th>Actions</th>
   </tr>";
            
        while($membre=$resultat->fetch(PDO::FETCH_ASSOC)) {
            
            echo" <tr>
                   <td>".$membre['id_membre']."</td>
                   <td>".$membre['pseudo']."</td>
                   <td>".$membre['nom']."</td>
                   <td>".$membre['prenom']."</td>
                   <td>".$membre['email']."</td>
                   <td>".$membre['civilite']."</td>
                   <td>".$membre['statut']."</td>
                   <td>".$membre['date_enregistrement']."</td>
                   <td><a href='gestionDesMembres.php?id_membre=".$membre['id_membre']."'>Modifier</a>
                       <a href='gestionDesMembres.php?Supid_membre=".$membre['id_membre']."'>Supprimer</a>
                   </td>
                </tr>";
        }
        
        echo "</table><br>";
}
  if(!isset($_GET['id_membre'])){
    

      echo '<form action="gestionDesMembres.php" method="POST"> 
            <fieldset><legend>Ajouter membre: </legend>
          <input type="text" name="pseudo" placeholder="Pseudo"/><br><br>

          <input type="password" name="mdp" placeholder="Mot de passe"/><br><br>

          <input type="text" name="nom" placeholder="Nom" /><br><br>

          <input type="text" name="prenom" placeholder="Prenom" /><br><br>

          <input type="text" name="email" placeholder="Email" /><br><br>

          <label for="civilite">Civilité </label>
                <select id="civilite" name="civilite">
                <option value="m">Homme</option>
                <option value="f">Femme</option>
                </select>
        <br><br>
            <label for="statut">Statut: </label>
            <select id="statut" name="statut">
            <option value="0">Membre</option>
            <option value="1">Admin</option>
            </select> <br><br>      

          <input type="submit" name="submit" value="Enregistrer">
          </fieldset>
        </form>';
        }else{
        
         $sql="SELECT * FROM membre WHERE id_membre=:id_membre";
         $resultat=execRequete($sql,array('id_membre'=>$_GET['id_membre']));
         $membre=$resultat->fetch(PDO::FETCH_ASSOC);
    
            
           echo '<form action="gestionDesMembres.php" method="POST"> 
            <fieldset><legend>Modifier membre: </legend>
          <input type="text" name="pseudo" placeholder="Pseudo" value="'.$membre["pseudo"].'"><br><br>

          <input type="password" name="mdp" placeholder="Mot de passe"><br><br>

          <input type="text" name="nom" placeholder="Nom" value="'.$membre["nom"].'" ><br><br>

          <input type="text" name="prenom" placeholder="Prenom" value="'.$membre["prenom"].'" ><br><br>
          
          
          <input type="text" name="email" placeholder="Email" value="'.$membre["email"].'" ><br><br>
           
          <label for="civilite">Civilité </label>'; ?>
          
                <select id="civilite" name="civilite">        
                <option value="m" <?= $membre['civilite']=='m' ? 'selected':''  ?> >Homme</option>
                <option value="f"  <?= $membre['civilite']=='f' ? 'selected':''  ?> >Femme</option>
          <?php
          echo'</select>
        <br><br>
            <label for="statut">Statut: </label>
            <select id="statut" name="statut">';?>
            
            <option value="0" <?= ($membre['statut']=='0') ? 'selected':''  ?>  >Membre</option>
            <option value="1" <?= ($membre['statut']=='1') ? 'selected':''  ?> >Admin</option>
        
        <?php
           echo' </select> <br><br>      
          <input type="hidden" name="id_membre"  value="'.$membre["id_membre"].'" >
          <input type="submit" name="submitMod" value="Enregistrer"> 
          </fieldset>
        </form>';
        
    }
}



?>
<?php
include "footer.php";
?>