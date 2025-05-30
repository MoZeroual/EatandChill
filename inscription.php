<?php
$showAccueil = false;  // Ne PAS afficher la section d’accueil sur cette page
include "header.php";

// Détecte si l'utilisateur vient de la page d'accueil
$fromHome = isset($_GET['from']) && $_GET['from'] === 'home';
?>

<main>
    <div class="container">
        <?php if (!$fromHome): ?>
            <h1>Rejoins Eat & Chill</h1>
            <p class="lead" style="text-align: center; margin-bottom: 30px;">
                Crée ton compte pour participer à des repas entre inconnus dans les meilleurs restos d’Île-de-France !
            </p>
        <?php endif; ?>

        <h1>Inscription</h1>

        <?php
        if (isset($_GET['erreur'])) {
            echo '<p class="error-message">' . htmlspecialchars($_GET['erreur']) . '</p>';
        }
        ?>

        <form action="inscription_traitement.php" method="post" class="form-inscription">

            <label for="civilite">Civilité</label>
            <select id="civilite" name="civilite" required>
                <option value="">-- Sélectionnez --</option>
                <option value="Homme">Homme</option>
                <option value="Femme">Femme</option>
            </select>

            <label for="nomComplet">Nom complet</label>
            <input type="text" id="nomComplet" name="nomComplet" placeholder="Mohammed ZEROUAL" required />

            <label for="pseudo">Pseudo</label>
            <input type="text" id="pseudo" name="pseudo" placeholder="Midou92" required />

            <label for="telephone">Téléphone</label>
            <input type="tel" id="telephone" name="telephone" required pattern="[0-9]{10}" placeholder="0612345678" />

            <label for="email">Email</label>
            <input type="email" id="email" name="email" placeholder="midou@gmail.com" required />

            <label for="ville">Ville</label>
            <input type="text" id="ville" name="ville" placeholder="Paris" required />

            <label for="annee">Année de naissance :</label>
            <input type="number" name="annee" id="annee" min="1900" max="<?php echo date('Y') - 18; ?>" step="1" placeholder="2004">

            <label for="motdepasse">Mot de passe</label>
            <div class="password-wrapper">
                <input type="password" id="motdepasse" name="motdepasse" required minlength="6" placeholder="Entrer votre mot de passe..." />
            </div>
            <div>
                <button type="button" id="togglePassword" aria-label="Afficher le mot de passe" title="Afficher / Cacher" class="toggle-btn">Afficher mon mot de passe</button>
            </div>
            <br />

            <button type="submit">S'inscrire</button>

            <p class="already-registered">
                Déjà inscrit ? <a href="connexion.php">Connectez-vous ici</a>.
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

<?php include "footer.php"; ?>
