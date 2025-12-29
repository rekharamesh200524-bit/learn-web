<!DOCTYPE html>
<html>
<head>
    <title>Upload File / Video</title>
    <link rel="stylesheet" href="<?= base_url('assets/css/style.css') ?>">

    <style>
        .upload-card {
            max-width: 520px;
            margin: 30px auto;
            background: #ffffff;
            padding: 25px;
            border-radius: 10px;
            box-shadow: 0 10px 25px rgba(0,0,0,0.08);
        }

        .upload-card h2 {
            text-align: center;
            margin-bottom: 20px;
        }

        .upload-card label {
            font-weight: 600;
            display: block;
            margin-bottom: 8px;
        }

        .upload-card input[type="radio"] {
            margin-right: 6px;
        }

        .upload-card select,
        .upload-card input[type="file"] {
            width: 100%;
            padding: 8px;
            border-radius: 6px;
            border: 1px solid #cbd5e1;
        }

        .upload-card button {
            width: 100%;
            background: #2563eb;
            color: white;
            padding: 10px;
            border: none;
            border-radius: 6px;
            font-weight: 600;
            cursor: pointer;
        }

        .upload-card button:hover {
            background: #1e40af;
        }

        .back-link {
            display: inline-block;
            margin-bottom: 15px;
            text-decoration: none;
            font-weight: 600;
        }

        .success-box {
            background:#e6fffa;
            color:#065f46;
            padding:10px;
            margin-bottom:15px;
            border-radius:6px;
            font-weight:600;
            text-align:center;
        }
        .radio-group {
    margin-top: 10px;
}

.radio-item {
    display: block;
    margin-bottom: 8px;
    font-size: 15px;
}

.radio-item input {
    vertical-align: middle;
    margin-right: 6px;
}

    </style>
</head>
<body>

<div class="upload-card">

    <a href="<?= base_url('index.php/admin/dashboard') ?>" class="back-link">
        ⬅ Back to Dashboard
    </a>

    <h2>Upload File / Video</h2>

    <?php if ($this->session->flashdata('success')): ?>
        <div class="success-box">
            <?= $this->session->flashdata('success'); ?>
        </div>
    <?php endif; ?>

    <form method="post" enctype="multipart/form-data"
          action="<?= base_url('index.php/admin/do_upload'); ?>">
<label>Upload Type</label>

<div class="radio-group">

    <?php if ($allow_all): ?>
        <label class="radio-item">
            <input type="radio" name="upload_type" value="all" required>
            All Users
        </label>
    <?php endif; ?>

    <label class="radio-item">
        <input type="radio" name="upload_type" value="department" required>
        My Department
    </label>

    <label class="radio-item">
        <input type="radio" name="upload_type" value="individual" required>
        Individual User
    </label>

</div>

        <br>

        <!-- ================= DEPARTMENT ================= -->
        <div id="department_div" style="display:none;">
            <label>Department</label>
            <select name="department">
                <?php foreach ($departments as $dept): ?>
                    <?php if (is_object($dept)): ?>
                        <option value="<?= $dept->department ?>">
                            <?= $dept->department ?>
                        </option>
                    <?php else: ?>
                        <option value="<?= $dept ?>">
                            <?= $dept ?>
                        </option>
                    <?php endif; ?>
                <?php endforeach; ?>
            </select>
        </div>

        <!-- ================= INDIVIDUAL ================= -->
        <div id="individual_div" style="display:none;">
            <label>Select User</label>
            <select name="user_id">
                <option value="">-- Select User --</option>
                <?php foreach ($users as $user): ?>
                    <option value="<?= $user->user_id ?>">
                        <?= $user->user_name ?> (<?= $user->department ?>)
                    </option>
                <?php endforeach; ?>
            </select>
        </div>

        <br>

        <label>Select File / Video</label>
        <input type="file" name="file" required>

        <br><br>

        <button type="submit">Upload</button>
    </form>
</div>

<script>
const radios = document.getElementsByName('upload_type');

radios.forEach(radio => {
    radio.addEventListener('change', function () {
        document.getElementById('department_div').style.display =
            this.value === 'department' ? 'block' : 'none';

        document.getElementById('individual_div').style.display =
            this.value === 'individual' ? 'block' : 'none';
    });
});
</script>

</body>
</html>
