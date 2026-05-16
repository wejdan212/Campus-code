<?php
// 1. استدعاء مفتاح الاتصال بقاعدة البيانات
include 'connection.php';

// متغير فارغ لعرض رسائل النجاح أو الخطأ للطالب
$message = "";
$is_success = false;

// 2. التحقق: هل قام الطالب بالضغط على زر "تسجيل"؟
if (isset($_POST['submit'])) {
    
    // سحب البيانات التي كتبها الطالب في المربعات
    $name = trim($_POST['name'] ?? '');
    $email = trim($_POST['email'] ?? '');
    $password = $_POST['password'] ?? '';

    // التحقق من صحة المدخلات
    if (empty($name)) {
        $message = "الاسم مطلوب";
    } elseif (empty($email)) {
        $message = "البريد الإلكتروني مطلوب";
    } elseif (empty($password)) {
        $message = "كلمة المرور مطلوبة";
    } elseif (strlen($password) < 6) {
        $message = "كلمة المرور يجب أن تكون 6 أحرف على الأقل";
    } else {
        // التحقق من عدم تكرار البريد الإلكتروني
        $check_stmt = mysqli_prepare($conn, "SELECT id FROM users WHERE email = ?");
        mysqli_stmt_bind_param($check_stmt, "s", $email);
        mysqli_stmt_execute($check_stmt);
        $check_result = mysqli_stmt_get_result($check_stmt);

        if (mysqli_num_rows($check_result) > 0) {
            $message = "هذا البريد الإلكتروني مسجل مسبقاً";
        } else {
            // تشفير كلمة المرور
            $hashed_password = password_hash($password, PASSWORD_DEFAULT);

            // إدراج المستخدم الجديد بصلاحية user فقط
            $stmt = mysqli_prepare($conn, "INSERT INTO users (name, email, password, role) VALUES (?, ?, ?, 'user')");
            mysqli_stmt_bind_param($stmt, "sss", $name, $email, $hashed_password);

            if (mysqli_stmt_execute($stmt)) {
                $message = "تم التسجيل بنجاح! يمكنك تسجيل الدخول الآن.";
                $is_success = true;
            } else {
                $message = "حدث خطأ أثناء التسجيل. حاول مرة أخرى.";
            }
            mysqli_stmt_close($stmt);
        }
        mysqli_stmt_close($check_stmt);
    }
}
?>

<!DOCTYPE html>
<html dir="rtl" lang="ar">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>تسجيل طالب جديد | Campus Code</title>
    <meta name="description" content="إنشاء حساب جديد في منصة Campus Code التعليمية">
    <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.5.0/css/all.min.css">
    <link rel="stylesheet" href="style.css">
    <script>
        (function() {
            var saved = localStorage.getItem('campus-theme');
            if (saved) document.documentElement.setAttribute('data-theme', saved);
        })();
    </script>
</head>
<body class="auth-page">

    <div class="auth-card">
        <div class="auth-icon">
            <i class="fas fa-user-plus"></i>
        </div>
        <h2>إنشاء حساب جديد</h2>
        <p class="auth-subtitle">سجّل حسابك للوصول إلى الدورات التعليمية</p>
        
        <?php if(!empty($message)) { 
            if($is_success) {
                echo "<div class='success-msg'><i class='fas fa-circle-check'></i> $message</div>";
            } else {
                echo "<div class='error-msg'><i class='fas fa-circle-exclamation'></i> $message</div>";
            }
        } ?>

        <form method="POST" action="">
            <div class="input-group">
                <input type="text" name="name" required placeholder="الاسم الكامل" id="register-name" value="<?php echo htmlspecialchars($name ?? ''); ?>">
                <i class="fas fa-user"></i>
            </div>
            <div class="input-group">
                <input type="email" name="email" required placeholder="البريد الإلكتروني" id="register-email" value="<?php echo htmlspecialchars($email ?? ''); ?>">
                <i class="fas fa-envelope"></i>
            </div>
            <div class="input-group">
                <input type="password" name="password" required placeholder="كلمة المرور (6 أحرف على الأقل)" id="register-password">
                <i class="fas fa-lock"></i>
            </div>
            <button type="submit" name="submit" class="btn btn-primary btn-block btn-lg" id="register-submit">
                <i class="fas fa-user-plus"></i> تسجيل الحساب
            </button>
        </form>

        <div class="auth-footer">
            لديك حساب بالفعل؟ <a href="login.php">تسجيل الدخول</a>
        </div>
    </div>

</body>
</html>
