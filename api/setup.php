<?php
include 'config.php';

$sql = "CREATE TABLE IF NOT EXISTS users (
    id INT AUTO_INCREMENT PRIMARY KEY,
    username VARCHAR(50) NOT NULL UNIQUE,
    password VARCHAR(255) NOT NULL,
    balance DECIMAL(10,2) DEFAULT 0.00
)";

if ($conn->query($sql) === TRUE) {
    echo "تم إنشاء جدول المستخدمين (users) مع خانة الرصيد بنجاح! احذف الملف ده دلوقتي.";
} else {
    echo "خطأ: " . $conn->error;
}
$conn->close();
?>
