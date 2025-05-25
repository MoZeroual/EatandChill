<?php

require_once 'config.php';

$civilite = $_POST['civilite'] ?? '';
$nomComplet = $_POST['nomComplet'] ?? '';
$pseudo = $_POST['pseudo'] ?? '';
$telephone = $_POST['telephone'] ?? '';
$email = $_POST['email'] ?? '';
$ville = $_POST['ville'] ?? '';
$annee = $_POST['annee'] ?? '';
$motdepasse = $_POST['motdepasse'] ?? '';

// Hachage du mot de passe
$motdepasse_hache = password_hash($motdepasse, PASSWORD_DEFAULT);

try {
    //requete pour verifier si l'email exist deja
     $sqlCheck = "SELECT email FROM users WHERE email = :email";
    $stmtCheck = $pdo->prepare($sqlCheck);
    $stmtCheck->execute([':email' => $email]);
    if ($stmtCheck->rowCount() > 0) {
        header("Location: inscription.php?erreur=Email déjà utilisé");
    } else {
    
    // Préparation de la requête
    $stmt = $pdo->prepare("INSERT INTO users (nom, pseudo, telephone, email, motdepasse, ville, annee, civilite)
                           VALUES (:nomComplet, :pseudo, :telephone, :email, :motdepasse, :ville, :annee, :civilite)");

    // Exécution
    $stmt->execute([
        ':nomComplet' => $nomComplet,
        ':pseudo' => $pseudo,
        ':telephone' => $telephone,
        ':email' => $email,
        ':motdepasse' => $motdepasse_hache,
        ':ville' => $ville,
        ':annee' => $annee,
        ':civilite' => $civilite
        
    ]);

    header("Location: connexion.php");
}

} catch (Exception $e) {
    echo "<p style='color: red; text-align: center;'>Erreur lors de l'inscription : " . $e->getMessage() . "</p>";
}

?>