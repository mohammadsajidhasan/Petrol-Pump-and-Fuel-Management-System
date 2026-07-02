<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Employee Portal | Fuel-X</title>
    <style>
        :root { --primary: #00d2ff; --bg: #0a0a0a; --card: #121212; }
        body { background: var(--bg); color: #fff; font-family: 'Segoe UI', sans-serif; display: flex; justify-content: center; align-items: center; min-height: 100vh; margin: 0; }
        .login-card { background: var(--card); padding: 40px; border-radius: 24px; width: 360px; border: 1px solid #222; box-shadow: 0 30px 70px rgba(0,0,0,0.8); text-align: center; }
        .input-group { text-align: left; margin-bottom: 25px; }
        .input-group label { display: block; font-size: 11px; color: #888; text-transform: uppercase; margin-bottom: 8px; letter-spacing: 1px; font-weight: bold; }
        .input-group input { width: 100%; padding: 15px; background: #161616; border: 1px solid #333; border-radius: 12px; color: #fff; font-size: 16px; box-sizing: border-box; text-align: center; letter-spacing: 1px; }
        .input-group input:focus { border-color: var(--primary); outline: none; box-shadow: 0 0 15px rgba(0,210,255,0.2); }
        .btn-login { width: 100%; padding: 16px; background: var(--primary); color: #000; border: none; border-radius: 12px; font-weight: bold; text-transform: uppercase; cursor: pointer; transition: 0.3s; letter-spacing: 1px; }
        .btn-login:hover { transform: translateY(-3px); box-shadow: 0 10px 20px rgba(0,210,255,0.3); }
    </style>
</head>
<body>
<div class="login-card">
    <h2 style="margin: 0 0 8px 0; color: var(--primary); letter-spacing: 1px;">Fuel-X Portal</h2>
    <p style="color: #555; font-size: 11px; margin-bottom: 35px; text-transform: uppercase; letter-spacing: 2px;">Employee Authentication</p>
    
    <form action="employee_verify.php" method="POST">
        <div class="input-group">
            <label>Enter Employee ID</label>
            <input type="number" name="employee_id" placeholder="Enter Numeric ID (e.g. 12)" autocomplete="off" required autofocus>
        </div>
        
        <div class="input-group">
            <label>Enter Password</label>
            <input type="password" name="password" placeholder="Enter Secure Password" required>
        </div>

        <button type="submit" name="emp_login_btn" class="btn-login">Verify & Enter</button>
    </form>
</div>
</body>
</html>