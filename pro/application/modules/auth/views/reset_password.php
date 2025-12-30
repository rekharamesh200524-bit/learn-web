<!DOCTYPE html>
<html>
<head>
<title>Reset Password</title>

<!-- NOTEBOOK PRO THEME (INLINE CSS) -->
<style>
/* RESET */
*{
    margin:0;
    padding:0;
    box-sizing:border-box;
}

/* NOTEBOOK BACKGROUND */
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

/* PAPER CARD */
form{
    width:100%;
    max-width:420px;
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

/* NOTE CLIP */
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

h2::after{
    content:"Set a new password and continue learning";
    display:block;
    font-size:13px;
    color:#6b7280;
    margin-top:6px;
}

/* INPUT */
input{
    width:100%;
    padding:14px 12px;
    margin-top:22px;
    margin-bottom:22px;
    border:none;
    border-bottom:2px solid #d1d5db;
    font-size:15px;
    background:transparent;
    transition:all .3s ease;
}

/* WRITING EFFECT */
input:focus{
    outline:none;
    border-bottom-color:#2563eb;
    background:
        linear-gradient(
            to right,
            rgba(37,99,235,.05),
            transparent
        );
}

/* BUTTON */
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
}

/* BUTTON HOVER */
button:hover{
    background:#1d4ed8;
    box-shadow:0 14px 30px rgba(37,99,235,.35);
    transform:translateY(-2px);
}

/* BUTTON PRESS */
button:active{
    transform:scale(.97);
    box-shadow:inset 0 4px 10px rgba(0,0,0,.25);
}

/* HELPER TEXT */
.helper-text{
    text-align:center;
    font-size:13px;
    color:#6b7280;
    margin-top:14px;
}
</style>

</head>
<body>

<div class="center-page">

    <form method="post" action="<?= base_url('index.php/auth/update_password') ?>">

        <h2>Reset Password</h2>

        <input type="hidden" name="token" value="<?= $token ?>">

        <input type="password"
               name="password"
               placeholder="New Password"
               required>

        <button type="submit">Update Password</button>

        <div class="helper-text">
            Choose a strong password to keep your learning secure
        </div>

    </form>

</div>

</body>
</html>
