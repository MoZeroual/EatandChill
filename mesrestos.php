<?php
$showInscription=false;

include 'header.php';


  echo '<h3>Tu recherches un restaurant, un bar ou un fast food ?</h3>

<form id="addressForm" class="search-form">
  <input
    type="text"
    id="address"
    name="address"
    placeholder="Saisir une adresse complète en France"
    required
  />

  <select id="type" name="type">
    <option value="all">Restaurants & Fast Food</option>
    <option value="restaurant">Restaurants</option>
    <option value="fast_food">Fast-foods & Bar</option>
  </select>

  <button type="submit">🔍</button>
</form>

  <div id="results" style="margin-top:20px;"></div>';

  echo "<h3>Tu sais où aller manger alors</h3>";
  echo'<form action="reservation.php" method="post">
    <label for="participants">Nombre de participants (max 10) :</label>
    <input type="number" id="participants" name="participants" min="2" max="10" required><br/>

    <label for="participantAvecMoi">Nombre de participant avec moi :</label>
    <input type="text" id="participantAvecMoi" name="participantAvecMoi" required><br/>

    <label for="restaurant">Nom du restaurant :</label>
    <input type="text" id="restaurant" name="restaurant" required><br/>

    <label for="ville">Ville :</label>
    <input type="text" id="ville" name="ville" required><br/>

    <label for="date">Date de réservation :</label>
    <input type="date" id="date" name="date" required><br/>

    <label for="heure">Heure de réservation :</label>
    <input type="time" id="heure" name="heure" required><br/>

    <button type="submit">EAT AND CHILL</button>
</form>';


echo '<h3>Mes participations en cours </h3>'; 
?>

<?php
      
      require_once 'config.php'; // Fichier avec la connexion PDO ($pdo)

      // Vérifier que l'utilisateur est connecté et que le pseudo est en session
      if (!isset($_SESSION['pseudo'])) {
          echo "Merci de vous connecter pour voir vos réservations.";
          exit;
      }

      $pseudo = $_SESSION['pseudo'];

      // Requête préparée pour récupérer les réservations de l'utilisateur
      $sql = "SELECT * FROM reservations WHERE pseudo = :pseudo ORDER BY date_creation ASC";
      $stmt = $pdo->prepare($sql);
      $stmt->execute([':pseudo' => $pseudo]);

      $reservations = $stmt->fetchAll();

?>




<?php if ($reservations): ?>
    <?php echo '<table>
        <thead>
            <tr>
                <th>ID</th>
                <th>Participants</th>
                <th>Restaurant</th>
                <th>Ville</th>
                <th>Date</th>
                <th>Heure</th>
                <th>Date de création</th>
            </tr>
        </thead>
        <tbody>';
        ?>
            
            <?php foreach ($reservations as $res): ?>
            
              <tr>
                <td><?= htmlspecialchars($res['pseudo']) ?></td>
                <td><?= htmlspecialchars($res['participants']) ?></td>
                <td><?= htmlspecialchars($res['restaurant']) ?></td>
                <td><?= htmlspecialchars($res['ville']) ?></td>
                <td><?= htmlspecialchars($res['date_resa']) ?></td>
                <td><?= htmlspecialchars(substr($res['heure_resa'], 0, 5)) ?></td>
                <td><?= htmlspecialchars($res['date_creation']) ?></td>
              </tr>
            
            <?php endforeach; ?>
        <?php echo '</tbody></table>';
        ?>
        

<?php else: ?>
    <?php echo'<p>Aucune réservation trouvée.</p>';
    ?>
<?php endif; ?>

  <script src="searchrestos.js"></script>
  <script src="validation.js"></script>


<?php
include_once 'footer.php';
?>



