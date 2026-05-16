<?php
// ==========================================
// Admin Sidebar & Header Layout (reusable)
// ==========================================
?>
<!DOCTYPE html>
<html dir="rtl" lang="ar">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title><?php echo isset($page_title) ? $page_title : 'لوحة التحكم'; ?> | Campus Code Admin</title>
    <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.5.0/css/all.min.css">
    <link rel="stylesheet" href="admin.css">
</head>
<body class="admin-body">

    <!-- Sidebar -->
    <aside class="admin-sidebar" id="admin-sidebar">
        <div class="sidebar-brand">
            <i class="fas fa-graduation-cap"></i>
            <h2>Campus Code</h2>
        </div>
        <nav class="sidebar-nav">
            <div class="sidebar-label">القائمة الرئيسية</div>
            <a href="admin_dashboard.php" class="sidebar-link <?php echo ($current_page ?? '') === 'dashboard' ? 'active' : ''; ?>">
                <i class="fas fa-chart-pie"></i> لوحة التحكم
            </a>
            <a href="admin_courses.php" class="sidebar-link <?php echo ($current_page ?? '') === 'courses' ? 'active' : ''; ?>">
                <i class="fas fa-book"></i> الدورات
            </a>
            <a href="admin_add_course.php" class="sidebar-link <?php echo ($current_page ?? '') === 'add_course' ? 'active' : ''; ?>">
                <i class="fas fa-plus-circle"></i> إضافة دورة
            </a>
            <a href="admin_users.php" class="sidebar-link <?php echo ($current_page ?? '') === 'users' ? 'active' : ''; ?>">
                <i class="fas fa-users"></i> المستخدمين
            </a>
            <div class="sidebar-label">إعدادات</div>
            <a href="index.php" class="sidebar-link">
                <i class="fas fa-globe"></i> الموقع الرئيسي
            </a>
        </nav>
        <div class="sidebar-footer">
            <a href="logout.php" class="sidebar-link" style="color: var(--admin-danger);">
                <i class="fas fa-right-from-bracket"></i> تسجيل الخروج
            </a>
        </div>
    </aside>

    <!-- Main Content Area -->
    <main class="admin-main">
        <header class="admin-header">
            <div class="admin-header-title">
                <h1><?php echo isset($page_title) ? $page_title : 'لوحة التحكم'; ?></h1>
                <p><?php echo isset($page_subtitle) ? $page_subtitle : 'مرحباً بك في لوحة إدارة المنصة'; ?></p>
            </div>
            <div class="admin-header-actions">
                <div class="admin-user-badge">
                    <i class="fas fa-user-shield"></i>
                    <span><?php echo htmlspecialchars($_SESSION['name'] ?? 'Admin'); ?></span>
                </div>
            </div>
        </header>
        <div class="admin-content">
