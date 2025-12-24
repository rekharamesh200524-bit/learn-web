<h2>Edit Lesson</h2>

<form method="post"
      action="<?= base_url('index.php/admin/update_lesson/'.$lesson->lesson_id) ?>">

    <label>Lesson Title</label><br>
    <input type="text"
           name="lesson_title"
           value="<?= htmlspecialchars($lesson->lesson_title) ?>"
           required
           style="width:100%;"><br><br>

    <label>Lesson Content</label><br>
    <textarea name="lesson_content"
              rows="10"
              required
              style="width:100%;"><?= htmlspecialchars($lesson->lesson_content) ?></textarea><br><br>

    <button type="submit">Save Changes</button>
</form>

<br>
<a href="<?= base_url('index.php/admin/edit_course/'.$lesson->course_id) ?>">
    ⬅ Back to Course
</a>
