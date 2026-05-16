<?php
// 1. بدء الجلسة واستدعاء الاتصال بقاعدة البيانات
session_start();
include 'connection.php';
include 'header.php';

// 2. نتأكد إن البيانات جات من صفحة تفاصيل الدورة
$course_id = isset($_POST['course_id']) ? $_POST['course_id'] : (isset($_GET['id']) ? $_GET['id'] : null);
$final_price = isset($_POST['final_price']) ? $_POST['final_price'] : null;
$original_price = isset($_POST['original_price']) ? $_POST['original_price'] : null;
$coupon_used = isset($_POST['coupon_used']) ? $_POST['coupon_used'] : '';

// 3. نتحقق إن في رقم دورة
if (!$course_id) {
    echo "<div class='checkout-error-page fade-in-up'>
            <div class='checkout-error-icon'><i class='fas fa-cart-shopping'></i></div>
            <h2>لا توجد دورة محددة</h2>
            <p>يرجى اختيار دورة أولاً من قائمة الدورات</p>
            <a href='display_courses.php' class='btn btn-primary btn-lg'><i class='fas fa-arrow-right'></i> تصفح الدورات</a>
          </div>";
    include 'footer.php';
    exit();
}

// 4. نجلب بيانات الدورة من قاعدة البيانات
$course_id = mysqli_real_escape_string($conn, $course_id);
$query = "SELECT * FROM courses WHERE id = '$course_id'";
$result = mysqli_query($conn, $query);

if (mysqli_num_rows($result) == 0) {
    echo "<div class='checkout-error-page fade-in-up'>
            <div class='checkout-error-icon'><i class='fas fa-circle-xmark'></i></div>
            <h2>الدورة غير موجودة</h2>
            <p>عذراً، لم نتمكن من العثور على هذه الدورة</p>
            <a href='display_courses.php' class='btn btn-primary btn-lg'><i class='fas fa-arrow-right'></i> تصفح الدورات</a>
          </div>";
    include 'footer.php';
    exit();
}

$course = mysqli_fetch_assoc($result);

// إذا ما جا سعر من الصفحة السابقة، نستخدم سعر الدورة الأصلي
if ($final_price === null) {
    $final_price = $course['price'];
}
if ($original_price === null) {
    $original_price = $course['price'];
}

$has_discount = ($final_price < $original_price);
$discount_amount = $original_price - $final_price;
$img = !empty($course['image']) ? $course['image'] : 'default.jpg';

// 5. معالجة الدفع عند الضغط على زر "إتمام الدفع"
$payment_success = false;
$payment_error = "";

if (isset($_POST['confirm_payment'])) {
    $card_name = isset($_POST['card_name']) ? trim($_POST['card_name']) : '';
    $card_number = isset($_POST['card_number']) ? trim($_POST['card_number']) : '';
    $card_expiry = isset($_POST['card_expiry']) ? trim($_POST['card_expiry']) : '';
    $card_cvv = isset($_POST['card_cvv']) ? trim($_POST['card_cvv']) : '';
    
    // تحقق بسيط من البيانات
    if (empty($card_name) || empty($card_number) || empty($card_expiry) || empty($card_cvv)) {
        $payment_error = "يرجى تعبئة جميع حقول بيانات البطاقة";
    } elseif (strlen(preg_replace('/\s+/', '', $card_number)) < 16) {
        $payment_error = "رقم البطاقة غير صحيح";
    } elseif (strlen($card_cvv) < 3) {
        $payment_error = "رمز CVV غير صحيح";
    } else {
        // الدفع ناجح (محاكاة)
        $payment_success = true;
        
        // هنا ممكن نسجل عملية الشراء في قاعدة البيانات
        // مثال: INSERT INTO enrollments ...
    }
    
    // نعيد تعيين بيانات الدورة لعرضها مرة ثانية
    $p_course_id = isset($_POST['course_id']) ? $_POST['course_id'] : $course_id;
    $final_price = isset($_POST['final_price']) ? $_POST['final_price'] : $final_price;
    $original_price = isset($_POST['original_price']) ? $_POST['original_price'] : $original_price;
    $has_discount = ($final_price < $original_price);
    $discount_amount = $original_price - $final_price;
}
?>

