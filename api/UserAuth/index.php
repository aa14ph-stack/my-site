<?php
include '../config.php';

// استقبال البيانات (سواء جاية من دخول أو تسجيل)
$username = isset($_POST['username']) ? $_POST['username'] : '';
$password = isset($_POST['password']) ? $_POST['password'] : '';
$action   = isset($_POST['action']) ? $_POST['action'] : '';

// لو التطبيق مبعتش action بس البيانات فاضية، نعتبرها طلب تسجيل تلقائي
if (empty($username) && empty($password)) {
    $action = 'register';
}

if ($action == 'register') {
    // ---- [ مرحلة إنشاء الحساب التلقائي ] ----
    
    // 1. توليد ID رقمي عشوائي من 8 أرقام والتأكد إنه مش مكرر
    do {
        $generated_id = (string)mt_rand(10000000, 99999999);
        $check_sql = "SELECT id FROM users WHERE username = '$generated_id'";
        $check_result = $conn->query($check_sql);
    } while ($check_result->num_rows > 0);

    // 2. توليد باسوورد عشوائي من 6 أرقام وحروف
    $generated_pass = (string)mt_rand(100000, 999999);
    
    // تشفير الباسوورد لحفظه في القاعدة بأمان
    $hashed_password = password_hash($generated_pass, PASSWORD_DEFAULT);
    
    // 3. إدخال المستخدم الجديد في القاعدة برصيد صفر
    $sql = "INSERT INTO users (username, password, balance) VALUES ('$generated_id', '$hashed_password', 0.00)";
    
    if ($conn->query($sql) === TRUE) {
        // الرد السوبر اللي فيه الـ ID والباسوورد الجداد عشان التطبيق يعرضهم ويفتح فوراً
        echo json_encode([
            "status"   => "success",
            "success"  => true,
            "code"     => 200,
            "message"  => "Account created successfully",
            "username" => $generated_id,         // الـ ID الرقمي الجديد
            "login"    => $generated_id,         // مفتاح بديل لو التطبيق بيقرأ كلمة login
            "password" => $generated_pass,       // الباسوورد المكشوف عشان التطبيق يوريه للمستخدم
            "token"    => "v_token_" . bin2hex(random_bytes(16)),
            "data"     => [
                "id"       => $generated_id,
                "username" => $generated_id,
                "balance"  => "0.00"
            ]
        ]);
    } else {
        echo json_encode(["status" => "error", "success" => false, "message" => "Failed to create account"]);
    }

} else {
    // ---- [ مرحلة تسجيل الدخول العادي ] ----
    if ($username != '' && $password != '') {
        $sql = "SELECT * FROM users WHERE username = '$username'";
        $result = $conn->query($sql);
        
        if ($result->num_rows > 0) {
            $row = $result->fetch_assoc();
            
            if (password_verify($password, $row['password'])) {
                echo json_encode([
                    "status"   => "success",
                    "success"  => true,
                    "code"     => 200,
                    "message"  => "Login successful",
                    "username" => $row['username'],
                    "login"    => $row['username'],
                    "token"    => "v_token_" . bin2hex(random_bytes(16)),
                    "data"     => [
                        "id"       => (string)$row['id'],
                        "username" => $row['username'],
                        "balance"  => (string)$row['balance']
                    ]
                ]);
            } else {
                echo json_encode(["status" => "error", "success" => false, "message" => "Wrong password"]);
            }
        } else {
            echo json_encode(["status" => "error", "success" => false, "message" => "User not found"]);
        }
    } else {
        echo json_encode(["status" => "error", "success" => false, "message" => "Missing data"]);
    }
}

$conn->close();
?>
