<?php
if (session_status() === PHP_SESSION_NONE) { session_start(); }
require_once 'includes/db.php';

// ওয়ার্কার সেশন প্রোটেকশন
if (!isset($_SESSION['emp_id'])) {
    header("Location: employee_login.php");
    exit();
}

/**
 * 🔄 স্মার্ট স্টেশন আইডি ট্র্যাকিং
 */
$station_id = $_SESSION['emp_station_id'] ?? 1;

$check_stmt = $conn->prepare("SELECT station_id FROM tokens WHERE station_id = ? AND status = 'Pending' LIMIT 1");
$check_stmt->bind_param("i", $station_id);
$check_stmt->execute();
$check_res = $check_stmt->get_result();

if ($check_res->num_rows === 0) {
    $fallback_res = $conn->query("SELECT station_id FROM tokens WHERE status = 'Pending' ORDER BY token_id DESC LIMIT 1");
    if ($fallback_res && $fallback_res->num_rows > 0) {
        $fallback_row = $fallback_res->fetch_assoc();
        $station_id = $fallback_row['station_id'];
    }
}

/**
 * ১. ফুয়েল প্রাইস ডাইনামিকালি তুলে আনা (ইউনিক রেট)
 */
$price_query = "SELECT fp.price_per_liter, ft.fuel_name 
                FROM FuelPrices fp 
                JOIN fueltypes ft ON fp.fuel_type_id = ft.fuel_type_id
                WHERE fp.price_id IN (
                    SELECT MAX(price_id) FROM FuelPrices GROUP BY fuel_type_id
                )
                ORDER BY ft.fuel_type_id ASC";
$price_result = $conn->query($price_query);

/**
 * ২. চূড়ান্ত কিউ লিস্ট কুয়েরি (অ্যাম্বুলেন্স থাকলে সে অন্য সব ইন-স্লট গাড়িকে ওভাররাইড করে এক নম্বরে চলে আসবে)
 */
$queue_query = "SELECT t.token_id, t.scheduled_time, v.plate_number 
                FROM tokens t
                JOIN vehicles v ON t.vehicle_id = v.vehicle_id
                WHERE t.station_id = ? AND (t.status = 'Pending' OR t.status = 'pending')
                ORDER BY 
                    CASE WHEN (t.token_id LIKE '%CHHA%' OR t.token_id LIKE '%AMB%') THEN 0 ELSE 1 END, 
                    t.token_id ASC";
$stmt = $conn->prepare($queue_query);
$stmt->bind_param("i", $station_id);
$stmt->execute();
$queue_result = $stmt->get_result();

