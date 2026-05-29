<?php
include '../config.php';

$username = isset($_POST['username']) ? $_POST['username'] : '';
$password = isset($_POST['password']) ? $_POST['password'] : '';
$action = isset($_POST['action']) ? $_POST['action'] : 'login'; // افتراضي دخول لو التطبيق لم يحدد

if ($username != '' && $password != '') {
    if ($action == 'register') {
        // إنشاء حساب جديد
        $hashed_password = password_hash($password, PASSWORD_DEFAULT);
        $sql = "INSERT INTO users (username, password, balance) VALUES ('$username', '$hashed_password', 0.00)";
        if ($conn->query($sql) === TRUE) {
            echo json_encode(["status" => "success", "message" => "Account created"]);
        } else {
            echo json_encode(["status" => "error", "message" => "Username exists"]);
        }
    } else {
        // تسجيل الدخول
        $sql = "SELECT * FROM users WHERE username = '$username'";
        $result = $conn->query($sql);
        if ($result->num_rows > 0) {
            $row = $result->fetch_assoc();
            if (password_verify($password, $row['password'])) {
                echo json_encode(["status" => "success", "message" => "Login successful"]);
            } else {
                echo json_encode(["status" => "error", "message" => "Wrong password"]);
            }
        } else {
            echo json_encode(["status" => "error", "message" => "User not found"]);
        }
    }
} else {
    echo json_encode(["status" => "error", "message" => "Missing data"]);
}
$conn->close();
?>
