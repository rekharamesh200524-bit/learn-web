<h2>Edit MCQ</h2>

<form method="post" action="<?= site_url('admin/update_mcq/'.$mcq->question_id) ?>">

    <input type="hidden" name="course_id" value="<?= $mcq->course_id ?>">

    <label>Day No</label>
    <input type="number" name="day_no" value="<?= $mcq->day_no ?>" required>

    <label>Question</label>
    <textarea name="question" required><?= $mcq->question ?></textarea>

    <label>Option A</label>
    <input type="text" name="option_a" value="<?= $mcq->option_a ?>">

    <label>Option B</label>
    <input type="text" name="option_b" value="<?= $mcq->option_b ?>">

    <label>Option C</label>
    <input type="text" name="option_c" value="<?= $mcq->option_c ?>">

    <label>Option D</label>
    <input type="text" name="option_d" value="<?= $mcq->option_d ?>">

    <label>Correct Option</label>
    <select name="correct_option">
        <option value="A" <?= $mcq->correct_option=='A'?'selected':'' ?>>A</option>
        <option value="B" <?= $mcq->correct_option=='B'?'selected':'' ?>>B</option>
        <option value="C" <?= $mcq->correct_option=='C'?'selected':'' ?>>C</option>
        <option value="D" <?= $mcq->correct_option=='D'?'selected':'' ?>>D</option>
    </select>

    <input type="hidden" name="mcq_type" value="<?= $mcq->mcq_type ?>">

    <button type="submit">Update MCQ</button>

</form>
