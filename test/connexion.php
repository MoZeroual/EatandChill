<?php
include "ini.php";
if(isset($_POST['submit'])){
       extract($_POST);
    
    $sql="SELECT * FROM membre WHERE pseudo=:membre AND mdp=:mdp";
    
    $resultat=execRequete($sql,array('membre'=>$membre,'mdp'=>md5($mdp)));
    
    if($resultat->rowCount()>0){
        // On crée les variables globales statut et membre
        $_SESSION['membre']=$membre;
        $sql="SELECT * FROM membre WHERE pseudo=:membre";
         
        $resultat=execRequete($sql,array('membre'=>$membre));
        
        while($res = $resultat->fetch()) {
            $_SESSION['statut']=$res['statut'];
            $_SESSION['id_membre']=$res['id_membre'];
           
        }
        header('Location: index.php?bvn');
        exit();
    }else{
        echo "<h3>Pseudo ou mot de passe incorrect</h3>";
    }
          
}
?>

<?php
include "en_tete.php";
?>

   <form action="connexion.php" method="post" >

        <fieldset>
            <legend>Connexion</legend>
            <label for="membre">Votre identifiant: </label>
            <input id="membre" name="membre" placeholder="Votre identifiant" required><br>
            
            <label for="mdp">Mot de passe: </label>
            <input id="mdp" type="password" name="mdp" placeholder="votre mot de passe" required><br>
            
        </fieldset>

        
        <input type="submit" name="submit" value="Connexion">          
    </form>



<?php
include "footer.php";
?>