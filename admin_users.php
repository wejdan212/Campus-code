<?php
require_once 'admin_check.php';
require_once 'connection.php';

$current_page = 'users';
$page_title = 'إدارة المستخدمين';
$page_subtitle = 'عرض جميع المستخدمين المسجلين';

$result = mysqli_query($conn, "SELECT * FROM users ORDER BY id DESC");

include 'admin_header.php';
?>

<div class="admin-table-wrapper">
    <div class="admin-table-header">
        <h3><i class="fas fa-users"></i> جميع المستخدمين (<?php echo mysqli_num_rows($result); ?>)</h3>
    </div>
    <table class="admin-table">
        <thead>
            <tr>
                <th>#</th>
                <th>الاسم</th>
                <th>البريد الإلكتروني</th>
                <th>الصلاحية</th>
                <th>تاريخ التسجيل</th>
            </tr>
        </thead>
        <tbody>
            <?php if (mysqli_num_rows($result) > 0): ?>
                <?php while ($user = mysqli_fetch_assoc($result)): ?>
                    <tr>
                        <td><?php echo $user['id']; ?></td>
                        <td><?php echo htmlspecialchars($user['name'] ?? 'غير محدد'); ?></td>
                        <td><?php echo htmlspecialchars($user['email']); ?></td>
                        <td>
                            <?php if (($user['role'] ?? 'user') === 'admin'): ?>
                                <span class="badge badge-admin"><i class="fas fa-shield-halved"></i> مدير</span>
                            <?php else: ?>
                                <span class="badge badge-user"><i class="fas fa-user"></i> مستخدم</span>
                            <?php endif; ?>
                        </td>
                        <td><?php echo htmlspecialchars($user['created_at'] ?? 'غير متوفر'); ?></td>
                    </tr>
                <?php endwhile; ?>
            <?php else: ?>
                <tr><td colspan="5" style="text-align:center; padding:30px; color:var(--admin-text-muted);">لا يوجد مستخدمين</td></tr>
            <?php endif; ?>
        </tbody>
    </table>
</div>

<?php include 'admin_footer.php'; ?>
