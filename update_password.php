<?php 
include 'header.php';
require_once 'config.php'; // Connexion à la base de données

if ($_SERVER["REQUEST_METHOD"] === "POST") {
    $token     = trim($_POST["token"] ?? '');
    $password  = trim($_POST["password"] ?? '');
    $telephone = trim($_POST["telephone"] ?? '');

    if (strlen($password) >= 6) {
        try {
            // Requête conditionnelle : avec ou sans téléphone
            if (!empty($telephone)) {
                $stmt = $pdo->prepare("
                    SELECT * FROM users 
                    WHERE reset_token = ? 
                    AND telephone = ? 
                    AND reset_expires >= NOW()
                ");
                $stmt->execute([$token, $telephone]);
            } else {
                $stmt = $pdo->prepare("
                    SELECT * FROM users 
                    WHERE reset_token = ? 
                    AND reset_expires >= NOW()
                ");
                $stmt->execute([$token]);
            }

            $user = $stmt->fetch();

            if ($user) {
                $ancienHash  = $user['motdepasse'];
                $nouveauHash = password_hash($password, PASSWORD_DEFAULT);

                if (password_verify($password, $ancienHash)) {
                    echo "<p style='color:orange;'>⚠️ Le nouveau mot de passe est identique à l'ancien.</p>";
                } else {
                    $update = $pdo->prepare("
                        UPDATE users 
                        SET motdepasse = ?, reset_token = NULL, reset_expires = NULL 
                        WHERE idu = ?
                    ");
                    $update->execute([$nouveauHash, $user['idu']]);

                    if ($update->rowCount() > 0) {
                        
                        echo'<div class="container">';
                        
                        echo "<p style='color:green;'>✅ Mot de passe mis à jour avec succès.</p>";
                        echo "<p><a href='connexion.php'>Se connecter</a></p>";
                        
                        echo "</div>";

                    } else {
                        echo "<p style='color:red;'>❌ La mise à jour du mot de passe a échoué.</p>";
                    }
                }
            } else {
                echo "<p style='color:red;'>❌ Aucun compte trouvé avec ce lien ou téléphone, ou lien expiré.</p>";
            }
        } catch (Exception $e) {
            echo "<p style='color:red;'>Erreur : " . $e->getMessage() . "</p>";
        }
    } else {
        echo "<p style='color:red;'>⚠️ Le mot de passe doit contenir au moins 6 caractères.</p>";
    }
} else {
    echo "<p style='color:red;'>❌ Méthode non autorisée.</p>";
}

include 'footer.php';
?>
