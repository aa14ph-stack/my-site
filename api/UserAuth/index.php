<?php

error_reporting(E_ALL);
ini_set('display_errors', 1);

header("Content-Type: application/json");

include '../config.php';

// استقبال البيانات
$username = isset($_POST['username']) ? trim($_POST['username']) : '';
$password = isset($_POST['password']) ? trim($_POST['password']) : '';
$action   = isset($_POST['action']) ? trim($_POST['action']) : '';


// ================================
// لو مفيش بيانات = تسجيل تلقائي
// ================================
if ($action == 'register' || (empty($username) && empty($password))) {

    // توليد ID عشوائي غير مكرر
    do {

        $generated_id = (string) mt_rand(10000000, 99999999);

        $check = $conn->prepare("SELECT id FROM users WHERE username=?");
        $check->bind_param("s", $generated_id);
        $check->execute();

        $result = $check->get_result();

    } while ($result->num_rows > 0);


    // توليد باسورد
    $generated_pass = (string) mt_rand(100000, 999999);

    // تشفير الباسورد
    $hashed_password = password_hash($generated_pass, PASSWORD_DEFAULT);


    // إدخال الحساب
    $stmt = $conn->prepare("INSERT INTO users (username, password, balance) VALUES (?, ?, 0.00)");

    $stmt->bind_param("ss", $generated_id, $hashed_password);


    if ($stmt->execute()) {

        echo json_encode([

            "status"   => "success",
            "success"  => true,
            "code"     => 200,

            "message"  => "Account created successfully",

            "username" => $generated_id,
            "login"    => $generated_id,

            // الباسورد الحقيقي
            "password" => $generated_pass,

            "token"    => "v_token_" . bin2hex(random_bytes(16)),

            "user" => [

                "id"       => $generated_id,
                "username" => $generated_id,
                "balance"  => "0.00"

            ]

        ]);

    } else {

        echo json_encode([

            "status"  => "error",
            "success" => false,
            "message" => "Failed to create account"

        ]);
    }

    exit;
}



// ================================
// تسجيل الدخول
// ================================
if ($action == 'login') {

    if (empty($username) || empty($password)) {

        echo json_encode([

            "status"  => "error",
            "success" => false,
            "message" => "Missing username or password"

        ]);

        exit;
    }


    // لوج للتجربة
    file_put_contents(
        "login_log.txt",
        "USER=".$username." PASS=".$password."\n",
        FILE_APPEND
    );


    // البحث عن المستخدم
    $stmt = $conn->prepare("SELECT * FROM users WHERE username=?");

    $stmt->bind_param("s", $username);

    $stmt->execute();

    $result = $stmt->get_result();


    if ($result->num_rows > 0) {

        $row = $result->fetch_assoc();


        // التحقق من الباسورد
        if (password_verify($password, $row['password'])) {

            echo json_encode([

                "status"  => "success",
                "success" => true,
                "code"    => 200,

                "message" => "Login successful",

                "username" => $row['username'],
                "login"    => $row['username'],

                "token" => "v_token_" . bin2hex(random_bytes(16)),

                "user" => [

                    "id"       => (string)$row['id'],
                    "username" => $row['username'],
                    "balance"  => (string)$row['balance']

                ]

            ]);

        } else {

            echo json_encode([

                "status"  => "error",
                "success" => false,
                "message" => "Wrong password"

            ]);
        }

    } else {

        echo json_encode([

            "status"  => "error",
            "success" => false,
            "message" => "User not found"

        ]);
    }

    exit;
}



// ================================
// لو action غلط
// ================================
echo json_encode([

    "status"  => "error",
    "success" => false,
    "message" => "Invalid action"

]);

$conn->close();

?>
