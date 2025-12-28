<!DOCTYPE html>
<html>
<head>
    <title>Edit Course</title>
    <link rel="stylesheet" href="<?= base_url('assets/css/style.css') ?>">
</head>
<body>

<div class="container">

<?php
    // ===============================
    // ROLE & PERMISSION CHECK
    // ===============================
    $role        = $this->session->userdata('role');
    $admin_dept  = $this->session->userdata('department');
    $course_dept = $course->department;

    $can_edit = (
        $role === 'master_admin' ||
        ($role === 'dept_head' && $admin_dept === $course_dept)
    );
?>

    <!-- HEADER -->
    <div style="display:flex; justify-content:space-between; align-items:center; margin-bottom:20px;">
        <h2>Edit Course: <?= htmlspecialchars($course->course_name) ?></h2>

        <div style="display:flex; gap:10px; flex-wrap:wrap;">

            <?php if ($can_edit): ?>
                <a href="<?= base_url('index.php/admin/add_lesson/'.$course->course_id) ?>" class="btn">
                    ➕ Add Lesson
                </a>

                <a href="<?= base_url('index.php/admin/manage_mcq/'.$course->course_id) ?>" class="btn">
                    📝 Manage MCQ
                </a>
            <?php endif; ?>

            <a href="<?= base_url('index.php/admin/manage_courses') ?>" class="btn">
                ⬅ Back
            </a>
        </div>
    </div>

    <!-- FLASH MESSAGE -->
    <?php if ($this->session->flashdata('success')): ?>
        <div class="success">
            <?= $this->session->flashdata('success') ?>
        </div>
    <?php endif; ?>

    <?php if ($this->session->flashdata('error')): ?>
        <div class="error">
            <?= $this->session->flashdata('error') ?>
        </div>
    <?php endif; ?>

    <!-- LESSON LIST -->
    <div class="section">
        <h3>Lessons</h3>

        <?php if (!empty($lessons)): ?>
            <?php foreach ($lessons as $lesson): ?>
                <div class="card">

                    <strong>
                        Day <?= (int)$lesson->day_no ?> – <?= htmlspecialchars($lesson->lesson_title) ?>
                    </strong>

                    <p><?= nl2br(htmlspecialchars($lesson->lesson_content)) ?></p>

                    <?php if ($can_edit): ?>
                        <a href="<?= base_url('index.php/admin/edit_lesson/'.$lesson->lesson_id) ?>"
                           class="btn">
                            ✏ Edit Lesson
                        </a>
                    <?php endif; ?>

                </div>
            <?php endforeach; ?>
        <?php else: ?>
            <p>No lessons added yet.</p>
        <?php endif; ?>
    </div>

</div>

</body>
</html>
