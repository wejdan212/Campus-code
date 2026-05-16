<?php
if (session_status() === PHP_SESSION_NONE) {
    session_start();
}
$is_logged_in = isset($_SESSION['user_id']);
$is_admin = $is_logged_in && (($_SESSION['role'] ?? '') === 'admin');
?>
<!DOCTYPE html>
<html dir="rtl" lang="ar">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>منصة الدورات التعليمية | Campus Code</title>
    <meta name="description" content="منصة Campus Code التعليمية - اكتشف أفضل الدورات التعليمية في البرمجة والتقنية">
    <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.5.0/css/all.min.css">
    <link rel="stylesheet" href="style.css">
    <script>
        // Apply saved theme BEFORE render to avoid flash
        (function() {
            var saved = localStorage.getItem('campus-theme');
            if (saved) {
                document.documentElement.setAttribute('data-theme', saved);
            }
        })();
    </script>
</head>
<body>

    <nav class="navbar">
        <div class="navbar-brand">
            <h2><i class="fas fa-graduation-cap"></i> Campus Code</h2>
        </div>
        <div class="navbar-links">
            <a href="display_courses.php"><i class="fas fa-house"></i> الرئيسية</a>

            <?php if ($is_admin): ?>
                <a href="admin_dashboard.php"><i class="fas fa-chart-pie"></i> لوحة التحكم</a>
            <?php endif; ?>

            <form action="search.php" method="GET" class="navbar-search">
                <input type="text" name="query" placeholder="ابحث عن دورة...">
                <button type="submit"><i class="fas fa-magnifying-glass"></i></button>
            </form>
            <button id="theme-toggle-btn" class="theme-toggle" title="تبديل الوضع" aria-label="تبديل المظهر">
                <i class="fas fa-sun icon-sun"></i>
                <i class="fas fa-moon icon-moon"></i>
            </button>

            <?php if ($is_logged_in): ?>
                <a href="logout.php" class="nav-link-logout"><i class="fas fa-right-from-bracket"></i> خروج</a>
            <?php else: ?>
                <a href="login.php"><i class="fas fa-right-to-bracket"></i> دخول</a>
                <a href="register.php"><i class="fas fa-user-plus"></i> تسجيل</a>
            <?php endif; ?>
        </div>
    </nav>

    <div class="container">