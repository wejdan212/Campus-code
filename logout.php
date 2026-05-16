<?php
// 1. استدعاء الجلسة الحالية
session_start();

// 2. تفريغ وحذف كل بيانات الجلسة (تسجيل الخروج الفعلي)
session_unset();
session_destroy();

// 3. طرد المستخدم وإعادته لصفحة تسجيل الدخول
header("Location: login.php");
exit();
?>