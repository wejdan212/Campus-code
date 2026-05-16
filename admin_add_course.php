<?php
require_once 'admin_check.php';
require_once 'connection.php';

$current_page = 'add_course';
$page_title = 'إضافة دورة جديدة';
$page_subtitle = 'أدخل بيانات الدورة الجديدة';

$errors = [];

if ($_SERVER['REQUEST_METHOD'] === 'POST') {
    $title = trim($_POST['title'] ?? '');
    $price = trim($_POST['price'] ?? '');
    $description = trim($_POST['description'] ?? '');
    $track_id = intval($_POST['track_id'] ?? 0);
    $status = $_POST['status'] ?? 'active';

    // التحقق من المدخلات
    if (empty($title)) $errors[] = 'عنوان الدورة مطلوب';
    if (empty($price)) $errors[] = 'السعر مطلوب';
    elseif (!is_numeric($price) || $price < 0) $errors[] = 'السعر يجب أن يكون رقماً لا يقل عن 0';
    if ($track_id <= 0) $errors[] = 'يجب اختيار المسار';
    if (empty($description)) $errors[] = 'وصف الدورة مطلوب';
    if (!in_array($status, ['active', 'inactive'])) $errors[] = 'الحالة غير صالحة';

    // معالجة الصورة
    $image_name = 'default.jpg';
    if (!empty($_FILES['image']['name'])) {
        $allowed = ['jpg', 'jpeg', 'png', 'webp'];
        $ext = strtolower(pathinfo($_FILES['image']['name'], PATHINFO_EXTENSION));
        if (!in_array($ext, $allowed)) {
            $errors[] = 'نوع الصورة غير مدعوم. الأنواع المسموحة: jpg, jpeg, png, webp';
        } else {
            $image_name = uniqid('course_') . '.' . $ext;
            move_uploaded_file($_FILES['image']['tmp_name'], 'uploads/' . $image_name);
        }
    }

    if (empty($errors)) {
        $stmt = mysqli_prepare($conn, "INSERT INTO courses (title, price, description, track_id, image, status) VALUES (?, ?, ?, ?, ?, ?)");
        mysqli_stmt_bind_param($stmt, "sssiss", $title, $price, $description, $track_id, $image_name, $status);

        if (mysqli_stmt_execute($stmt)) {
            header("Location: admin_courses.php?msg=added");
            exit();
        } else {
            $errors[] = 'حدث خطأ أثناء الإضافة';
        }
        mysqli_stmt_close($stmt);
    }
}

// جلب المسارات
$tracks = mysqli_query($conn, "SELECT * FROM tracks ORDER BY name");

include 'admin_header.php';
?>

<?php if (!empty($errors)): ?>
    <div class="admin-alert admin-alert-error">
        <i class="fas fa-circle-exclamation"></i>
        <?php echo implode(' | ', $errors); ?>
    </div>
<?php endif; ?>

<div class="admin-form-card">
    <h2><i class="fas fa-plus-circle"></i> بيانات الدورة الجديدة</h2>
    <form method="POST" enctype="multipart/form-data">
        <div class="form-row">
            <label for="title"><i class="fas fa-heading"></i> عنوان الدورة</label>
            <input type="text" name="title" id="title" value="<?php echo htmlspecialchars($title ?? ''); ?>" required>
        </div>
        <div class="form-row">
            <label for="price"><i class="fas fa-coins"></i> السعر (ريال)</label>
            <input type="text" name="price" id="price" value="<?php echo htmlspecialchars($price ?? ''); ?>" required>
        </div>
        <div class="form-row">
            <label for="track_id"><i class="fas fa-route"></i> المسار التعليمي</label>
            <select name="track_id" id="track_id" required>
                <option value="">-- اختر المسار --</option>
                <?php while ($t = mysqli_fetch_assoc($tracks)): ?>
                    <option value="<?php echo $t['id']; ?>" <?php echo (isset($track_id) && $track_id == $t['id']) ? 'selected' : ''; ?>>
                        <?php echo htmlspecialchars($t['name']); ?>
                    </option>
                <?php endwhile; ?>
            </select>
        </div>
        <div class="form-row">
            <label for="description"><i class="fas fa-align-right"></i> وصف الدورة</label>
            <textarea name="description" id="description" rows="4" required><?php echo htmlspecialchars($description ?? ''); ?></textarea>
        </div>
        <div class="form-row">
            <label for="status"><i class="fas fa-toggle-on"></i> الحالة</label>
            <select name="status" id="status">
                <option value="active" <?php echo (isset($status) && $status === 'active') ? 'selected' : ''; ?>>نشطة</option>
                <option value="inactive" <?php echo (isset($status) && $status === 'inactive') ? 'selected' : ''; ?>>غير نشطة</option>
            </select>
        </div>
        <div class="form-row">
            <label for="image"><i class="fas fa-image"></i> صورة الدورة</label>
            <input type="file" name="image" id="image" accept=".jpg,.jpeg,.png,.webp">
        </div>
        <div class="form-actions">
            <button type="submit" class="btn-admin btn-save"><i class="fas fa-floppy-disk"></i> حفظ الدورة</button>
            <a href="admin_courses.php" class="btn-admin btn-cancel"><i class="fas fa-xmark"></i> إلغاء</a>
        </div>
    </form>
</div>

<?php include 'admin_footer.php'; ?>
