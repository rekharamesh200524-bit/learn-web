<h2>Registration Status</h2>

<?php if (!empty($request)): ?>

    <?php if ($request->status == 'Pending'): ?>
        <p style="color:orange;">
            Your request is waiting for admin approval.
        </p>

    <?php elseif ($request->status == 'Approved'): ?>
        <p style="color:green;">
            Your request has been approved. You can login now.
        </p>
        <a href="<?= base_url('index.php/auth/login') ?>">Go to Login</a>

    <?php elseif ($request->status == 'Rejected'): ?>
        <p style="color:red;">
            Your request has been rejected. Please contact admin.
        </p>
    <?php endif; ?>

<?php else: ?>
    <p>No request found.</p>
<?php endif; ?>
