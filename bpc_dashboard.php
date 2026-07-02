<?php
require_once 'includes/db.php';

// Strict Corporate Supplier Verification Guard
if (!isset($_SESSION['supplier_id'])) {
    header("Location: bpc_login.php");
    exit;
}

$message = "";

// --- INLINE REFILL SYSTEM CONTROLLER ---
if ($_SERVER['REQUEST_METHOD'] === 'POST' && isset($_POST['action_inline_refill'])) {
    $input_inventory_id = intval($_POST['inventory_id']);
    $refill_amount = floatval($_POST['refill_amount']);

    if ($input_inventory_id > 0 && $refill_amount > 0) {
        $update_sql = "UPDATE FuelInventory SET quantity_available = quantity_available + ? WHERE inventory_id = ?";
        $stmt = $conn->prepare($update_sql);
        $stmt->bind_param("di", $refill_amount, $input_inventory_id);
        
        if ($stmt->execute()) {
            // Update old unfulfilled order traces if any exist
            $order_sql = "UPDATE FuelOrder SET delivery_date = NOW() WHERE inventory_id = ? AND delivery_date IS NULL";
            $order_stmt = $conn->prepare($order_sql);
            $order_stmt->bind_param("i", $input_inventory_id);
            $order_stmt->execute();
            
            // If no prior manual request rows matched, add a standard log
            if($order_stmt->affected_rows == 0) {
                $order_log = "INSERT INTO FuelOrder (inventory_id, order_date, delivery_date) VALUES (?, NOW(), NOW())";
                $log_stmt = $conn->prepare($order_log);
                $log_stmt->bind_param("i", $input_inventory_id);
                $log_stmt->execute();
            }

            // Flush the session tracker amount metric cleanly
            unset($_SESSION['pending_liters_'.$input_inventory_id]);

            $message = "<div class='alert success'><i class='fa-solid fa-circle-check'></i> Stock dispatched successfully. Network metrics synchronized.</div>";
        } else {
            $message = "<div class='alert error'>Database transaction update failed.</div>";
        }
    } else {
        $message = "<div class='alert error'>Please enter a valid refill quantity.</div>";
    }
}

// --- AUTOMATED CRITICAL LOW ALERTS QUERY ---
$alerts_query = "SELECT 
                    i.inventory_id, 
                    i.quantity_available, 
                    COALESCE(a.threshold_level, 2500.00) as safety_limit,
                    s.name as station_name, 
                    f.fuel_name 
                 FROM FuelInventory i
                 JOIN stations s ON i.station_id = s.station_id
                 JOIN FuelTypes f ON i.fuel_type_id = f.fuel_type_id
                 LEFT JOIN InventoryAlerts a ON i.inventory_id = a.inventory_id
                 WHERE i.quantity_available <= COALESCE(a.threshold_level, 2500.00)
                 ORDER BY i.quantity_available ASC";
$alerts_result = $conn->query($alerts_query);
$active_alerts_count = $alerts_result ? $alerts_result->num_rows : 0;

// --- OWNER INBOUND CUSTOM VOLUME REQUESTSTREAM QUERY ---
$custom_req_query = "SELECT 
                        fo.inventory_id,
                        fo.order_date,
                        i.quantity_available,
                        s.name as station_name,
                        f.fuel_name
                     FROM FuelOrder fo
                     JOIN FuelInventory i ON fo.inventory_id = i.inventory_id
                     JOIN stations s ON i.station_id = s.station_id
                     JOIN FuelTypes f ON i.fuel_type_id = f.fuel_type_id
                     WHERE fo.delivery_date IS NULL
                     ORDER BY fo.order_date ASC";
$custom_req_result = $conn->query($custom_req_query);
$custom_req_count = $custom_req_result ? $custom_req_result->num_rows : 0;

$global_fuel = $conn->query("SELECT SUM(quantity_available) as total FROM FuelInventory")->fetch_assoc()['total'] ?? 0;
$pending_orders = $conn->query("SELECT COUNT(*) as total FROM FuelOrder WHERE delivery_date IS NOT NULL")->fetch_assoc()['total'] ?? 0;
?>
<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>BPC HQ Logistics Center</title>
    <link href="https://fonts.googleapis.com/css2?family=Inter:wght@300;400;500;600;700&display=swap" rel="stylesheet">
    <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.4.0/css/all.min.css">
    <link rel="stylesheet" href="style_station_bpc.css">
