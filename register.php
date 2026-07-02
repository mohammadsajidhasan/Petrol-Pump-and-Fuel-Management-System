<?php
session_start();

// PHPMailer এর প্রয়োজনীয় ফাইলগুলো লোড করা
use PHPMailer\PHPMailer\PHPMailer;
use PHPMailer\PHPMailer\Exception;
use PHPMailer\PHPMailer\SMTP;

require 'PHPMailer/Exception.php';
require 'PHPMailer/PHPMailer.php';
require 'PHPMailer/SMTP.php';

if ($_SERVER['REQUEST_METHOD'] == 'POST' && isset($_POST['register_btn'])) {
    $name = $_POST['name'];
    $phone = $_POST['phone'];
    $password = $_POST['password'];
    $method = $_POST['otp_method'];
    $otp_code = rand(1000, 9999);

    if ($method == 'telegram') {
        // --- TELEGRAM LOGIC ---
        $chat_id = $_POST['chat_id'];
        $apiToken = "8545213477:AAHpedoU0PPhRY2I6eh7mppKUMxQOWiENzE";
        $message = "🔐 *FUEL-X OTP*\n\nHello $name,\nYour Secure OTP is: *$otp_code*";
        $url = "https://api.telegram.org/bot$apiToken/sendMessage?chat_id=$chat_id&text=" . urlencode($message) . "&parse_mode=Markdown";
        @file_get_contents($url);
        $delivery_target = $chat_id;
    } else {
        // --- PROFESSIONAL EMAIL LOGIC (PHPMailer) ---
        $email = $_POST['email'];
        $mail = new PHPMailer(true);

        try {
            // SMTP সেটিংস
            $mail->isSMTP();
            $mail->Host       = 'smtp.gmail.com'; // UIU মেইলও জিমেইল হোস্ট ব্যবহার করে
            $mail->SMTPAuth   = true;
            $mail->Username   = 'ashahriar2320293@bscse.uiu.ac.bd'; // আপনার ইউআইইউ মেইল আইডি
            $mail->Password   = 'rxptcabeklwjslth';              // আপনার ১৬ অক্ষরের App Password
            $mail->SMTPSecure = PHPMailer::ENCRYPTION_STARTTLS;
            $mail->Port       = 587;

            // ইমেইল হেডার ও কন্টেন্ট
            $mail->setFrom('ashahriar2320293@bscse.uiu.ac.bd', 'Fuel-X Security');
            $mail->addAddress($email, $name); 
            $mail->isHTML(true);
            $mail->Subject = 'Fuel-X Identity Verification Code';
            
            // প্রফেশনাল ডার্ক-থিম HTML টেমপ্লেট
            $mail->Body = "
            <div style='font-family: Arial, sans-serif; background-color: #0d0d0d; padding: 40px; color: #fff;'>
                <div style='max-width: 500px; margin: auto; background: #151515; padding: 30px; border-radius: 20px; border: 1px solid #00d2ff; text-align: center;'>
                    <h2 style='color: #00d2ff; letter-spacing: 2px;'>FUEL-X AUTHENTICATION</h2>
                    <hr style='border: 0.5px solid #333; margin: 20px 0;'>
                    <p style='color: #888;'>Hello <b>$name</b>,</p>
                    <p>Your one-time security code for registration is:</p>
                    <div style='font-size: 36px; font-weight: bold; color: #00d2ff; letter-spacing: 8px; margin: 20px 0; padding: 15px; background: rgba(0,210,255,0.1); border-radius: 12px; border: 1px dashed #00d2ff;'>$otp_code</div>
                    <p style='font-size: 12px; color: #555;'>This code will expire in 2 minutes. Please do not share this with anyone.</p>
                </div>
            </div>";

            $mail->send();
            $delivery_target = $email;
        } catch (Exception $e) {
            echo "<script>alert('Email sending failed. Error: {$mail->ErrorInfo}'); window.history.back();</script>";
            exit();
        }
    }

    // সেশনে তথ্য জমা রাখা
    $_SESSION['temp_user'] = [
        'full_name' => $name,
        'phone' => $phone,
        'email' => ($method == 'email') ? $email : null,
        'password' => $password,
        'otp' => $otp_code,
        'method' => $method,
        'target' => $delivery_target,
        'expires' => time() + 120 
    ];

    header("Location: verify_otp.php");
    exit();
}
?>
<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <title>Fuel-X | Secure Registration</title>
    <link rel="stylesheet" href="assets/css/style.css">
    <style>
        .otp-options { display: flex; gap: 10px; margin-bottom: 20px; }
        .method-btn { flex: 1; padding: 10px; background: #1a1a1a; border: 1px solid #333; color: #888; border-radius: 10px; cursor: pointer; text-align: center; font-size: 12px; transition: 0.3s; }
        .method-btn.active { border-color: #00d2ff; color: #00d2ff; background: rgba(0, 210, 255, 0.1); }
        .hidden-group { display: none; }
        .visible { display: block; }
        .help-text { font-size: 10px; color: #00d2ff; text-decoration: none; display: block; margin-top: 5px; }
    </style>
</head>
<body>
    <div class="login-card">
        <h2 style="letter-spacing: 2px;">SECURE REGISTER</h2>
        <form action="" method="POST">
            <div class="input-group">
                <label>Full Name</label>
                <input type="text" name="name" placeholder="Enter Full Name" required>
            </div>

            <div class="input-group">
                <label>Phone Number</label>
                <input type="text" name="phone" placeholder="01XXXXXXXXX" required>
            </div>

            <label style="font-size: 11px; color: #555; margin-bottom: 8px; display: block;">VERIFICATION CHANNEL</label>
            <div class="otp-options">
                <div class="method-btn active" id="btn-tg" onclick="setMethod('telegram')">TELEGRAM</div>
                <div class="method-btn" id="btn-email" onclick="setMethod('email')">EMAIL</div>
            </div>
            <input type="hidden" name="otp_method" id="otp_method" value="telegram">

            <div id="tg-input" class="input-group visible">
                <label>Telegram Chat ID</label>
                <input type="text" name="chat_id" id="field-tg" placeholder="Ex: 12345678" required>
                <a href="https://t.me/userinfobot" target="_blank" class="help-text">Get ID from @userinfobot</a>
            </div>

            <div id="email-input" class="input-group hidden-group" style="display:none;">
                <label>Email Address</label>
                <input type="email" name="email" id="field-email" placeholder="example@mail.com">
            </div>

            <div class="input-group">
                <label>Security Key (Password)</label>
                <input type="password" name="password" placeholder="••••••••" required>
            </div>
            
            <button type="submit" name="register_btn" class="btn-submit">Request Secure Code</button>
        </form>
        <p style="font-size: 12px; margin-top: 15px;">Already have an account? <a href="index.php" style="color: #00d2ff; text-decoration: none;">Login</a></p>
    </div>

    <script>
        function setMethod(method) {
            document.getElementById('otp_method').value = method;
            document.getElementById('btn-tg').classList.toggle('active', method === 'telegram');
            document.getElementById('btn-email').classList.toggle('active', method === 'email');

            if(method === 'telegram') {
                document.getElementById('tg-input').style.display = 'block';
                document.getElementById('email-input').style.display = 'none';
                document.getElementById('field-tg').required = true;
                document.getElementById('field-email').required = false;
            } else {
                document.getElementById('email-input').style.display = 'block';
                document.getElementById('tg-input').style.display = 'none';
                document.getElementById('field-email').required = true;
                document.getElementById('field-tg').required = false;
            }
        }
    </script>
</body>
</html>