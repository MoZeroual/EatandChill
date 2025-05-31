<?php
$showAccueil = false;
include 'header.php';
require_once 'config.php';

if ($_SERVER["REQUEST_METHOD"] === "POST") {
    $reservation_id = (int)$_POST['reservation_id'];
    $pseudo = $_SESSION['pseudo'];


    $user_id = $_SESSION['user_id'];

    // Vérifier s'il reste de la place
    $stmtCheck = $pdo->prepare("SELECT * FROM reservations WHERE id = :id");
    $stmtCheck->execute([':id' => $reservation_id]);
    $reservation = $stmtCheck->fetch(PDO::FETCH_ASSOC);

    if (!$reservation) {
        echo "Réservation introuvable.";
        exit;
    }

    if ($reservation['participantsactuel'] >= $reservation['participants']) {
        echo "Désolé, cette réservation est déjà complète.";
        exit;
    }

    // Vérifier si l'utilisateur est déjà inscrit à cette réservation
    $stmtExist = $pdo->prepare("SELECT * FROM rencontres WHERE idu = :idu AND idr = :idr");
    $stmtExist->execute([
        ':idu' => $user_id,
        ':idr' => $reservation_id
    ]);

    if ($stmtExist->fetch()) {
        echo "Vous participez déjà à cette réservation.";
        exit;
    }

    // Insertion dans la table rencontres
    $stmtInsert = $pdo->prepare("INSERT INTO rencontres (idu, idr) VALUES (:idu, :idr)");
    $stmtInsert->execute([
        ':idu' => $user_id,
        ':idr' => $reservation_id
    ]);

    // Mise à jour du nombre de participants actuels
    $stmtUpdate = $pdo->prepare("UPDATE reservations SET participantsactuel = participantsactuel + 1 WHERE id = :id");
    $stmtUpdate->execute([':id' => $reservation_id]);

    $stmtCheck = $pdo->prepare("SELECT * FROM reservations WHERE id = :id");
    $stmtCheck->execute([':id' => $reservation_id]);
    $reservation = $stmtCheck->fetch(PDO::FETCH_ASSOC);

    if (!$reservation) {
        echo "Réservation introuvable.";
        exit;
    }
    //traitement de validation d'une reservation et reservation valide
    $participantsactuel = $reservation['participantsactuel'];
    $participantsmax = $reservation['participants'];
    $pseudoCreateur = $reservation['pseudo'];

     $stmtCreateur = $pdo->prepare("SELECT email FROM users WHERE pseudo = :pseudo");
    $stmtCreateur->execute([':pseudo' => $pseudoCreateur]);
    $createur = $stmtCreateur->fetch(PDO::FETCH_ASSOC);

    if ($createur && isset($createur['email'])) {
        $emailCreateur = $createur['email'];

        $subject = "";
        $message = "";
        $headers = "From: lm_zeroual@esi.dz\r\n";

        if ($participantsactuel == 2 && $participantsactuel < $participantsmax) {
            // Envoi mail validation réservation
            echo "il ya maitenant deux paticipant donc la reservation est validé voici le mail";
            echo $emailCreateur ;
            $subject = "Votre réservation est validée";
            $message = "Bonjour $pseudoCreateur,\n\n";
            $message .= "Votre réservation au restaurant " . $reservation['restaurant'] . " est maintenant validée car elle compte $participantsactuel participants.\n\n";
            $message .= "Merci pour votre confiance.\nL'équipe de réservation.";
            if (mail($emailCreateur, $subject, $message, $headers)) {
            echo "<p>Un e-mail de confirmation a été envoyé à <strong>$emailCreateur</strong>.</p>";
        } else {
            echo "<p style='color:red;'>Erreur lors de l'envoi de l'e-mail de confirmation.</p>";
        }

        } elseif ($participantsactuel == $participantsmax) {
            // Envoi mail réservation complète
            echo "il ya maitenant toutes l'equipe";
            echo $emailCreateur ;
            $subject = "Votre réservation est complète";
            $message = "Bonjour $pseudoCreateur,\n\n";
            $message .= "Votre réservation au restaurant " . $reservation['restaurant'] . " est maintenant complète avec $participantsactuel participants.\n\n";
            $message .= "Merci pour votre confiance.\nL'équipe de réservation.";
            
            if (mail($emailCreateur, $subject, $message, $headers)) {
            echo "<p>Un e-mail de confirmation a été envoyé à <strong>$emailCreateur</strong>.</p>";
        } else {
            echo "<p style='color:red;'>Erreur lors de l'envoi de l'e-mail de confirmation.</p>";
        }
        }
    }


    echo "<h2>Participation confirmée !</h2>";
    echo "<p>Merci, vous avez été ajouté à la réservation.</p>";
    echo '<a href="participation.php">Retour aux réservations</a>';

} else {
    echo "Accès non autorisé.";
}
?>