</head>
<body class="bpc-body">
    <div class="sidebar">
        <div class="brand"><i class="fa-solid fa-building-flag"></i> BPC HQ Center</div>
        <ul>
            <li><a href="bpc_dashboard.php" class="active"><i class="fa-solid fa-tower-shield"></i> Supply Command</a></li>
        </ul>
        <a href="logout.php" class="logout-link"><i class="fa-solid fa-power-off"></i> Disconnect Session</a>
    </div>

    <div class="main-content">
        <div class="header">
            <div>
                <h1>Bangladesh Petroleum Corp. (BPC) HQ</h1>
                <p style="color: #94a3b8; margin-top:5px;"><i class="fa-solid fa-user-shield"></i> Authorized Account: <?php echo htmlspecialchars($_SESSION['supplier_name'] ?? 'BPC Admin'); ?></p>
            </div>
        </div>

        <?php echo $message; ?>

        <div class="metrics-grid">
            <div class="card">
                <div class="info"><h3>Low Stock Arrays</h3><p style="color:#f43f5e;"><?php echo $active_alerts_count; ?> Lines</p></div>
                <div class="icon-box rose-box"><i class="fa-solid fa-triangle-exclamation"></i></div>
            </div>
            <div class="card">
                <div class="info"><h3>Inbound Owner Requests</h3><p style="color:#60a5fa;"><?php echo $custom_req_count; ?> Orders</p></div>
                <div class="icon-box amber-box"><i class="fa-solid fa-envelope-open-text"></i></div>
            </div>
            <div class="card"><div class="info"><h3>Gross Reserves Tracked</h3><p><?php echo number_format($global_fuel, 2); ?> L</p></div><div class="icon-box slate-box"><i class="fa-solid fa-database"></i></div></div>
        </div>

        <div class="panel">
            <h2><i class="fa-solid fa-bell" style="color:#f43f5e;"></i> Automated Low Fuel Network Alerts</h2>
            <table style="margin-top: 10px;">
                <thead>
                    <tr>
                        <th>Station Target</th>
                        <th>Fuel Variant</th>
                        <th>Current Level</th>
                        <th>Threshold Limit</th>
                        <th>Status</th>
                        <th style="width: 260px;">Action: Dispatch Refill</th>
                    </tr>
                </thead>
                <tbody>
                    <?php if($active_alerts_count > 0): 
                        $alerts_result->data_seek(0);
                        while($row = $alerts_result->fetch_assoc()): 
                    ?>
                            <tr>
                                <td><strong><?php echo htmlspecialchars($row['station_name']); ?></strong></td>
                                <td><?php echo htmlspecialchars($row['fuel_name']); ?></td>
                                <td style="color:#f43f5e; font-weight:600;"><?php echo number_format($row['quantity_available'], 2); ?></td>
                                <td><?php echo number_format($row['safety_limit'], 2); ?></td>
                                <td><span class="badge-alert">CRITICAL LOW</span></td>
                                <td>
                                    <form action="bpc_dashboard.php" method="POST" class="inline-refill-form">
                                        <input type="hidden" name="inventory_id" value="<?php echo $row['inventory_id']; ?>">
                                        <input type="number" step="0.01" min="1" name="refill_amount" class="inline-input" placeholder="Amount" required>
                                        <button type="submit" name="action_inline_refill" class="btn-inline-submit">
                                            <i class="fa-solid fa-truck-moving"></i> Refill
                                        </button>
                                    </form>
                                </td>
                            </tr>
                        <?php endwhile; ?>
                    <?php else: ?>
                        <tr><td colspan="6" style="text-align:center; color:#94a3b8; padding:40px;"><i class="fa-solid fa-circle-check" style="color:#10b981;"></i> All station network reserves are normal. No active low stock items.</td></tr>
                    <?php endif; ?>
                </tbody>
            </table>
        </div>

        <div class="panel">
            <h2><i class="fa-solid fa-truck-ramp-box" style="color:#60a5fa;"></i> Owner Custom Liter Refill Inbound Stream</h2>
            <table style="margin-top: 10px;">
                <thead>
                    <tr>
                        <th>Station Target</th>
                        <th>Fuel Variant</th>
                        <th>Current Storage</th>
                        <th>Requested Liters</th>
                        <th>Status</th>
                        <th>Action: Dispatch Requested Volume</th>
                    </tr>
                </thead>
                <tbody>
                    <?php if($custom_req_count > 0): 
                        while($row = $custom_req_result->fetch_assoc()): 
                            $saved_liters = $_SESSION['pending_liters_'.$row['inventory_id']] ?? 5000.00;
                    ?>
                            <tr>
                                <td><strong><?php echo htmlspecialchars($row['station_name']); ?></strong></td>
                                <td><?php echo htmlspecialchars($row['fuel_name']); ?></td>
                                <td><?php echo number_format($row['quantity_available'], 2); ?> L</td>
                                <td style="color:#60a5fa; font-weight:700;"><?php echo number_format($saved_liters, 2); ?> Ltrs</td>
                                <td><span class="badge-custom-req">MANUAL REQ</span></td>
                                <td>
                                    <form action="bpc_dashboard.php" method="POST" class="inline-refill-form">
                                        <input type="hidden" name="inventory_id" value="<?php echo $row['inventory_id']; ?>">
                                        <input type="number" step="0.01" min="1" name="refill_amount" class="inline-input" value="<?php echo $saved_liters; ?>" required>
                                        <button type="submit" name="action_inline_refill" class="btn-inline-submit" style="background-color:#0284c7;">
                                            <i class="fa-solid fa-shipping-fast"></i> Fulfill Order
                                        </button>
                                    </form>
                                </td>
                            </tr>
                        <?php endwhile; ?>
                    <?php else: ?>
                        <tr><td colspan="6" style="text-align:center; color:#94a3b8; padding:30px;"><i class="fa-solid fa-circle-check" style="color:#10b981;"></i> No unique manual owner requests found in the current stream.</td></tr>
                    <?php endif; ?>
                </tbody>
            </table>
        </div>

    </div>
</body>
</html>