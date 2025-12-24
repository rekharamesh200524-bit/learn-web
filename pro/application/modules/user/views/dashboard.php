<!DOCTYPE html>
<html>
<head>
    <title>User Dashboard</title>
    <link rel="stylesheet" href="<?= base_url('assets/css/style.css') ?>">
</head>
<body>

<div class="container">

    <h2>User Dashboard</h2>
    <p>Welcome <?= htmlspecialchars($this->session->userdata('user_name')) ?>!</p>

    <a href="<?= base_url('index.php/auth/login') ?>" class="btn">Logout</a>

    <hr>

    <!-- ================= SHARED FILES ================= -->
    <div class="section">
        <h3>Shared Files</h3>

        <?php if (!empty($files)): ?>
            <?php foreach ($files as $file): ?>

                <?php
                $file_url = base_url($file->file_path);
                $ext = strtolower(pathinfo($file->file_name, PATHINFO_EXTENSION));
                ?>

                <div class="card">

                    <strong><?= $file->file_name ?></strong>
                    <br><br>

                    <?php if (in_array($ext, ['jpg','jpeg','png','gif'])): ?>

                        <img src="<?= $file_url ?>" style="max-width:300px;">

                    <?php elseif (in_array($ext, ['mp4','webm'])): ?>

                        <video width="320" controls>
                            <source src="<?= $file_url ?>" type="video/<?= $ext ?>">
                        </video>

                    <?php elseif ($ext === 'pdf'): ?>

                        <iframe src="<?= $file_url ?>" width="100%" height="400px"></iframe>

                    <?php else: ?>

                        <a href="<?= $file_url ?>" download class="btn">Download File</a>

                    <?php endif; ?>

                </div>

            <?php endforeach; ?>
        <?php else: ?>
            <p>No files shared with you.</p>
        <?php endif; ?>
    </div>

    <!-- ================= ERROR MESSAGE ================= -->
    <?php if ($this->session->flashdata('error')): ?>
        <div class="error">
            <?= $this->session->flashdata('error'); ?>
        </div>
    <?php endif; ?>

    <!-- ================= COURSES ================= -->
    <div class="section">
        <h3>Your Courses</h3>

        <?php if (!empty($courses)): ?>

            <div class="cards">
                <?php foreach ($courses as $course): ?>

                    <?php
                    $status = '🔒 LOCKED';

                    if (in_array($course->course_id, $completed_course_ids)) {
                        $status = '✅ COMPLETED';
                    }
                    elseif ($in_progress && $in_progress->course_id == $course->course_id) {
                        $status = '🔄 IN PROGRESS';
                    }
                    ?>

                    <div class="course-box">

                        <?php if ($status === '🔄 IN PROGRESS'): ?>

                            <a href="<?= base_url('index.php/user/course/'.$course->course_id) ?>">
                                <strong><?= $course->course_name ?></strong><br>
                                <span class="status-progress"><?= $status ?></span>
                            </a>

                        <?php elseif ($status === '🔒 LOCKED'): ?>

                            <a href="javascript:void(0);"
                               onclick="unlockCourse(<?= $course->course_id ?>)">
                                <strong><?= $course->course_name ?></strong><br>
                                <span class="status-locked"><?= $status ?></span>
                            </a>

                        <?php else: ?>

                            <strong><?= $course->course_name ?></strong><br>
                            <span class="status-completed"><?= $status ?></span>

                        <?php endif; ?>

                        <?php if (!empty($course->description)): ?>
                            <p><?= $course->description ?></p>
                        <?php endif; ?>

                    </div>

                <?php endforeach; ?>
            </div>

        <?php else: ?>
            <p>No courses available.</p>
        <?php endif; ?>
    </div>

</div>

<script>
function unlockCourse(courseId) {
    if (confirm("Do you want to unlock this course?")) {
        window.location.href =
            "<?= base_url('index.php/user/start_course/') ?>" + courseId;
    }
}
</script>

</body>
</html>
