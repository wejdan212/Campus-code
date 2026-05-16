<?php
// 1. استدعاء مفتاح الاتصال بقاعدة البيانات
include 'connection.php';

// بدء الجلسة للتحقق من الصلاحيات
if (session_status() === PHP_SESSION_NONE) {
    session_start();
}
$is_logged_in = isset($_SESSION['user_id']);
$is_admin = $is_logged_in && (($_SESSION['role'] ?? '') === 'admin');

// 2. استدعاء الهيدر (الشريط العلوي) الذي يحتوي على التصميم وزر الخروج
include 'header.php';
?>

<?php
// ==========================================
// المرحلة الثانية: عرض الدورات داخل المسار المختار
// ==========================================
if (isset($_GET['track_id'])) {
    $selected_track = intval($_GET['track_id']);

    // جلب اسم المسار لطباعته في العنوان
    $track_stmt = mysqli_prepare($conn, "SELECT name FROM tracks WHERE id = ?");
    mysqli_stmt_bind_param($track_stmt, "i", $selected_track);
    mysqli_stmt_execute($track_stmt);
    $track_result = mysqli_stmt_get_result($track_stmt);
    $track_info = mysqli_fetch_assoc($track_result);
    mysqli_stmt_close($track_stmt);

    echo "<div class='page-header'>";
    echo "<h2>الدورات المتاحة في مسار: <span>" . htmlspecialchars($track_info['name']) . "</span></h2>";
    echo "<a href='display_courses.php' class='back-link'><i class='fas fa-arrow-right'></i> العودة لقائمة المسارات</a>";
    echo "</div>";

    // جلب الدورات - المستخدم العادي يرى فقط النشطة
    if ($is_admin) {
        $courses_query = "SELECT * FROM courses WHERE track_id = '$selected_track'";
    } else {
        $courses_query = "SELECT * FROM courses WHERE track_id = '$selected_track' AND status = 'active'";
    }
    $result = mysqli_query($conn, $courses_query);

    // طباعة الدورات على شكل بطاقات
    if (mysqli_num_rows($result) > 0) {
        echo "<div class='cards-grid'>";
        while ($row = mysqli_fetch_assoc($result)) {
            // عرض صورة الدورة (مع وضع صورة افتراضية في حال عدم وجود صورة)
            $img = !empty($row['image']) ? $row['image'] : 'default.jpg';
            
            echo "<div class='course-card'>";
            echo "<div class='course-card-image'>";
            echo "<img src='uploads/" . htmlspecialchars($img) . "' alt='" . htmlspecialchars($row['title']) . "'>";
            echo "<div class='price-badge'><i class='fas fa-tag'></i> " . htmlspecialchars($row['price']) . " ريال</div>";
            echo "</div>";
            echo "<div class='course-card-body'>";
            echo "<h3>" . htmlspecialchars($row['title']) . "</h3>";
            echo "<div class='course-card-actions'>";
            // زر عرض التفاصيل (ينقل للصفحة المستقلة حسب شروط المشروع)
            echo "<a href='course_details.php?id=" . $row['id'] . "' class='btn btn-primary btn-block'><i class='fas fa-eye'></i> عرض التفاصيل</a>";
            echo "</div>";
            echo "</div>";
            echo "</div>";
        }
        echo "</div>";
    } else {
        echo "<div class='empty-state'><i class='fas fa-folder-open'></i><h4>لا توجد دورات متاحة في هذا المسار حالياً.</h4></div>";
    }

// ==========================================
// المرحلة الأولى: عرض المسارات (الصفحة الرئيسية)
// ==========================================
} else {
    echo "<div class='page-header'>";
    echo "<h2><i class='fas fa-route'></i> المسارات التعليمية</h2>";
    echo "<p>اختر المسار الذي يناسب طموحك لتبدأ رحلة التعلم</p>";
    echo "</div>";

    // جلب كل المسارات من قاعدة البيانات
    $tracks_query = "SELECT * FROM tracks";
    $result = mysqli_query($conn, $tracks_query);

    // طباعة المسارات على شكل بطاقات
    echo "<div class='cards-grid'>";
    while ($row = mysqli_fetch_assoc($result)) {
        echo "<div class='track-card'>";
        echo "<h3><i class='fas fa-layer-group'></i> " . htmlspecialchars($row['name']) . "</h3>";
        echo "<p>" . htmlspecialchars($row['description']) . "</p>";
        // زر يرسل رقم المسار في الرابط لفتح دوراته
        echo "<a href='display_courses.php?track_id=" . $row['id'] . "' class='btn btn-primary'><i class='fas fa-arrow-left'></i> استعرض الدورات</a>";
        echo "</div>";
    }
    echo "</div>";
}
?>

<?php
// 3. استدعاء الفوتر (الشريط السفلي)
include 'footer.php';
?>