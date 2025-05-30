
<?php
header('Content-Type: application/json');

if (!isset($_POST['address']) || trim($_POST['address']) === '') {
    echo json_encode(['erreur' => 'Adresse non fournie']);
    exit;
}

$address = urlencode(trim($_POST['address']));
$page = isset($_POST['page']) ? max(1, intval($_POST['page'])) : 1;
$limit = 20;
$typeFilter = $_POST['type'] ?? 'all';
$typeFilter = in_array($typeFilter, ['restaurant', 'fast_food', 'all']) ? $typeFilter : 'all';

function haversineDistance($lat1, $lon1, $lat2, $lon2) {
    $earthRadius = 6371000; // mètres
    $dLat = deg2rad($lat2 - $lat1);
    $dLon = deg2rad($lon2 - $lon1);
    $a = sin($dLat / 2) ** 2 + cos(deg2rad($lat1)) * cos(deg2rad($lat2)) * sin($dLon / 2) ** 2;
    $c = 2 * atan2(sqrt($a), sqrt(1 - $a));
    return $earthRadius * $c;
}

$nominatim_url = "https://nominatim.openstreetmap.org/search?q=$address&format=json&limit=1&countrycodes=fr";
$options = ["http" => ["header" => "User-Agent: MonProjetRecherche/1.0\r\n"]];
$context = stream_context_create($options);

$geo = file_get_contents($nominatim_url, false, $context);
$geoData = json_decode($geo, true);

if (!$geoData || count($geoData) === 0) {
    echo json_encode(['erreur' => 'Adresse non trouvée']);
    exit;
}

$lat = $geoData[0]['lat'];
$lon = $geoData[0]['lon'];
//Distance restaurant
$radius = 400;

$baseQuery = '[out:json][timeout:25];(';
$amenities = [];

if ($typeFilter === 'restaurant' || $typeFilter === 'all') {
    $amenities[] = '
      node["amenity"="restaurant"](around:' . $radius . ',' . $lat . ',' . $lon . ');
      way["amenity"="restaurant"](around:' . $radius . ',' . $lat . ',' . $lon . ');
      relation["amenity"="restaurant"](around:' . $radius . ',' . $lat . ',' . $lon . ');
    ';
}

if ($typeFilter === 'fast_food' || $typeFilter === 'all') {
    $amenities[] = '
      node["amenity"="fast_food"](around:' . $radius . ',' . $lat . ',' . $lon . ');
      way["amenity"="fast_food"](around:' . $radius . ',' . $lat . ',' . $lon . ');
      relation["amenity"="fast_food"](around:' . $radius . ',' . $lat . ',' . $lon . ');
    ';
}

// On inclut toujours les bars (ou tu peux les retirer si besoin)
$amenities[] = '
  node["amenity"="bar"](around:' . $radius . ',' . $lat . ',' . $lon . ');
  way["amenity"="bar"](around:' . $radius . ',' . $lat . ',' . $lon . ');
  relation["amenity"="bar"](around:' . $radius . ',' . $lat . ',' . $lon . ');
';

$overpass_query = $baseQuery . implode("\n", $amenities) . ');out center;';
$overpass_url = "https://overpass-api.de/api/interpreter?data=" . urlencode($overpass_query);
$places_json = file_get_contents($overpass_url);
$places_data = json_decode($places_json, true);

if (!$places_data || !isset($places_data['elements'])) {
    echo json_encode(['erreur' => 'Erreur lors de la récupération des lieux']);
    exit;
}

$allResults = [];
foreach ($places_data['elements'] as $element) {
    $name = $element['tags']['name'] ?? 'Nom inconnu';
    $type = $element['tags']['amenity'] ?? 'Lieu';

    if (isset($element['lat']) && isset($element['lon'])) {
        $placeLat = $element['lat'];
        $placeLon = $element['lon'];
    } elseif (isset($element['center']['lat']) && isset($element['center']['lon'])) {
        $placeLat = $element['center']['lat'];
        $placeLon = $element['center']['lon'];
    } else {
        continue;
    }

    $distanceMeters = haversineDistance($lat, $lon, $placeLat, $placeLon);

    $allResults[] = [
        'type' => ucfirst(str_replace('_', ' ', $type)),
        'name' => $name,
        'distanceMeters' => $distanceMeters,
        'distanceStr' => ($distanceMeters >= 1000) ? round($distanceMeters / 1000, 2) . " km" : round($distanceMeters) . " m"
    ];
}

usort($allResults, function($a, $b) {
    return $a['distanceMeters'] <=> $b['distanceMeters'];
});

$total = count($allResults);
$start = ($page - 1) * $limit;
$pagedResults = array_slice($allResults, $start, $limit);

$finalResults = array_map(function($place) {
    return "{$place['type']} : {$place['name']} — Distance : {$place['distanceStr']}";
}, $pagedResults);

echo json_encode([
    'total' => $total,
    'page' => $page,
    'limit' => $limit,
    'results' => $finalResults
]);
?>