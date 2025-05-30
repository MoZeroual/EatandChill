<?php
include "ini.php";
include "en_tete.php";
?>
   <form action="inscription.php" method="post" >

        <fieldset>
            <legend>Inscription</legend>
            
            <label for="membre">Votre identifiant: </label>
            <input id="membre" name="membre" placeholder="Votre identifiant" required><br>
            
            <label for="prenom">Votre prenom: </label>
            <input id="prenom" name="prenom" placeholder="Votre prenom" required><br>
            
            <label for="nom">Votre nom: </label>
            <input id="nom" name="nom" placeholder="Votre nom" required><br>
            
            <label for="mdp">Mot de passe: </label>
            <input id="mdp" type="password" name="mdp" placeholder="votre mot de passe" required><br>
            
            <label for="email">Email </label>
            <input id="email" type="email" name="courriel" placeholder="votre email" required pattern="^[a-zA-Z0-9._-]+@[a-zA-Z0-9._-]{2,}\.[a-zA-Z]{2,4}$"><br>
            <label for="civilite">Civilité </label>
            <select id="civilite" name="civilite">
            <option value="m">Homme</option>
            <option value="f">Femme</option>
            </select>       
        </fieldset>

        <input type="submit" name="submit" value="Inscription">          
    </form>



<?php
if(isset($_GET['inscription'])){
    
    echo"<p>Veuillez créez un compte pour pouvoir réserver</p>";
}


if(isset($_POST['submit'])){
    //var_dump($_POST);    
    
    // Crée les variables du $_POST
    extract($_POST);
    $statut=0;
//    $membre=$_POST['membre'];
//    $mdp=$_POST['mdp'];
//    $nom=$_POST['nom'];
//    $prenom=$_POST['prenom'];
//    $courriel=$_POST['courriel'];
//    $civilite=$_POST['civilite'];
    
    $verif_pseudo="SELECT * FROM membre WHERE pseudo=:membre";
    
    $resultat=execRequete($verif_pseudo,array('membre'=>$membre));
    
    if($resultat->rowCount()==0){
        
     echo "<h3>Vous etes maintenant membre</h3>";
        
    $sql = "INSERT INTO membre (pseudo,mdp,nom,prenom,email,civilite,statut,date_enregistrement )
    VALUES (:membre,:mdp,:nom,:prenom,:courriel,:civilite,:statut,NOW())";
    execRequete($sql,array('membre'=>$membre,'mdp'=>md5($mdp),'nom'=>$nom,'prenom'=>$prenom,'courriel'=>$courriel,'civilite'=>$civilite,'statut'=>$statut));
    
    }else{
        echo "<h3>Pseudo indisponible,Veuillez choisir un autre</h3";
    }
 
}



include "footer.php";
?>