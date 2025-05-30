<?php

include 'header.php';
//print_r($_SESSION);
if ($_SERVER["REQUEST_METHOD"] === "POST") {
    require_once 'config.php'; // inclure la connexion à la BDD

    $pseudo=$_SESSION['pseudo'];
    $participants = (int)$_POST['participants'];
    $restaurant = trim($_POST['restaurant']);
    $ville = trim($_POST['ville']);
    $date_resa = $_POST['date'];
    $heure_resa = $_POST['heure'];

    if ($participants < 1 || $participants > 10) {
        echo "Erreur : nombre de participants invalide.";
        exit;
    }

    // Préparer et exécuter la requête d'insertion
    $sql = "INSERT INTO reservations (participants,pseudo, restaurant, ville,date_resa, heure_resa) VALUES (:participants,:pseudo, :restaurant, :ville, :date_resa, :heure_resa)";
    $stmt = $pdo->prepare($sql);
    $stmt->execute([
        ':participants' => $participants,
        ':pseudo' => $pseudo,
        ':restaurant' => htmlspecialchars($restaurant),
        ':ville' => htmlspecialchars($ville),
        ':date_resa' => $date_resa,
        ':heure_resa' => $heure_resa
    ]);

    echo "<h2>Organisation enregistrée avec succès !</h2>";
    echo "<p><strong>Nombre de participants :</strong> $participants</p>";
    echo "<p><strong>Restaurant :</strong> " . htmlspecialchars($restaurant) . "</p>";
    echo "<p><strong>Ville :</strong> " . htmlspecialchars($ville) . "</p>";
    echo "<p><strong>Date de reservation :</strong> " . $date_resa . "</p>";
    echo "<p><strong>Confirmation heure de reservation :</strong> " .$heure_resa . "</p>";
} else {
    echo "Accès non autorisé.";
}
?>


<?php
include_once 'footer.php';
?>

