<?php 
if (session_status() === PHP_SESSION_NONE) { session_start(); }
if(!isset($_SESSION['user_id'])) { header("Location: index.php"); exit(); }
?>
<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Fuel-X | Smart Dashboard</title>
    <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.0.0/css/all.min.css">
    <style>
        :root { --bg: #0d0d0d; --card: #151515; --primary: #00d2ff; }
        body { background: var(--bg); color: #fff; font-family: 'Segoe UI', sans-serif; margin: 0; }
        .main-container { width: 95%; max-width: 1200px; margin: 40px auto; padding: 20px; display: flex; gap: 25px; flex-wrap: wrap; }
        .dashboard-content { flex: 3; }
        .price-side-panel { flex: 1; min-width: 250px; background: var(--card); padding: 20px; border-radius: 18px; border: 1px solid rgba(0,210,255,0.2); height: fit-content; }
        .header-section { text-align: center; margin-bottom: 40px; }
        .main-title { font-size: 32px; font-weight: 900; letter-spacing: 5px; color: #fff; text-transform: uppercase; }
        .grid-wrapper { display: grid; grid-template-columns: repeat(auto-fit, minmax(320px, 1fr)); gap: 25px; }
        .station-card { background: var(--card); border-radius: 18px; padding: 25px; transition: 0.4s; position: relative; border: 1px solid rgba(255,255,255,0.05); }
        .station-card.low { border-top: 5px solid #28a745; }
        .station-card.medium { border-top: 5px solid #ffc107; }
        .station-card.high { border-top: 5px solid #dc3545; }
        .queue-badge { position: absolute; top: 15px; right: 15px; padding: 4px 12px; border-radius: 8px; font-size: 11px; font-weight: bold; }
        .bg-low { background: #28a745; color: #fff; } .bg-medium { background: #ffc107; color: #000; } .bg-high { background: #dc3545; color: #fff; }
        .distance-tag { color: var(--primary); font-weight: bold; font-size: 14px; margin: 10px 0; display: block; }
        .map-box { background: rgba(0, 210, 255, 0.05); border: 1px dashed var(--primary); border-radius: 10px; padding: 10px; text-align: center; display: block; margin-bottom: 15px; }
        .btn-select { display: block; width: 100%; padding: 12px; background: var(--primary); color: #000; text-align: center; border-radius: 10px; font-weight: bold; text-decoration: none; }
    </style>
</head>
<body>
    <div class="main-container">
        <div class="dashboard-content">
            <div class="header-section">
                <h2 class="main-title">SMART TERMINAL FINDER</h2>
                <p style="color: #888;">Detecting your location and finding stations within <b>20 KM</b>...</p>
            </div>
            <div id="station-container" class="grid-wrapper">
                <div style="text-align: center; width: 100%; padding: 50px;">Accessing GPS...</div>
            </div>
        </div>
        <div class="price-side-panel">
            <h3 style="color:var(--primary); margin-top:0;">FUEL PRICES</h3>
            <div id="price-list">Loading...</div>
        </div>
    </div>

    <script>
        function fetchStations(lat, lng) {
            fetch(`/fuel_system/fetch_stations.php?lat=${lat}&lng=${lng}`)
                .then(res => res.json())
                .then(data => {
                    // প্রাইস আপডেট
                    document.getElementById('price-list').innerHTML = data.prices.map(p => 
    `<div style="padding: 10px 0; border-bottom: 1px solid #333;">${p.fuel_name}: <b>${p.price_per_liter} Tk</b></div>`
).join('');

                    // স্টেশন আপডেট
                    const container = document.getElementById('station-container');
                    if(data.stations.length === 0) { container.innerHTML = `<div style="grid-column: 1/-1; text-align: center; padding: 50px; color: #666;">No stations found.</div>`; return; }
                    container.innerHTML = data.stations.map(s => `
                        <div class="station-card ${s.status_type}">
                            <div class="queue-badge ${s.badge_class}">${s.label}</div>
                            <span style="color: #666; font-size: 12px;">📍 ${s.location}</span>
                            <h3 style="margin: 10px 0; color: #fff;">${s.name}</h3>
                            <span class="distance-tag">📏 ${s.distance} KM away</span>
                            <a href="https://www.google.com/maps/search/?api=1&query=${s.latitude},${s.longitude}" target="_blank" class="map-box">🗺️ VIEW ON GOOGLE MAPS</a>
                            <a href="vehicle_entry.php?station_id=${s.station_id}" class="btn-select">SELECT STATION</a>
                        </div>
                    `).join('');
                });
        }
        fetchStations(23.8103, 90.4125);
        setInterval(() => fetchStations(23.8103, 90.4125), 5000);
    </script>
</body>
</html>