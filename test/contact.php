

    <?php
        include "ini.php";
        include "en_tete.php";
?>
   <div align="center">
        <h1>Notre agence:</h1>
    <iframe src="https://www.google.com/maps/embed?pb=!1m18!1m12!1m3!1d2631
        .8887293473745!2d2.252456515659925!3d48.72671777927482!2m3!1f0!2f0
        !3f0!3m2!1i1024!2i768!4f13.1!3m3!1m2!1s0x47e6783aa15ce7ef%3A0xc5cd
        950a7833446d!2sAvenue+Georges+Clemenceau%2C+91300+Massy!5e0!3m2!1sfr!2sfr!4v1545581588198"
         width="600" height="450" frameborder="0" style="border:0" allowfullscreen></iframe>
    </div>
    <form action="contact.php" method="post" >
        
        <fieldset>
            <legend>Contact</legend>
            <label for="nom">Nom: </label>

            <input id="nom" name="nom" placeholder="Votre nom" required=""><br>
            <label for="telephone">Portable</label>
            <input id="telephone" type="tel" name="tel" placeholder="Votre numero" pattern="0[6-7]{1}[0-9]{8}" required><br>
            <label for="email">Email </label>
            <input id="email" type="email" name="courriel" placeholder="votre email" required pattern="^[a-zA-Z0-9._-]+@[a-zA-Z0-9._-]{2,}\.[a-zA-Z]{2,4}$"><br>
            
        </fieldset>

        <fieldset>
            <legend>Fiche renseignement</legend>

            
            <br>
            <label for="comments">Votre message: </label>
            <textarea id="comments"name="comments" placeholder="Donnez le plus d'informations" autofocus required></textarea>
        </fieldset>
        <input type="submit" name="submit" value="Envoyer"> <?php
         if(isset($_POST['submit'])){
            echo "Message envoyé à: ";
             
            $header="Message de ".$_POST['nom'];          
            $site="Date message ";
            $_POST['comments'].=" Portable: ".$_POST['tel']." Email: ".$_POST['courriel'];
            mail("samy.liani@gmail.com",$site,$_POST['comments'],$header);
            } ?>
    </form>


    <?php
    include "footer.php";
    ?>