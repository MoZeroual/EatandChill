<?php

//include "ini.php";
if(isset($_GET['logout'])){
    $membre=$_SESSION['membre'];
    session_unset(); 
    session_destroy();
}
?>

<!DOCTYPE html>
<html lang="fr">

<head>
    <title>Veville</title>
    <meta charset="utf-8">
    <meta name="Description" content="Site de location de voiture" />
    <meta name="viewport" content="width=device-width, initial-scale=1" />
    <meta name="keywords" lang="fr" content="location de voiture,location,voiture" />
    
    <link href="https://fonts.googleapis.com/css?family=Lobster" rel="stylesheet">
    <meta name="google-site-verification" content="NS5QWtChL9OzqrVKMcLYfZ8qZqzhyLdy4qyEFA_DwNg" />
    <link rel="shortcut icon" type="image/x-icon" href="inc/img/ico.png" />
<!--    <link rel="stylesheet" href="https://stackpath.bootstrapcdn.com/bootstrap/4.2.1/css/bootstrap.min.css" >-->
    <script src="https://code.jquery.com/jquery-3.3.1.min.js"></script>
    <script src="https://cdnjs.cloudflare.com/ajax/libs/popper.js/1.14.6/umd/popper.min.js" ></script>
    <script src="https://stackpath.bootstrapcdn.com/bootstrap/4.2.1/js/bootstrap.min.js" ></script>
    <link rel="stylesheet" href="inc/css/style.css">

</head>

<body>
    <header>
        <div class="logo">
            <a href="index.php"><img src="inc/img/vehicule.png" alt="logo de voiture" /></a>
            <h1 class="slogan"><a href="index.php">Bienvenue à bord location de voiture 24h/24 7j/7</a></h1>
            
            <?php 
            setlocale(LC_TIME, 'fr_FR.UTF-8');
            date_default_timezone_set('Europe/Paris');
           
                /*
                $timestamp = strtotime(date('Y-m-d')) + 3600*24;
                $time = date('Y-m-d', $timestamp);
               
                 $tomorrow = new DateTime('+1day'); 
                 $dateRetour=$tomorrow->format('Y-m-d');
            
            echo"<p>Nous sommes: ".utf8_encode(strftime('%A %d %B %Y'))."</p>";
 */
            $formatter = new \IntlDateFormatter(
                'fr_FR', // locale
                \IntlDateFormatter::FULL,
                \IntlDateFormatter::NONE,
                'Europe/Paris',
                \IntlDateFormatter::GREGORIAN,
                "EEEE d MMMM yyyy"
            );
            
            echo"<p>". $formatter->format(new \DateTime());"</p>"
            ?>

        </div>
    </header>
<?php
    
    if(!isset($_SESSION['membre']))
			{
        echo '<nav>
        <ul id="menu1">

            <li><a href="inscription.php">S\'inscrire</a>
                
            </li>
            
            <li><a href="connexion.php">Se connecter</a>

            </li>
            <li><a href="contact.php">Contactez-nous</a>

            </li>

        </ul>
    </nav>';
    }
     if(isset($_SESSION['membre']))
			{
	           $membre=$_SESSION['membre'];
        echo '<nav>
        <ul id="menu1">

            <li><a href="monCompte.php">Mon compte</a>
                
            </li>
            <li><a href="contact.php">Contactez-nous</a>

            </li>
            <li><a href="index.php?logout">Deconnexion</a>

            </li>

        </ul>
    </nav>';
    }
    if(isset($_GET['logout'])){
    echo "Au revoir ".$membre;
}
       if(isset($_GET['bvn'])){
           echo "Bienvenue ". $_SESSION['membre'];
       }
    ?>
  
    