<?php 
require_once 'includes/db.php';
if (session_status() === PHP_SESSION_NONE) { session_start(); }

/**
 * ১. ফ্লো ভ্যালিডেশন:
 * স্টেশন আইডি পাওয়া না গেলে ইউজারকে পুনরায় ড্যাশবোর্ডে পাঠাবে।
 */

// ইউআরএল থেকে আসা স্টেশন আইডি সেশনে সেভ করা
if (isset($_GET['station_id'])) {
    $_SESSION['station_id'] = intval($_GET['station_id']);
}

// সেশনে স্টেশন আইডি না থাকলে ড্যাশবোর্ডে ফেরত পাঠানো
if (!isset($_SESSION['station_id'])) {
    header("Location: dashboard.php"); 
    exit();
}
?>
<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Vehicle Verification | Fuel-X</title>
    <style>
        :root {
            --primary: #00d2ff;
            --bg-dark: #0d0d0d;
            --card-bg: #151515;
            --text-dim: #888;
            --error: #ff4d4d;
        }

        body { 
            background: var(--bg-dark); 
            color: #fff; 
            font-family: 'Inter', 'Segoe UI', sans-serif; 
            display: flex; 
            justify-content: center; 
            align-items: center; 
            min-height: 100vh; 
            margin: 0; 
            overflow: hidden;
        }

        body::before {
            content: '';
            position: absolute;
            width: 300px;
            height: 300px;
            background: var(--primary);
            filter: blur(150px);
            opacity: 0.1;
            z-index: -1;
        }

        .auth-container { 
            background: var(--card-bg); 
            padding: 50px 40px; 
            border-radius: 24px; 
            width: 100%; 
            max-width: 450px; 
            text-align: center; 
            border: 1px solid #222; 
            box-shadow: 0 25px 50px rgba(0,0,0,0.6); 
            transition: 0.4s ease;
        }

        /*--- নতুন ওয়েলকাম ব্যাজ ডিজাইন (Glow Effect) ---*/
        .welcome-badge {
            font-size: 11px;
            font-weight: 800;
            letter-spacing: 2px;
            color: #666;
            margin-bottom: 15px;
            text-transform: uppercase;
            display: block;
        }

        .welcome-badge span {
            color: var(--primary);
            text-shadow: 0 0 10px rgba(0, 210, 255, 0.4);
            animation: pulseGlow 2s infinite;
        }

        @keyframes pulseGlow {
            0%, 100% { opacity: 0.7; text-shadow: 0 0 5px var(--primary); }
            50% { opacity: 1; text-shadow: 0 0 15px var(--primary); }
        }
        /*--------------------------------------------*/

        h2 { 
            color: var(--primary); 
            margin-bottom: 8px; 
            font-weight: 800; 
            font-size: 28px;
            margin-top: 5px;
        }

        p { 
            color: var(--text-dim); 
            margin-bottom: 35px; 
            font-size: 15px; 
            line-height: 1.5;
        }
        
        .plate-input { 
            width: 100%; 
            padding: 20px; 
            background: #1a1a1a; 
            border: 2px solid #333; 
            border-radius: 14px; 
            color: var(--primary); 
            font-size: 20px; 
            text-align: center; 
            margin-bottom: 20px; 
            box-sizing: border-box; 
            text-transform: uppercase; 
            letter-spacing: 2px; 
            font-weight: 700; 
            transition: 0.3s; 
        }

        .plate-input:focus { 
            border-color: var(--primary); 
            outline: none; 
            background: #222;
            box-shadow: 0 0 20px rgba(0, 210, 255, 0.15);
        }

        .btn-main { 
            width: 100%; 
            padding: 20px; 
            background: var(--primary); 
            color: #000; 
            border: none; 
            border-radius: 14px; 
            font-weight: 800; 
            cursor: pointer; 
            text-transform: uppercase; 
            transition: 0.3s; 
            font-size: 16px; 
            letter-spacing: 1px;
        }

        .btn-main:hover { 
            background: #00b8e6; 
            transform: translateY(-3px); 
        }

        .error-hint { 
            color: var(--error); 
            font-size: 14px; 
            margin-bottom: 25px; 
            padding: 15px; 
            background: rgba(255, 77, 77, 0.08); 
            border-radius: 12px; 
            border-left: 4px solid var(--error); 
            display: none; 
            animation: slideIn 0.4s ease;
        }

        @keyframes slideIn {
            from { opacity: 0; transform: translateY(-10px); }
            to { opacity: 1; transform: translateY(0); }
        }

        .example-text {
            margin-top: 30px; 
            font-size: 12px; 
            color: #555;
            line-height: 1.6;
        }
    </style>
</head>
<body>
    <div class="auth-container">
        <div class="welcome-badge">
            WELCOME - STATION ID: <span>#<?php echo $_SESSION['station_id']; ?></span>
        </div>

        <h2>Vehicle Entry</h2>
        <p>Please enter your digital plate number to proceed</p>
        
        <?php if(isset($_GET['error'])): ?>
            <div class="error-hint" style="display: block;">
                <?php 
                    $err = $_GET['error'];
                    if($err == 'not_found') echo "❌ Plate number not recognized.";
                    elseif($err == 'invalid_session') echo "⚠️ Session expired. Please enter details again.";
                    else echo "⚠️ Registration failed. Try again.";
                ?>
            </div>
        <?php endif; ?>

        <div id="js-error" class="error-hint"></div>

        <form id="vEntryForm" action="process_vehicle.php" method="POST">
            <input type="text" name="plate_number" id="plate_number" class="plate-input" 
                   placeholder="CITY-METRO-CODE-XX-XXXX" required autocomplete="off" autofocus>
            
            <button type="submit" class="btn-main">VERIFY & NEXT</button>
        </form>
        
        <div class="example-text">
            <strong>Example:</strong> DHAKA-METRO-CHHA-11-2222<br>
            <strong>Regular:</strong> DHAKA-METRO-GA-11-2222
        </div>
    </div>

    <script>
    document.getElementById('vEntryForm').onsubmit = function(e) {
        const plateInput = document.getElementById('plate_number');
        const plateValue = plateInput.value.trim().toUpperCase();
        const errorDiv = document.getElementById('js-error');
        
        const flexibleRegex = /^[A-Z]+(-METRO)?-[A-Z]{2,4}-\d{2}-\d{4}$/;
        
        if(!flexibleRegex.test(plateValue)) {
            e.preventDefault(); 
            errorDiv.style.display = 'block';
            errorDiv.innerHTML = "❌ <strong>Invalid Format!</strong><br>Example: DHAKA-METRO-CHHA-11-2222";
            plateInput.style.borderColor = '#ff4d4d';
            plateInput.focus();
        } else {
            errorDiv.style.display = 'none';
        }
    };

    document.getElementById('plate_number').addEventListener('input', function (e) {
        this.style.borderColor = '#333';
    });
    </script>
</body>
</html>