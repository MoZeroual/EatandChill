<?php
$showAccueil = false;  // Ne PAS afficher la section d’accueil sur cette page
include "header.php";
require_once 'config.php'; // connexion DB
?>

<?php
$message = "";

if ($_SERVER["REQUEST_METHOD"] === "POST") {
    $email = trim($_POST["email"]);
    $phone = trim($_POST["phone"]);

    if (!empty($email) || !empty($phone)) {
        // Recherche par email ou téléphone
        if (!empty($email)) {
            $stmt = $pdo->prepare("SELECT * FROM users WHERE email = ?");
            $stmt->execute([$email]);
        } else {
            $stmt = $pdo->prepare("SELECT * FROM users WHERE telephone = ?");
            $stmt->execute([$phone]);
        }

        $user = $stmt->fetch();

        if ($user) {
            // Génère un token et date d’expiration
            $token = bin2hex(random_bytes(32));
            $expires = date("Y-m-d H:i:s", time() + 3600); // 1h

            // Mise à jour du token dans la base
            $update = $pdo->prepare("UPDATE users SET reset_token = ?, reset_expires = ? WHERE idu = ?");
            $update->execute([$token, $expires, $user['idu']]);

            // Lien de réinitialisation
            $resetLink = "https://eatandchill.alwaysdata.net/reset.php?token=$token";

            // Simule l'envoi d'email (ou SMS si plus tard tu veux l'ajouter)
            $message = "✅ Un lien de réinitialisation a été généré : <a href=\"$resetLink\">$resetLink</a>";
        } else {
            $message = "❌ Aucun compte trouvé avec cet email ou téléphone.";
        }
    } else {
        $message = "⚠️ Veuillez renseigner au moins l'email ou le téléphone.";
    }
}
?>

<body>
    <div class="container">
        <h2>Mot de passe oublié</h2>
        <h4>Tkt pas, On a besoin juste de ton email ou ton mot de passe et on t'envoie le lien de reinitialisation </h4>

        <?php if (!empty($message)): ?>
            <p><?= $message ?></p>
        <?php endif; ?>

        <form method="POST" class="form-connexion">
            <label>Email : </label><input type="email" name="email"><br/><br/>
            <label>Téléphone : </label><input type="text" name="phone"><br/><br/>
            <input type="submit" class="toggle-btn" value="Réinitialisation du mot de passe">
        </form>
    </div>
</body>

<?php include("footer.php"); ?>
