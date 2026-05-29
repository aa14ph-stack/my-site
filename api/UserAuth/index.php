<?php
// إعلام التطبيق أن الرد سيكون بصيغة JSON دائماً
header('Content-Type: application/json; charset=UTF-8');

// استقبال البيانات القادمة من التطبيق المعدل (الـ Request Body) لو وُجدت
$input_data = file_get_contents('php://input');
$request = json_decode($input_data, true);

// الرد الافتراضي المأخوذ من التطبيق الأصلي لتسجيل الدخول أو إنشاء الحساب بنجاح
$response = [
    "Success" => true,
    "Value" => [
        "UserData" => [
            "UserId" => isset($request['Login']) ? $request['Login'] : "1684870011",
            "LD" => "/Date(-62135596800000)/"
        ],
        "Token" => "eyJhbGciOiJFUzI1NiIsImtpZCI6IjEiLCJ0eXAiOiJKV1QifQ.eyJzdWIiOiI1MC8xNjg0ODcwMDExIiwicGlkIjoiMjUzIiwianRpIjoiMC9lMjNhNzZlOGU5YmFiYzUwMDhlMTFkYjBhNzVlYmY4MGM3YzM3ZTkyNjcwMWJmZDZkYzBlY2ZjMjdhYWRiM2QxIiwiYXBwIjoiZGQyZmMwMGNmNDc2NjAzOV8yIiwic2lkIjoiMDE5ZTc1NDUtMzMzZC03MzIyLWFlN2EtOWU1MmY0MWQ4OWU2IiwieHBqIjoiMCIsInhnciI6IjAiLCJzY29wZSI6ImFsbCIsIm5iZiI6MTc4MDA4MzkyOSwiZXhwIjoxNzgwMDg1MTI5LCJpYXQiOjE3ODAwODM5Mjl9.w-xOQlHxl4mz6wx9eDO9lmUfKrjacQ12y8150UEXEEi6pGq8V_IBAMyGm5CxepwFGPQ5q1To5SrKS5CiJXxU9A",
        "TokenExpiry" => 1199,
        "RefreshToken" => "eyJhbGciOiJFUzI1NiIsImtpZCI6IjEiLCJ0eXAiOiJKV1QifQ.eyJzdWIiOiI1MC8xNjg0ODcwMDExIiwicGlkIjoiMjUzIiwianRpIjoiMC81OWRjOWJlYzliMmFkMjhhZGYxODQwYzQ3MGE3NjFkMGU0NTJlYWJkNDk2N2FjNDZlYmM4OTk2MDI4ZTM0Mjg1IiwiYXBwIjoiZGQyZmMwMGNmNDc2NjAzOV8yIiwic2lkIjoiMDE5ZTc1NDUtMzMzZC03MzIyLWFlN2EtOWU1MmY0MWQ4OWU2IiwieHBqIjoiMCIsInhnciI6IjAiLCJzY29wZSI6ImFsbCIsIm5iZiI6MTvAwODM5Mjl9.x1PjFl7fHLgUdepdYgKIYgBkWrzf9E0a_okjtXVuQKc8l4JaqKJqMsjD50CjHCeRrm91HZauMrwXOoiTCpzvKA",
        "RefreshExpiry" => 365832870
    ]
];

echo json_encode($response);
exit();
