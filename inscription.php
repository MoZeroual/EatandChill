<!DOCTYPE html>
<html lang="fr">
<head>
    <meta charset="UTF-8" />
    <meta name="viewport" content="width=device-width, initial-scale=1" />
    <title>Inscription - Recettes</title>
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
        <h1>Inscription</h1>
        <form action="inscription_traitement.php" method="post" class="form-inscription">


            <label for="nomComplet">Nom complet</label>
            <input type="text" id="nomComplet" name="nomComplet" required />

            <label for="pseudo">Pseudo</label>
            <input type="text" id="pseudo" name="pseudo" required />

            <label for="telephone">Téléphone</label>
            <input type="tel" id="telephone" name="telephone" required pattern="[0-9]{10}" placeholder="Ex: 0612345678" />

            <label for="email">Email</label>
            <input type="email" id="email" name="email" required />

            <label for="ville">Ville</label>
            <input type="text" id="ville" name="ville" required />

            <label for="annee">Année de naissance :</label>
            <input type="number" name="annee" id="annee" min="1900" max="<?php echo date('Y')-18; ?>" step="1">

            <label for="motdepasse">Mot de passe</label>
                <div class="password-wrapper">
                    <input type="password" id="motdepasse" name="motdepasse" required minlength="6" />
                </div>
                <div>   
                     <button type="button" id="togglePassword" aria-label="Afficher le mot de passe" title="Afficher / Cacher" class="toggle-btn">Afficher mon mot de passe</button>
                </div>
                <br/>

            <button type="submit">S'inscrire</button>

            <p class="already-registered">
                Déjà inscrit ? <a href="connexion.php">Connectez-vous ici</a>.
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
    this.textContent = type === 'password' ? 'Afficher le mot de passe' : 'Masquer le mot de passse';
  });
</script>



</body>
</html>
