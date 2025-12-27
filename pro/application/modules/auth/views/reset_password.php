<!DOCTYPE html>
<html>
<head>
<title>Reset Password</title>
<link rel="stylesheet" href="<?= base_url('assets/css/style.css') ?>">
</head>
<body>

<div class="center-page">
<form method="post" action="<?= base_url('index.php/auth/update_password') ?>">

<input type="hidden" name="token" value="<?= $token ?>">

<input type="password" name="password" placeholder="New Password" required>

<button type="submit" style="width:100%;">Update Password</button>

</form>
</div>

</body>
</html>
