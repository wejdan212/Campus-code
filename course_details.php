<?php
// 1. نستدعي ملف الاتصال بقاعدة البيانات، ونستدعي الهيدر (الجزء العلوي للموقع)
include 'connection.php';

if (session_status() === PHP_SESSION_NONE) {
    session_start();
}
$is_logged_in = isset($_SESSION['user_id']);
$is_admin = $is_logged_in && (($_SESSION['role'] ?? '') === 'admin');

include 'header.php';

// 2. نتأكد إن الرابط فيه رقم الدورة (id) عشان نعرف أي دورة نعرض
if (isset($_GET['id'])) {
    $course_id = intval($_GET['id']);
    
    // 3. نجلب بيانات هذي الدورة فقط من جدول الدورات
    $stmt = mysqli_prepare($conn, "SELECT * FROM courses WHERE id = ?");
    mysqli_stmt_bind_param($stmt, "i", $course_id);
    mysqli_stmt_execute($stmt);
    $result = mysqli_stmt_get_result($stmt);

    // إذا لقينا الدورة في قاعدة البيانات
    if (mysqli_num_rows($result) > 0) {
        $course = mysqli_fetch_assoc($result);
        
        // التحقق: إذا الدورة غير نشطة والمستخدم مو أدمن، نمنعه
        if (($course['status'] ?? 'active') === 'inactive' && !$is_admin) {
            echo "<div class='empty-state'><i class='fas fa-lock'></i><h4>هذه الدورة غير متاحة حالياً.</h4></div>";
            include 'footer.php';
            exit();
        }
        
        // ==========================================
        // بداية كود منطق الخصم (Coupon Logic)
        // ==========================================
        
        $original_price = $course['price']; 
        $display_price = $original_price;
        $coupon_message = "";

        if (isset($_POST['apply_coupon'])) {
            $entered_code = trim($_POST['coupon_code'] ?? '');
            
            $coupon_stmt = mysqli_prepare($conn, "SELECT * FROM coupons WHERE code = ?");
            mysqli_stmt_bind_param($coupon_stmt, "s", $entered_code);
            mysqli_stmt_execute($coupon_stmt);
            $check_query = mysqli_stmt_get_result($coupon_stmt);
            
            if (mysqli_num_rows($check_query) > 0) {
                $coupon_data = mysqli_fetch_assoc($check_query);
                $percent = $coupon_data['discount_percent'];
                $discount_amount = $original_price * ($percent / 100);
                $display_price = $original_price - $discount_amount;
                $coupon_message = "<p class='coupon-success'><i class='fas fa-circle-check'></i> تم تطبيق خصم $percent% بنجاح!</p>";
            } else {
                $coupon_message = "<p class='coupon-error'><i class='fas fa-circle-xmark'></i> كود الخصم غير صحيح</p>";
            }
            mysqli_stmt_close($coupon_stmt);
        }
        // ==========================================
        // نهاية كود منطق الخصم
        // ==========================================

        $img = !empty($course['image']) ? $course['image'] : 'default.jpg';
        ?>
        
        <div class="course-detail-card fade-in-up">
            
            <h1 class="course-detail-title"><i class="fas fa-graduation-cap"></i> <?php echo htmlspecialchars($course['title']); ?></h1>
            
            <div class="course-detail-layout">
                
                <div class="course-detail-image">
                    <img src="uploads/<?php echo htmlspecialchars($img); ?>" alt="<?php echo htmlspecialchars($course['title']); ?>">
                </div>
                
                <div class="course-detail-info">
                    
                    <p><strong><i class="fas fa-align-right"></i> تفاصيل الدورة:</strong><br> <?php echo htmlspecialchars($course['description']); ?></p>
                    
                    <div class="price-section">
                        
                        <p class="price-display"><i class="fas fa-coins"></i> السعر المطلوب: <b><?php echo $display_price; ?> ريال</b></p>
                        
                        <form method="POST" class="coupon-form">
                            <input type="text" name="coupon_code" placeholder="أدخل كود الخصم" id="coupon-input">
                            <button type="submit" name="apply_coupon" id="apply-coupon-btn"><i class="fas fa-tag"></i> تطبيق</button>
                        </form>
                        
                        <?php echo $coupon_message; ?>
                    </div>

                    <form method="POST" action="checkout.php" style="margin-top: 24px;">
                        <input type="hidden" name="course_id" value="<?php echo $course['id']; ?>">
                        <input type="hidden" name="final_price" value="<?php echo $display_price; ?>">
                        <input type="hidden" name="original_price" value="<?php echo $original_price; ?>">
                        <input type="hidden" name="coupon_used" value="<?php echo isset($_POST['coupon_code']) ? htmlspecialchars($_POST['coupon_code']) : ''; ?>">
                        <button type="submit" class="btn btn-primary btn-block btn-lg" id="enroll-btn"><i class="fas fa-cart-shopping"></i> سجل الآن</button>
                    </form>
                    
                    <?php if ($is_admin): ?>
                    <div class="action-buttons">
                        <a href="admin_edit_course.php?id=<?php echo $course['id']; ?>" class="btn btn-warning" id="edit-course-btn"><i class="fas fa-pen-to-square"></i> تعديل</a>
                        <a href="admin_delete_course.php?id=<?php echo $course['id']; ?>" onclick="return confirm('هل أنت متأكد من الحذف؟');" class="btn btn-danger" id="delete-course-btn"><i class="fas fa-trash"></i> حذف</a>
                    </div>
                    <?php endif; ?>
                </div>
            </div>
            
            <div class="back-section">
                <a href="display_courses.php" class="back-link"><i class="fas fa-arrow-right"></i> العودة لقائمة الدورات</a>
            </div>
        </div>

        <?php
    }
    mysqli_stmt_close($stmt);
}
// في النهاية نستدعي الفوتر (الجزء السفلي للموقع)
include 'footer.php';
?>