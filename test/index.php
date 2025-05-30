<?php
include "ini.php";
include "en_tete.php";
?>


    <div align="center">
        <div class="contener_slideshow">
            <div class="contener_slide">
                <div class="slid_1"><img src="inc/img/location-voiture-pas-cher.jpg"></div>
                <div class="slid_2"><img src="inc/img/location-de-voiture.png"></div>
                <div class="slid_3"><img src="inc/img/98.png"></div>
                <div class="slid_4"><img src="inc/img/SUV.png"></div>
            </div>
        </div>
    </div>
  <form action="index.php" method="post" class="formulaire">

        <fieldset>
            <legend>Reservation: </legend>
            <label for="ville">Adresse de Départ </label>

            <select id="ville" name="id_agence">
            <option value="0">Choisir agence: </option>;
            <?php    
            $sql="SELECT * FROM agences";
            $resultat_ville=execRequete($sql);
            
            while($agence=$resultat_ville->fetch(PDO::FETCH_ASSOC)){  
                echo'<option value="'.$agence["id_agence"].'">'.$agence["titre"].'</option>';
            }      
    ?>   
            </select>  
            <br><br>

            <label for="debut">Départ: </label>
            <input id="debut" type="date" name="debutLocation" min="<?php echo date('Y-m-d'); ?>" value="<?php echo date('Y-m-d'); ?>" required>
            <br>
              <label for="debut">Heure de location</label>
            <input id="debut" type="time" name="debutLocationHeure" value="<?php echo date('H:i'); ?>" required>
            <br>
            
            <label for="fin">Retour: </label>
            <input id="fin" type="date" name="finLocation" value="<?php echo $dateRetour; ?>" required>
            <br>
            <label for="fin">Heure retour</label>
            <input id="fin" type="time" name="finLocationHeure"  min="" value="<?php echo date('H:i'); ?>" required>
            <br><br>
            <input type="submit" name="submit" value="Rechercher un véhicule"/>
             </fieldset>
            
        
    </form>
  
        <?php

                if(isset($_POST['submit'])){
                    
                    extract($_POST);
                    if($id_agence>0){
                        
                    $debutLocation.=" ".$debutLocationHeure;
                    $finLocation.=" ".$finLocationHeure;
                    
                    $debut = new DateTime($debutLocation);
                    $fin = new DateTime($finLocation);

                    $interval = $debut->diff($fin);
        
                   $totalHeureReservation=($interval->format('%R%d') * 24 ) + $interval->format('%R%h');
                    
                        //intval pour supprimer les chiffres apres la virgule
                        
                        $nbJour = $totalHeureReservation / 24;
                            if($totalHeureReservation%24>=2){    
                            $nbJour++;
                            }
                
                        
                        
                    if($totalHeureReservation <2){
                        
                        echo "<p>Veuillez entrez une date valide!<br>La durée de réservation doit être supérieur à 2 heures </p>";
                    }else{
                        // Traitement d'une recherche de reservation
                        

                         $sql="SELECT vehicule.*,agences.id_agence,agences.titre AS titreAgence FROM vehicule,agences WHERE vehicule.id_agence = :id_agence AND id_vehicule NOT IN 
             (SELECT id_vehicule FROM commande WHERE   (:date_depart  BETWEEN commande.date_heure_depart AND commande.date_heure_fin) 
                                                 OR    (:date_fin  BETWEEN commande.date_heure_depart and commande.date_heure_fin)
                                    ) AND vehicule.id_agence=agences.id_agence ORDER BY prix_journalier";
                     
                        
                        
                        $resultat=execRequete($sql,array('id_agence'=>$id_agence,'date_depart'=>$debutLocation,'date_fin'=>$finLocation));
                        
                            if($resultat->rowCount()){
                                
                                $nbVoiture=$resultat->rowCount();
                            
                            echo"<h3>Véhicules disponible le ".$debutLocation." jusqu'au ".$finLocation." ( ".$nbJour. " jour(s) )</h3><p>Résultat: $nbVoiture</p>";
                        }else {
                            echo "<h3>Pas de véhicules disponible le ". $debutLocation." jusqu'au ".$finLocation."</h3>";
                        }
                        while($voiture=$resultat->fetch(PDO::FETCH_ASSOC)){
                            
                            $prixTotal= intval($nbJour)*$voiture['prix_journalier'];
                            
                            echo'<div class="description">';
                            
                            
                            echo'<form action="reservation.php" method="post" >';
                            echo '<img src="inc/img/'.$voiture["photo"].'"/><br>';
                            echo $voiture['titre']."<br>";
                            echo $voiture['description']."<br>";
                            echo $voiture['prix_journalier']."€/jour - ";
                            echo $voiture['titreAgence']."<br>";
                            echo '<input type="hidden" name="debutLocation" value="'.$debutLocation.'" />';
                            echo '<input type="hidden" name="finLocation" value="'.$finLocation.'" />';
                            echo '<input type="hidden" name="prixTotal" value="'.$prixTotal.'" />';
                            echo '<input type="hidden" name="nbJour" value="'.$nbJour.'" />';   
                            echo '<input type="hidden" name="id_vehicule" value="'.$voiture["id_vehicule"].'" />';
                            echo '<input type="hidden" name="id_agence" value="'.$voiture['id_agence'].'" />';
                            echo '<input type="hidden" name="titreAgence" value="'.$voiture["titreAgence"].'" />';
                            echo '<input type="submit" name="rechercheReservation" value="Reserver" />';
                            echo '</form>';
                            echo'</div>';
                            
                        }
                    }
                    
                }else{
                      echo "<h3>Veuillez renseignez un nom d'agence</h3>";  
                    }
                }
                    else{
                    // Affichage par default
                        
                        
                    
                        $date_depart = date('Y-m-d H:i');
                        
                        $tomorrow = new DateTime('+1day');
                        
                        $date_fin=$tomorrow->format('Y-m-d H:i');
                        
                        // ASC : Croissant
                        // DESC: Decroissant
                   
                        if(!isset($_GET['croissant'])){
                            
                            echo '<a href="index.php?croissant">Prix croissant</a>';

                            
                         $sql="SELECT vehicule.*,agences.id_agence,agences.titre AS titreAgence FROM vehicule INNER JOIN agences ON vehicule.id_agence=agences.id_agence  WHERE id_vehicule NOT IN 
             (SELECT id_vehicule FROM commande WHERE   (:date_depart  BETWEEN commande.date_heure_depart AND commande.date_heure_fin) 
                                                 OR    (:date_fin  BETWEEN commande.date_heure_depart and commande.date_heure_fin)
                                                 ) ORDER BY  prix_journalier DESC";
                        }else{
                            echo '<a href="index.php?decroissant">Prix decroissant</a>';

                            
                         $sql="SELECT vehicule.*,agences.id_agence,agences.titre AS titreAgence FROM vehicule INNER JOIN agences ON vehicule.id_agence=agences.id_agence  WHERE id_vehicule NOT IN 
             (SELECT id_vehicule FROM commande WHERE   (:date_depart  BETWEEN commande.date_heure_depart AND commande.date_heure_fin) 
                                                 OR    (:date_fin  BETWEEN commande.date_heure_depart and commande.date_heure_fin)
                                                 ) ORDER BY  prix_journalier ASC";
                        }
                        
                        $resultat=execRequete($sql,array('date_depart'=>$date_depart,'date_fin'=>$date_fin));
                        
                    if($resultat->rowCount()){
                            $nbVoiture=$resultat->rowCount();
                            echo"<h3>Véhicules disponible le ". date('d-m-Y')." jusqu'au ".$tomorrow->format('d-m-Y')."</h3><p>Résultat: $nbVoiture</p>";
                        }else {
                            echo "<h3>Pas de véhicules disponible le ". date('d-m-Y')." jusqu'au ".$tomorrow->format('d-m-Y')."</h3>";
                        }
                        while($voiture=$resultat->fetch(PDO::FETCH_ASSOC)) {
                            
                            
                            
                            echo'<div class="description">';
                            
                            if(!isConnected()){
                            echo'<form action="inscription.php?inscription" method="post" >';
                            }else{
                                echo'<form action="reservation.php?fastReservation" method="post" >';
                            }
                            
                            echo'<img src="inc/img/'.$voiture["photo"].'"/>';
                            
                            
                            echo $voiture['titre']."<br>";
                            echo $voiture['description']."<br>";
                            echo $voiture['prix_journalier']."€/jour - ";
                            echo $voiture['titreAgence']."<br>";
                            
                            
                            echo '<input type="hidden" name="id_vehicule" value="'.$voiture["id_vehicule"].'" />';
                            echo '<input type="hidden" name="id_agence" value="'.$voiture['id_agence'].'" />';
                            echo '<input type="hidden" name="titreAgence" value="'.$voiture["titreAgence"].'" />';
                            echo '<input type="submit" name="reserverRapide" value="Reserver" />';
                            echo '</form>';
                            echo'</div>';
                            
                        }
                }



        ?>
      


  
<?php





include "footer.php";
?>