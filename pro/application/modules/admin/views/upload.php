<h2>Upload File / Video</h2>

<form method="post" enctype="multipart/form-data" action="<?php echo base_url('index.php/Admin/do_upload'); ?>">

    <label>Upload Type:</label><br>
    <input type="radio" name="upload_type" value="all" required> All Users <br>
    <input type="radio" name="upload_type" value="department"> Department <br>
    <input type="radio" name="upload_type" value="individual"> Individual <br><br>

    <!-- Department dropdown -->
    <div id="department_div" style="display:none;">
        <label>Select Department:</label><br>
        <select name="department">
            <option value="">--Select Department--</option>
            <?php foreach($departments as $dept): ?>
                <option value="<?php echo $dept->department; ?>">
                    <?php echo $dept->department; ?>
                </option>
            <?php endforeach; ?>
        </select>
    </div><br>



    <!-- Individual dropdown -->
    <div id="individual_div" style="display:none;">
        <label>Select User:</label><br>
        <select name="user_id">
            <option value="">--Select User--</option>
            <?php foreach($users as $user): ?>
                <option value="<?php echo $user->user_id; ?>">
                   <?php echo $user->user_id; ?>

                </option>
            <?php endforeach; ?>
        </select>
    </div><br>

    <label>Select File / Video:</label><br>
    <input type="file" name="file" required><br><br>

    <button type="submit">Upload</button>
</form>
<a href="<?= base_url('index.php/admin/dashboard') ?>">⬅ Back</a>

<script>
// Show/hide fields based on radio selection
const radios = document.getElementsByName('upload_type');
radios.forEach(radio => {
    radio.addEventListener('change', function() {
        if(this.value === 'department'){
            document.getElementById('department_div').style.display = 'block';
            document.getElementById('individual_div').style.display = 'none';
        } else if(this.value === 'individual'){
            document.getElementById('department_div').style.display = 'none';
            document.getElementById('individual_div').style.display = 'block';
        } else {
            document.getElementById('department_div').style.display = 'none';
            document.getElementById('individual_div').style.display = 'none';
        }
    });
});
</script>