<!DOCTYPE html>
<html>
<head>
    <title>Register</title>

    <!-- Global CSS -->
    <link rel="stylesheet" href="<?= base_url('assets/css/style.css') ?>">
</head>
<body>

<div class="center-page">

    <form method="post" action="<?= base_url('index.php/auth/register_submit') ?>">

        <h2 style="text-align:center;">Register</h2>

        <?php if (validation_errors()): ?>
            <div class="error">
                <?= validation_errors(); ?>
            </div>
        <?php endif; ?>

        <input type="text" name="user_name" placeholder="User Name" required>

        <input type="email" name="email" placeholder="Email" required>

        <input type="text" name="mobile" placeholder="Mobile Number" required>

        <select name="department" required>
            <option value="">Select Department</option>
            <option value="IT">IT</option>
            <option value="HR">HR</option>
            <option value="Finance">Finance</option>
            <option value="Admin">Admin</option>
        </select>

        <input type="password" name="password" placeholder="Password" required>

        <button type="submit" style="width:100%;">Register</button>

        <p style="text-align:center; margin-top:15px;">
            Already have an account?
            <a href="<?= base_url('index.php/auth/login') ?>">Login here</a>
        </p>

    </form>

</div>

</body>
</html>
