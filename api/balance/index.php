<?php
include '../config.php';

$username = isset($_POST['username']) ? $_POST['username'] : '';

if ($username != '') {
    $sql = "SELECT balance FROM users WHERE username = '$username'";
    $result = $conn->query($sql);
    
    if ($result->num_rows > 0) {
        $row = $result->fetch_assoc();
        // إرسال الرصيد للتطبيق
        echo json_encode(["status" => "success", "balance" => $row['balance']]);
    } else {
        echo json_encode(["status" => "error", "message" => "User not found"]);
    }
} else {
    echo json_encode(["status" => "error", "message" => "No username provided"]);
}
$conn->close();
?>
