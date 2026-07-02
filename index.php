<?php require_once 'includes/db.php'; ?>
<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Fuel-X | Futuristic Login</title>
    <link rel="stylesheet" href="assets/css/style.css">
    <style>
        .reg-link { display: block; margin-top: 20px; text-align: center; font-size: 13px; color: #555; text-decoration: none; transition: 0.3s; }
        .reg-link span { color: #00d2ff; font-weight: bold; }
        .reg-link:hover { color: #fff; }

        .portal-gateways-divider { margin: 25px 0 20px; border: 0; border-top: 1px dashed #334155; position: relative; }
        .portal-gateways-divider::after { content: "OR ACCESS CENTRAL PORTALS"; position: absolute; top: 50%; left: 50%; transform: translate(-50%, -50%); background: #0f172a; padding: 0 10px; font-size: 10px; color: #64748b; font-weight: 600; letter-spacing: 1px; }
        .portal-grid { display: grid; grid-template-columns: 1fr 1fr; gap: 12px; margin-top: 15px; }
        .portal-btn { display: flex; flex-direction: column; align-items: center; justify-content: center; padding: 12px 8px; border-radius: 6px; text-decoration: none; font-size: 12px; font-weight: 600; text-transform: uppercase; letter-spacing: 0.5px; transition: all 0.2s ease-in-out; text-align: center; }
        
        .btn-station-portal { background-color: #1e293b; border: 1px solid #0284c7; color: #38bdf8; }
        .btn-station-portal:hover { background-color: #0284c7; color: #fff; }
        .btn-bpc-portal { background-color: #1e293b; border: 1px solid #e11d48; color: #f43f5e; }
        .btn-bpc-portal:hover { background-color: #e11d48; color: #fff; }
        
        .portal-btn span { font-size: 9px; color: #94a3b8; font-weight: 400; margin-top: 2px; text-transform: none; }
        .portal-btn:hover span { color: #fff; }

        /* Employee Portal Styles */
        .btn-employee-portal { background-color: #1e293b; border: 1px solid #10b981; color: #10b981; margin-bottom: 15px; width: 100%; box-sizing: border-box; position: relative; }
        .btn-employee-portal:hover { background-color: #10b981; color: #fff; }
        @keyframes blinker { 50% { opacity: 0; } }
    </style>
</head>
<body>

    <div class="login-card">
        <h2 style="letter-spacing: 3px;">FUEL-X</h2>
        <form action="auth_action.php" method="POST">
            <div class="input-group">
                <label>Access Code (Phone)</label>
                <input type="text" name="phone" placeholder="01XXXXXXXXX" required autocomplete="off">
            </div>
            <div class="input-group">
                <label>Security Key (Password)</label>
                <input type="password" name="password" placeholder="••••••••" required>
            </div>
            <button type="submit" name="login_btn" class="btn-submit">Initialize Session</button>
        </form>

        <a href="register.php" class="reg-link">New to Fuel-X? <span>Register Now</span></a>

        <hr class="portal-gateways-divider">
        
        <a href="employee_login.php" class="portal-btn btn-employee-portal">
            <div style="position: absolute; top: 5px; right: 8px; font-size: 8px; color: orange; animation: blinker 1s linear infinite;">
                TOKEN: <?php 
                    $res = $conn->query("SELECT token_id FROM tokens ORDER BY created_at DESC LIMIT 1");
                    $row = $res->fetch_assoc();
                    echo $row['token_id'] ?? 'N/A';
                ?>
            </div>
            Employee Portal
            <span>Authorized Staff Hub</span>
        </a>
        
        <div class="portal-grid">
            <a href="station_login.php" class="portal-btn btn-station-portal">
                Station Portal
                <span>Owner Node Login</span>
            </a>
            <a href="bpc_login.php" class="portal-btn btn-bpc-portal">
                BPC Logistics
                <span>Corporate Supply Hub</span>
            </a>
        </div>
    </div>

</body>
</html>