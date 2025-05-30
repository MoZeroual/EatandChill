<?php
include("ini.php");

if (session_status() === PHP_SESSION_NONE) {
    session_start();
}

// Définition de $showInscription avant le HTML
if (!isset($_SESSION['pseudo']) || empty($_SESSION['pseudo'])) {
    $showInscription = false;
} else {
    $showInscription = true;
}
?>

<!DOCTYPE html>
<html lang="fr">
<head>
    <meta charset="UTF-8" />
    <meta name="viewport" content="width=device-width, initial-scale=1" />
    <title>EAT & CHILL</title>
    <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.3/dist/css/bootstrap.min.css" rel="stylesheet">
    <link rel="stylesheet" href="style.css" />
</head>
<body>

<!-- ✅ NAVBAR Bootstrap -->
<nav class="navbar navbar-expand-lg navbar-dark" style="background-color: #0b3d0b;">
    <div class="container-fluid">
        <a class="navbar-brand fw-bold" href="#">Eat & Chill</a>
        <button class="navbar-toggler" type="button" data-bs-toggle="collapse" data-bs-target="#navbarNav">
            <span class="navbar-toggler-icon"></span>
        </button>

        <div class="collapse navbar-collapse justify-content-end" id="navbarNav">
            <ul class="navbar-nav">
                <li class="nav-item">
                    <a class="nav-link" href="index.php">Accueil</a>
                </li>

                <?php if (empty($_SESSION['pseudo']) || isset($_GET['logout'])): ?>
                    <li class="nav-item">
                        <a class="nav-link" href="contact.php">Contact</a>
                    </li>
                    <li class="nav-item">
                        <a class="nav-link" href="connexion.php">Connexion</a>
                    </li>
                    <li class="nav-item">
                        <a class="nav-link" href="inscription.php">Inscription</a>
                    </li>
                <?php else: ?>
                    <li class="nav-item">
                        <a class="nav-link" href="mesrestos.php">Mes restos</a>
                    </li>
                    <li class="nav-item">
                        <a class="nav-link" href="contact.php">Contact</a>
                    </li>
                    <li class="nav-item">
                        <a class="nav-link" href="index.php?logout">Déconnexion</a>
                    </li>
                <?php endif; ?>
            </ul>
        </div>
    </div>
</nav>

<!-- ✅ Message de déconnexion -->
<?php if (isset($_GET['logout'])): ?>
    <div class="container text-center mt-4">
        <h3 class="text-success">À bientôt et au plaisir de te revoir !</h3>
    </div>
<?php endif; ?>

<!-- ✅ Section d’accueil (affichée sauf si $showAccueil est false) -->
<?php if (!isset($showAccueil) || $showAccueil === true): ?>
<main class="concept container mt-5">
    <section class="text-center">
        <h1 class="mb-4">Bienvenue sur <strong>Eat & Chill</strong></h1>
        <p class="lead">Eat & Chill est le premier réseau qui connecte des inconnus autour d’un repas convivial dans des restaurants soigneusement sélectionnés en Île-de-France.</p>
        <p class="mb-4">Que tu sois nouveau en ville ou simplement curieux, viens partager un bon moment, découvrir de nouvelles personnes et te régaler sans rester seul.</p>
        
       <?php if (!isset($showInscription) || $showInscription === true): ?>
         <a href="inscription.php" class="btn btn-success btn-lg">Rejoins l'aventure !</a>
        
        <?php endif; ?>
            
    </section>
</main>
<?php endif; ?>

<!-- ✅ Bootstrap JS -->
<script src="https://cdn.jsdelivr.net/npm/bootstrap@5.3.3/dist/js/bootstrap.bundle.min.js"></script>
</body>
</html>
