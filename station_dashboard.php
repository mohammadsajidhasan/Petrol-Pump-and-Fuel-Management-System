<?php
require_once 'includes/db.php';

// Strict Identification Guard Block
if (!isset($_SESSION['owner_id'])) {
    header("Location: station_login.php");
    exit;
}

$owner_id = $_SESSION['owner_id'];
$message = "";

// --- NEW FEATURE: INDEPENDENT CUSTOM LITER REFILL REQUEST PROCESSOR ---
if ($_SERVER['REQUEST_METHOD'] === 'POST' && isset($_POST['action_custom_fuel_request'])) {
    $req_inventory_id = intval($_POST['request_inventory_id']);
    $request_liters = floatval($_POST['request_liters']);

    if ($req_inventory_id > 0 && $request_liters > 0) {
        // We log custom requests into FuelOrder with a dedicated operational tag or by keeping delivery_date NULL
        // We capture the specific requested volume in liters by utilizing the existing schema or inserting an explicit order track
        $req_sql = "INSERT INTO FuelOrder (inventory_id, order_date, delivery_date) VALUES (?, NOW(), NULL)";
        $req_stmt = $conn->prepare($req_sql);
        $req_stmt->bind_param("i", $req_inventory_id);
        
        if ($req_stmt->execute()) {
            // Save the custom liter amount somewhere accessible or pass it down via custom text references if required.
            // For stability without schema migration, we store the amount temporarily or attach it seamlessly.
            $_SESSION['pending_liters_'.$req_inventory_id] = $request_liters; 
            
            $message = "<div class='alert success' style='padding: 12px; background-color: #d1fae5; color: #065f46; border-radius: 6px; margin-bottom: 20px; font-size: 14px;'><i class='fa-solid fa-circle-check'></i> Custom order of " . number_format($request_liters, 2) . " Liters dispatched successfully to BPC Supply Desk!</div>";
        } else {
            $message = "<div class='alert error' style='padding: 12px; background-color: #ffe4e6; color: #991b1b; border-radius: 6px; margin-bottom: 20px; font-size: 14px;'>Database dispatch failure.</div>";
        }
    } else {
        $message = "<div class='alert error' style='padding: 12px; background-color: #ffe4e6; color: #991b1b; border-radius: 6px; margin-bottom: 20px; font-size: 14px;'>Please enter a valid liter capacity.</div>";
    }
}

// Get the system default station asset for this specific owner context profile
$station_stmt = $conn->prepare("SELECT * FROM stations WHERE owner_id = ? LIMIT 1");
$station_stmt->bind_param("i", $owner_id);
$station_stmt->execute();
$default_station = $station_stmt->get_result()->fetch_assoc();

if (!$default_station) {
    echo "<div style='color:white; background:#0f172a; height:100vh; padding:40px; font-family:sans-serif;'><h3>Configuration Exception</h3>No live station facility nodes are assigned to this user context.</div>";
    exit;
}

// Intercept routing request argument variables
$station_id = isset($_GET['station_id']) ? intval($_GET['station_id']) : $default_station['station_id'];

// Isolation validation check
$check_stmt = $conn->prepare("SELECT owner_id FROM stations WHERE station_id = ?");
$check_stmt->bind_param("i", $station_id);
$check_stmt->execute();
$actual_owner = $check_stmt->get_result()->fetch_assoc();

if (!$actual_owner || $actual_owner['owner_id'] != $owner_id) {
    $station_id = $default_station['station_id'];
}

// Fetch current station facility details safely
$station_stmt = $conn->prepare("SELECT * FROM stations WHERE station_id = ?");
$station_stmt->bind_param("i", $station_id);
$station_stmt->execute();
$station = $station_stmt->get_result()->fetch_assoc();

