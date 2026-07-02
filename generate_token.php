<?php
require_once 'includes/db.php';
if (session_status() === PHP_SESSION_NONE) { session_start(); }

// রিকোয়েস্ট ভ্যালিডেশন
if ($_SERVER['REQUEST_METHOD'] === 'POST' && isset($_POST['fuel_id'])) {
    
    $v_id = intval($_POST['v_id']);
    $fuel_id = intval($_POST['fuel_id']); // ফুয়েল আইডি
    $amount = floatval($_POST['amount']); // পরিমাণ
    
    /**
     * 🔄 স্টেশন আইডি ফিক্স লজিক (আগের ফ্লো ১০০% অক্ষত রেখে)
     */
    $s_id = 1; 
    if (isset($_POST['s_id']) && intval($_POST['s_id']) > 0) {
        $s_id = intval($_POST['s_id']);
    } elseif (isset($_SESSION['station_id']) && intval($_SESSION['station_id']) > 0) {
        $s_id = intval($_SESSION['station_id']);
    } elseif (isset($_SESSION['emp_station_id']) && intval($_SESSION['emp_station_id']) > 0) {
        $s_id = intval($_SESSION['emp_station_id']);
    }
    
    // সেশন থেকে ডাটা সংগ্রহ
    $plate = $_SESSION['v_plate'] ?? '';
    
    /**
     * ১. প্লেট নম্বর থেকে ডাইনামিক ক্যাটাগরি কোড এক্সট্রাকশন
     */
    $type_code = "REG";
    if (!empty($plate)) {
        $parts = explode('-', $plate);
        foreach($parts as $part) {
            $trimmed = trim($part);
            if(preg_match('/^[A-Z]{2,4}$/', $trimmed) && $trimmed !== 'METRO') {
                $type_code = $trimmed; 
                break;
            }
        }
    }

    /**
     * ২. ইউনিক টোকেন আইডি জেনারেশন
     */
    $station_code = "ST" . str_pad($s_id, 2, "0", STR_PAD_LEFT); 
    $random_suffix = substr(md5(uniqid(mt_rand(), true)), 0, 3); 
    $custom_token_id = strtoupper("FX-{$station_code}-{$type_code}-{$random_suffix}");

    /**
     * ৩. চ্যাম্পিয়ন লেভেল প্রায়োরিটি কিউ অ্যালগরিদম
     */
    $is_ambulance = ($type_code === 'CHHA');

    if ($is_ambulance) {
        $scheduled_time = date('Y-m-d H:i:s'); 
    } else {
        $queue_query = $conn->query("SELECT COUNT(*) as total FROM tokens WHERE station_id = $s_id AND (status = 'Pending' OR status = 'pending')");
        $queue_data = $queue_query->fetch_assoc();
        $pending_count = $queue_data['total'] ?? 0;
        
        $waiting_minutes = $pending_count * 3; 
        $scheduled_time = date('Y-m-d H:i:s', strtotime("+$waiting_minutes minutes"));
    }

    /**
     * ৪. ডাটাবেসের Tokens টেবিলে ডাটা ইনসার্ট করা
     * এখানে fuel_type_id এবং quantity সঠিকভাবে ডাটাবেসে যাচ্ছে
     */
    $stmt = $conn->prepare("INSERT INTO tokens (token_id, vehicle_id, station_id, fuel_type_id, quantity, scheduled_time, status, created_at) VALUES (?, ?, ?, ?, ?, ?, 'Pending', NOW())");
    
    // bind_param: s=string, i=int, d=double/float
    // ফরম্যাট স্ট্রিং "siiids" এ কোনো স্পেস রাখা যাবে না।
    $stmt->bind_param("siiids", $custom_token_id, $v_id, $s_id, $fuel_id, $amount, $scheduled_time);
    
    if ($stmt->execute()) {
        $_SESSION['last_token_id'] = $custom_token_id;
        $_SESSION['scheduled_time'] = $scheduled_time; 
        $_SESSION['token_type_code'] = $type_code; 
        $_SESSION['station_id'] = $s_id;
        
        header("Location: view_token.php");
        exit();
    } else {
        header("Location: fuel_selection.php?error=token_failed");
        exit();
    }
} else {
    header("Location: vehicle_entry.php");
    exit();
}
?>