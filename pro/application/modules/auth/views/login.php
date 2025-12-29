<!DOCTYPE html>
<html>
<head>
    <title>Login</title>
    <link rel="stylesheet" href="<?= base_url('assets/css/style.css') ?>">
</head>
<body>

<div class="center-page">

    <form method="post" action="<?= base_url('index.php/auth/login_check') ?>">

        <h2 style="text-align:center;">Login</h2>

       <?php if ($this->session->flashdata('email_error')): ?>
    <div class="error">
        <?= $this->session->flashdata('email_error'); ?>
    </div>
<?php endif; ?>

<?php if ($this->session->flashdata('password_error')): ?>
    <div class="error">
        <?= $this->session->flashdata('password_error'); ?>
    </div>
<?php endif; ?>

<?php if ($this->session->flashdata('login_error')): ?>
    <div class="error">
        <?= $this->session->flashdata('login_error'); ?>
    </div>
<?php endif; ?>


       <input type="email" name="email"
       placeholder="Email"
       value="<?= $this->session->flashdata('old_email') ?? '' ?>"
       required>  

        
        <input type="password"
               name="password"
               placeholder="Password"
               required>

        
        <button type="submit" style="width:100%;">Login</button>

        
        <p style="text-align:center; margin-top:15px;">
            New user?
            <a href="<?= base_url('index.php/auth/register') ?>">Register here</a>
        </p>

        
        <p style="text-align:center; margin-top:8px;">
            <a href="<?= base_url('index.php/auth/forgot_password') ?>">
                Forgot Password?
            </a>
        </p>

    </form>

</div>

</body>
</html>
