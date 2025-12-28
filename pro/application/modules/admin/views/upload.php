

<h2>Upload File / Video</h2>
<a href="<?= base_url('index.php/admin/dashboard') ?>">⬅ Back</a>
<?php if ($this->session->flashdata('success')): ?>
    <div style="
        background:#e6fffa;
        color:#065f46;
        padding:10px;
        margin-bottom:15px;
        border-radius:5px;
        font-weight:600;
    ">
        <?= $this->session->flashdata('success'); ?>
    </div>
<?php endif; ?>



<form method="post" enctype="multipart/form-data"
      action="<?= base_url('index.php/admin/do_upload'); ?>">

    <label>Upload Type:</label><br>

    <?php if ($allow_all): ?>
        <input type="radio" name="upload_type" value="all" required>
        All Users <br>
    <?php endif; ?>

    <input type="radio" name="upload_type" value="department" required>
    My Department <br>

    <input type="radio" name="upload_type" value="individual" required>
    Individual User <br><br>

    <!-- ================= DEPARTMENT ================= -->
    <div id="department_div" style="display:none;">
        <label>Department:</label><br>
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
    </div><br>

    <!-- ================= INDIVIDUAL ================= -->
    <div id="individual_div" style="display:none;">
        <label>Select User:</label><br>
        <select name="user_id">
            <option value="">--Select User--</option>
            <?php foreach ($users as $user): ?>
                <option value="<?= $user->user_id ?>">
                    <?= $user->user_name ?> (<?= $user->department ?>)
                </option>
            <?php endforeach; ?>
        </select>
    </div><br>

    <label>Select File / Video:</label><br>
    <input type="file" name="file" required><br><br>

    <button type="submit">Upload</button>
</form>

<script>
const radios = document.getElementsByName('upload_type');

radios.forEach(radio => {
    radio.addEventListener('change', function () {
        if (this.value === 'department') {
            document.getElementById('department_div').style.display = 'block';
            document.getElementById('individual_div').style.display = 'none';
        }
        else if (this.value === 'individual') {
            document.getElementById('department_div').style.display = 'none';
            document.getElementById('individual_div').style.display = 'block';
        }
        else {
            document.getElementById('department_div').style.display = 'none';
            document.getElementById('individual_div').style.display = 'none';
        }
    });
});
</script>
