<!DOCTYPE html>
<html>
<head>
    <title>Admin Dashboard</title>

    <link rel="stylesheet" href="<?= base_url('assets/css/style.css') ?>">

    <!-- Chart.js -->
    <script src="https://cdn.jsdelivr.net/npm/chart.js"></script>
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

    <!-- ================= MASTER ADMIN VIEW ================= -->
    <?php if ($this->session->userdata('role') === 'master_admin'): ?>

        <!-- ===== SYSTEM STATS ===== -->
        <div class="section">
            <h3>System Overview</h3>

            <div style="display:flex; gap:20px; flex-wrap:wrap;">
                <div class="card">Total Users<br><b><?= $total_users ?></b></div>
                <div class="card">Active Users<br><b><?= $active_users ?></b></div>
                <div class="card">Inactive Users<br><b><?= $inactive_users ?></b></div>
                <div class="card">Today's Logins<br><b><?= $today_logins ?></b></div>
            </div>
        </div>

        <!-- ===== ANALYTICS CHARTS ===== -->
        <div class="section">
            <h3>Analytics</h3>

            <div style="display:flex; gap:40px; flex-wrap:wrap;">
                <div style="width:300px;">
                    <h4>User Status</h4>
                    <canvas id="statusChart"></canvas>
                </div>

                <div style="width:400px;">
                    <h4>Role Distribution</h4>
                    <canvas id="roleChart"></canvas>
                </div>
            </div>
        </div>

        <!-- ===== USER DETAILS ===== -->
        <div class="section">
            <h3>All Users</h3>

            <table>
                <tr>
                    <th>Name</th>
                    <th>Email</th>
                    <th>Role</th>
                    <th>Department</th>
                    <th>Status</th>
                    <th>Last Login</th>
                    <th>Progress</th>
                </tr>

                <?php if (!empty($all_users)): ?>
                    <?php foreach ($all_users as $u): ?>
                        <tr>
                            <td><?= $u->user_name ?></td>
                            <td><?= $u->email ?></td>
                            <td><?= $u->role ?></td>
                            <td><?= $u->department ?: '-' ?></td>
                            <td><?= $u->status ? 'Active' : 'Inactive' ?></td>
                            <td><?= $u->last_login ?: 'Never' ?></td>
                            <td>0%</td>
                        </tr>
                    <?php endforeach; ?>
                <?php else: ?>
                    <tr>
                        <td colspan="7">No users found</td>
                    </tr>
                <?php endif; ?>
            </table>
        </div>

    <?php endif; ?>
    <!-- ================= END MASTER ADMIN VIEW ================= -->

    <!-- ================= REQUESTS TABLE ================= -->
    <div class="section">
        <h3>User Registration Requests</h3>

        <table>
            <tr>
                <th>User Name</th>
                <th>Email</th>
                <th>Mobile</th>
                <th>Department</th>
                <th>Role</th>
                <th>Intern Duration</th>
                <th>Status</th>
                <th>Action</th>
            </tr>

            <?php if (!empty($requests)): ?>
                <?php foreach ($requests as $row): ?>
                    <tr>
                        <td><?= $row->user_name ?></td>
                        <td><?= $row->email ?></td>
                        <td><?= $row->mobile ?></td>
                        <td><?= $row->department ?></td>
                        <td><?= $row->role ?></td>
                        <td><?= ($row->role === 'Intern') ? $row->intern_duration : '-' ?></td>
                        <td><?= $row->status ?></td>
                        <td>
                            <?php if ($row->status === 'Pending'): ?>
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
                    <td colspan="8">No requests found</td>
                </tr>
            <?php endif; ?>
        </table>
    </div>

</div>

<!-- ================= CHART SCRIPTS ================= -->
<?php if ($this->session->userdata('role') === 'master_admin'): ?>
<script>
/* USER STATUS CHART */
const statusCtx = document.getElementById('statusChart');
if (statusCtx) {
    new Chart(statusCtx, {
        type: 'doughnut',
        data: {
            labels: ['Active', 'Inactive'],
            datasets: [{
                data: [<?= $active_users ?>, <?= $inactive_users ?>],
                backgroundColor: ['#22c55e', '#ef4444']
            }]
        }
    });
}

/* ROLE DISTRIBUTION CHART */
const roleCtx = document.getElementById('roleChart');
if (roleCtx) {
    new Chart(roleCtx, {
        type: 'bar',
        data: {
            labels: [
                <?php foreach ($role_counts as $r) { echo "'".$r->role."',"; } ?>
            ],
            datasets: [{
                label: 'Users',
                data: [
                    <?php foreach ($role_counts as $r) { echo $r->total.","; } ?>
                ],
                backgroundColor: '#3b82f6'
            }]
        },
        options: {
            responsive: true,
            scales: {
                y: { beginAtZero: true }
            }
        }
    });
}
</script>
<?php endif; ?>

</body>
</html>
