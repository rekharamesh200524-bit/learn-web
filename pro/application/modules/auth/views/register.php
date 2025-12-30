<!DOCTYPE html>
<html>
<head>
    <title>Register</title>

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
        max-width:460px;
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
        content:"Create your learning account";
        display:block;
        font-size:13px;
        color:#6b7280;
        margin-top:6px;
    }

    /* INPUTS & SELECTS */
    input,
    select{
        width:100%;
        padding:14px 12px;
        margin-bottom:20px;
        border:none;
        border-bottom:2px solid #d1d5db;
        font-size:15px;
        background:transparent;
        transition:all .3s ease;
    }

    input:focus,
    select:focus{
        outline:none;
        border-bottom-color:#2563eb;
        background:
            linear-gradient(
                to right,
                rgba(37,99,235,.05),
                transparent
            );
    }

    select{
        cursor:pointer;
    }

    /* INTERN BOX ANIMATION */
    #internBox{
        animation:slideDown .4s ease;
    }

    @keyframes slideDown{
        from{opacity:0; transform:translateY(-10px);}
        to{opacity:1; transform:translateY(0);}
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

    button:hover{
        background:#1d4ed8;
        box-shadow:0 14px 30px rgba(37,99,235,.35);
        transform:translateY(-2px);
    }

    button:active{
        transform:scale(.97);
        box-shadow:inset 0 4px 10px rgba(0,0,0,.25);
    }

    /* FOOTER TEXT */
    p{
        text-align:center;
        margin-top:16px;
        font-size:14px;
        color:#374151;
    }

    a{
        color:#2563eb;
        font-weight:600;
        text-decoration:none;
    }

    a:hover{
        text-decoration:underline;
    }

    /* ERROR */
    .error{
        background:#fff1f2;
        color:#9f1239;
        padding:12px;
        border-radius:8px;
        margin-bottom:18px;
        border-left:4px solid #ef4444;
        animation:penMark .45s;
    }

    @keyframes penMark{
        0%{transform:translateX(0)}
        30%{transform:translateX(-5px)}
        60%{transform:translateX(5px)}
        100%{transform:translateX(0)}
    }
    page-turn {
    position: fixed;
    inset: 0;
    background:
        repeating-linear-gradient(
            to bottom,
            #f9fafb 0px,
            #f9fafb 28px,
            #e5e7eb 29px
        );
    transform-origin: left center;
    transform: perspective(1200px) rotateY(0deg);
    animation: pageTurn 0.8s ease forwards;
    z-index: 9999;
}

/* PAGE TURN ANIMATION */
@keyframes pageTurn {
    0% {
        transform: perspective(1200px) rotateY(0deg);
    }
    100% {
        transform: perspective(1200px) rotateY(-110deg);
    }
}
    </style>
</head>

<body>

<div class="center-page">

    <form method="post" action="<?= base_url('index.php/auth/register_submit') ?>">

        <h2>Register</h2>

        <?php if (validation_errors()): ?>
            <div class="error">
                <?= validation_errors(); ?>
            </div>
        <?php endif; ?>

        <input type="text"
               name="user_name"
               placeholder="User Name"
               value="<?= set_value('user_name') ?>"
               required>

        <input type="email"
               name="email"
               placeholder="Email"
               value="<?= set_value('email') ?>"
               required>

        <input type="text"
               name="mobile"
               placeholder="Mobile Number"
               value="<?= set_value('mobile') ?>"
               required>

        <select name="department" required>
            <option value="">Select Department</option>
            <option value="IT" <?= set_select('department','IT') ?>>IT</option>
            <option value="HR" <?= set_select('department','HR') ?>>HR</option>
            <option value="Finance" <?= set_select('department','Finance') ?>>Finance</option>
        </select>

        <input type="password"
               name="password"
               placeholder="Password"
               required>

        <select name="role"
                id="role"
                required
                onchange="toggleInternBox()">
            <option value="">Select Role</option>
            <option value="employee" <?= set_select('role','employee') ?>>Employee</option>
            <option value="intern" <?= set_select('role','intern') ?>>Intern</option>
        </select>

        <div id="internBox"
             style="display:<?= (set_value('role') === 'intern') ? 'block' : 'none'; ?>;">
            <select name="intern_duration">
                <option value="">Select Internship Duration</option>
                <option value="7_days" <?= set_select('intern_duration','7_days') ?>>7 Days</option>
                <option value="1_month" <?= set_select('intern_duration','1_month') ?>>1 Month</option>
                <option value="2_months" <?= set_select('intern_duration','2_months') ?>>2 Months</option>
                <option value="3_months" <?= set_select('intern_duration','3_months') ?>>3 Months</option>
                <option value="6_months" <?= set_select('intern_duration','6_months') ?>>6 Months</option>
            </select>
        </div>

        <button type="submit">Register</button>

        <p>
            Already have an account?
            <a href="<?= base_url('index.php/auth/login') ?>">Login here</a>
        </p>

    </form>

</div>

<script>
function toggleInternBox() {
    let role = document.getElementById("role").value;
    let internBox = document.getElementById("internBox");

    if (role === "intern") {
        internBox.style.display = "block";
    } else {
        internBox.style.display = "none";
    }
}
</script>

</body>
</html>
