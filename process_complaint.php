<?php
require_once 'includes/db.php';
if (session_status() === PHP_SESSION_NONE) { session_start(); }

if ($_SERVER['REQUEST_METHOD'] === 'POST' && isset($_POST['description'])) {
    
    $user_id = $_SESSION['user_id'] ?? null;
    $station_id = $_SESSION['station_id'] ?? null;
    $description = trim($_POST['description']);

    // সিকিউরিটি গার্ড চেক
    if (!$user_id || !$station_id || empty($description)) {
        header("Location: view_token.php?error=invalid_data");
        exit();
    }

    // ব্যাকএন্ড শব্দ সংখ্যা ভ্যালিডেশন
    $word_count = count(preg_split('/\s+/', trim($description)));
    if ($word_count > 360) {
        header("Location: view_token.php?error=word_limit_exceeded");
        exit();
    }

    // নতুন Complaints টেবিলে রিয়েল ডাটা ইনসার্ট
    $stmt = $conn->prepare("INSERT INTO Complaints (user_id, station_id, description, status, created_at) VALUES (?, ?, ?, 'Pending', NOW())");
    $stmt->bind_param("iis", $user_id, $station_id, $description);
    
    if ($stmt->execute()) {
        // সফলভাবে সেভ হলে কিউ লিস্টে পাঠানো
        header("Location: queue_list.php?complaint=success");
        exit();
    } else {
        header("Location: view_token.php?error=db_error");
        exit();
    }
} else {
    header("Location: dashboard.php");
    exit();
}
?>