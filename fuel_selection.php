<?php
require_once 'includes/db.php';
if (session_status() === PHP_SESSION_NONE) { session_start(); }

/**
 * ১. প্রফেশনাল সিকিউরিটি ও ফ্লো চেক:
 * সেশনে প্লেট নম্বর, ভেহিকেল আইডি অথবা স্টেশন আইডি কোনোটি না থাকলে 
 * ইউজারকে সরাসরি ভেহিকেল এন্ট্রি পেজে পাঠিয়ে দেওয়া হবে।
 * এটি "ব্রাউজার ট্যাব বন্ধ করে পুনরায় ঢোকা" এর समस्या সমাধান করবে।
 */
if(!isset($_SESSION['v_plate']) || !isset($_SESSION['v_id']) || !isset($_SESSION['station_id'])) { 
    header("Location: vehicle_entry.php"); 
    exit(); 
}

// ২. সেশন থেকে ডাটা সংগ্রহ (process_vehicle.php থেকে আসা)
$plate = $_SESSION['v_plate'];
$type  = $_SESSION['v_type'] ?? 'Unknown';
$limit = isset($_SESSION['v_limit']) ? (float)$_SESSION['v_limit'] : 0.0;
$station_id = $_SESSION['station_id']; // ইউআরএল এর পরিবর্তে এখন সেশন থেকে নেওয়া হচ্ছে

// প্লেটের ভিজ্যুয়াল কালার (process_vehicle.php এর ম্যাপিং থেকে প্রাপ্ত)
$plate_bg   = $_SESSION['v_bg'] ?? '#ffffff';
$plate_text = $_SESSION['v_text'] ?? '#2d3436';

// ৩. প্রফেশনাল প্রায়োরিটি চেক (CHHA বা Ambulance এর জন্য)
$plate_upper = strtoupper($plate);
$is_ambulance = (strpos($plate_upper, 'CHHA') !== false || strtolower($type) == 'ambulance');
?>

