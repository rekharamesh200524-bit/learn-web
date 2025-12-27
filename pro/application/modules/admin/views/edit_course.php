<!DOCTYPE html>
<html>
<head>
    <title>Edit Course</title>
    <link rel="stylesheet" href="<?= base_url('assets/css/style.css') ?>">
</head>
<body>

<div class="container">

    
    <div style="display:flex; justify-content:space-between; align-items:center; margin-bottom:20px;">
        <h2>Edit Course: <?= $course->course_name ?></h2>

    
        <div style="display:flex; gap:10px; flex-wrap:wrap;">
            <a href="<?= base_url('index.php/admin/add_lesson/'.$course->course_id) ?>" class="btn">
                ➕ Add Lesson
            </a>

            <a href="<?= base_url('index.php/admin/manage_mcq/'.$course->course_id) ?>" class="btn">
                📝 Manage MCQ
            </a>

            <a href="<?= base_url('index.php/admin/manage_courses') ?>" class="btn">
                ⬅ Back
            </a>
        </div>
    </div>

   
    <div class="section">
        <h3>Lessons</h3>

        <?php if (!empty($lessons)): ?>
            <?php foreach ($lessons as $lesson): ?>
                <div class="card">

                    <strong>
                        Day <?= $lesson->day_no ?> – <?= $lesson->lesson_title ?>
                    </strong>

                    <p><?= $lesson->lesson_content ?></p>

                    <a href="<?= base_url('index.php/admin/edit_lesson/'.$lesson->lesson_id) ?>"
                       class="btn">
                        ✏ Edit Lesson
                    </a>

                </div>
            <?php endforeach; ?>
        <?php else: ?>
            <p>No lessons added yet.</p>
        <?php endif; ?>
    </div>

</div>

</body>
</html>
