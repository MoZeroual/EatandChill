<?php
$showAccueil = false;  // Ne PAS afficher la section d’accueil sur cette page
include 'header.php';

$token = $_GET['token'] ?? '';
$valid = false;

require_once 'config.php'; // Connexion DB

// Vérifie le token
if (!empty($token)) {
    $stmt = $pdo->prepare("SELECT * FROM users WHERE reset_token = ? AND reset_expires >= NOW()");
    $stmt->execute([$token]);
    $user = $stmt->fetch();

    if ($user) {
        $valid = true;
    }
}
?>

<body>
    <div class="container">
        <h2>Créer un nouveau mot de passe</h2>

        <?php if ($valid): ?>
            <form method="POST" action="update_password.php" class="form-connexion">
                <!-- Champ caché pour transmettre le token -->
                <input type="hidden" name="token" value="<?= htmlspecialchars($token) ?>">

                <!-- Champ pour le mot de passe -->
                <label for="password">Mot de passe</label>
                <div class="password-wrapper">
                    <input type="password" id="password" name="password" required minlength="6" placeholder="Entrer votre mot de passe..." />
                </div>
                <div>
                    <button type="button" id="togglePassword" class="toggle-btn">
                        Afficher mon mot de passe
                    </button>
                </div>

                <input type="submit" value="Réinitialise mon foutu mot de passe">
            </form>
        <?php else: ?>
            <p>❌ Le lien est invalide ou expiré.</p>
        <?php endif; ?>
    </div>
</body>

<script>
  const togglePassword = document.querySelector('#togglePassword');
  const passwordInput = document.querySelector('#password');

  togglePassword.addEventListener('click', function () {
    const type = passwordInput.getAttribute('type') === 'password' ? 'text' : 'password';
    passwordInput.setAttribute('type', type);
    this.textContent = type === 'password' ? 'Afficher le mot de passe' : 'Masquer le mot de passe';
  });
</script>

<?php include 'footer.php'; ?>