// মাস্টার বাটনের জন্য সিস্টেম অটো-ডিটেকশন লজিক
$next_token_id = null;
$temporary_list = [];
while($row = $queue_result->fetch_assoc()) {
    $temporary_list[] = $row;
}
if (!empty($temporary_list)) {
    // তালিকার একদম ১ নম্বরে থাকা গাড়িটিকেই (যা অ্যাম্বুলেন্স থাকলে অটোমেটিক অ্যাম্বুলেন্সই হবে) বাটন রিসিভ করবে
    $next_token_id = $temporary_list[0]['token_id'];
}
?>
<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Worker Station Dashboard | Fuel-X</title>
    <style>
        :root { --primary: #00d2ff; --bg: #0a0a0a; --card: #121212; --success: #28a745; --danger: #ff4d4d; --warning: #ff9f43; }
        body { background: var(--bg); color: #fff; font-family: 'Segoe UI', sans-serif; margin: 0; padding: 30px; }
        .layout { display: grid; grid-template-columns: 280px 1fr; gap: 30px; max-width: 1300px; margin: 0 auto; }
        
        /* ফুয়েল রেট উইজেট */
        .price-card { background: var(--card); padding: 25px; border-radius: 18px; border: 1px solid #222; height: fit-content; box-shadow: 0 15px 40px rgba(0,0,0,0.5); }
        .price-item { display: flex; justify-content: space-between; padding: 12px 0; border-bottom: 1px solid #222; }
        .price-item:last-child { border: none; }
        
        /* কিউ টেবিল ড্যাশবোর্ড */
        .main-content { background: var(--card); padding: 30px; border-radius: 20px; border: 1px solid #222; position: relative; }
        .queue-table { width: 100%; border-collapse: separate; border-spacing: 0 10px; margin-bottom: 30px; }
        .queue-table th { padding: 12px; color: #555; text-transform: uppercase; font-size: 11px; text-align: left; }
        .queue-row td { padding: 16px; background: #161616; border-top: 1px solid #222; border-bottom: 1px solid #222; }
        .queue-row td:first-child { border-left: 1px solid #222; border-radius: 10px 0 0 10px; }
        .queue-row td:last-child { border-right: 1px solid #222; border-radius: 0 10px 10px 0; }
        
        /* 🚨 অ্যাম্বুলেন্স রেড নিয়ন অ্যালার্ট গ্লোয়িং ইফেক্ট */
        .ambulance-alert td { 
            background: rgba(255, 77, 77, 0.08) !important; 
            border-top: 1px solid var(--danger) !important;
            border-bottom: 1px solid var(--danger) !important;
            color: #ffb3b3;
            animation: pulse-red 2s infinite;
        }
        .ambulance-alert td:first-child { border-left: 1px solid var(--danger) !important; }
        .ambulance-alert td:last-child { border-right: 1px solid var(--danger) !important; }
        
        @keyframes pulse-red {
            0% { box-shadow: inset 0 0 5px rgba(255, 77, 77, 0.2); }
            50% { box-shadow: inset 0 0 15px rgba(255, 77, 77, 0.5); }
            100% { box-shadow: inset 0 0 5px rgba(255, 77, 77, 0.2); }
        }
        
        .badge { padding: 5px 10px; border-radius: 6px; font-size: 11px; font-weight: bold; }
        .badge-in { background: rgba(40,167,69,0.1); color: var(--success); border: 1px solid var(--success); }
        .badge-off { background: rgba(255,77,77,0.1); color: var(--danger); border: 1px solid var(--danger); }
        .badge-emergency { background: rgba(255,77,77,0.2); color: #ff4d4d; border: 1px solid #ff4d4d; box-shadow: 0 0 10px rgba(255,77,77,0.4); }
        
        /* 🎯 সেন্ট্রাল মাস্টার বাটন সিএসএস */
        .master-action-container { text-align: right; margin-top: 25px; border-top: 1px solid #222; padding-top: 20px; }
        .btn-master-fuel { background: var(--primary); color: #000; padding: 15px 35px; border-radius: 10px; font-size: 15px; font-weight: bold; text-decoration: none; display: inline-block; transition: 0.3s; box-shadow: 0 5px 20px rgba(0,210,255,0.2); }
        .btn-master-fuel:hover { transform: translateY(-3px); box-shadow: 0 8px 25px rgba(0,210,255,0.5); background: #00bcd4; }
        .btn-disabled { background: #222 !important; color: #555 !important; cursor: not-allowed; box-shadow: none !important; transform: none !important; }
    </style>
</head>
<body>

<div class="layout">
    <div class="price-card">
        <h3 style="color: var(--warning); margin-top: 0; font-size: 14px; text-transform: uppercase; letter-spacing: 1px;">⛽ Live Fuel Rates</h3>
        <?php if($price_result && $price_result->num_rows > 0): ?>
            <?php while($price = $price_result->fetch_assoc()): ?>
                <div class="price-item">
                    <span style="color: #aaa;"><?php echo htmlspecialchars($price['fuel_name']); ?></span>
                    <strong style="color: #fff;"><?php echo number_format($price['price_per_liter'], 2); ?> TK</strong>
                </div>
            <?php endwhile; ?>
        <?php else: ?>
            <p style="color:#555; font-size:12px; margin:0;">No price data available.</p>
        <?php endif; ?>
    </div>

    <div class="main-content">
        <div style="display:flex; justify-content:space-between; align-items:center; margin-bottom:20px;">
            <h2 style="margin:0; color: var(--primary); font-size: 20px;">Live Station Queue Control</h2>
            <span style="font-size:12px; background:#222; padding:6px 12px; border-radius:20px; color:#aaa;">
                Active Monitoring: <strong>Station <?php echo str_pad($station_id, 2, "0", STR_PAD_LEFT); ?></strong>
            </span>
        </div>
        
        <table class="queue-table">
            <thead>
                <tr>
                    <th>Token ID</th>
                    <th>Vehicle No</th>
                    <th>Slot Status</th>
                </tr>
            </thead>
            <tbody>
                <?php if(!empty($temporary_list)): ?>
                    <?php foreach($temporary_list as $row): 
                        // টোকেন আইডি অথবা গাড়ির প্লেট নম্বরে অ্যাম্বুলেন্সের উপস্থিতি ডিটেক্ট করা হচ্ছে
                        $is_amb = (strpos(strtoupper($row['token_id']), 'CHHA') !== false || strpos(strtoupper($row['token_id']), 'AMB') !== false || strpos(strtoupper($row['plate_number']), 'CHHA') !== false);
                        
                        $start_time = strtotime($row['scheduled_time']);
                        $end_time = strtotime("+1 hour", $start_time);
                        $current_time = time();
                        $in_slot = ($current_time >= $start_time && $current_time <= $end_time);
                    ?>
                        <tr class="queue-row <?php echo $is_amb ? 'ambulance-alert' : ''; ?>">
                            <td><strong><?php echo htmlspecialchars($row['token_id']); ?></strong></td>
                            <td><span style="font-family:monospace; font-size:14px; letter-spacing:0.5px;"><?php echo htmlspecialchars($row['plate_number']); ?></span></td>
                            <td>
                                <?php if($is_amb): ?>
                                    <span class="badge badge-emergency">EMERGENCY PRIORITY</span>
                                <?php else: ?>
                                    <span class="badge <?php echo $in_slot ? 'badge-in' : 'badge-off'; ?>">
                                        <?php echo $in_slot ? 'In Slot' : 'Off Slot'; ?>
                                    </span>
                                <?php endif; ?>
                            </td>
                        </tr>
                    <?php endforeach; ?>
                <?php else: ?>
                    <tr><td colspan="3" style="text-align:center; color:#555; padding:40px;">No pending vehicles in queue for Station <?php echo $station_id; ?>.</td></tr>
                <?php endif; ?>
            </tbody>
        </table>

        <div class="master-action-container">
            <?php if($next_token_id): ?>
                <a href="dispense_fuel.php?token_id=<?php echo urlencode($next_token_id); ?>" class="btn-master-fuel">
                    Proceed to Next Fueling Process →
                </a>
            <?php else: ?>
                <a href="#" class="btn-master-fuel btn-disabled" onclick="return false;">
                    Queue Empty
                </a>
            <?php endif; ?>
        </div>
    </div>
</div>

</body>
</html>