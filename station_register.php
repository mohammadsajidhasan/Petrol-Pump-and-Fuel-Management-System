<?php
require_once 'includes/db.php';
session_start();

$message = "";

if ($_SERVER['REQUEST_METHOD'] === 'POST') {
    // 1. Capture Owner Account Registration Inputs
    $full_name = trim($_POST['full_name']);
    $email = trim($_POST['email']);
    $password = trim($_POST['password']); // Plain-text input string

    // 2. Capture Fuel Station Management Parameters
    $station_name = trim($_POST['station_name']);
    $location = trim($_POST['location']);
    $address = trim($_POST['address']);
    $latitude = !empty($_POST['latitude']) ? floatval($_POST['latitude']) : 23.6850;
    $longitude = !empty($_POST['longitude']) ? floatval($_POST['longitude']) : 90.3563;

    if (!empty($full_name) && !empty($email) && !empty($password) && !empty($station_name)) {
        
        // Check if the operator email address is already registered
        $check_stmt = $conn->prepare("SELECT owner_id FROM owners WHERE email = ? LIMIT 1");
        $check_stmt->bind_param("s", $email);
        $check_stmt->execute();
        $check_result = $check_stmt->get_result();

        if ($check_result->num_rows > 0) {
            $message = "<div class='alert error'>Registration Failed: Email configuration key already active.</div>";
        } else {
            // Start transaction to keep tables synchronized
            $conn->begin_transaction();

            try {
                // INSERT INTO OWNERS TABLE (WITHOUT PASSWORD HASHING)
                // We pass the raw $password variable directly into the string parameter slot
                $owner_sql = "INSERT INTO owners (full_name, email, password_hash, created_at) VALUES (?, ?, ?, NOW())";
                $owner_stmt = $conn->prepare($owner_sql);
                $owner_stmt->bind_param("sss", $full_name, $email, $password);
                $owner_stmt->execute();
                
                // Retrieve the automatically generated owner ID primary key
                $new_owner_id = $conn->insert_id;

                // INSERT INTO STATIONS TABLE
                $station_sql = "INSERT INTO stations (name, location, address, latitude, longitude, current_queue, status, owner_id) VALUES (?, ?, ?, ?, ?, 0, 'active', ?)";
                $station_stmt = $conn->prepare($station_sql);
                $station_stmt->bind_param("sssddi", $station_name, $location, $address, $latitude, $longitude, $new_owner_id);
                $station_stmt->execute();
                
                $new_station_id = $conn->insert_id;

                // INITIALIZE DEFAULT FUEL INVENTORY ITEMS FOR THE NEW STATION
                // Automatically links 3 standard fuel array entries to avoid baseline display errors
                $default_fuels = [1, 2, 3]; // Assumes 1=Octane, 2=Diesel, 3=Petrol
                $inv_sql = "INSERT INTO FuelInventory (station_id, fuel_type_id, quantity_available, last_updated) VALUES (?, ?, 5000.00, NOW())";
                $inv_stmt = $conn->prepare($inv_sql);
                
                foreach ($default_fuels as $fuel_type_id) {
                    $inv_stmt->bind_param("ii", $new_station_id, $fuel_type_id);
                    $inv_stmt->execute();
                }

                // Everything succeeded, commit changes permanently
                $conn->commit();

                // Automatically sign the user into the session context space
                $_SESSION['owner_id'] = $new_owner_id;
                $_SESSION['owner_name'] = $full_name;

                header("Location: station_dashboard.php");
                exit;

            } catch (Exception $e) {
                // Roll back database states if any individual step throws an exception error
                $conn->rollback();
                $message = "<div class='alert error'>Provisioning Exception Error: " . htmlspecialchars($e->getMessage()) . "</div>";
            }
        }
    } else {
        $message = "<div class='alert error'>Please complete all standard field components.</div>";
    }
}
?>
<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Register New Station Node Profile</title>
    <link href="https://fonts.googleapis.com/css2?family=Inter:wght@400;500;600;700&display=swap" rel="stylesheet">
    <link rel="stylesheet" href="style_station_bpc.css">
</head>
<body class="station-reg-body">
    <div class="reg-card">
        <h2>Register Node Profile</h2>
        <p class="subtitle">Deploy new node station infrastructures to the tracking system matrix.</p>
        
        <?php echo $message; ?>

        <form action="station_register.php" method="POST">
            <div class="section-divider">Operator Profile Credentials</div>
            <div class="form-group">
                <label>Full Operator Name</label>
                <input type="text" name="full_name" placeholder="e.g. Tariq Anam" required>
            </div>
            <div class="form-grid">
                <div class="form-group">
                    <label>Corporate Email Identity</label>
                    <input type="email" name="email" placeholder="name@domain.com" required>
                </div>
                <div class="form-group">
                    <label>Plain-text Access Password</label>
                    <input type="text" name="password" placeholder="Plaintext testing key string" required>
                </div>
            </div>

            <div class="section-divider">Physical Asset Infrastructure Parameters</div>
            <div class="form-group">
                <label>Station Facility Designation Name</label>
                <input type="text" name="station_name" placeholder="e.g. Eco Station Badda" required>
            </div>
            <div class="form-grid">
                <div class="form-group">
                    <label>General Location Region</label>
                    <input type="text" name="location" placeholder="e.g. Badda" required>
                </div>
                <div class="form-group">
                    <label>Complete Street Address Map</label>
                    <input type="text" name="address" placeholder="e.g. Pragati Sarani, Dhaka" required>
                </div>
                <div class="form-group">
                    <label>Coordinate Latitude (Optional)</label>
                    <input type="number" step="0.000001" name="latitude" placeholder="23.781500">
                </div>
                <div class="form-group">
                    <label>Coordinate Longitude (Optional)</label>
                    <input type="number" step="0.000001" name="longitude" placeholder="90.426700">
                </div>
            </div>

            <button type="submit">Register & Provision Facility Node</button>
        </form>

        <div class="footer-link">
            Already mapped? <a href="station_login.php">Return to Station Portal</a>
        </div>
    </div>
</body>
</html>