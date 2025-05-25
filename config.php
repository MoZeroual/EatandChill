
// fichier de connexion a la base de donnee

<?php
// config.php

$host = 'mysql-eatandchill.alwaysdata.net'; // hote SQL
$dbname = 'eatandchill_bd';         // nom de la base
$username = '414826';                  // identifiant utilisateur de la base
$password = 'Stifler2010!';          // mdp

try {
    $pdo = new PDO("mysql:host=$host;dbname=$dbname;charset=utf8", $username, $password);
    $pdo->setAttribute(PDO::ATTR_ERRMODE, PDO::ERRMODE_EXCEPTION);

    // message succes si le connexion est etablie
    echo "<h2 style='color: green;'>✅ Connexion réussie à la base de données !</h2>";
} catch (PDOException $e) {
    // Si une erreur se produit, on l'affiche
    echo "<h2 style='color: red;'>❌ Erreur de connexion : " . $e->getMessage() . "</h2>";
}
?>

