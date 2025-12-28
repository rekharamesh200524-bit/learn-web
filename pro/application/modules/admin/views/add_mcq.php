<!DOCTYPE html>
<html>
<head>
    <title>Add MCQ</title>
</head>
<body>

<h2>Add MCQ Question</h2>

<form method="post" action="<?= site_url('admin/save_mcq') ?>">

    <!-- REQUIRED -->
    <input type="hidden" name="course_id" value="<?= $course_id ?>">

    <!-- REQUIRED -->
    <label>Day No</label>
    <select name="day_no" required>
        <option value="">Select Day</option>
        <?php for ($i = 1; $i <= 10; $i++): ?>
            <option value="<?= $i ?>">Day <?= $i ?></option>
        <?php endfor; ?>
    </select>

    <label>Question</label>
    <textarea name="question" required></textarea>

    <label>Option A</label>
    <input type="text" name="option_a">

    <label>Option B</label>
    <input type="text" name="option_b">

    <label>Option C</label>
    <input type="text" name="option_c">

    <label>Option D</label>
    <input type="text" name="option_d">

    <label>Correct Option</label>
    <select name="correct_option" required>
        <option value="A">A</option>
        <option value="B">B</option>
        <option value="C">C</option>
        <option value="D">D</option>
    </select>

    <!-- REQUIRED -->
    <input type="hidden" name="mcq_type" value="daily">

    <button type="submit">Save MCQ</button>

</form>


</body>
</html>
