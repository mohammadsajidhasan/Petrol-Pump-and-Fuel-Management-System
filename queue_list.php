<?php
require_once 'includes/db.php';

// ১. স্টেশন আইডি রিসিভ করা
$station_id = $_GET['station_id'] ?? die("Error: Station ID missing.");

// ২. টোকেন স্ট্যাটাস আপডেট লজিক (Complete বাটন প্রেস করলে)
if (isset($_GET['complete_id'])) {
    $complete_id = $_GET['complete_id'];
    $update_sql = "UPDATE Tokens SET status = 'Completed' WHERE token_id = ?";
    $stmt_up = $conn->prepare($update_sql);
    $stmt_up->bind_param("i", $complete_id);
    
    if ($stmt_up->execute()) {
        // আপডেট সফল হলে একই পেজে রিডাইরেক্ট যাতে লিস্ট রিফ্রেশ হয়
        header("Location: queue_list.php?station_id=" . $station_id);
        exit();
    }
}

/** 
 * ৩. QUEUE RETRIEVAL: 
 * scheduled_time অনুযায়ী ছোট থেকে বড় (ASC) সাজানো হয়েছে। 
 * অ্যাম্বুলেন্সের সময় যেহেতু বর্তমান সময় দেওয়া হয়, সে অটোমেটিক উপরে থাকবে।
 */
$sql = "SELECT t.*, v.plate_number, vt.type_name 
        FROM Tokens t 
        JOIN Vehicles v ON t.vehicle_id = v.vehicle_id 
        JOIN VehicleTypes vt ON v.vehicle_type_id = vt.vehicle_type_id 
        WHERE t.station_id = ? AND t.status = 'Waiting' 
        ORDER BY t.scheduled_time ASC";

$stmt = $conn->prepare($sql);
$stmt->bind_param("i", $station_id);
$stmt->execute();
$result = $stmt->get_result();
?>

<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <title>Fuel-X | Live Queue List</title>
    <style>
        body { background: #0d0d0d; color: #fff; font-family: 'Segoe UI', sans-serif; padding: 40px; }
        .queue-container { max-width: 900px; margin: 0 auto; background: #151515; padding: 30px; border-radius: 20px; box-shadow: 0 10px 30px rgba(0,0,0,0.5); }
        h3 { color: #00d2ff; letter-spacing: 1px; margin-bottom: 25px; }
        
        .queue-table { width: 100%; border-collapse: collapse; text-align: left; }
        .queue-table th { background: #00d2ff; color: #000; padding: 15px; text-transform: uppercase; font-size: 13px; }
        .queue-table td { padding: 15px; border-bottom: 1px solid #222; font-size: 14px; }
        
        /* অ্যাম্বুলেন্সের জন্য স্পেশাল হাইলাইট */
        .priority-row { background: rgba(255, 75, 43, 0.15) !important; color: #ff4b2b; font-weight: bold; }
        .ambulance-tag { background: #ff4b2b; color: #fff; padding: 3px 8px; border-radius: 4px; font-size: 10px; margin-left: 10px; }
        
        .complete-btn { 
            background: #00ffcc; color: #000; border: none; padding: 8px 15px; 
            border-radius: 5px; cursor: pointer; font-weight: bold; text-decoration: none; font-size: 12px;
            transition: 0.3s;
        }
        .complete-btn:hover { background: #fff; transform: translateY(-2px); }
    </style>
</head>
<body>

<div class="queue-container">
    <h3>Live Queue Status - Station #<?= htmlspecialchars($station_id); ?></h3>
    
    <table class="queue-table">
        <thead>
            <tr>
                <th>Pos</th>
                <th>Token ID</th>
                <th>Plate Number</th>
                <th>Vehicle Type</th>
                <th>Estimated Time</th>
                <th>Action</th>
            </tr>
        </thead>
        <tbody>
            <?php 
            $pos = 1; 
            if ($result->num_rows > 0):
                while($row = $result->fetch_assoc()): 
                    $is_ambulance = ($row['type_name'] == 'Ambulance');
            ?>
            <tr class="<?= $is_ambulance ? 'priority-row' : ''; ?>">
                <td>#<?= $pos++; ?></td>
                <td>ID-<?= $row['token_id']; ?></td>
                <td><?= htmlspecialchars($row['plate_number']); ?></td>
                <td>
                    <?= htmlspecialchars($row['type_name']); ?>
                    <?php if($is_ambulance) echo '<span class="ambulance-tag">PRIORITY</span>'; ?>
                </td>
                <td><?= date('h:i A', strtotime($row['scheduled_time'])); ?></td>
                <td>
                    <a href="queue_list.php?station_id=<?= $station_id ?>&complete_id=<?= $row['token_id'] ?>" 
                       class="complete-btn" 
                       onclick="return confirm('Mark as Fueling Completed?')">
                       COMPLETE
                    </a>
                </td>
            </tr>
            <?php 
                endwhile; 
            else:
            ?>
            <tr>
                <td colspan="6" style="text-align: center; color: #555; padding: 40px;">No vehicles in queue.</td>
            </tr>
            <?php endif; ?>
        </tbody>
    </table>
    
    <div style="margin-top: 20px;">
        <a href="dashboard.php" style="color: #555; text-decoration: none; font-size: 13px;">← Back to Dashboard</a>
    </div>
</div>

</body>
</html>