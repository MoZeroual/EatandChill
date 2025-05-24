<!DOCTYPE html>
<html lang="fr">
<head>
    <meta charset="UTF-8" />
    <meta name="viewport" content="width=device-width, initial-scale=1" />
    <title>Connexion - Recettes</title>
    <link rel="stylesheet" href="style.css" />
</head>
<body>

<header>
    <nav class="navbar">
        <div class="logo">Eat and Chill</div>
        <form class="search-form" action="recherche.php" method="get">
            <input type="search" name="q" placeholder="Rechercher..." required />
            <button type="submit">🔍</button>
        </form>
        <ul class="nav-links">
            <li><a href="index.php">Accueil</a></li>
            <li><a href="contact.php">Contact</a></li>
            <li><a href="connexion.php">Connexion</a></li>
            <li><a href="inscription.php">Inscription</a></li>
        </ul>
    </nav>
</header>

<main>
    <div class="container">
        <h1>Connexion</h1>
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

        </form>
    </div>
</main>

<footer>
    <div class="footer-content">
        <p>&copy; 2025 Eat and Chill. Tous droits réservés.</p>
        <p>Contact : contact@eatandchill.com | Téléphone : 06 12 34 56 78</p>
        <p>Adresse : 09 rue d'austerlitz, Paris, France</p>
    </div>
</footer>

<script>
  const togglePassword = document.querySelector('#togglePassword');
  const passwordInput = document.querySelector('#motdepasse');

  togglePassword.addEventListener('click', function () {
    const type = passwordInput.getAttribute('type') === 'password' ? 'text' : 'password';
    passwordInput.setAttribute('type', type);
    this.textContent = type === 'password' ? 'Afficher le mot de passe' : 'Masquer le mot de passe';
  });
</script>

</body>
</html>
