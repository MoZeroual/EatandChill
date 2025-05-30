<?php
include "ini.php";
include "en_tete.php";
?>



<?php

if(isset($_GET['fastReservation'])){
    
                        extract($_POST);
                        $dateDepart = date('Y-m-d H:i');
                        $tomorrow = new DateTime('+1day');
                        
                        $dateFin=$tomorrow->format('Y-m-d H:i');
                        
                        $sql="SELECT * FROM vehicule WHERE id_vehicule=:id_vehicule";
    
                        $resultat=execRequete($sql,array('id_vehicule'=>$_POST['id_vehicule']));
                        echo"<h3>Réservation du ". date('d-m-Y')." jusqu'au ".$tomorrow->format('d-m-Y')."</h3>";
                        while($voiture=$resultat->fetch(PDO::FETCH_ASSOC)) {
                            
                            
                            echo'<div class="description">';
                            echo'<form action="reservation.php" method="post" >';
                            echo '<img src="inc/img/'.$voiture["photo"].'"/><br>';
                            echo $voiture['titre']."<br>";
                            echo $voiture['description']."<br>";
                            echo "Prix total: ".$voiture['prix_journalier']."€ - ";
                            echo $_POST['titreAgence']."<br>";
                            echo '<input type="hidden" name="idVehicule" value="'.$id_vehicule.'" />';
                            echo '<input type="hidden" name="idAgence" value="'.$id_agence.'" />';
                            echo '<input type="hidden" name="dateDepart" value="'.$dateDepart.'" />';
                            echo '<input type="hidden" name="dateFin" value="'.$dateFin.'" />';
                            echo '<input type="hidden" name="prixTotal" value="'.$voiture['prix_journalier'].'" />';
                            
                            echo '<input type="submit" name="validerFastReservation" value="Confirmer reservation" />';
                            echo '</form>';
                            echo'</div>';
                            
                        }
}

    if(isset($_POST['validerFastReservation'])){
        
        $id_membre=$_SESSION['id_membre'];
        extract($_POST);
        
        $sqlValidercommande="INSERT INTO commande (id_membre,id_vehicule,id_agence,date_heure_depart,date_heure_fin,prix_total,date_enregistrement)
        VALUES (:id_membre,:idVehicule,:idAgence,:dateDepart,:dateFin,:prixTotal,NOW())";
       
        execRequete($sqlValidercommande,array('id_membre'=>$id_membre,'idVehicule'=>$idVehicule,'idAgence'=>$idAgence,'dateDepart'=>$dateDepart,'dateFin'=>$dateFin,'prixTotal'=>$prixTotal));
        echo "<h3>Merci d'avoir reserver chez Nous! </h3>";
}


if(isset($_POST['rechercheReservation'])){
    
            extract($_POST);
                        
                        $sql="SELECT * FROM vehicule WHERE id_vehicule=:id_vehicule";
    
                        $resultat=execRequete($sql,array('id_vehicule'=>$_POST['id_vehicule']));
                        echo"<h3>Réservation du ". $debutLocation." jusqu'au ".$finLocation." ( ".intval($nbJour). " jour(s) )</h3>";
                        while($voiture=$resultat->fetch(PDO::FETCH_ASSOC)) {
                            
                            
                            echo'<div class="description">';
                            echo'<form action="reservation.php" method="post" >';
                            echo '<img src="inc/img/'.$voiture["photo"].'"/><br>';
                            echo $voiture['titre']."<br>";
                            echo $voiture['description']."<br>";
                            echo "Prix total: ".$prixTotal."€ - ";
                            echo $titreAgence."<br>";
                            echo '<input type="hidden" name="idVehicule" value="'.$id_vehicule.'" />';
                            echo '<input type="hidden" name="idAgence" value="'.$id_agence.'" />';
                            echo '<input type="hidden" name="dateDepart" value="'.$debutLocation.'" />';
                            echo '<input type="hidden" name="dateFin" value="'.$finLocation.'" />';
                            echo '<input type="hidden" name="prixTotal" value="'.$prixTotal.'" />';
                            
                            echo '<input type="submit" name="validerRechercheReservation" value="Confirmer reservation" />';
                            echo '</form>';
                            echo'</div>';
                            
                        }
    
}

    if(isset($_POST['validerRechercheReservation'])){
        
        $id_membre=$_SESSION['id_membre'];
        extract($_POST);
        
        $sqlValidercommande="INSERT INTO commande (id_membre,id_vehicule,id_agence,date_heure_depart,date_heure_fin,prix_total,date_enregistrement)
        VALUES (:id_membre,:idVehicule,:idAgence,:dateDepart,:dateFin,:prixTotal,NOW())";
       
        execRequete($sqlValidercommande,array('id_membre'=>$id_membre,'idVehicule'=>$idVehicule,'idAgence'=>$idAgence,'dateDepart'=>$dateDepart,'dateFin'=>$dateFin,'prixTotal'=>$prixTotal));
        echo "<h3>Merci d'avoir reserver chez Nous! </h3>";
}
                                              

?>



























<?php
include "footer.php";
?>