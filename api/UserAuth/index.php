<?php

header('Content-Type: application/json');

include '../config.php';

$login    = isset($_POST['Login']) ? trim($_POST['Login']) : '';
$password = isset($_POST['Password']) ? trim($_POST['Password']) : '';


// فك تشفير Base64
$decoded_password = base64_decode($password);


// البحث عن المستخدم
$stmt = $conn->prepare("SELECT * FROM users WHERE username=?");
$stmt->bind_param("s", $login);
$stmt->execute();

$result = $stmt->get_result();


if ($result->num_rows > 0) {

    $row = $result->fetch_assoc();

    // التحقق من الباسورد
    if (
        password_verify($decoded_password, $row['password'])
        ||
        $decoded_password == $row['password']
    ) {

        echo json_encode([

            "Success" => true,

            "Value" => [

                "UserData" => [

                    "UserId" => $row['username'],
                    "LD" => "/Date(-62135596800000)/"

                ],

                "Token" => bin2hex(random_bytes(64)),

                "TokenExpiry" => 1199,

                "RefreshToken" => bin2hex(random_bytes(64)),

                "RefreshExpiry" => 365833324

            ]

        ], JSON_UNESCAPED_UNICODE);

    } else {

        echo json_encode([

            "Success" => false,
            "Error" => "Wrong password"

        ]);
    }

} else {

    echo json_encode([

        "Success" => false,
        "Error" => "User not found"

    ]);
}

$conn->close();

?>
