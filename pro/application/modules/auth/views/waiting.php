<!DOCTYPE html>
<html>
<head>
    <title>Registration Status</title>

    <style>
    /* RESET */
    *{
        margin:0;
        padding:0;
        box-sizing:border-box;
    }

    /* BACKGROUND WITH DEPTH */
    body{
        min-height:100vh;
        display:flex;
        justify-content:center;
        align-items:center;
        font-family:"Segoe UI", system-ui, sans-serif;
        background:
            radial-gradient(circle at top left, #c7d2fe, transparent 40%),
            radial-gradient(circle at bottom right, #fde68a, transparent 45%),
            linear-gradient(135deg,#f8fafc,#eef2ff);
        perspective:1200px;
        overflow:hidden;
    }

    /* FLOATING 3D CARD */
    /* MAIN 3D CARD – FIXED ALIGNMENT */
.center-page{
    width:100%;
    max-width:540px;
    background:linear-gradient(145deg,#ffffff,#f3f4f6);
    padding:48px 44px;
    border-radius:24px;
    box-shadow:
        0 40px 100px rgba(0,0,0,0.15),
        inset 0 1px 0 rgba(255,255,255,0.6);

    /* 🔑 ALIGNMENT FIX */
    display:flex;
    flex-direction:column;
    align-items:center;
    justify-content:center;
    text-align:center;
    gap:14px;

    /* 3D */
    transform-style:preserve-3d;
    animation:floatCard 6s ease-in-out infinite;
    transition:transform 0.3s ease;
    position:relative;
}


    /* FLOAT ANIMATION */
    @keyframes floatCard{
        0%,100%{ transform:translateY(0) rotateX(0deg) rotateY(0deg); }
        50%{ transform:translateY(-12px) rotateX(3deg) rotateY(-3deg); }
    }

    /* GLOSSY LIGHT OVERLAY */
    .center-page::after{
        content:"";
        position:absolute;
        inset:0;
        border-radius:24px;
        background:linear-gradient(
            120deg,
            transparent 40%,
            rgba(255,255,255,0.5),
            transparent 60%
        );
        opacity:0.35;
        pointer-events:none;
    }

    /* TITLE */
    h2{
        font-size:30px;
        font-weight:800;
        color:#111827;
        margin-bottom:10px;
        transform:translateZ(40px);
    }

    /* SUBTITLE */
    .subtitle{
        font-size:15px;
        color:#6b7280;
        margin-bottom:30px;
        transform:translateZ(30px);
    }

    /* STATUS ICON */
    .status-icon{
        font-size:64px;
        margin-bottom:18px;
        animation:iconPulse 1.8s infinite;
        transform:translateZ(60px);
    }

    @keyframes iconPulse{
        0%,100%{ transform:translateZ(60px) scale(1); }
        50%{ transform:translateZ(60px) scale(1.15); }
    }

    /* STATUS TEXT */
    .status-text{
        font-size:18px;
        font-weight:700;
        margin-bottom:14px;
        transform:translateZ(40px);
    }

    .pending{color:#d97706;}
    .approved{color:#15803d;}
    .rejected{color:#b91c1c;}

    /* INFO BOX */
    .info-box{
        background:rgba(255,255,255,0.7);
        backdrop-filter:blur(6px);
        border-radius:14px;
        padding:18px;
        font-size:14px;
        color:#374151;
        line-height:1.6;
        margin-bottom:26px;
        transform:translateZ(25px);
        box-shadow:inset 0 0 0 1px rgba(0,0,0,0.05);
    }

    /* BUTTONS */
    .btn{
        display:inline-block;
        padding:14px 30px;
        border-radius:14px;
        font-weight:700;
        text-decoration:none;
        background:linear-gradient(135deg,#4f46e5,#2563eb);
        color:#ffffff;
        transition:all .3s ease;
        transform:translateZ(50px);
        box-shadow:0 20px 40px rgba(79,70,229,.4);
    }

    .btn:hover{
        transform:translateZ(50px) translateY(-4px);
        box-shadow:0 30px 60px rgba(79,70,229,.55);
    }

    .btn-secondary{
        margin-top:26px;
        background:linear-gradient(135deg,#6b7280,#4b5563);
        box-shadow:0 18px 35px rgba(0,0,0,.25);
    }

    /* HOVER TILT (3D INTERACTION) */
    .center-page:hover{
        transform:rotateX(6deg) rotateY(-6deg);
    }
    </style>
</head>

<body>

<div class="center-page">

    <h2>Registration Status</h2>
    <div class="subtitle">Your learning journey is being prepared</div>

    <?php if (!empty($request)): ?>

        <?php if ($request->status === 'Pending'): ?>
            <div class="status-icon">⏳</div>
            <div class="status-text pending">
                Approval in Progress
            </div>
            <div class="info-box">
                Our team is carefully reviewing your registration.<br>
                Sit tight — your learning access is coming soon 📘
            </div>

        <?php elseif ($request->status === 'Approved'): ?>
            <div class="status-icon">🚀</div>
            <div class="status-text approved">
                You’re Approved!
            </div>
            <div class="info-box">
                Welcome aboard 🎓<br>
                You can now log in and begin your courses.
            </div>

            <a href="<?= base_url('index.php/auth/login') ?>" class="btn">
                🔐 Go to Login
            </a>

        <?php elseif ($request->status === 'Rejected'): ?>
            <div class="status-icon">❌</div>
            <div class="status-text rejected">
                Registration Not Approved
            </div>
            <div class="info-box">
                Please contact the administrator for assistance.
            </div>
        <?php endif; ?>

    <?php else: ?>
        <div class="status-icon">⚠️</div>
        <div class="status-text rejected">
            No Request Found
        </div>
        <div class="info-box">
            Please submit a registration request to continue.
        </div>
    <?php endif; ?>

    <a href="<?= base_url('index.php/auth/register') ?>" class="btn btn-secondary">
        ⬅ Back to Register
    </a>

</div>

</body>
</html>
