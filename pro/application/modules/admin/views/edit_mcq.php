<h2>Edit MCQ Question</h2>

<form method="post"
      action="<?= base_url('index.php/admin/update_mcq/'.$question->question_id) ?>">

    <label>Question</label><br>
    <textarea name="question" required
              style="width:100%;"><?= htmlspecialchars($question->question) ?></textarea><br><br>

    <label>Option A</label><br>
    <input type="text" name="option_a"
           value="<?= htmlspecialchars($question->option_a) ?>" required><br><br>

    <label>Option B</label><br>
    <input type="text" name="option_b"
           value="<?= htmlspecialchars($question->option_b) ?>" required><br><br>

    <label>Option C</label><br>
    <input type="text" name="option_c"
           value="<?= htmlspecialchars($question->option_c) ?>" required><br><br>

    <label>Option D</label><br>
    <input type="text" name="option_d"
           value="<?= htmlspecialchars($question->option_d) ?>" required><br><br>

    <label>Correct Option</label><br>
    <select name="correct_option" required>
        <option value="A" <?= $question->correct_option=='A'?'selected':'' ?>>A</option>
        <option value="B" <?= $question->correct_option=='B'?'selected':'' ?>>B</option>
        <option value="C" <?= $question->correct_option=='C'?'selected':'' ?>>C</option>
        <option value="D" <?= $question->correct_option=='D'?'selected':'' ?>>D</option>
    </select><br><br>

    <button type="submit">Save Changes</button>
</form>

<br>
<a href="<?= base_url('index.php/admin/manage_mcq/'.$question->course_id) ?>">
    ⬅ Back
</a>
