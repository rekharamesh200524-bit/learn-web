<h2>Manage MCQ Questions</h2>

<a href="<?= base_url('index.php/admin/add_mcq/'.$course_id) ?>">
    ➕ Add New Question
</a>

<br><br>

<table border="1" cellpadding="10">
    <tr>
        <th>Question</th>
        <th>Correct Option</th>
        <th>Action</th>
    </tr>

    <?php if (!empty($questions)): ?>
        <?php foreach ($questions as $q): ?>
            <tr>
                <td><?= $q->question ?></td>
                <td><?= $q->correct_option ?></td>
                <td>
    <a href="<?= base_url('index.php/admin/edit_mcq/'.$q->question_id) ?>">
        Edit
    </a>
    |
    <a href="<?= base_url('index.php/admin/delete_mcq/'.$q->question_id) ?>"
       onclick="return confirm('Are you sure you want to delete this question?')">
        Delete
    </a>
</td>

            </tr>
        <?php endforeach; ?>
    <?php else: ?>
        <tr>
            <td colspan="3">No MCQ questions found</td>
        </tr>
    <?php endif; ?>
</table>

<br>
<a href="<?= base_url('index.php/admin/edit_course/'.$course_id) ?>">
    ⬅ Back to Course
</a>
