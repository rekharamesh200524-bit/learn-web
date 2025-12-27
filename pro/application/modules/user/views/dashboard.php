<!DOCTYPE html>
<html>
<head>
    <title>User Dashboard</title>
    <link rel="stylesheet" href="<?= base_url('assets/css/style.css') ?>">
</head>

<body>

<div class="container">

   
    <div class="dashboard-header">
        <div style="display:flex; justify-content:space-between; align-items:center; margin:20px;"> <h2>User Dashboard</h2>

        <a href="<?= base_url('index.php/auth/login') ?>" class="btn logout-btn">
            Logout
        </a></div>
       
    </div>

    <p>Welcome <?= htmlspecialchars($this->session->userdata('user_name')) ?>!</p>
    <hr>

   
    <input
        type="text"
        id="globalSearch"
        placeholder="Search files and courses..."
        onkeyup="globalSearch()"
    >

   
    <p id="noResults" style="display:none; font-weight:600; color:#dc2626;">
        No results found
    </p>

    
    <?php if ($this->session->flashdata('error')): ?>
        <div class="error">
            <?= $this->session->flashdata('error'); ?>
        </div>
    <?php endif; ?>

    
    <div class="section">
        <h3>Shared Files</h3>

        <div class="cards">
            <?php if (!empty($files)): ?>
                <?php foreach ($files as $file): ?>

                    <?php
                    $file_url = base_url($file->file_path);
                    $ext = strtolower(pathinfo($file->file_name, PATHINFO_EXTENSION));
                    ?>

                    <div class="card searchable-item">
                        <strong><?= $file->file_name ?></strong>
                        <br><br>

                        <?php if (in_array($ext, ['jpg','jpeg','png','gif'])): ?>
                            <img src="<?= $file_url ?>" alt="image">

                        <?php elseif (in_array($ext, ['mp4','webm'])): ?>
                            <video controls>
                                <source src="<?= $file_url ?>" type="video/<?= $ext ?>">
                            </video>

                        <?php elseif ($ext === 'pdf'): ?>
                            <iframe src="<?= $file_url ?>" height="220"></iframe>

                        <?php else: ?>
                            <a href="<?= $file_url ?>" download class="btn">Download</a>
                        <?php endif; ?>
                    </div>

                <?php endforeach; ?>
            <?php else: ?>
                <p>No files shared with you.</p>
            <?php endif; ?>
        </div>
    </div>

   
    <div class="section">
        <h3>Your Courses</h3>

        <div class="cards">
            <?php if (!empty($courses)): ?>
                <?php foreach ($courses as $course): ?>

                    <?php
                    $status = '🔒 LOCKED';

                    if (in_array($course->course_id, $completed_course_ids)) {
                        $status = '✅ COMPLETED';
                    } elseif ($in_progress && $in_progress->course_id == $course->course_id) {
                        $status = '🔄 IN PROGRESS';
                    }
                    ?>

                    <div class="course-box searchable-item">

                        <?php if ($status === '🔄 IN PROGRESS'): ?>
                            <a href="<?= base_url('index.php/user/course/'.$course->course_id) ?>">
                                <strong><?= $course->course_name ?></strong><br>
                                <span class="status-progress"><?= $status ?></span>
                            </a>

                        <?php elseif ($status === '🔒 LOCKED'): ?>
                            <a href="javascript:void(0);" onclick="unlockCourse(<?= $course->course_id ?>)">
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
            <?php else: ?>
                <p>No courses available.</p>
            <?php endif; ?>
        </div>
    </div>

</div>

<script>
function unlockCourse(courseId) {
    if (confirm("Do you want to unlock this course?")) {
        window.location.href =
            "<?= base_url('index.php/user/start_course/') ?>" + courseId;
    }
}

function globalSearch() {
    let input = document.getElementById("globalSearch").value.toLowerCase();
    let items = document.querySelectorAll(".searchable-item");
    let noResults = document.getElementById("noResults");

    let found = false;

    items.forEach(item => {
        let text = item.innerText.toLowerCase();
        if (text.includes(input)) {
            item.style.display = "block";
            found = true;
        } else {
            item.style.display = "none";
        }
    });

    
    if (input !== "" && !found) {
        noResults.style.display = "block";
    } else {
        noResults.style.display = "none";
    }
}
</script>

</body>
</html>