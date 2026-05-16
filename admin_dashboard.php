<?php
require_once 'admin_check.php';
require_once 'connection.php';

$current_page = 'dashboard';
$page_title = 'لوحة التحكم';
$page_subtitle = 'نظرة عامة على إحصائيات المنصة';

// إحصائيات
$total_courses = mysqli_fetch_assoc(mysqli_query($conn, "SELECT COUNT(*) as c FROM courses"))['c'];
$total_users = mysqli_fetch_assoc(mysqli_query($conn, "SELECT COUNT(*) as c FROM users"))['c'];
$total_tracks = mysqli_fetch_assoc(mysqli_query($conn, "SELECT COUNT(*) as c FROM tracks"))['c'];
$total_coupons = mysqli_fetch_assoc(mysqli_query($conn, "SELECT COUNT(*) as c FROM coupons"))['c'];
$active_courses = mysqli_fetch_assoc(mysqli_query($conn, "SELECT COUNT(*) as c FROM courses WHERE status='active'"))['c'];
$inactive_courses = mysqli_fetch_assoc(mysqli_query($conn, "SELECT COUNT(*) as c FROM courses WHERE status='inactive'"))['c'];

include 'admin_header.php';
?>

<!-- Stats Grid -->
<div class="stats-grid">
    <div class="stat-card">
        <div class="stat-icon primary"><i class="fas fa-book"></i></div>
        <div class="stat-info">
            <h3><?php echo $total_courses; ?></h3>
            <p>إجمالي الدورات</p>
        </div>
    </div>
    <div class="stat-card">
        <div class="stat-icon info"><i class="fas fa-users"></i></div>
        <div class="stat-info">
            <h3><?php echo $total_users; ?></h3>
            <p>إجمالي المستخدمين</p>
        </div>
    </div>
    <div class="stat-card">
        <div class="stat-icon warning"><i class="fas fa-route"></i></div>
        <div class="stat-info">
            <h3><?php echo $total_tracks; ?></h3>
            <p>المسارات التعليمية</p>
        </div>
    </div>
    <div class="stat-card">
        <div class="stat-icon danger"><i class="fas fa-tags"></i></div>
        <div class="stat-info">
            <h3><?php echo $total_coupons; ?></h3>
            <p>كوبونات الخصم</p>
        </div>
    </div>
    <div class="stat-card">
        <div class="stat-icon success"><i class="fas fa-circle-check"></i></div>
        <div class="stat-info">
            <h3><?php echo $active_courses; ?></h3>
            <p>دورات نشطة</p>
        </div>
    </div>
    <div class="stat-card">
        <div class="stat-icon danger"><i class="fas fa-circle-xmark"></i></div>
        <div class="stat-info">
            <h3><?php echo $inactive_courses; ?></h3>
            <p>دورات غير نشطة</p>
        </div>
    </div>
</div>

<!-- Quick Actions -->
<div class="quick-actions">
    <a href="admin_add_course.php" class="btn-admin btn-add"><i class="fas fa-plus"></i> إضافة دورة جديدة</a>
    <a href="admin_courses.php" class="btn-admin btn-edit"><i class="fas fa-list"></i> إدارة الدورات</a>
    <a href="admin_users.php" class="btn-admin btn-cancel"><i class="fas fa-users"></i> عرض المستخدمين</a>
</div>

<?php include 'admin_footer.php'; ?>
