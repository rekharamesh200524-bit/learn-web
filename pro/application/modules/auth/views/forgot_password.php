<!DOCTYPE html>
<html>
<head>
<title>Forgot Password</title>
<link rel="stylesheet" href="<?= base_url('assets/css/style.css') ?>">
</head>
<body>

<div class="center-page">
<form method="post" action="<?= base_url('index.php/auth/send_reset_link') ?>">

<h2 style="text-align:center;">Forgot Password</h2>

<?php if (!empty($error)): ?>
    <div class="error"><?= $error ?></div>
<?php endif; ?>

<input type="email" name="email" placeholder="Enter registered email" required>

<button type="submit" style="width:100%;">Send Reset Link</button>

</form>
</div>

</body>
</html>
