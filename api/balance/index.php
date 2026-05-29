<?php
// إعلام التطبيق أن الرد سيكون بصيغة JSON دائماً
header('Content-Type: application/json; charset=UTF-8');

// الرد الخاص بعرض الفلوس والرصيد للحساب الرئيسي
$response = [
    "Success" => true,
    "Value" => [
        [
            "Id" => "1684870011",
            "Money" => 5000, // الرقم اللي هيظهر للمستخدم كحجم الرصيد في التطبيق
            "CurrencyId" => 119,
            "Points" => 0,
            "Type" => 0,
            "Name" => "Main account",
            "OpenBonusStatus" => 0,
            "OpenBonusExists" => false
        ]
    ]
];

echo json_encode($response);
exit();
