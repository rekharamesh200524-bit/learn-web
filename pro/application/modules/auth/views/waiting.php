<!DOCTYPE html>
<html>
<head>
    <title>Registration Status</title>
    <link rel="stylesheet" href="<?= base_url('assets/css/style.css') ?>">
</head>
<body>

<div class="center-page">

    <h2 style="text-align:center;">Registration Status</h2>

    <?php if (!empty($request)): ?>

        <?php if ($request->status === 'Pending'): ?>
            <p style="color:orange; text-align:center;">
                ⏳ Your request is waiting for approval.
            </p>

        <?php elseif ($request->status === 'Approved'): ?>
            <p style="color:green; text-align:center;">
                ✅ Your request has been approved.
            </p>

            <!-- LOGIN BUTTON (ONLY AFTER APPROVAL) -->
            <div style="text-align:center; margin-top:15px;">
                <a href="<?= base_url('index.php/auth/login') ?>" class="btn">
                    🔐 Go to Login
                </a>
            </div>

        <?php elseif ($request->status === 'Rejected'): ?>
            <p style="color:red; text-align:center;">
                ❌ Your request has been rejected. Please contact admin.
            </p>

        <?php endif; ?>

    <?php else: ?>
        <p style="text-align:center; color:red;">
            No registration request found.
        </p>
    <?php endif; ?>

    <!-- BACK TO REGISTER (ALWAYS VISIBLE) -->
    <div style="text-align:center; margin-top:25px;">
        <a href="<?= base_url('index.php/auth/register') ?>" class="btn btn-secondary">
            ⬅ Back to Register
        </a>
    </div>

</div>

</body>
</html>
