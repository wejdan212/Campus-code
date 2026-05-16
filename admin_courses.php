<?php
require_once 'admin_check.php';
require_once 'connection.php';

$current_page = 'courses';
$page_title = 'إدارة الدورات';
$page_subtitle = 'عرض وإدارة جميع الدورات التعليمية';

// جلب الدورات مع اسم المسار
$query = "SELECT c.*, t.name as track_name FROM courses c LEFT JOIN tracks t ON c.track_id = t.id ORDER BY c.id DESC";
$result = mysqli_query($conn, $query);

// رسالة النجاح من الإضافة أو التعديل
$success_msg = '';
if (isset($_GET['msg'])) {
    if ($_GET['msg'] === 'added') $success_msg = 'تمت إضافة الدورة بنجاح!';
    elseif ($_GET['msg'] === 'updated') $success_msg = 'تم تحديث الدورة بنجاح!';
    elseif ($_GET['msg'] === 'deleted') $success_msg = 'تم حذف الدورة بنجاح!';
}

include 'admin_header.php';
?>

<?php if (!empty($success_msg)): ?>
    <div class="admin-alert admin-alert-success">
        <i class="fas fa-circle-check"></i> <?php echo $success_msg; ?>
    </div>
<?php endif; ?>

<div class="quick-actions">
    <a href="admin_add_course.php" class="btn-admin btn-add"><i class="fas fa-plus"></i> إضافة دورة جديدة</a>
</div>

<div class="admin-table-wrapper">
    <div class="admin-table-header">
        <h3><i class="fas fa-book"></i> جميع الدورات (<?php echo mysqli_num_rows($result); ?>)</h3>
    </div>
    <table class="admin-table">
        <thead>
            <tr>
                <th>#</th>
                <th>الصورة</th>
                <th>العنوان</th>
                <th>السعر</th>
                <th>المسار</th>
                <th>الوصف</th>
                <th>الحالة</th>
                <th>الإجراءات</th>
            </tr>
        </thead>
        <tbody>
            <?php if (mysqli_num_rows($result) > 0): ?>
                <?php while ($row = mysqli_fetch_assoc($result)): ?>
                    <tr>
                        <td><?php echo $row['id']; ?></td>
                        <td>
                            <?php $img = !empty($row['image']) ? $row['image'] : 'default.jpg'; ?>
                            <img src="uploads/<?php echo htmlspecialchars($img); ?>" alt="" class="table-img">
                        </td>
                        <td><strong><?php echo htmlspecialchars($row['title']); ?></strong></td>
                        <td><?php echo htmlspecialchars($row['price']); ?> ريال</td>
                        <td><?php echo htmlspecialchars($row['track_name'] ?? 'غير محدد'); ?></td>
                        <td><?php echo mb_strimwidth(htmlspecialchars($row['description']), 0, 60, '...'); ?></td>
                        <td>
                            <?php if (($row['status'] ?? 'active') === 'active'): ?>
                                <span class="badge badge-active"><i class="fas fa-circle" style="font-size:0.5rem"></i> نشطة</span>
                            <?php else: ?>
                                <span class="badge badge-inactive"><i class="fas fa-circle" style="font-size:0.5rem"></i> غير نشطة</span>
                            <?php endif; ?>
                        </td>
                        <td>
                            <div class="action-btns">
                                <a href="admin_edit_course.php?id=<?php echo $row['id']; ?>" class="btn-admin btn-edit" title="تعديل">
                                    <i class="fas fa-pen"></i>
                                </a>
                                <a href="admin_delete_course.php?id=<?php echo $row['id']; ?>" class="btn-admin btn-delete" title="حذف"
                                   onclick="return confirm('هل أنت متأكد من حذف هذه الدورة؟');">
                                    <i class="fas fa-trash"></i>
                                </a>
                            </div>
                        </td>
                    </tr>
                <?php endwhile; ?>
            <?php else: ?>
                <tr><td colspan="8" style="text-align:center; padding:30px; color:var(--admin-text-muted);">لا توجد دورات بعد</td></tr>
            <?php endif; ?>
        </tbody>
    </table>
</div>

<?php include 'admin_footer.php'; ?>
