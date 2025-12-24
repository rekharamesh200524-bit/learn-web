<!DOCTYPE html>
<html>
<head>
    <title>Admin Dashboard</title>
    <link rel="stylesheet" href="<?= base_url('assets/css/style.css') ?>">
</head>
<body>

<div class="container">

    <!-- ================= HEADER ================= -->
    <div style="display:flex; justify-content:space-between; align-items:center; margin-bottom:20px;">
        <h2>Admin Dashboard</h2>

        <a href="<?= base_url('index.php/auth/login') ?>" class="btn">
            Logout
        </a>
    </div>

    <!-- ================= ACTION BUTTONS ================= -->
    <div class="section">

        <div style="display:flex; gap:15px; flex-wrap:wrap;">

            <a href="<?= base_url('index.php/admin/upload') ?>" class="btn">
                📤 Upload Files
            </a>

            <a href="<?= base_url('index.php/admin/manage_courses') ?>" class="btn">
                📚 Manage Courses
            </a>

        </div>

    </div>

    <!-- ================= REQUESTS TABLE ================= -->
    <div class="section">

        <h3>User Registration Requests</h3>

        <table>
            <tr>
                <th>User Name</th>
                <th>Email</th>
                <th>Status</th>
                <th>Action</th>
            </tr>

            <?php if (!empty($requests)): ?>
                <?php foreach ($requests as $row): ?>
                    <tr>
                        <td><?= $row->user_name ?></td>
                        <td><?= $row->email ?></td>
                        <td><?= $row->status ?></td>
                        <td>
                            <?php if ($row->status == 'Pending'): ?>

                                <a href="<?= base_url('index.php/admin/approve/'.$row->request_id) ?>" class="btn">
                                    Approve
                                </a>

                                <a href="<?= base_url('index.php/admin/reject/'.$row->request_id) ?>"
                                   class="btn"
                                   style="background:#dc3545; margin-left:5px;"
                                   onclick="return confirm('Are you sure you want to reject this user?')">
                                    Reject
                                </a>

                            <?php else: ?>
                                <?= $row->status ?>
                            <?php endif; ?>
                        </td>
                    </tr>
                <?php endforeach; ?>
            <?php else: ?>
                <tr>
                    <td colspan="4">No requests found</td>
                </tr>
            <?php endif; ?>
        </table>

    </div>

</div>

</body>
</html>
