<?php
session_start();
require_once 'includes/db.php';

// ১. এমপ্লয়ি লগইন ভেরিফিকেশন (Shifts টেবিল থেকে রিয়েল টাইম তুলে আনার অংশ)
if (isset($_POST['emp_login_btn'])) {
    $emp_id = intval($_POST['employee_id']);
    
    // employee টেবিল থেকে বেসিক ডেটা চেক
    $stmt = $conn->prepare("SELECT employee_id, station_id, designation FROM employee WHERE employee_id = ?");
    $stmt->bind_param("i", $emp_id);
    $stmt->execute();
    $result = $stmt->get_result();

    if ($result->num_rows > 0) {
        $emp = $result->fetch_assoc();
        
        // সেশনে ডেটা সেভ
        $_SESSION['emp_id'] = $emp['employee_id'];
        $_SESSION['emp_station_id'] = $emp['station_id'];
        $_SESSION['emp_role'] = strtoupper(trim($emp['designation']));

        // রোল চেক: ADMIN নাকি WORKER
        if ($_SESSION['emp_role'] === 'ADMIN') {
            $_SESSION['emp_shift_time'] = "Full-Time Access (Admin)";
            header("Location: employee_verify.php");
            exit();
        } else {
            // WORKER হলে shifts টেবিল থেকে তার নির্ধারিত শিফট টাইম সরাসরি তুলে আনা হবে
            // (এখানে কোনো BETWEEN কন্ডিশন রাখা হয়নি, যাতে শিফটের সময় পার হয়ে গেলেও ডাটাবেসের টাইমটাই শো করে)
            $stmt_shift = $conn->prepare("SELECT start_time, end_time FROM shifts WHERE employee_id = ? LIMIT 1");
            $stmt_shift->bind_param("i", $emp_id);
            $stmt_shift->execute();
            $res_shift = $stmt_shift->get_result();

            if ($row = $res_shift->fetch_assoc()) {
                // ডাটাবেসের start_time এবং end_time-কে সুন্দর ফরম্যাটে রূপান্তর (যেমন: 08:00 AM - 04:00 PM)
                $st = date('h:i A', strtotime($row['start_time']));
                $et = date('h:i A', strtotime($row['end_time']));
                $_SESSION['emp_shift_time'] = "$st - $et";
            } else {
                // ডাটাবেসে যদি ওই আইডির কোনো শিফট এন্ট্রি একদমই না থাকে
                $_SESSION['emp_shift_time'] = "Not Assigned";
            }
            
            header("Location: employee_verify.php");
            exit();
        }
    } else {
        header("Location: employee_login.php");
        exit();
    }
}

// ২. ওল্ড ফ্লো (কাস্টমার / ড্রাইভার লগইন) - এটি ১০০% অক্ষত রাখা হয়েছে
if (isset($_POST['login_btn'])) {
    $phone = mysqli_real_escape_string($conn, $_POST['phone']);
    $password = $_POST['password'];

    $result = $conn->query("SELECT * FROM users WHERE phone = '$phone'");
    
    if ($result->num_rows > 0) {
        $user = $result->fetch_assoc();
        
        if (password_verify($password, $user['password_hash'])) {
            $_SESSION['user_id'] = $user['user_id']; 
            $_SESSION['user_name'] = $user['name'];
            $_SESSION['user_phone'] = $user['phone'];

            header("Location: location_detector.php");
            exit();
        } else {
            echo "<script>alert('Incorrect Security Key!'); window.location='index.php';</script>";
        }
    } else {
        echo "<script>alert('User not found!'); window.location='index.php';</script>";
    }
}
?>