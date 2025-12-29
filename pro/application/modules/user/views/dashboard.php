<!DOCTYPE html>
<html>
<head>
    <title>User Dashboard</title>
    <link rel="stylesheet" href="<?= base_url('assets/css/style.css') ?>">
</head>

<body>

<div class="container">

    <!-- HEADER -->
    <div style="display:flex; justify-content:space-between; align-items:center; margin:20px 0;">
        <h2>User Dashboard</h2>
        <a href="<?= base_url('index.php/auth/login') ?>" class="btn logout-btn">Logout</a>
    </div>

    <p>Welcome <b><?= htmlspecialchars($this->session->userdata('user_name')) ?></b>!</p>
    <hr>

    <!-- FLASH MESSAGE -->
    <?php if ($this->session->flashdata('error')): ?>
        <div class="error"><?= $this->session->flashdata('error') ?></div>
    <?php endif; ?>

    <?php if ($this->session->flashdata('success')): ?>
        <div class="success"><?= $this->session->flashdata('success') ?></div>
    <?php endif; ?>

    <!-- ================= FILES ================= -->
   <div class="section">
    <h3>Shared Files</h3>

    <div class="cards">
        <?php if (!empty($files)): ?>
            <?php foreach ($files as $file): ?>

                <?php
                    $file_path = base_url($file->file_path);
                    $ext = strtolower(pathinfo($file->file_path, PATHINFO_EXTENSION));

                    $image_types = ['jpg', 'jpeg', 'png', 'gif', 'webp'];
                    $video_types = ['mp4', 'webm', 'ogg'];
                ?>

                <div class="card">
                    <strong><?= $file->file_name ?></strong><br><br>

                    <!-- 📸 IMAGE -->
                    <?php if (in_array($ext, $image_types)): ?>
                        <img src="<?= $file_path ?>" 
                             style="max-width:100%; height:auto; border-radius:8px;">

                    <!-- 🎥 VIDEO -->
                    <?php elseif (in_array($ext, $video_types)): ?>
                        <video controls style="max-width:100%; border-radius:8px;">
                            <source src="<?= $file_path ?>" type="video/<?= $ext ?>">
                            Your browser does not support video.
                        </video>

                    <!-- 📄 OTHER FILES -->
                    <?php else: ?>
                        <a href="<?= $file_path ?>" download class="btn">Download</a>
                    <?php endif; ?>
                </div>

            <?php endforeach; ?>
        <?php else: ?>
            <p>No files shared with you.</p>
        <?php endif; ?>
    </div>
</div>
    <!-- ================= COURSES ================= -->
    <div class="section">
        <h3>Your Courses</h3>

        <div class="cards">

            <?php if (!empty($courses)): ?>
                <?php foreach ($courses as $course): ?>

                    <?php
                    $is_completed = in_array($course->course_id, $completed_course_ids ?? []);
                    $has_active   = !empty($in_progress);
                    $is_current   = $has_active && $in_progress->course_id == $course->course_id;
                    ?>

                    <div class="course-box">

                        <?php if ($is_completed): ?>
                            <!-- ✅ COMPLETED -->
                            <strong><?= $course->course_name ?></strong><br>
                            <span class="status-completed">✅ COMPLETED</span>

                        <?php elseif ($is_current): ?>
                            <!-- 🔄 IN PROGRESS -->
                            <a href="<?= base_url('index.php/user/course/'.$course->course_id) ?>">
                                <strong><?= $course->course_name ?></strong><br>
                                <span class="status-progress">🔄 IN PROGRESS</span>
                            </a>

                        <?php elseif ($has_active): ?>
                            <!-- 🔒 LOCKED -->
                            <strong><?= $course->course_name ?></strong><br>
                            <span class="status-locked">🔒 Finish current course to unlock</span>

                        <?php else: ?>
                            <!-- ▶ START -->
                            <a href="<?= base_url('index.php/user/start_course/'.$course->course_id) ?>"
                               onclick="return confirm('Start this course?')">
                                <strong><?= $course->course_name ?></strong><br>
                                <span class="status-start">▶ Start Course</span>
                            </a>
                        <?php endif; ?>

                        <?php if (!empty($course->description)): ?>
                            <p><?= $course->description ?></p>
                        <?php endif; ?>

                    </div>

                <?php endforeach; ?>
            <?php else: ?>
                <p>No courses available.</p>
            <?php endif; ?>

        </div>
    </div>

</div>

</body>
</html>