<?php if ($payment_success): ?>
<!-- ============================================ -->
<!-- صفحة نجاح الدفع -->
<!-- ============================================ -->
<div class="checkout-success-page fade-in-up">
    <div class="success-animation">
        <div class="success-circle">
            <i class="fas fa-check"></i>
        </div>
    </div>
    <h2>تم الدفع بنجاح! 🎉</h2>
    <p class="success-subtitle">تهانينا! تم تسجيلك في الدورة بنجاح</p>
    
    <div class="success-details">
        <div class="success-detail-item">
            <i class="fas fa-graduation-cap"></i>
            <div>
                <span class="detail-label">الدورة</span>
                <span class="detail-value"><?php echo $course['title']; ?></span>
            </div>
        </div>
        <div class="success-detail-item">
            <i class="fas fa-coins"></i>
            <div>
                <span class="detail-label">المبلغ المدفوع</span>
                <span class="detail-value"><?php echo $final_price; ?> ريال</span>
            </div>
        </div>
        <div class="success-detail-item">
            <i class="fas fa-calendar-check"></i>
            <div>
                <span class="detail-label">تاريخ التسجيل</span>
                <span class="detail-value"><?php echo date('Y/m/d - h:i A'); ?></span>
            </div>
        </div>
    </div>
    
    <div class="success-actions">
        <a href="display_courses.php" class="btn btn-primary btn-lg"><i class="fas fa-house"></i> الصفحة الرئيسية</a>
        <a href="course_details.php?id=<?php echo $course['id']; ?>" class="btn btn-outline btn-lg"><i class="fas fa-eye"></i> عرض الدورة</a>
    </div>
</div>

