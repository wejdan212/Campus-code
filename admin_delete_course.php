<?php
require_once 'admin_check.php';
require_once 'connection.php';

$course_id = intval($_GET['id'] ?? 0);

if ($course_id <= 0) {
    header("Location: admin_courses.php");
    exit();
}

// حذف الصورة من مجلد uploads إذا لم تكن الافتراضية
$stmt = mysqli_prepare($conn, "SELECT image FROM courses WHERE id = ?");
mysqli_stmt_bind_param($stmt, "i", $course_id);
mysqli_stmt_execute($stmt);
$result = mysqli_stmt_get_result($stmt);
$row = mysqli_fetch_assoc($result);
mysqli_stmt_close($stmt);

if ($row) {
    $image_path = "uploads/" . $row['image'];
    if (file_exists($image_path) && $row['image'] !== 'default.jpg' && !empty($row['image'])) {
        unlink($image_path);
    }
}

// حذف الدورة
$stmt = mysqli_prepare($conn, "DELETE FROM courses WHERE id = ?");
mysqli_stmt_bind_param($stmt, "i", $course_id);
mysqli_stmt_execute($stmt);
mysqli_stmt_close($stmt);

header("Location: admin_courses.php?msg=deleted");
exit();
?>
