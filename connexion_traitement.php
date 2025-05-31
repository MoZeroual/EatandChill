<?php
require_once 'config.php'; // la connexion a la bd

if ($_SERVER['REQUEST_METHOD'] === 'POST') {
    $email = $_POST['email'];
    $mot_de_passe = trim($_POST['motdepasse']);

    try {
        $stmt = $pdo->prepare("SELECT * FROM users WHERE email = ?");
        $stmt->execute([$email]);
        $user = $stmt->fetch(PDO::FETCH_ASSOC);
  

        
        if ($user && password_verify($mot_de_passe, $user['motdepasse'])) {
            // Connexion réussie
            session_start();
            $_SESSION['user_id'] = $user['idu'];
            $_SESSION['email'] = $user['email'];
            $_SESSION['pseudo']=$user['pseudo'];
            header("Location: mesrestos.php"); 
            exit();
        } else {
            //Email ou mot de passe incorrect
            header("Location: connexion.php?erreur=Email ou mot de passe incorrect");
            exit();
        }

    } catch (PDOException $e) {
        header("Location: connexion.php?erreur=Erreur serveur");
        exit();
    }
}
?>
