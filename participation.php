<?php
$showAccueil = false;
include 'header.php';
require_once 'config.php';

$sql = "SELECT * FROM reservations WHERE participantsactuel < participants";
$stmt = $pdo->prepare($sql);
$stmt->execute();
$reservations = $stmt->fetchAll(PDO::FETCH_ASSOC);

if (count($reservations) === 0) {
    echo "<h2>Toutes les réservations sont complètes.</h2>";
    echo '<a href="mesrestos.php" class="btn btn-primary">Créer ma propre réservation</a>';
} else {
    echo "<h2>Réservations disponibles</h2>";
    echo "<table border='1' cellpadding='8'>";
    echo "<tr>
            <th>Restaurant</th>
            <th>Ville</th>
            <th>Date</th>
            <th>Heure</th>
            <th>Places restantes</th>
            <th>Action</th>
          </tr>";
    foreach ($reservations as $resa) {
        $places_restantes = $resa['participants'] - $resa['participantsactuel'];
        echo "<tr>
                <td>" . htmlspecialchars($resa['restaurant']) . "</td>
                <td>" . htmlspecialchars($resa['ville']) . "</td>
                <td>" . $resa['date_resa'] . "</td>
                <td>" . $resa['heure_resa'] . "</td>
                <td>" . $places_restantes . "</td>
                <td>
                    <form method='POST' action='rejoindre.php'>
                        <input type='hidden' name='reservation_id' value='" . $resa['id'] . "'>
                        <button type='submit'>Participer</button>
                    </form>
                </td>
              </tr>";
    }
    echo "</table>";
}

include 'footer.php';
?>
