<?php
$showAccueil = false;  // Ne PAS afficher la section d’accueil sur cette page
include 'header.php';

// Fonction d'affichage de la page d'accueil avec présentation
function afficherAccueil() {
    echo '    
    <div class="accueil">
        <h1>Bienvenue sur <span style="color: #e74c3c;">Eat & Chill</span> !</h1>
        
        <p style="font-size: 18px; max-width: 800px; margin: 20px auto;">
            Marre de manger seul ? Besoin de faire de nouvelles rencontres autour d\'un bon plat ou d\'un verre ? <strong>Eat & Chill</strong> est une plateforme conviviale qui te permet 
            d\'<strong>organiser</strong> ou de <strong>participer</strong> à des sorties dans des <em>restaurants</em>, <em>bars</em> ou <em>fast-foods</em> à proximité. Viens passer un moment d’échange et de bonne humeur avec d’autres adultes !
        </p>

        <div style="display: flex; justify-content: center; flex-wrap: wrap; gap: 30px; margin: 30px auto; max-width: 1000px;">
            <div>
                <img src="img/bonheur-repas-partages.jpg" alt="Sortie restaurant" style="width:300px; border-radius: 15px;">
                <p style="text-align:center; margin-top: 10px;">Repas entre inconnus devenus amis</p>
            </div>
            <div>
                <img src="img/repas-animé-animation-repas-assis-1024x703.jpg" alt="Soirée bar" style="width:300px; border-radius: 15px;">
                <p style="text-align:center; margin-top: 10px;">Détente autour d\'un verre</p>
            </div>
            <div>
                <img src="img/Repas-Convivial-entre-Amis-Facile-qq8q2c3l66v9tjoxh9ftvxit4k2fpq1dpcuehlcx1c.jpg" alt="Fast-food entre amis" style="width:300px; border-radius: 15px;">
                <p style="text-align:center; margin-top: 10px;">Moment chill au fast-food</p>
            </div>
        </div>

        <h2 style="text-align:center; margin-top: 40px;">Comment ça marche ?</h2>
        <ul style="max-width: 700px; margin: 20px auto; font-size: 17px; line-height: 1.6;">
            <li>🌟 <strong>Inscris-toi</strong> gratuitement sur la plateforme</li>
            <li>🍽️ <strong>Propose</strong> une sortie ou <strong>rejoint</strong> un événement existant</li>
            <li>🥂 <strong>Partage</strong> un bon moment avec des adultes comme toi, autour d\'un repas ou d\'un verre</li>
            <li>✨ <strong>Fais des rencontres</strong> authentiques dans une ambiance chaleureuse</li>
        </ul>

        <p style="text-align:center; font-size: 18px; margin-top: 30px;">Manger seul ? C\'est fini. Avec Eat & Chill, tu choisis la convivialité !</p>';

    // Affichage du bouton d'inscription si $showAccueil est vrai ou non défini
    global $showAccueil;
    if (!isset($showAccueil) || $showAccueil === true) {
        echo '
        <div style="text-align:center; margin-top: 40px;">
            <a href="inscription.php" style="background-color: #e74c3c; color: white; padding: 15px 30px; border-radius: 8px; font-size: 18px; text-decoration: none;">
                Je m\'inscris maintenant
            </a>
        </div><br/><br/>
        ';
    }

    echo '</div>'; // fermeture div accueil
}



// Contrôle du flux de la page
if (isConnected() && !isset($_GET['logout'])) {
    afficherAccueil();
    include_once 'footer.php';

} elseif (isset($_GET['logout'])) {
    isLogout();
    // Ici, isLogout() fait une redirection donc code après ne sera pas exécuté, mais sinon :
    // afficherAccueil();
    // include_once 'footer.php';

} else {
    afficherAccueil();
    include_once 'footer.php';
}
?>
