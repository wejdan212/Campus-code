<?php
// الملف القديم - يتم التوجيه للملف الجديد في لوحة الإدارة
require_once 'admin_check.php';
$id = intval($_GET['id'] ?? 0);
header("Location: admin_edit_course.php?id=" . $id);
exit();
?>