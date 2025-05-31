<?php

include 'header.php';
//print_r($_SESSION);
if ($_SERVER["REQUEST_METHOD"] === "POST") {
    require_once 'config.php'; // inclure la connexion à la BDD

    $pseudo=$_SESSION['pseudo'];
    $participants = (int)$_POST['participants'];
    $participants_avec_moi = (int)$_POST['participantAvecMoi'];
    $restaurant = trim($_POST['restaurant']);
    $ville = trim($_POST['ville']);
    $date_resa = $_POST['date'];
    $heure_resa = $_POST['heure'];
    $pactuels = $participants_avec_moi+1;

    if ($participants < 1 || $participants > 10) {
        echo "Erreur : nombre de participants invalide.";
        exit;
    }

    // Préparer et exécuter la requête d'insertion
    $sql = "INSERT INTO reservations (participants, paarticipantsavecmoi, participantsactuel,pseudo, restaurant, ville,date_resa, heure_resa) VALUES (:participants, :pavecmoi, :pactuel,:pseudo, :restaurant, :ville, :date_resa, :heure_resa)";
    $stmt = $pdo->prepare($sql);
    $stmt->execute([
        ':participants' => $participants,
        ':pavecmoi' => $participants_avec_moi,
        ':pactuel' =>  $pactuels,
        ':pseudo' => $pseudo,
        ':restaurant' => htmlspecialchars($restaurant),
        ':ville' => htmlspecialchars($ville),
        ':date_resa' => $date_resa,
        ':heure_resa' => $heure_resa
    ]);
    
    //insertion dans la table rencontre
    $reservation_id = $pdo->lastInsertId();
    $user_id = $_SESSION['user_id'];
    $sql_rencontre = "INSERT INTO rencontres (idr, idu) VALUES (:idr, :idu)";
    $stmt_rencontre = $pdo->prepare($sql_rencontre);
    $stmt_rencontre->execute([
    ':idr' => $reservation_id,
    ':idu' => $user_id
]);


    

    echo "<h2>Organisation enregistrée avec succès !</h2>";
    echo "<p><strong>Nombre de participants :</strong> $participants</p>";
    echo "<p><strong>MIDOU ZEROUAL</strong>";
    echo "<p><strong>nombre de participants avec moi :</strong> $participants_avec_moi</p>";
    echo "<p><strong>Restaurant :</strong> " . htmlspecialchars($restaurant) . "</p>";
    echo "<p><strong>Ville :</strong> " . htmlspecialchars($ville) . "</p>";
    echo "<p><strong>Date de reservation :</strong> " . $date_resa . "</p>";
    echo "<p><strong>Confirmation heure de reservation :</strong> " .$heure_resa . "</p>";
    if($participants_avec_moi>=1)
    {
        //envoie du mail de validation
        $to = $_SESSION['email']; 
        $subject = "Validation de votre réservation";
        $message = "Bonjour $pseudo,\n\n";
        $message .= "Félicitations ! \nVotre réservation est bien validé.\n\n";
        $message .= "Détails de votre réservation :\n";
        $message .= "- Restaurant : $restaurant\n";
        $message .= "- Ville : $ville\n";
        $message .= "- Date : $date_resa\n";
        $message .= "- Heure : $heure_resa\n";
        $message .= "- Nombre de participants : $participants\n\n";
        $message .= "Merci pour votre confiance.\nL'équipe de réservation.";

        $headers = "From: lm_zeroual@esi.dz"; 
        if (mail($to, $subject, $message, $headers)) {
            echo "<p>Un e-mail de confirmation a été envoyé à <strong>$to</strong>.</p>";
        } else {
            echo "<p style='color:red;'>Erreur lors de l'envoi de l'e-mail de confirmation.</p>";
        }
    }
    else
    {
    //envoie du mail de prise en compte
        $to = $_SESSION['email']; 
        $subject = "Prise en compte de votre réservation";
        $message = "Bonjour $pseudo,\n\n";
        $message .= "Votre réservation a bien été prise en compte.\n\n";
        $message .= "Détails de votre réservation :\n";
        $message .= "- Restaurant : $restaurant\n";
        $message .= "- Ville : $ville\n";
        $message .= "- Date : $date_resa\n";
        $message .= "- Heure : $heure_resa\n";
        $message .= "- Nombre de participants : $participants\n\n";
        $message .= "On revient vers vous quand votre reservation sera validée";
        $message .= "Merci pour votre confiance.\nL'équipe de réservation.";

        $headers = "From: lm_zeroual@esi.dz"; 
        if (mail($to, $subject, $message, $headers)) {
            echo "<p>Un e-mail de confirmation a été envoyé à <strong>$to</strong>.</p>";
        } else {
            echo "<p style='color:red;'>Erreur lors de l'envoi de l'e-mail de confirmation.</p>";
        }
    }

} else {
    echo "Accès non autorisé.";
}
?>


<?php
include_once 'footer.php';
?>

