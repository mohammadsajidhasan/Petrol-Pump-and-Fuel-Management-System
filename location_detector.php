<?php
session_start();
// ইউজার লগইন করা আছে কিনা নিশ্চিত করা
if (!isset($_SESSION['user_id'])) { 
    header("Location: index.php"); 
    exit(); 
}
?>
<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <title>Fuel-X | Initializing...</title>
    <style>
        body { background: #0d0d0d; color: #fff; font-family: 'Segoe UI', sans-serif; display: flex; justify-content: center; align-items: center; height: 100vh; margin: 0; }
        .loader { text-align: center; }
        .spinner { border: 4px solid rgba(255,255,255,0.1); border-left-color: #00d2ff; border-radius: 50%; width: 50px; height: 50px; animation: spin 1s linear infinite; margin: 20px auto; }
        @keyframes spin { to { transform: rotate(360deg); } }
        h2 { color: #00d2ff; text-transform: uppercase; }
    </style>
</head>
<body>
    <div class="loader">
        <div class="spinner"></div>
        <h2>Initializing Smart Navigation...</h2>
        <p>Pinpointing your location for nearby stations.</p>
    </div>
    <script>
        window.onload = function() {
            // ড্যাশবোর্ডে রিডাইরেক্ট করার সময় ডিফল্ট ঢাকার কোঅর্ডিনেট পাঠানোই এখন সবচেয়ে নিরাপদ
            // কারণ ব্রাউজার পারমিশন ব্লক করলে লুপ তৈরি হতে পারে।
            const defaultLat = 23.8103;
            const defaultLng = 90.4125;

            if (navigator.geolocation) {
                navigator.geolocation.getCurrentPosition(
                    (pos) => {
                        window.location.href = `dashboard.php?lat=${pos.coords.latitude}&lng=${pos.coords.longitude}`;
                    },
                    (err) => {
                        // এরর হলেও ঢাকার লোকেশন দিয়েই ড্যাশবোর্ডে পাঠাবে
                        window.location.href = `dashboard.php?lat=${defaultLat}&lng=${defaultLng}`;
                    },
                    { enableHighAccuracy: true, timeout: 5000, maximumAge: 0 }
                );
            } else {
                window.location.href = `dashboard.php?lat=${defaultLat}&lng=${defaultLng}`;
            }
        };
    </script>
</body>
</html>