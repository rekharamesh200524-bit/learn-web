<!DOCTYPE html>
<html>
<head>
    <title>Login</title>

    <!-- INLINE DARK THEME CSS -->
   <style>
/* RESET */
*{
    margin:0;
    padding:0;
    box-sizing:border-box;
}

/* NOTEBOOK BACKGROUND WITH MOVING LINES */
body{
    min-height:100vh;
    display:flex;
    justify-content:center;
    align-items:center;
    font-family:"Segoe UI", system-ui, sans-serif;
    background:
        repeating-linear-gradient(
            to bottom,
            #f9fafb 0px,
            #f9fafb 28px,
            #e5e7eb 29px
        );
    position:relative;
    overflow:hidden;
}

/* RED NOTEBOOK MARGIN */
body::before{
    content:"";
    position:absolute;
    left:7%;
    top:0;
    width:3px;
    height:100%;
    background:#f87171;
    animation:marginGlow 4s ease-in-out infinite;
}

/* MARGIN ANIMATION */
@keyframes marginGlow{
    0%,100%{opacity:.4}
    50%{opacity:.8}
}

/* CENTER */
.center-page{
    width:100%;
    display:flex;
    justify-content:center;
    z-index:2;
}

/* PAPER CARD (STACK EFFECT) */
form{
    width:100%;
    max-width:430px;
    background:#ffffff;
    padding:42px;
    border-radius:12px;
    border:1px solid #e5e7eb;
    box-shadow:
        0 20px 40px rgba(0,0,0,0.12),
        0 8px 0 #f3f4f6,
        0 16px 0 #e5e7eb;
    animation:pageEnter 1s ease;
    position:relative;
}

/* PAGE ENTER */
@keyframes pageEnter{
    from{
        opacity:0;
        transform:translateY(-30px) rotate(-1.5deg);
    }
    to{
        opacity:1;
        transform:translateY(0) rotate(0);
    }
}

/* TOP NOTE CLIP */
form::before{
    content:"";
    position:absolute;
    top:-10px;
    left:50%;
    transform:translateX(-50%);
    width:70px;
    height:14px;
    background:#d1d5db;
    border-radius:4px;
}

/* HEADING */
h2{
    text-align:center;
    font-size:27px;
    font-weight:700;
    color:#111827;
    margin-bottom:6px;
}

/* SUBTITLE */
h2::after{
    content:"Open your notebook and continue learning";
    display:block;
    font-size:13px;
    color:#6b7280;
    margin-top:6px;
}

/* INPUTS (WRITING EFFECT) */
input{
    width:100%;
    padding:14px 12px;
    margin-bottom:20px;
    border:none;
    border-bottom:2px solid #d1d5db;
    font-size:15px;
    background:transparent;
    transition:all .3s ease;
}

/* FOCUS = WRITING */
input:focus{
    outline:none;
    border-bottom-color:#2563eb;
    background:
        linear-gradient(
            to right,
            rgba(37,99,235,.05),
            transparent
        );
    animation:writing 0.4s ease;
}

/* WRITING ANIMATION */
@keyframes writing{
    from{background-size:0% 100%}
    to{background-size:100% 100%}
}

/* BUTTON (INK PRESS) */
button{
    width:100%;
    padding:14px;
    border:none;
    border-radius:8px;
    background:#2563eb;
    color:#ffffff;
    font-size:15px;
    font-weight:700;
    cursor:pointer;
    transition:all .25s ease;
    position:relative;
}

/* BUTTON PRESS */
button:active{
    transform:scale(.97);
    box-shadow:inset 0 4px 10px rgba(0,0,0,.25);
}

/* BUTTON HOVER */
button:hover{
    background:#1d4ed8;
    box-shadow:0 14px 30px rgba(37,99,235,.35);
}

/* FOOTER TEXT */
p{
    text-align:center;
    margin-top:16px;
    font-size:14px;
    color:#374151;
}

/* LINKS */
a{
    color:#2563eb;
    font-weight:600;
    text-decoration:none;
}

a:hover{
    text-decoration:underline;
}

/* ERROR (RED PEN MARK) */
.error{
    background:#fff1f2;
    color:#9f1239;
    padding:12px;
    border-radius:8px;
    margin-bottom:18px;
    border-left:4px solid #ef4444;
    animation:penMark .45s;
}

/* PEN MARK SHAKE */
@keyframes penMark{
    0%{transform:translateX(0)}
    30%{transform:translateX(-5px)}
    60%{transform:translateX(5px)}
    100%{transform:translateX(0)}
}
</style>

</head>

<body>

<div class="center-page">

    <form method="post" action="<?= base_url('index.php/auth/login_check') ?>">

        <h2>Login</h2>

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

        <input type="email"
               name="email"
               placeholder="Email"
               value="<?= $this->session->flashdata('old_email') ?? '' ?>"
               required>

        <input type="password"
               name="password"
               placeholder="Password"
               required>

        <button type="submit">Login</button>

        <p>
            New user?
            <a href="<?= base_url('index.php/auth/register') ?>">Register here</a>
        </p>

        <p>
            <a href="<?= base_url('index.php/auth/forgot_password') ?>">
                Forgot Password?
            </a>
        </p>

    </form>

</div>

</body>
</html>
