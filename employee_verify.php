<?php
if (session_status() === PHP_SESSION_NONE) { session_start(); }
require_once 'includes/db.php';

// লগইন লজিক
if ($_SERVER["REQUEST_METHOD"] == "POST" && isset($_POST['emp_login_btn'])) {
    $emp_id = (int)$_POST['employee_id'];
    $password = $_POST['password'];

    $stmt = $conn->prepare("SELECT e.*, s.start_time, s.end_time FROM employee e LEFT JOIN shifts s ON e.employee_id = s.employee_id WHERE e.employee_id = ?");
    $stmt->bind_param("i", $emp_id);
    $stmt->execute();
    $result = $stmt->get_result();

    if ($result->num_rows > 0) {
        $emp = $result->fetch_assoc();

        // Start এবং End উভয় সময় ফরম্যাট করা
        $formatted_shift = "";
        if ($emp['start_time'] && $emp['end_time']) {
            $start = date("h:i A", strtotime($emp['start_time']));
            $end = date("h:i A", strtotime($emp['end_time']));
            $formatted_shift = $start . " - " . $end;
        } else {
            $formatted_shift = "Not Assigned";
        }

        // লজিক ১: যদি ডাটাবেসে পাসওয়ার্ড খালি থাকে
        if (empty($emp['password'])) {
            $hashed_password = password_hash($password, PASSWORD_DEFAULT);
            $update = $conn->prepare("UPDATE employee SET password = ? WHERE employee_id = ?");
            $update->bind_param("si", $hashed_password, $emp_id);
            $update->execute();
            
            $_SESSION['emp_id'] = $emp['employee_id'];
            $_SESSION['emp_station_id'] = $emp['station_id'];
            $_SESSION['emp_role'] = $emp['designation'];
            $_SESSION['emp_shift_time'] = $formatted_shift;
        } 
        // লজিক ২: যদি পাসওয়ার্ড থাকে, তবে verify করা
        elseif (password_verify($password, $emp['password'])) {
            $_SESSION['emp_id'] = $emp['employee_id'];
            $_SESSION['emp_station_id'] = $emp['station_id'];
            $_SESSION['emp_role'] = $emp['designation'];
            $_SESSION['emp_shift_time'] = $formatted_shift;
        } else {
            die("<script>alert('Invalid Password!'); window.location='employee_login.php';</script>");
        }
    } else {
        die("<script>alert('Employee ID not found!'); window.location='employee_login.php';</script>");
    }
}

// সিকিউরিটি চেক
if (!isset($_SESSION['emp_id'])) {
    header("Location: employee_login.php");
    exit();
}

$emp_id = $_SESSION['emp_id'];
$station_id = $_SESSION['emp_station_id'];
$designation = $_SESSION['emp_role'];
$shift_time = $_SESSION['emp_shift_time'];
$next_page = ($designation === 'ADMIN') ? 'admin_dashboard.php' : 'worker_dashboard.php';
?>
<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Verification Success | Fuel-X</title>
    <style>
        :root { --primary: #00d2ff; --bg: #0a0a0a; --card: #121212; --success: #28a745; }
        body { background: var(--bg); color: #fff; font-family: 'Segoe UI', sans-serif; display: flex; flex-direction: column; justify-content: center; align-items: center; min-height: 100vh; margin: 0; padding: 20px; }
        .welcome-badge { background: rgba(0, 210, 255, 0.08); border: 2px solid var(--primary); color: var(--primary); padding: 16px 32px; border-radius: 50px; font-weight: bold; font-size: 16px; letter-spacing: 1px; margin-bottom: 30px; text-align: center; text-transform: uppercase; animation: welcomeGlow 2s infinite; }
        @keyframes welcomeGlow { 0%, 100% { box-shadow: 0 0 15px rgba(0, 210, 255, 0.2); opacity: 0.9; } 50% { box-shadow: 0 0 35px rgba(0, 210, 255, 0.7); opacity: 1; } }
        .info-card { background: var(--card); padding: 40px; border-radius: 24px; width: 100%; max-width: 360px; border: 1px solid #222; box-shadow: 0 30px 70px rgba(0,0,0,0.8); text-align: center; }
        .info-box { text-align: left; background: #161616; padding: 20px; border-radius: 12px; border: 1px solid #222; margin-bottom: 25px; font-size: 15px; }
        .info-box p { margin: 10px 0; color: #aaa; }
        .info-box strong { color: #fff; }
        .btn-next { display: block; width: 100%; padding: 16px; background: var(--success); color: #fff; border: none; border-radius: 12px; font-weight: bold; text-transform: uppercase; cursor: pointer; transition: 0.3s; letter-spacing: 1px; text-decoration: none; text-align: center; }
        .btn-next:hover { transform: translateY(-3px); box-shadow: 0 10px 20px rgba(40,167,69,0.3); }
    </style>
</head>
<body>
<div class="welcome-badge">⚡ Welcome Employee <?php echo htmlspecialchars($emp_id); ?> in Station <?php echo str_pad($station_id, 2, "0", STR_PAD_LEFT); ?></div>
<div class="info-card">
    <h3 style="margin: 0 0 20px 0; color: var(--primary); letter-spacing: 1px; text-transform: uppercase; font-size: 16px;">Identity Verified</h3>
    <div class="info-box">
        <p>Designation: <strong><?php echo htmlspecialchars($designation); ?></strong></p>
        <p>Shift Time: <strong><?php echo htmlspecialchars($shift_time); ?></strong></p>
    </div>
    <a href="<?php echo htmlspecialchars($next_page); ?>" class="btn-next">Proceed to Next →</a>
</div>
</body>
</html>