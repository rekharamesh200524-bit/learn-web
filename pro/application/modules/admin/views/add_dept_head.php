<!DOCTYPE html>
<html>
<head>
    <title>Add Department Head</title>
    <link rel="stylesheet" href="<?= base_url('assets/css/style.css') ?>">
</head>
<body>

<div class="container" style="max-width:500px; margin:auto;">

    <h2>Add Department Head</h2>

    <?php if (validation_errors()): ?>
        <div class="error">
            <?= validation_errors(); ?>
        </div>
    <?php endif; ?>

    <form method="post" action="<?= base_url('index.php/admin/save_dept_head') ?>">

        <label>Name</label>
        <input type="text" name="user_name" required>

        <label>Email</label>
        <input type="email" name="email" required>

        <label>Department</label>
        <select name="department" required>
            <option value="">Select Department</option>
            <option value="IT">IT</option>
            <option value="HR">HR</option>
            <option value="Finance">Finance</option>
        </select>

        <label>Password</label>
        <input type="password" name="password" required>

        <button type="submit" style="width:100%;">Create Department Head</button>

        <br><br>
        <a href="<?= base_url('index.php/admin/dashboard') ?>" class="btn">
            ⬅ Back
        </a>

    </form>

</div>

</body>
</html>
