<?php
include "ini.php";
include "en_tete.php"; 


if($_SESSION['statut']==1 && isConnected()){
    echo "<h3>DASHBORD Administrateur</h3>";
    echo '<h3><a href="gestionDeVehicule.php">Gestion de Véhicule</a></h3>';
    echo '<h3><a href="gestionDesAgences.php">Gestion des Agences</a></h3>';
    echo '<h3><a href="gestionDesMembres.php">Gestion des membres</a></h3>';
    echo '<h3><a href="gestionDesCommandes.php">Gestion des Commandes</a></h3>';
    
}

if($_SESSION['statut']==0 && isConnected()){
    
    echo "<h3>Espace Membre</h3>";
    
    $sql="SELECT commande.id_commande, membre.nom, membre.prenom, membre.email, vehicule.titre AS titreVehicule, agences.titre AS titreAgence,commande.date_heure_depart,commande.date_heure_fin,commande.prix_total,commande.date_enregistrement FROM commande INNER JOIN membre ON commande.id_membre = membre.id_membre INNER JOIN vehicule ON commande.id_vehicule = vehicule.id_vehicule INNER JOIN agences ON commande.id_agence = agences.id_agence WHERE commande.id_membre=:id_membre";
    
    
    $resultat=execRequete($sql,array('id_membre' => $_SESSION['id_membre']));
       
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
                   
                </tr>";
        }
        
        echo "</table>";
}
}



?>
  
<?php
include "footer.php";
?>