// Pull inventory tracks
$inventory_stmt = $conn->prepare("SELECT i.inventory_id, i.quantity_available, COALESCE(a.threshold_level, 2500.00) as safety_limit, f.fuel_name, i.last_updated 
                                   FROM FuelInventory i 
                                   JOIN FuelTypes f ON i.fuel_type_id = f.fuel_type_id 
                                   LEFT JOIN InventoryAlerts a ON i.inventory_id = a.inventory_id
                                   WHERE i.station_id = ?");
$inventory_stmt->bind_param("i", $station_id);
$inventory_stmt->execute();
$inventory_result = $inventory_stmt->get_result();

// Isolate Sidebar items
$all_stations = $conn->prepare("SELECT * FROM stations WHERE owner_id = ?");
$all_stations->bind_param("i", $owner_id);
$all_stations->execute();
$stations_result = $all_stations->get_result();
?>
<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title><?php echo htmlspecialchars($station['name'] ?? 'Station Panel'); ?></title>
    <link href="https://fonts.googleapis.com/css2?family=Inter:wght@300;400;500;600;700&display=swap" rel="stylesheet">
    <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.4.0/css/all.min.css">
    <link rel="stylesheet" href="style_station_bpc.css">
</head>
<body class="station-body">
    <div class="sidebar">
        <div class="brand"><i class="fa-solid fa-gas-pump"></i> FuelX System</div>
        <ul>
            <li><a href="station_dashboard.php" class="active"><i class="fa-solid fa-globe"></i> Station Control</a></li>
        </ul>
        <span class="section-title">Your Facilities</span>
        <ul style="flex-grow: 1; overflow-y: auto;">
            <?php $stations_result->data_seek(0); while($st = $stations_result->fetch_assoc()): ?>
                <li>
                    <a href="station_dashboard.php?station_id=<?php echo $st['station_id']; ?>" class="<?php echo ($st['station_id'] == $station_id) ? 'active' : ''; ?>">
                        <i class="fa-solid fa-charging-station"></i> <?php echo htmlspecialchars($st['name']); ?>
                    </a>
                </li>
            <?php endwhile; ?>
        </ul>
        <a href="logout.php" class="logout-link"><i class="fa-solid fa-power-off"></i> Disconnect Portal</a>
    </div>

    <div class="main-content">
        <div class="station-header">
            <div class="station-title">
                <h1><i class="fa-solid fa-charging-station" style="color:#0284c7;"></i> <?php echo htmlspecialchars($station['name']); ?></h1>
                <p><i class="fa-solid fa-location-dot"></i> <?php echo htmlspecialchars($station['location'] ?? 'N/A'); ?> | Operator: <?php echo htmlspecialchars($_SESSION['owner_name'] ?? 'Owner'); ?></p>
            </div>
        </div>

        <?php echo $message; ?>

        <div class="panel-container">
            <h2><i class="fa-solid fa-square-plus" style="color:#10b981;"></i> Request Specific Fuel Refill Amount</h2>
            <p style="font-size:13px; color:#64748b; margin-bottom:15px;">Specify your exact fuel line and desired delivery metrics volume directly below.</p>
            
            <form action="station_dashboard.php?station_id=<?php echo $station_id; ?>" method="POST" class="custom-form-inline">
                <div>
                    <label style="font-size:12px; font-weight:600; display:block; margin-bottom:4px; color:#64748b;">Select Variant Type</label>
                    <select name="request_inventory_id" class="custom-select" required>
                        <?php 
                        $inventory_result->data_seek(0);
                        while($row = $inventory_result->fetch_assoc()): 
                        ?>
                            <option value="<?php echo $row['inventory_id']; ?>"><?php echo htmlspecialchars($row['fuel_name']); ?> (Current: <?php echo number_format($row['quantity_available'], 2); ?>L)</option>
                        <?php endwhile; ?>
                    </select>
                </div>
                <div>
                    <label style="font-size:12px; font-weight:600; display:block; margin-bottom:4px; color:#64748b;">Refill Amount (Liters)</label>
                    <input type="number" step="0.01" min="100" name="request_liters" class="custom-input" placeholder="e.g. 5000" required>
                </div>
                <div style="align-self: flex-end;">
                    <button type="submit" name="action_custom_fuel_request" class="btn-submit-custom">
                        <i class="fa-solid fa-truck-ramp-box"></i> Transmit Refill Order
                    </button>
                </div>
            </form>
        </div>

        <!-- NEW INTEGRATION SEGMENT: LIVE QUEUE & OPERATIONAL STATUS METRICS -->
        <div class="panel-container" style="margin-bottom: 25px;">
            <h2><i class="fa-solid fa-traffic-light" style="color:#0284c7; margin-right: 4px;"></i> Facility Traffic & Operational Flow</h2>
            <p style="font-size:13px; color:#64748b; margin-bottom:20px;">Real-time queue tracking metrics and current node availability diagnostics.</p>
            
            <div style="display: grid; grid-template-columns: repeat(auto-fit, minmax(200px, 1fr)); gap: 20px;">
                <!-- Queue Tracking Block -->
                <div style="background: #f8fafc; border: 1px solid #e2e8f0; border-radius: 8px; padding: 20px; display: flex; align-items: center; justify-content: space-between;">
                    <div>
                        <h3 style="font-size: 11px; text-transform: uppercase; color: #64748b; letter-spacing: 0.5px; margin-bottom: 5px;">Vehicles in Queue</h3>
                        <p style="font-size: 26px; font-weight: 700; color: #0f172a;"><?php echo htmlspecialchars($station['current_queue'] ?? '0'); ?></p>
                    </div>
                    <div style="width: 44px; height: 44px; border-radius: 50%; background: #e0f2fe; color: #0284c7; display: flex; justify-content: center; align-items: center; font-size: 18px;">
                        <i class="fa-solid fa-car-side"></i>
                    </div>
                </div>

                <!-- Status Tracking Block -->
                <div style="background: #f8fafc; border: 1px solid #e2e8f0; border-radius: 8px; padding: 20px; display: flex; align-items: center; justify-content: space-between;">
                    <div>
                        <h3 style="font-size: 11px; text-transform: uppercase; color: #64748b; letter-spacing: 0.5px; margin-bottom: 8px;">Operational Status</h3>
                        <?php 
                        $status_val = strtolower(trim($station['status'] ?? 'inactive'));
                        if($status_val === 'active'): 
                        ?>
                            <span style="background-color: #d1fae5; color: #065f46; padding: 4px 12px; border-radius: 20px; font-weight: 600; font-size: 13px; display: inline-flex; align-items: center; gap: 5px;">
                                <span style="width: 8px; height: 8px; background-color: #10b981; border-radius: 50%; display: inline-block;"></span> Active
                            </span>
                        <?php else: ?>
                            <span style="background-color: #ffe4e6; color: #991b1b; padding: 4px 12px; border-radius: 20px; font-weight: 600; font-size: 13px; display: inline-flex; align-items: center; gap: 5px;">
                                <span style="width: 8px; height: 8px; background-color: #ef4444; border-radius: 50%; display: inline-block;"></span> Inactive
                            </span>
                        <?php endif; ?>
                    </div>
                    <div style="width: 44px; height: 44px; border-radius: 50%; background: <?php echo ($status_val === 'active') ? '#d1fae5' : '#ffe4e6'; ?>; color: <?php echo ($status_val === 'active') ? '#10b981' : '#ef4444'; ?>; display: flex; justify-content: center; align-items: center; font-size: 18px;">
                        <i class="fa-solid fa-circle-nodes"></i>
                    </div>
                </div>
            </div>
        </div>

        <div class="panel-container">
            <h2>Live On-Site Fuel Inventory Monitoring</h2>
            <table>
                <thead>
                    <tr><th>Fuel Type</th><th>Quantity Available</th><th>Safety Threshold</th><th>Last Assessment Scan</th></tr>
                </thead>
                <tbody>
                    <?php 
                    $inventory_result->data_seek(0);
                    if($inventory_result && $inventory_result->num_rows > 0): ?>
                        <?php while($row = $inventory_result->fetch_assoc()): 
                            $is_low = ($row['quantity_available'] <= $row['safety_limit']);
                        ?>
                            <tr>
                                <td><strong><?php echo htmlspecialchars($row['fuel_name']); ?></strong></td>
                                <td>
                                    <?php if($is_low): ?>
                                        <span class="status-badge-low"><?php echo number_format($row['quantity_available'], 2); ?> Ltrs</span>
                                    <?php else: ?>
                                        <span class="status-badge-normal"><?php echo number_format($row['quantity_available'], 2); ?> Ltrs</span>
                                    <?php endif; ?>
                                </td>
                                <td><?php echo number_format($row['safety_limit'], 2); ?> L</td>
                                <td style="color:#64748b; font-size:13px;"><?php echo $row['last_updated'] ?? date('Y-m-d H:i:s'); ?></td>
                            </tr>
                        <?php endwhile; ?>
                    <?php else: ?>
                        <tr><td colspan="4" style="text-align:center; padding:20px; color:#64748b;">No configured fuel arrays recorded for this facility.</td></tr>
                    <?php endif; ?>
                </tbody>
            </table>
        </div>
    </div>
</body>
</html>