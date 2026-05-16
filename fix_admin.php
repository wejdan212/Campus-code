<?php
include 'connection.php';

// إعادة تعيين كلمة مرور الأدمن
$new_password = password_hash('admin', PASSWORD_DEFAULT);
$email = 'admin@admin.com';

$stmt = mysqli_prepare($conn, "UPDATE users SET password = ? WHERE email = ?");
mysqli_stmt_bind_param($stmt, "ss", $new_password, $email);

if (mysqli_stmt_execute($stmt) && mysqli_stmt_affected_rows($stmt) > 0) {
    echo "<h2 style='color:green;'>✅ تم تحديث كلمة المرور بنجاح!</h2>";
    echo "<p>البريد: admin@admin.com</p>";
    echo "<p>كلمة المرور: admin</p>";
    echo "<p style='color:red;'>احذف هذا الملف الآن!</p>";
} else {
    echo "<h2 style='color:red;'>خطأ</h2>";
    echo "<p>" . mysqli_error($conn) . "</p>";
}
mysqli_stmt_close($stmt);
?>
