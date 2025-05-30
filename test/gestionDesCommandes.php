<?php
include "ini.php";
include "en_tete.php"; 

/*

$debut=new DateTime("");
$fin=new DateTime("");

$interval=$debut->diff($fin);
echo ($interval->format("%d")*24)+$interval->format("%h"):



*/



if($_SESSION['statut']==1 && isConnected()){
    echo "<h3><a href='gestionDesCommandes.php'>DASHBORD: Gestion des Commandes</a></h3>";
    
    
      
    if(isset($_POST['submit'])){
       
        extract($_POST);
        
        $sql = "UPDATE commande SET date_heure_depart=:date_heure_depart,date_heure_fin=:date_heure_fin,prix_total=:prix_total WHERE id_commande=:id_commande";
        
        $dateDepart=$debutLocation." ".$debutLocationHeure;
        
        $dateRetour=$finLocation." ".$finLocationHeure;
        
            
        execRequete($sql,array('id_commande'=>$id_commande,'date_heure_depart'=>$dateDepart,'date_heure_fin'=>$dateRetour,'prix_total'=>$prix_total));
     
        
    }
    
     if(isset($_GET['Supid_commande'])){
        
        $supprimer="DELETE FROM commande WHERE id_commande=:id_commande";
        execRequete($supprimer,array('id_commande'=>$_GET['Supid_commande']));
    }
            
    $sql="SELECT commande.id_commande, membre.nom, membre.prenom, membre.email, vehicule.titre AS titreVehicule, agences.titre AS titreAgence,commande.date_heure_depart,commande.date_heure_fin,commande.prix_total,commande.date_enregistrement FROM commande INNER JOIN membre ON commande.id_membre = membre.id_membre INNER JOIN vehicule ON commande.id_vehicule = vehicule.id_vehicule INNER JOIN agences ON commande.id_agence = agences.id_agence";
    
  
    $resultat=execRequete($sql);
       
    if($resultat->rowCount()!=0){
        
        echo "<table>

   <tr>
       <th>Commande</th>
       <th>Membre</th>
       <th>Vehicule</th>
       <th>Agence</th>
       <th>Date et heure de départ</th>
       <th>Date et heure de fin</th>
       <th>Prix total</th>
       <th>Date et heure enregistrement</th>
       <th>Actions</th>
   </tr>";
            
        while($commande=$resultat->fetch(PDO::FETCH_ASSOC)) {
            
            echo" <tr>
                   <td>".$commande['id_commande']."</td>
                   <td>- ".$commande['nom']." ".$commande['prenom']." -<br>".$commande['email']."</td>
                   <td>".$commande['titreVehicule']."</td>
                   <td>".$commande['titreAgence']."</td>
                   <td>".$commande['date_heure_depart']."</td>
                   <td>".$commande['date_heure_fin']."</td>
                   <td>".$commande['prix_total']."</td>
                   <td>".$commande['date_enregistrement']."</td>
                   <td><a href='gestionDesCommandes.php?id_commande=".$commande['id_commande']."'>Modifier</a>
                       <a href='gestionDesCommandes.php?Supid_commande=".$commande['id_commande']."'>Supprimer</a>
                   </td>
                </tr>";
        }
        
        echo "</table>";
    }
  
    if(isset($_GET['id_commande'])){
        
        
        $sql= "SELECT * FROM commande WHERE id_commande=:id_commande";
        $resultat=execRequete($sql,array('id_commande'=>$_GET['id_commande']));
         $commande=$resultat->fetch(PDO::FETCH_ASSOC);
        
         $depart=explode(" ",$commande['date_heure_depart']);
         $retour=explode(" ",$commande['date_heure_fin']);
          
        
      echo' <form action="gestionDesCommandes.php" method="post" >

        <fieldset>
            <legend>Reservation: </legend>
           

            <label for="debut">Départ: </label>
            <input id="debut" type="date" name="debutLocation" value="'.$depart[0].'" required>
            <br>
              <label for="debut">Heure de location</label>
            <input id="debut" type="time" name="debutLocationHeure" value="'.substr($depart[1],0,5).'" required>
            <br>
            
            <label for="fin">Retour: </label>
            <input id="fin" type="date" name="finLocation" value="'.$retour[0].'" required>
            <br>
            <label for="fin">Heure retour</label>
            <input id="fin" type="time" name="finLocationHeure" value="'.substr($retour[1],0,5).'"  required>
            <br>
            <label for"prix">Prix total</label>
            <input id="prix type="text" name="prix_total" value="'.$commande["prix_total"].'"  required/>
            <br><br>
            <input type="hidden" name="id_commande" value="'.$_GET['id_commande'].'"/>
            <input type="submit" name="submit" value="Modifier"/>
             </fieldset>
      
    </form>';
        
    }
    
    
    
    
    
}
    
 


?>
  
<?php
include "footer.php";
?>