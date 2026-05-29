<?php
$host = 'mysql-28688313-aa14ph-0af4.a.aivencloud.com';
$user = 'avnadmin';
$pass = 'AVNS_MeM1Y9UHIALfwFYsQ0';
$db   = 'defaultdb';
$port = 12232;

$conn = new mysqli($host, $user, $pass, $db, $port);

if ($conn->connect_error) {
    die("Connection failed: " . $conn->connect_error);
}
$conn->set_charset("utf8mb4");
?>
