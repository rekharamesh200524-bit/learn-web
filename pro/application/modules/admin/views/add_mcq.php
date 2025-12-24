<h2>Add New MCQ Question</h2>

<form method="post"
      action="<?= base_url('index.php/admin/save_mcq/'.$course_id) ?>">

    <label>Question</label><br>
    <textarea name="question" required style="width:100%;" rows="3"></textarea><br><br>

    <label>Option A</label><br>
    <input type="text" name="option_a" required><br><br>

    <label>Option B</label><br>
    <input type="text" name="option_b" required><br><br>

    <label>Option C</label><br>
    <input type="text" name="option_c" required><br><br>

    <label>Option D</label><br>
    <input type="text" name="option_d" required><br><br>

    <label>Correct Option</label><br>
    <select name="correct_option" required>
        <option value="">Select</option>
        <option value="A">A</option>
        <option value="B">B</option>
        <option value="C">C</option>
        <option value="D">D</option>
    </select><br><br>

    <button type="submit">Save Question</button>
</form>

<br>
<a href="<?= base_url('index.php/admin/manage_mcq/'.$course_id) ?>">
    ⬅ Back to MCQ List
</a>
