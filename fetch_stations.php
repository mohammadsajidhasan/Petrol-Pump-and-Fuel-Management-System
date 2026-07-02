<?php
require_once 'includes/db.php'; 

$user_lat = isset($_GET['lat']) ? floatval($_GET['lat']) : 23.8103;
$user_lng = isset($_GET['lng']) ? floatval($_GET['lng']) : 90.4125;

// ১. স্টেশন ডাটা ফেচ করা (আগের লজিক)
$sql = "SELECT *, 
        (6371 * acos(cos(radians($user_lat)) * cos(radians(latitude)) * cos(radians(longitude) - radians($user_lng)) + sin(radians($user_lat)) * sin(radians(latitude)))) AS distance 
        FROM stations HAVING distance <= 20 ORDER BY distance ASC";
$result = $conn->query($sql);
$stations = [];
while($row = $result->fetch_assoc()) {
    $queue = isset($row['current_queue']) ? intval($row['current_queue']) : 0; 
    $stations[] = [
        "station_id" => $row['station_id'],
        "name" => $row['name'],
        "location" => $row['address'], 
        "distance" => round($row['distance'], 2),
        "status_type" => ($queue < 5) ? "low" : (($queue < 10) ? "medium" : "high"),
        "badge_class" => ($queue < 5) ? "bg-low" : (($queue < 10) ? "bg-medium" : "bg-high"),
        "label" => ($queue < 5) ? "Smooth" : (($queue < 10) ? "Busy" : "Heavy"),
        "latitude" => $row['latitude'],
        "longitude" => $row['longitude']
    ];
}

// ২. ফুয়েল প্রাইস ও নাম ফেচ
$price_sql = "SELECT ft.fuel_name, fp.price_per_liter FROM fuelprices fp INNER JOIN fueltypes ft ON fp.fuel_type_id = ft.fuel_type_id WHERE fp.effective_from = (SELECT MAX(effective_from) FROM fuelprices WHERE fuel_type_id = fp.fuel_type_id)";
$p_result = $conn->query($price_sql);
$prices = [];
while($p = $p_result->fetch_assoc()) { $prices[] = $p; }

header('Content-Type: application/json');
echo json_encode(['stations' => $stations, 'prices' => $prices]);
?>