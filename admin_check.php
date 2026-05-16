<?php
// ==========================================
// ملف حماية صفحات الإدارة
// يجب استدعاء هذا الملف في أعلى كل صفحة خاصة بالأدمن
// ==========================================

// بدء الجلسة إذا لم تكن قد بدأت
if (session_status() === PHP_SESSION_NONE) {
    session_start();
}

// التحقق من تسجيل الدخول
if (!isset($_SESSION['user_id'])) {
    header("Location: login.php");
    exit();
}

// التحقق من صلاحية الأدمن
if ($_SESSION['role'] !== 'admin') {
    header("Location: index.php");
    exit();
}
?>
