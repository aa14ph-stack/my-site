<?php

error_reporting(E_ALL);
ini_set('display_errors', 1);

header('Content-Type: application/json');

include '../config.php';

// استقبال البيانات
$username = isset($_POST['username']) ? trim($_POST['username']) : '';
$password = isset($_POST['password']) ? trim($_POST['password']) : '';
$action   = isset($_POST['action']) ? trim($_POST['action']) : '';


// ====================================
// إنشاء حساب تلقائي
// ====================================
if ($action == 'register' || (empty($username) && empty($password))) {

    // توليد ID غير مكرر
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


    // إضافة المستخدم
    $stmt = $conn->prepare(
        "INSERT INTO users (username, password, balance)
         VALUES (?, ?, 0.00)"
    );

    $stmt->bind_param(
        "ss",
        $generated_id,
        $hashed_password
    );


    if ($stmt->execute()) {

        echo json_encode([

            "code" => 200,
            "status" => true,
            "success" => true,
            "message" => "Account created",

            "data" => [

                "id" => $generated_id,
                "username" => $generated_id,
                "login" => $generated_id,

                "password" => $generated_pass,

                "balance" => "0.00",

                "token" => "v_token_" . bin2hex(random_bytes(16))

            ]

        ], JSON_UNESCAPED_UNICODE);

    } else {

        echo json_encode([

            "code" => 500,
            "status" => false,
            "success" => false,
            "message" => "Create account failed"

        ], JSON_UNESCAPED_UNICODE);
    }

    exit;
}



// ====================================
// تسجيل الدخول
// ====================================
if ($action == 'login') {

    if (empty($username) || empty($password)) {

        echo json_encode([

            "code" => 400,
            "status" => false,
            "success" => false,
            "message" => "Missing data"

        ], JSON_UNESCAPED_UNICODE);

        exit;
    }


    // تسجيل بيانات التجربة
    file_put_contents(
        "login_log.txt",
        date("Y-m-d H:i:s") .
        " USER=".$username.
        " PASS=".$password."\n",
        FILE_APPEND
    );


    // البحث عن المستخدم
    $stmt = $conn->prepare(
        "SELECT * FROM users WHERE username=?"
    );

    $stmt->bind_param("s", $username);

    $stmt->execute();

    $result = $stmt->get_result();


    if ($result->num_rows > 0) {

        $row = $result->fetch_assoc();


        // التحقق من الباسورد
        if (password_verify($password, $row['password'])) {

            echo json_encode([

                "code" => 200,
                "status" => true,
                "success" => true,
                "message" => "Login successful",

                "data" => [

                    "id" => (string)$row['id'],
                    "username" => $row['username'],
                    "login" => $row['username'],

                    "balance" => (string)$row['balance'],

                    "token" => "v_token_" . bin2hex(random_bytes(16))

                ]

            ], JSON_UNESCAPED_UNICODE);

        } else {

            echo json_encode([

                "code" => 401,
                "status" => false,
                "success" => false,
                "message" => "Wrong password"

            ], JSON_UNESCAPED_UNICODE);
        }

    } else {

        echo json_encode([

            "code" => 404,
            "status" => false,
            "success" => false,
            "message" => "User not found"

        ], JSON_UNESCAPED_UNICODE);
    }

    exit;
}



// ====================================
// أي طلب غلط
// ====================================
echo json_encode([

    "code" => 400,
    "status" => false,
    "success" => false,
    "message" => "Invalid request"

], JSON_UNESCAPED_UNICODE);

$conn->close();

?>
