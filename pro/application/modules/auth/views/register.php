<!DOCTYPE html>
<html>
<head>
    <title>Register</title>

   
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

    <option value="employee" <?= set_select('role','employee') ?>>
        Employee
    </option>

    <option value="intern" <?= set_select('role','intern') ?>>
        Intern
    </option>

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


      
        <button type="submit" style="width:100%;">Register</button>

        <p style="text-align:center; margin-top:15px;">
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