<?php else: ?>
<!-- ============================================ -->
<!-- صفحة الدفع الرئيسية -->
<!-- ============================================ -->
<div class="checkout-page fade-in-up">
    
    <div class="checkout-header">
        <div class="checkout-header-icon">
            <i class="fas fa-lock"></i>
        </div>
        <h1><i class="fas fa-credit-card"></i> إتمام عملية الدفع</h1>
        <p>أكمل بيانات الدفع لتسجيلك في الدورة</p>
    </div>

    <?php if (!empty($payment_error)): ?>
        <div class="error-msg"><i class="fas fa-circle-exclamation"></i> <?php echo $payment_error; ?></div>
    <?php endif; ?>

    <div class="checkout-layout">
        
        <!-- القسم الأيسر: ملخص الطلب -->
        <div class="checkout-summary">
            <div class="summary-card">
                <h3><i class="fas fa-receipt"></i> ملخص الطلب</h3>
                
                <div class="summary-course">
                    <div class="summary-course-image">
                        <img src="uploads/<?php echo $img; ?>" alt="<?php echo $course['title']; ?>">
                    </div>
                    <div class="summary-course-info">
                        <h4><?php echo $course['title']; ?></h4>
                        <p class="summary-course-desc"><?php echo mb_substr($course['description'], 0, 80) . '...'; ?></p>
                    </div>
                </div>
                
                <div class="summary-divider"></div>
                
                <div class="summary-prices">
                    <div class="summary-row">
                        <span>السعر الأصلي</span>
                        <span><?php echo $original_price; ?> ريال</span>
                    </div>
                    
                    <?php if ($has_discount): ?>
                    <div class="summary-row discount-row">
                        <span><i class="fas fa-tag"></i> الخصم <?php echo !empty($coupon_used) ? "($coupon_used)" : ''; ?></span>
                        <span class="discount-value">- <?php echo $discount_amount; ?> ريال</span>
                    </div>
                    <?php endif; ?>
                    
                    <div class="summary-divider"></div>
                    
                    <div class="summary-row total-row">
                        <span>الإجمالي</span>
                        <span class="total-value"><?php echo $final_price; ?> ريال</span>
                    </div>
                </div>
                
                <div class="summary-secure">
                    <i class="fas fa-shield-halved"></i>
                    <span>دفع آمن ومشفر 100%</span>
                </div>
            </div>
        </div>
        
        <!-- القسم الأيمن: نموذج الدفع -->
        <div class="checkout-form-section">
            <div class="payment-card">
                <h3><i class="fas fa-credit-card"></i> بيانات البطاقة البنكية</h3>
                
                <div class="accepted-cards">
                    <i class="fab fa-cc-visa"></i>
                    <i class="fab fa-cc-mastercard"></i>
                    <i class="fab fa-cc-amex"></i>
                    <i class="fab fa-cc-apple-pay"></i>
                </div>
                
                <form method="POST" action="" id="checkout-form">
                    <!-- حقول مخفية لنقل بيانات الدورة -->
                    <input type="hidden" name="course_id" value="<?php echo $course['id']; ?>">
                    <input type="hidden" name="final_price" value="<?php echo $final_price; ?>">
                    <input type="hidden" name="original_price" value="<?php echo $original_price; ?>">
                    <input type="hidden" name="coupon_used" value="<?php echo $coupon_used; ?>">
                    
                    <div class="form-group">
                        <label for="card-name"><i class="fas fa-user"></i> الاسم على البطاقة</label>
                        <input type="text" name="card_name" id="card-name" placeholder="مثال:   وجدان خلف الطويرقي" required>
                    </div>
                    
                    <div class="form-group">
                        <label for="card-number"><i class="fas fa-hashtag"></i> رقم البطاقة</label>
                        <input type="text" name="card_number" id="card-number" placeholder="0000 0000 0000 0000" maxlength="19" required dir="ltr">
                    </div>
                    
                    <div class="form-row-half">
                        <div class="form-group">
                            <label for="card-expiry"><i class="fas fa-calendar"></i> تاريخ الانتهاء</label>
                            <input type="text" name="card_expiry" id="card-expiry" placeholder="MM/YY" maxlength="5" required dir="ltr">
                        </div>
                        <div class="form-group">
                            <label for="card-cvv"><i class="fas fa-lock"></i> رمز CVV</label>
                            <input type="text" name="card_cvv" id="card-cvv" placeholder="123" maxlength="4" required dir="ltr">
                        </div>
                    </div>
                    
                    <button type="submit" name="confirm_payment" class="btn btn-primary btn-block btn-lg checkout-btn" id="checkout-pay-btn">
                        <i class="fas fa-lock"></i> ادفع <?php echo $final_price; ?> ريال
                    </button>
                </form>
                
                <div class="checkout-back">
                    <a href="course_details.php?id=<?php echo $course['id']; ?>" class="back-link"><i class="fas fa-arrow-right"></i> العودة لتفاصيل الدورة</a>
                </div>
            </div>
        </div>
        
    </div>
</div>

<script>
// تنسيق رقم البطاقة تلقائياً (إضافة مسافات كل 4 أرقام)
document.getElementById('card-number').addEventListener('input', function(e) {
    var val = this.value.replace(/\s+/g, '').replace(/[^0-9]/gi, '');
    var formatted = val.match(/.{1,4}/g);
    this.value = formatted ? formatted.join(' ') : '';
});

// تنسيق تاريخ الانتهاء تلقائياً (MM/YY)
document.getElementById('card-expiry').addEventListener('input', function(e) {
    var val = this.value.replace(/\D/g, '');
    if (val.length >= 2) {
        this.value = val.substring(0, 2) + '/' + val.substring(2, 4);
    }
});

// السماح فقط بأرقام في CVV
document.getElementById('card-cvv').addEventListener('input', function(e) {
    this.value = this.value.replace(/\D/g, '');
});
</script>

<?php endif; ?>

<?php
include 'footer.php';
?>
