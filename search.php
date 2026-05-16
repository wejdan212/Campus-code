<?php
include 'connection.php';
if (session_status() === PHP_SESSION_NONE) {
    session_start();
}
$is_admin = isset($_SESSION['user_id']) && (($_SESSION['role'] ?? '') === 'admin');
include 'header.php';
?>

<div style="max-width: 1000px; margin: 0 auto; padding: 0;">
    
    <?php
    // التحقق من أن المستخدم كتب شيئاً في مربع البحث
    if (isset($_GET['query']) && !empty($_GET['query'])) {
        $search_query = trim($_GET['query']);
        
        echo "<div class='search-header'>";
        echo "<h2><i class='fas fa-magnifying-glass'></i> نتائج البحث عن: '<span style='color: var(--primary-light);'>" . htmlspecialchars($search_query) . "</span>'</h2>";
        echo "</div>";
        
        // البحث مع تصفية الدورات غير النشطة للمستخدمين العاديين
        if ($is_admin) {
            $stmt = mysqli_prepare($conn, "SELECT * FROM courses WHERE title LIKE ? OR description LIKE ?");
        } else {
            $stmt = mysqli_prepare($conn, "SELECT * FROM courses WHERE (title LIKE ? OR description LIKE ?) AND status = 'active'");
        }
        $like_query = "%" . $search_query . "%";
        mysqli_stmt_bind_param($stmt, "ss", $like_query, $like_query);
        mysqli_stmt_execute($stmt);
        $result = mysqli_stmt_get_result($stmt);
        
        if (mysqli_num_rows($result) > 0) {
            echo "<div class='cards-grid'>";
            while ($row = mysqli_fetch_assoc($result)) {
                $img = !empty($row['image']) ? $row['image'] : 'default.jpg';
                
                echo "<div class='course-card'>";
                echo "<div class='course-card-image'>";
                echo "<img src='uploads/" . htmlspecialchars($img) . "' alt='" . htmlspecialchars($row['title']) . "'>";
                echo "<div class='price-badge'><i class='fas fa-tag'></i> " . htmlspecialchars($row['price']) . " ريال</div>";
                echo "</div>";
                echo "<div class='course-card-body'>";
                echo "<h3>" . htmlspecialchars($row['title']) . "</h3>";
                echo "<div class='course-card-actions'>";
                echo "<a href='course_details.php?id=" . $row['id'] . "' class='btn btn-secondary btn-block'><i class='fas fa-eye'></i> عرض التفاصيل</a>";
                echo "</div>";
                echo "</div>";
                echo "</div>";
            }
            echo "</div>";
        } else {
            echo "<div class='no-results'><i class='fas fa-face-frown'></i><h3>عذراً، لم نجد أي دورات تطابق بحثك</h3></div>";
        }
        mysqli_stmt_close($stmt);
    } else {
        echo "<div class='search-empty'><i class='fas fa-magnifying-glass'></i><h3>الرجاء كتابة كلمة في مربع البحث</h3></div>";
    }
    ?>

</div>

<?php include 'footer.php'; ?>