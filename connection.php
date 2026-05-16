<?php
// هذا الملف يربط موقعنا بقاعدة البيانات
// إعدادات الاستضافة - غيّر هذي القيم حسب بيانات استضافتك

// كشف البيئة تلقائياً: لوكال أو استضافة
if ($_SERVER['HTTP_HOST'] === 'localhost' || $_SERVER['HTTP_HOST'] === '127.0.0.1') {
    // إعدادات السيرفر المحلي (WAMP)
    $db_host = "localhost";
    $db_user = "root";
    $db_pass = "";
    $db_name = "my_project";
} else {
    // إعدادات الاستضافة (InfinityFree)
    // ========================================
    // غيّر هذي القيم بعد ما تنشئ قاعدة البيانات في الاستضافة
    // ========================================
    $db_host = "sql300.infinityfree.com";  // سيرفر قاعدة البيانات (تلقاه في لوحة التحكم)
    $db_user = "if0_XXXXXXX";              // اسم المستخدم لقاعدة البيانات
    $db_pass = "كلمة_المرور";              // كلمة مرور قاعدة البيانات
    $db_name = "if0_XXXXXXX_my_project";   // اسم قاعدة البيانات
}

$conn = mysqli_connect($db_host, $db_user, $db_pass, $db_name);

if (!$conn) {
    echo "يوجد مشكلة في الاتصال بقاعدة البيانات: " . mysqli_connect_error();
}

// ضبط الترميز للغة العربية
mysqli_set_charset($conn, "utf8mb4");
?>
