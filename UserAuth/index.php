<?php
include '../config.php';

// استقبال البيانات
$username = isset($_POST['username']) ? $_POST['username'] : '';
$password = isset($_POST['password']) ? $_POST['password'] : '';

if ($username != '' && $password != '') {
    $sql = "SELECT * FROM users WHERE username = '$username'";
    $result = $conn->query($sql);
    
    if ($result->num_rows > 0) {
        $row = $result->fetch_assoc();
        
        if (password_verify($password, $row['password'])) {
            // الرد السوبر: فيه كل المفاتيح الممكنة اللي الجافا بتدور عليها
            echo json_encode([
                "status"   => "success",     // نص
                "success"  => true,          // قيمة منطقية Boolean
                "code"     => 200,           // رقم كود النجاح
                "message"  => "Login successful",
                "token"    => "v_token_" . bin2hex(random_bytes(16)),
                "data"     => [              // مصفوفة بيانات فرعية لو التطبيق بيقرا منها
                    "id"       => (string)$row['id'],
                    "uid"      => (string)$row['id'],
                    "username" => $row['username'],
                    "balance"  => (string)$row['balance']
                ],
                "user"     => [              // مصفوفة بديلة باسم user
                    "id"       => (string)$row['id'],
                    "username" => $row['username']
                ]
            ]);
        } else {
            // رد كلمة المرور الخاطئة
            echo json_encode([
                "status"  => "error",
                "success" => false,
                "code"    => 401,
                "message" => "Wrong password"
            ]);
        }
    } else {
        // رد مستخدم غير موجود
        echo json_encode([
            "status"  => "error",
            "success" => false,
            "code"    => 404,
            "message" => "User not found"
        ]);
    }
} else {
    // بيانات ناقصة
    echo json_encode([
        "status"  => "error",
        "success" => false,
        "code"    => 400,
        "message" => "Missing data"
    ]);
}
$conn->close();
?>
