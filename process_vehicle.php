<?php
require_once 'includes/db.php';

if (session_status() === PHP_SESSION_NONE) {
    session_start();
}

/**
 * ১. রিকোয়েস্ট ভ্যালিডেশন
 */
if ($_SERVER['REQUEST_METHOD'] === 'POST' && isset($_POST['plate_number'])) {
    
    /**
     * ২. স্মার্ট সেশন হ্যান্ডলিং:
     * গাড়ির পুরাতন সেশন ডাটা ক্লিয়ার করা হচ্ছে।
     */
    $vehicle_session_keys = ['v_plate', 'v_id', 'v_type', 'v_limit', 'v_bg', 'v_text', 'v_type_id'];
    foreach ($vehicle_session_keys as $key) {
        if (isset($_SESSION[$key])) {
            unset($_SESSION[$key]);
        }
    }

    // প্লেট নম্বর ক্লিন এবং ফরম্যাট করা
    $plate = strtoupper(trim($_POST['plate_number']));
    
    /**
     * ৩. ক্যাটাগরি কোড এক্সট্রাকশন লজিক:
     */
    $parts = explode('-', $plate);
    $type_code = "";
    foreach($parts as $part) {
        $trimmed = trim($part);
        if(preg_match('/^[A-Z]{2,4}$/', $trimmed) && $trimmed !== 'METRO') {
            $type_code = $trimmed;
            break;
        }
    }

    /**
     * ৪. বাংলাদেশ স্ট্যান্ডার্ড অনুযায়ী প্রফেশনাল ম্যাপিং
     * (আপনার আগের ফ্লো ঠিক রেখে এখানে শুধু 'type_id' যোগ করা হয়েছে)
     */
    $mapping = [
        'CHHA' => ['name' => 'Ambulance (Emergency)', 'limit' => 100.0, 'bg' => '#FFFFFF', 'text' => '#D32F2F', 'type_id' => 1], // ছ - অ্যাম্বুলেন্স (ID: 1)
        'GA'   => ['name' => 'Private Car',          'limit' => 40.0,  'bg' => '#FFFFFF', 'text' => '#000000', 'type_id' => 2], // গ - প্রাইভেট কার (ID: 2)
        'KHA'  => ['name' => 'Private Car (New)',     'limit' => 60.0,  'bg' => '#FFFFFF', 'text' => '#000000', 'type_id' => 3], // খ - জিপ/বড় গাড়ি (ID: 3)
        'HA'   => ['name' => 'Motorcycle',           'limit' => 15.0,  'bg' => '#FFFFFF', 'text' => '#000000', 'type_id' => 4], // হ - মোটরসাইকেল (ID: 4)
        'BA'   => ['name' => 'Bus',                  'limit' => 150.0, 'bg' => '#FFFFFF', 'text' => '#000000', 'type_id' => 5], // ব - বাস (ID: 5)
        'CHA'  => ['name' => 'Microbus',             'limit' => 80.0,  'bg' => '#FFFFFF', 'text' => '#000000', 'type_id' => 6], // চ - মাইক্রোবাস (ID: 6)
        'DA'   => ['name' => 'Truck',                'limit' => 120.0, 'bg' => '#FFFFFF', 'text' => '#000000', 'type_id' => 7], // ড - ট্রাক (ID: 7)
        'THA'  => ['name' => 'Auto Rickshaw',        'limit' => 25.0,  'bg' => '#006400', 'text' => '#FFFFFF', 'type_id' => 8]  // থ - সিএনজি (ID: 8)
    ];

    /**
     * ৫. ডাটা সেশনে এসাইন করা
     */
    if (isset($mapping[$type_code])) {
        $data = $mapping[$type_code];
        $_SESSION['v_plate']   = $plate;
        $_SESSION['v_type']    = $data['name'];
        $_SESSION['v_limit']   = (float)$data['limit']; 
        $_SESSION['v_bg']      = $data['bg'];
        $_SESSION['v_text']    = $data['text'];
        $_SESSION['v_type_id'] = $data['type_id']; // সেশনে টাইপ আইডি রাখা হলো
    } else {
        $_SESSION['v_plate']   = $plate;
        $_SESSION['v_type']    = "Standard Vehicle";
        $_SESSION['v_limit']   = 30.0; 
        $_SESSION['v_bg']      = "#FFFFFF";
        $_SESSION['v_text']    = "#000000";
        $_SESSION['v_type_id'] = null; // ডিফল্ট বা আননোন গাড়ির জন্য NULL
    }

    /**
     * ৬. ডাটাবেসের vehicles টেবিলে রিয়েল-টাইম ডাটা সিঙ্ক (user_id এবং vehicle_type_id সহ)
     */
    $db_plate = $_SESSION['v_plate'];
    $db_type_id = $_SESSION['v_type_id'];
    
    // লগইন সেশন থেকে লাইভ ইউজারের আইডি নেওয়া (নাবিলার লগইন সিস্টেম থেকে আসবে)
    // যদি লগইন সেশন না থাকে, তবে ডাটাবেসে সেভ করার জন্য এটিকে NULL রাখা হবে
    $db_user_id = isset($_SESSION['user_id']) ? intval($_SESSION['user_id']) : null;
    
    try {
        // প্রথমে চেক করা হচ্ছে প্লেটটি আগে থেকেই ডাটাবেসে আছে কি না
        $stmt_check = $conn->prepare("SELECT vehicle_id FROM vehicles WHERE plate_number = ?");
        $stmt_check->bind_param("s", $db_plate);
        $stmt_check->execute();
        $result_check = $stmt_check->get_result();

        if ($row_check = $result_check->fetch_assoc()) {
            // গাড়িটি আগে থেকে থাকলে ডাটাবেস থেকে পাওয়া তার আসল আইডি সেশনে সেট হবে
            $_SESSION['v_id'] = intval($row_check['vehicle_id']);
        } else {
            // গাড়িটি একদম নতুন হলে plate_number, vehicle_type_id এবং user_id সহ ইনসার্ট হবে
            $stmt_insert = $conn->prepare("INSERT INTO vehicles (plate_number, vehicle_type_id, user_id) VALUES (?, ?, ?)");
            $stmt_insert->bind_param("sii", $db_plate, $db_type_id, $db_user_id);
            
            if ($stmt_insert->execute()) {
                // ইনসার্ট সফল হলে নতুন জেনারেট হওয়া আইডি সেশনে যাবে
                $_SESSION['v_id'] = intval($conn->insert_id);
            } else {
                $_SESSION['v_id'] = 1; // ফলব্যাক
            }
        }
    } catch (Exception $e) {
        $_SESSION['v_id'] = 1; // এক্সেপশন ফলব্যাক
    }

    // ৭. সফলভাবে ডাটা সিঙ্ক শেষে ফুয়েল সিলেকশন পেজে পাঠানো
    header("Location: fuel_selection.php");
    exit();

} else {
    header("Location: vehicle_entry.php");
    exit();
}
?>