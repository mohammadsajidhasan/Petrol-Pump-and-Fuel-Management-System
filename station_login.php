<?php
require_once 'includes/db.php';

$error_message = "";

if ($_SERVER['REQUEST_METHOD'] === 'POST') {
    $email = trim($_POST['email']);
    $password = trim($_POST['password']);

    if (!empty($email) && !empty($password)) {
        // Query the owners table directly
        $stmt = $conn->prepare("SELECT * FROM owners WHERE email = ? LIMIT 1");
        $stmt->bind_param("s", $email);
        $stmt->execute();
        $result = $stmt->get_result();

        if ($result && $result->num_rows === 1) {
            $user = $result->fetch_assoc();
            
            // DIRECT PLAIN TEXT COMPARISON (No Hashing)
            if ($password === $user['password_hash']) {
                // Provision session variables
                $_SESSION['owner_id'] = $user['owner_id'];
                $_SESSION['owner_name'] = $user['full_name'];
                
                header("Location: station_dashboard.php");
                exit;
            } else {
                $error_message = "Invalid matching criteria profile keys.";
            }
        } else {
            $error_message = "Invalid matching criteria profile keys.";
        }
    } else {
        $error_message = "Please fill in all security input parameters.";
    }
}
?>
<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Station Node Portal</title>
    <link href="https://fonts.googleapis.com/css2?family=Inter:wght@400;500;600;700&display=swap" rel="stylesheet">
    <link rel="stylesheet" href="style_station_bpc.css">
</head>
<body class="station-login-body">
    <div class="login-card">
        <h2>Station Node Portal</h2>
        
        <?php if (!empty($error_message)): ?>
            <div class="alert"><?php echo $error_message; ?></div>
        <?php endif; ?>

        <form action="station_login.php" method="POST">
            <div class="form-group">
                <label>Email Address</label>
                <input type="email" name="email" required>
            </div>
            <div class="form-group">
                <label>Password</label>
                <input type="password" name="password" required>
            </div>
            <button type="submit">Authenticate System Access</button>
        </form>
    </div>
</body>
</html>