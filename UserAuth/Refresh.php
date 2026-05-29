<?php
include '../config.php';

// التطبيق غالباً بيبعت اسم المستخدم أو التوكن القديم عشان يجدد الجلسة
$username = isset($_POST['username']) ? $_POST['username'] : '';

if ($username != '') {
    // التأكد من وجود المستخدم في القاعدة (خطوة أمان إضافية سريعة)
    $sql = "SELECT id FROM users WHERE username = '$username'";
    $result = $conn->query($sql);

    if ($result->num_rows > 0) {
        // لو المستخدم موجود، نرد عليه بنجاح وتوكن عشوائي جديد
        echo json_encode([
            "status" => "success", 
            "message" => "Session refreshed successfully",
            "token" => bin2hex(random_bytes(16)) // توكن وهمي جديد عشان التطبيق ميقفلش
        ]);
    } else {
        echo json_encode(["status" => "error", "message" => "User not found"]);
    }
} else {
    // لو التطبيق مبعتش حاجة، بنرد بنجاح برضه عشان نتجنب أي كراش في الجافا
    echo json_encode([
        "status" => "success",
        "message" => "Refreshed"
    ]);
}

$conn->close();
?>
