<h2>Add Course</h2>

<form method="post" action="<?= base_url('index.php/admin/save_course') ?>">

    <label>Course Name</label><br>
    <input type="text" name="course_name" required><br><br>

    <label>Description</label><br>
    <textarea name="description" required></textarea><br><br>

    <?php if ($this->session->userdata('role') === 'master_admin'): ?>
        <label>Department</label><br>
        <select name="department" required>
            <option value="">Select</option>
            <option value="IT">IT</option>
            <option value="HR">HR</option>
            <option value="Finance">Finance</option>
        </select>
    <?php else: ?>
        <!-- Dept head: FORCE department -->
        <input type="hidden"
               name="department"
               value="<?= $this->session->userdata('department') ?>">

        <p><b>Department:</b> <?= $this->session->userdata('department') ?></p>
    <?php endif; ?>

    <br>
    <button type="submit">Save Course</button>
</form>
