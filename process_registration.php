<?php
session_start();
require_once 'includes/db.php';

if (isset($_POST['final_register_btn'])) {
    if (!isset($_SESSION['temp_user'])) {
        echo "<script>alert('Session expired!'); window.location.href='register.php';</script>";
        exit();
    }

    $entered_otp = $_POST['otp_input'];
    $confirm_pass = $_POST['confirm_password'];
    $temp_data = $_SESSION['temp_user'];

    // ১. ওটিপি এবং সময় ভ্যালিডেশন
    if ($entered_otp == $temp_data['otp'] && time() <= $temp_data['expires']) {
        
        // ২. সিকিউরিটি কি (পাসওয়ার্ড) কনফার্মেশন চেক
        if ($confirm_pass === $temp_data['password']) {
            
            $name = $temp_data['full_name'];
            $phone = $temp_data['phone'];
            $email = isset($temp_data['email']) ? $temp_data['email'] : null;
            $hashed_pass = password_hash($temp_data['password'], PASSWORD_DEFAULT);

            // ৩. ডাটাবেসে ইনসার্ট (টেবিল নাম 'users' আপনার রিসেট করা ডাটাবেস অনুযায়ী)
            $sql = "INSERT INTO users (name, phone, email, password_hash) VALUES (?, ?, ?, ?)";
            $stmt = $conn->prepare($sql);
            
            if ($stmt) {
                $stmt->bind_param("ssss", $name, $phone, $email, $hashed_pass);

                if ($stmt->execute()) {
                    // ৪. অটোমেটিক সেশন জেনারেশন
                    $new_id = $stmt->insert_id; 
                    
                    $_SESSION['user_id'] = $new_id;
                    $_SESSION['user_name'] = $name;
                    $_SESSION['user_phone'] = $phone;

                    // অস্থায়ী সেশন ডেটা মুছে ফেলা
                    unset($_SESSION['temp_user']); 

                    // ৫. স্মার্ট রিডাইরেকশন (চ্যাম্পিয়ন ফিচার)
                    // সরাসরি dashboard.php তে না পাঠিয়ে location_detector.php তে পাঠানো হচ্ছে
                    echo "<script>
                        alert('Identity Verified Successfully! Proceeding to location detection.');
                        window.location.href='location_detector.php';
                    </script>";
                    exit();
                } else {
                    echo "<script>alert('Registration Failed: " . addslashes($stmt->error) . "'); window.history.back();</script>";
                }
                $stmt->close();
            } else {
                echo "Database Error: " . $conn->error;
            }
        } else {
            echo "<script>alert('Security Key mismatch! Please re-type your password.'); window.history.back();</script>";
        }
    } else {
        echo "<script>alert('Invalid or Expired OTP! Please try again.'); window.location.href='register.php';</script>";
    }
}
?>