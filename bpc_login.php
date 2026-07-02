<?php
require_once 'includes/db.php';

$error_message = "";

if ($_SERVER['REQUEST_METHOD'] === 'POST') {
    $email = trim($_POST['email']);
    $password = trim($_POST['password']);

    if (!empty($email) && !empty($password)) {
        // Query corporate supply nodes
        $stmt = $conn->prepare("SELECT * FROM suppliers WHERE email = ? LIMIT 1");
        $stmt->bind_param("s", $email);
        $stmt->execute();
        $result = $stmt->get_result();

        if ($result && $result->num_rows === 1) {
            $supplier = $result->fetch_assoc();
            
            // DIRECT PLAIN TEXT COMPARISON (No Hashing)
            if ($password === $supplier['password_hash']) {
                $_SESSION['supplier_id'] = $supplier['supplier_id'];
                $_SESSION['supplier_name'] = $supplier['company_name'];
                
                header("Location: bpc_dashboard.php");
                exit;
            } else {
                $error_message = "Invalid corporate access keys.";
            }
        } else {
            $error_message = "Invalid corporate access keys.";
        }
    } else {
        $error_message = "All field parameters must be provided.";
    }
}
?>
<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>BPC Supply Portal Login</title>
    <link href="https://fonts.googleapis.com/css2?family=Inter:wght@400;500;600;700&display=swap" rel="stylesheet">
    <link rel="stylesheet" href="style_station_bpc.css">
</head>
<body class="bpc-login-body">
    <div class="login-card">
        <h2>BPC Logistics Login</h2>
        
        <?php if (!empty($error_message)): ?>
            <div class="alert"><?php echo $error_message; ?></div>
        <?php endif; ?>

        <form action="bpc_login.php" method="POST">
            <div class="form-group">
                <label>Email Address</label>
                <input type="email" name="email" required>
            </div>
            <div class="form-group">
                <label>Password</label>
                <input type="password" name="password" required>
            </div>
            <button type="submit">Verify Corporate Identity</button>
        </form>
    </div>
</body>
</html>