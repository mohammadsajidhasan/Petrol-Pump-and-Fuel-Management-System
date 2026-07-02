<?php
session_start();
// ইউজার সরাসরি এই পেজে আসতে পারবে না, তাকে রেজিস্ট্রেশন পেজ হয়ে আসতে হবে
if (!isset($_SESSION['temp_user'])) {
    header("Location: register.php");
    exit();
}

// সেশন থেকে মেথড এবং টার্গেট (ইমেইল/ফোন) নেওয়া
$method = $_SESSION['temp_user']['method'];
$target = $_SESSION['temp_user']['target'];
?>
<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Fuel-X | Verification</title>
    <link rel="stylesheet" href="assets/css/style.css">
    <style>
        body { background: #0d0d0d; color: #fff; font-family: 'Segoe UI', sans-serif; display: flex; justify-content: center; align-items: center; height: 100vh; margin: 0; }
        .verify-card { 
            background: #151515; 
            padding: 40px; 
            border-radius: 25px; 
            border: 2px dashed rgba(0, 210, 255, 0.4); 
            width: 350px; 
            text-align: center; 
            box-shadow: 0 0 30px rgba(0, 210, 255, 0.1); 
        }
        .otp-input { 
            width: 100%; 
            background: #0d0d0d; 
            border: 1px solid #333; 
            color: #00d2ff; 
            font-size: 32px; 
            text-align: center; 
            padding: 15px 0; 
            border-radius: 12px; 
            margin: 20px 0; 
            letter-spacing: 10px; 
            font-weight: bold; 
            outline: none; 
            transition: 0.3s;
        }
        .otp-input:focus { border-color: #00d2ff; box-shadow: 0 0 10px rgba(0, 210, 255, 0.2); }
        .btn-submit { 
            width: 100%; 
            padding: 15px; 
            background: #00d2ff; 
            border: none; 
            border-radius: 10px; 
            font-weight: bold; 
            color: #000; 
            cursor: pointer; 
            text-transform: uppercase; 
            transition: 0.3s; 
            letter-spacing: 1px;
        }
        .btn-submit:hover { background: #fff; box-shadow: 0 0 20px #00d2ff; }
        .info-text { font-size: 13px; color: #888; margin-bottom: 5px; }
        .target-val { color: #00d2ff; font-weight: bold; }
    </style>
</head>
<body>
    <div class="verify-card">
        <h2 style="letter-spacing: 2px; margin-bottom: 10px;">VERIFY IDENTITY</h2>
        
        <p class="info-text">Code sent via <span style="text-transform: uppercase;"><?php echo htmlspecialchars($method); ?></span></p>
        <p style="font-size: 11px; color: #555;">Check: <span class="target-val"><?php echo htmlspecialchars($target); ?></span></p>
        
        <!-- Form action process_registration.php তেই থাকবে, সেখানে রিডাইরেক্ট লজিক আপডেট হবে -->
        <form action="process_registration.php" method="POST">
            <input type="text" name="otp_input" maxlength="4" placeholder="0000" class="otp-input" required autofocus autocomplete="off">
            
            <div style="text-align: left; color: #555; font-size: 11px; margin-bottom: 5px; text-transform: uppercase;">Confirm Security Key</div>
            <input type="password" name="confirm_password" placeholder="Re-enter Password" required 
                   style="width: 100%; padding: 12px; background: rgba(255,255,255,0.05); border: 1px solid #222; color: #fff; border-radius: 10px; box-sizing: border-box; margin-bottom: 20px; outline: none;">
            
            <!-- বাটনের টেক্সট আপডেট করা হয়েছে যা নতুন ফ্লো নির্দেশ করে -->
            <button type="submit" name="final_register_btn" class="btn-submit">Verify & Detect Location</button>
        </form>

        <p id="timer" style="color: #ff4b2b; font-size: 12px; margin-top: 20px;">Link expires in: <span id="time">02:00</span></p>
    </div>

    <script>
        // ২ মিনিটের ওটিপি টাইমার
        var seconds = 120;
        function countdown() {
            seconds--;
            var min = Math.floor(seconds / 60);
            var sec = seconds % 60;
            document.getElementById('time').innerHTML = (min < 10 ? "0" : "") + min + ":" + (sec < 10 ? "0" : "") + sec;
            if (seconds > 0) {
                setTimeout(countdown, 1000);
            } else { 
                alert('OTP Expired! Redirecting to register page.');
                window.location.href="register.php"; 
            }
        }
        countdown();
    </script>
</body>
</html>