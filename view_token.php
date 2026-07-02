<?php
require_once 'includes/db.php';
if (session_status() === PHP_SESSION_NONE) { session_start(); }

// সেশন চেক: যদি টোকেন না থাকে তবে ড্যাশবোর্ডে পাঠাবে
if (!isset($_SESSION['last_token_id'])) {
    header("Location: dashboard.php");
    exit();
}

$token_id = $_SESSION['last_token_id'];
$scheduled_time = $_SESSION['scheduled_time'];
$plate = $_SESSION['v_plate'] ?? 'N/A';
$v_type = $_SESSION['v_type'] ?? 'General';
$type_code = $_SESSION['token_type_code'] ?? 'REG';
$station_id = $_SESSION['station_id'] ?? 1;

$is_ambulance = ($type_code === 'CHHA');

$start_time_timestamp = strtotime($scheduled_time);
$end_time_timestamp = strtotime("+1 hour", $start_time_timestamp);
?>
<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Your Fuel Token | Fuel-X</title>
    <style>
        :root { --primary: #00d2ff; --bg: #0a0a0a; --card: #121212; --error: #ff4d4d; --warning: #ffc107; }
        body { background: var(--bg); color: #fff; font-family: 'Segoe UI', sans-serif; display: flex; justify-content: center; align-items: center; min-height: 100vh; margin: 0; }
        .token-card { background: var(--card); padding: 40px; border-radius: 24px; width: 420px; text-align: center; border: 1px solid #222; box-shadow: 0 30px 70px rgba(0,0,0,0.8); }
        .emergency-priority-alert { background: rgba(255, 77, 77, 0.08); border: 2px solid var(--error); color: var(--error); padding: 14px; border-radius: 12px; font-weight: 900; letter-spacing: 2px; font-size: 12px; margin-bottom: 25px; text-transform: uppercase; animation: emergencyGlow 1.5s infinite; }
        @keyframes emergencyGlow { 0%, 100% { opacity: 0.7; box-shadow: 0 0 5px rgba(255, 77, 77, 0.3); } 50% { opacity: 1; box-shadow: 0 0 25px rgba(255, 77, 77, 0.7); } }
        .token-display { font-size: 24px; font-weight: 900; color: var(--primary); background: #161616; padding: 18px; border-radius: 14px; border: 1px dashed var(--primary); margin: 20px 0; letter-spacing: 2px; }
        .details-box { text-align: left; font-size: 14px; color: #aaa; background: #151515; padding: 18px; border-radius: 14px; border: 1px solid #222; }
        .time-box { background: #1a1a1a; padding: 16px; border-radius: 14px; margin-top: 20px; border-bottom: 4px solid #ffd700; }
        .time-box.instant { border-bottom-color: var(--error); background: #221010; }
        .action-wrapper { display: flex; flex-direction: column; gap: 12px; margin-top: 25px; }
        .btn-main, .btn-secondary { display: block; width: 100%; padding: 16px; border-radius: 12px; font-weight: bold; text-transform: uppercase; transition: 0.3s; letter-spacing: 1px; text-decoration: none; text-align: center; cursor: pointer; border: none; }
        .btn-main { background: var(--primary); color: #000; }
        .btn-main:hover { transform: translateY(-3px); box-shadow: 0 10px 20px rgba(0,210,255,0.3); }
        .btn-secondary { background: #1a1a1a; color: #aaa; border: 1px solid #333; }
        .btn-secondary:hover { background: #222; color: #fff; }
        .complaint-modal { position: fixed; top: 0; left: 0; width: 100%; height: 100%; background: rgba(0,0,0,0.85); display: flex; justify-content: center; align-items: center; z-index: 999; opacity: 0; pointer-events: none; transition: 0.3s ease; }
        .complaint-modal.open { opacity: 1; pointer-events: auto; }
        .modal-box { background: #151515; padding: 35px; border-radius: 20px; width: 100%; max-width: 450px; border: 1px solid #333; }
        textarea { width: 100%; height: 150px; background: #0d0d0d; border: 1px solid #333; border-radius: 10px; color: #fff; padding: 15px; box-sizing: border-box; resize: none; margin-bottom: 5px; }
    </style>
</head>
<body>

<div class="token-card">
    <?php if($is_ambulance): ?>
        <div class="emergency-priority-alert">🚨 EMERGENCY PRIORITY ACTIVE 🚨</div>
    <?php endif; ?>

    <h3 style="margin: 0; color: #666; letter-spacing: 2px; font-size: 12px; text-transform: uppercase;">FUEL TOKEN GENERATED</h3>
    
    <div class="token-display"><?php echo htmlspecialchars($token_id); ?></div>

    <div class="details-box">
        <p style="margin: 6px 0;"><strong>Vehicle Plate:</strong> <span style="color: #fff;"><?php echo htmlspecialchars($plate); ?></span></p>
        <p style="margin: 6px 0;"><strong>Class:</strong> <?php echo htmlspecialchars($v_type); ?></p>
        <p style="margin: 6px 0;"><strong>Queue Status:</strong> <span style="color: #28a745; font-weight: bold;">Verified</span></p>
    </div>

    <div class="time-box <?php echo $is_ambulance ? 'instant' : ''; ?>">
        <span style="font-size: 11px; color: #888; display: block; text-transform: uppercase; letter-spacing: 1px; margin-bottom: 4px;">Scheduled Refuel Time Slot</span>
        <strong style="font-size: 15px; color: #fff;">
            <?php 
            if ($is_ambulance) echo "⚡ INSTANT EMERGENCY ACCESS";
            else echo date('h:i A', $start_time_timestamp) . " - " . date('h:i A', $end_time_timestamp) . " (" . date('d M, Y', $start_time_timestamp) . ")";
            ?>
        </strong>
    </div>

    <div class="action-wrapper">
        <a href="employee_login.php" class="btn-main">Proceed to Queue List</a>
        <button type="button" class="btn-secondary" onclick="toggleComplaintModal(true)">Report a Complaint</button>
    </div>
</div>

<div id="complaintModal" class="complaint-modal">
    <div class="modal-box">
        <h4>File a Complaint</h4>
        <form action="process_complaint.php" method="POST">
            <textarea name="description" id="complaintText" placeholder="Describe your issue..." required></textarea>
            <div class="word-counter">Words: <span id="wordCount">0</span> / 360</div>
            <div class="modal-actions">
                <button type="button" class="btn-secondary" onclick="toggleComplaintModal(false)">Cancel</button>
                <button type="submit" id="submitComplaint" class="btn-main" style="background: var(--warning);">Submit</button>
            </div>
        </form>
    </div>
</div>

<script>
    function toggleComplaintModal(show) { document.getElementById('complaintModal').classList.toggle('open', show); }
    const textarea = document.getElementById('complaintText');
    const wordCountSpan = document.getElementById('wordCount');
    const submitBtn = document.getElementById('submitComplaint');
    textarea.addEventListener('input', () => {
        const count = textarea.value.trim().split(/\s+/).filter(w => w.length > 0).length;
        wordCountSpan.textContent = count;
        submitBtn.disabled = count > 360;
    });
</script>

</body>
</html>