<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Fuel Selection | Fuel-X System</title>
    <style>
        :root { --accent: #00d2ff; --bg-dark: #0a0a0a; --card-bg: #121212; }
        body { background: var(--bg-dark); color: #eee; font-family: 'Inter', 'Segoe UI', sans-serif; display: flex; justify-content: center; align-items: center; min-height: 100vh; margin: 0; }
        
        .card { background: var(--card-bg); padding: 40px; border-radius: 30px; width: 450px; border: 1px solid #333; box-shadow: 0 25px 80px rgba(0,0,0,0.8); position: relative; overflow: hidden; }
        
        /* প্রফেশনাল ডিজিটাল প্লেট ডিজাইন */
        .digital-plate {
            background: <?php echo $plate_bg; ?>;
            color: <?php echo $plate_text; ?>;
            padding: 20px;
            border-radius: 12px;
            text-align: center;
            border: 5px solid #333;
            margin-bottom: 30px;
            box-shadow: inset 0 0 15px rgba(0,0,0,0.1);
            position: relative;
            transition: 0.3s;
        }
        .plate-header { font-size: 10px; font-weight: bold; letter-spacing: 3px; display: block; margin-bottom: 5px; opacity: 0.8; }
        .plate-main { font-size: 26px; font-weight: 900; letter-spacing: 1px; display: block; }

        .info-grid { display: flex; gap: 15px; margin-bottom: 30px; }
        .info-item { flex: 1; background: #1a1a1a; padding: 15px; border-radius: 15px; border-bottom: 3px solid var(--accent); transition: 0.3s; }
        .info-item span { font-size: 11px; color: #888; display: block; text-transform: uppercase; margin-bottom: 5px; }
        .info-item strong { font-size: 15px; color: #fff; }

        /* অ্যাম্বুলেন্স (CHHA) প্রায়োরিটি এনিমেশন */
        .priority-alert { border-bottom-color: #ff4d4d !important; background: #2a0a0a !important; animation: glow 2s infinite; }
        @keyframes glow { 0% { box-shadow: 0 0 5px rgba(255, 77, 77, 0.2); } 50% { box-shadow: 0 0 20px rgba(255, 77, 77, 0.5); } 100% { box-shadow: 0 0 5px rgba(255, 77, 77, 0.2); } }

        label { font-size: 13px; color: #aaa; margin-left: 5px; margin-bottom: 8px; display: block; }
        select, input { width: 100%; padding: 16px; background: #1a1a1a; border: 1px solid #333; color: #fff; border-radius: 12px; margin-bottom: 20px; font-size: 15px; transition: 0.3s; box-sizing: border-box; }
        select:focus, input:focus { border-color: var(--accent); outline: none; background: #222; }

        .confirm-btn { width: 100%; padding: 20px; background: var(--accent); border: none; border-radius: 15px; font-size: 16px; font-weight: bold; cursor: pointer; color: #000; text-transform: uppercase; transition: 0.4s; letter-spacing: 1px; }
        .confirm-btn:hover { transform: translateY(-3px); box-shadow: 0 10px 25px rgba(0, 210, 255, 0.4); }
        .confirm-btn:disabled { background: #333; color: #666; cursor: not-allowed; transform: none; box-shadow: none; }
    </style>
</head>
<body>

<div class="card">
    <h3 style="text-align: center; color: var(--accent); margin-top: 0; margin-bottom: 25px; letter-spacing: 2px;">FUEL TERMINAL</h3>
    
    <div class="digital-plate">
        <span class="plate-header">BANGLADESH</span>
        <span class="plate-main"><?php echo htmlspecialchars($plate); ?></span>
    </div>

    <div class="info-grid">
        <div class="info-item">
            <span>Vehicle Class</span>
            <strong><?php echo $type; ?></strong>
        </div>
        
        <div class="info-item <?php echo $is_ambulance ? 'priority-alert' : ''; ?>" style="<?php echo !$is_ambulance ? 'border-color: #ffd700;' : ''; ?>">
            <span>Available Quota</span>
            <strong id="quota_display"><?php echo number_format($limit, 2); ?> Liters</strong>
        </div>
    </div>

    <form action="generate_token.php" method="POST">
        <input type="hidden" name="v_id" value="<?php echo $_SESSION['v_id']; ?>">
        <input type="hidden" name="s_id" value="<?php echo $station_id; ?>">

        <label>Selected Fuel Type</label>
        <select name="fuel_id" required>
            <option value="" disabled selected>Choose from list...</option>
            <?php 
            $fuels = $conn->query("SELECT fuel_type_id, fuel_name FROM FuelTypes");
            if($fuels && $fuels->num_rows > 0){
                while($f = $fuels->fetch_assoc()) { 
                    echo "<option value='{$f['fuel_type_id']}'>{$f['fuel_name']}</option>"; 
                }
            } else {
                echo "<option value='1'>Octane</option>";
                echo "<option value='2'>Diesel</option>";
            }
            ?>
        </select>

        <label>Required Quantity (L)</label>
        <input type="number" name="amount" id="qty" step="0.1" max="<?php echo $limit; ?>" placeholder="Max allowable: <?php echo $limit; ?>L" required>
        
        <button type="submit" id="submit" class="confirm-btn">Confirm & Generate Token</button>
    </form>
</div>

<script>
    const qty = document.getElementById('qty');
    const btn = document.getElementById('submit');
    const max = <?php echo (float)$limit; ?>;
    
    qty.addEventListener('input', () => {
        const val = parseFloat(qty.value);
        if(val > max || val <= 0 || isNaN(val)) {
            btn.disabled = true;
            qty.style.borderColor = "#ff4d4d";
        } else {
            btn.disabled = false;
            qty.style.borderColor = "var(--accent)";
        }
    });
</script>

</body>
</html>