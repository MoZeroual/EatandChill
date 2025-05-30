<?php
$showAccueil = false;  // Ne PAS afficher la section d’accueil sur cette page
include "header.php";
?>


<main>
    <div class="container">
        <h1>Connexion</h1>
        <?php
            if (isset($_GET['erreur'])) {
                echo '<p class="error-message">' . htmlspecialchars($_GET['erreur']) . '</p>';
            }
        ?>
        <form action="connexion_traitement.php" method="post" class="form-connexion">

            <label for="email">Adresse e-mail</label>
            <input type="email" id="email" name="email" placeholder="midou@gmail.com" required />

            <label for="motdepasse">Mot de passe</label>
            <div class="password-wrapper">
                <input type="password" id="motdepasse" name="motdepasse" required minlength="6" placeholder="Entrer votre mot de passe..." />
            </div>
            <div>   
                <button type="button" id="togglePassword" aria-label="Afficher le mot de passe" title="Afficher / Cacher" class="toggle-btn">Afficher mon mot de passe</button>
            </div>
            <br/>

            <button type="submit">Se connecter</button>

            <p class="already-registered">
                Pas encore inscrit ? <a href="inscription.php">Créez un compte ici</a>.
            </p>
            <p class="already-registered">
                <a href="forgot.php">Mot de passe oublié ? </a>
            </p>
        </form>
    </div>
</main>



<script>
  const togglePassword = document.querySelector('#togglePassword');
  const passwordInput = document.querySelector('#motdepasse');

  togglePassword.addEventListener('click', function () {
    const type = passwordInput.getAttribute('type') === 'password' ? 'text' : 'password';
    passwordInput.setAttribute('type', type);
    this.textContent = type === 'password' ? 'Afficher le mot de passe' : 'Masquer le mot de passe';
  });
</script>

<?php
include ("footer.php");
?>
