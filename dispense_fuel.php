<?php
if (session_status() === PHP_SESSION_NONE) { session_start(); }
require_once 'includes/db.php';

$station_id = $_SESSION['emp_station_id'] ?? 1;

$query = "SELECT t.token_id, v.plate_number 
          FROM tokens t 
          JOIN vehicles v ON t.vehicle_id = v.vehicle_id 
          WHERE t.station_id = ? AND t.status = 'Pending' 
          ORDER BY (v.plate_number LIKE '%CHHA%' OR v.plate_number LIKE '%AMB%') DESC, t.token_id ASC 
          LIMIT 1";

$stmt = $conn->prepare($query);
$stmt->bind_param("i", $station_id);
$stmt->execute();
$res = $stmt->get_result();
$vehicle = $res->fetch_assoc();

if (!$vehicle) { 
    die("<script>alert('No pending vehicles in queue!'); window.location='worker_dashboard.php';</script>"); 
}

$token_id = $vehicle['token_id'];
?>

<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <title>Fuel-X Dispense Hub</title>
    <style>
        :root { --primary: #00d2ff; --bg: #0a0a0a; --success: #28a745; --warning: #ff9f43; }
        body { background: var(--bg); color: #fff; font-family: 'Segoe UI', sans-serif; display: flex; justify-content: center; align-items: center; min-height: 100vh; margin: 0; }
        .panel { background: #121212; padding: 40px; border-radius: 24px; width: 450px; border: 1px solid #333; text-align: center; }
        
        .qr-box { width: 200px; height: 200px; background: #fff; margin: 0 auto 20px; padding: 10px; border-radius: 12px; border: 4px solid transparent; animation: pulse-orange 1.5s infinite; }
        @keyframes pulse-orange { 0% { border-color: transparent; } 50% { border-color: var(--warning); box-shadow: 0 0 20px var(--warning); } 100% { border-color: transparent; } }
        
        .road { width: 100%; height: 100px; background: #222; margin: 20px 0; position: relative; overflow: hidden; border-radius: 8px; }
        .vehicle-icon { position: absolute; left: -100px; top: 30px; font-size: 40px; transition: 2s ease-out; }
        .worker-icon { position: absolute; left: 55%; top: 30px; font-size: 35px; opacity: 0; transition: 0.5s; }
        .hose { position: absolute; left: 52%; top: 35px; font-size: 20px; opacity: 0; transform: rotate(-20deg); }
        
        .progress-bar { height: 12px; background: #222; border-radius: 10px; margin: 10px 0; }
        .progress-fill { height: 100%; width: 0%; background: var(--success); }
        .thank-you { color: var(--warning); font-size: 22px; font-weight: bold; margin: 15px 0; }
    </style>
</head>
<body>

<div class="panel">
    <div id="step-qr">
        <h2 style="color: var(--primary);">Scan Vehicle Token</h2>
        <div class="qr-box"><img src="https://api.qrserver.com/v1/create-qr-code/?size=180x180&data=<?php echo $token_id; ?>" width="100%"></div>
        <p>Awaiting Vehicle...</p>
    </div>

    <div id="step-fuel" style="display:none;">
        <h3 style="color: var(--primary);">Dispensing...</h3>
        <div class="road">
            <div id="car" class="vehicle-icon">🚗</div>
            <div id="worker" class="worker-icon">👨‍🔧</div>
            <div id="hose" class="hose">⛽</div>
        </div>
        <div class="progress-bar"><div id="p-fill" class="progress-fill"></div></div>
        <p id="p-text">0%</p>
    </div>

    <div id="step-success" style="display:none;">
        <div style="font-size: 50px;">✅</div>
        <div class="thank-you">THANK YOU - Token #<?php echo $token_id; ?></div>
        <p>The fuel has been delivered on time! Please visit again!</p>
        <a href="process_payment.php?token_id=<?php echo $token_id; ?>" style="background:var(--success); color:#fff; padding:15px; display:block; border-radius:10px; text-decoration:none; margin-top:20px;">Proceed to Payment →</a>
    </div>
</div>

<script>
    setTimeout(() => {
        document.getElementById('step-qr').style.display = 'none';
        document.getElementById('step-fuel').style.display = 'block';
        
        // গাড়ি বাম থেকে সচল হয়ে এসে কর্মীর সামনে থামবে
        const car = document.getElementById('car');
        car.style.left = '40%'; 
        
        // গাড়ি থামার পর কর্মী এবং পাইপ গাড়ির সাথে যুক্ত হবে
        setTimeout(() => {
            document.getElementById('worker').style.opacity = '1';
            document.getElementById('hose').style.opacity = '1';
            
            let pct = 0;
            let timer = setInterval(() => {
                pct++;
                document.getElementById('p-fill').style.width = pct + '%';
                document.getElementById('p-text').innerText = pct + '%';
                if(pct >= 100) {
                    clearInterval(timer);
                    setTimeout(() => {
                        document.getElementById('step-fuel').style.display = 'none';
                        document.getElementById('step-success').style.display = 'block';
                    }, 500);
                }
            }, 40);
        }, 1000);
    }, 2500);
</script>
</body>
</html>