<!DOCTYPE html>
<html>
<head>
    <title>Add Course</title>
    <link rel="stylesheet" href="<?= base_url('assets/css/style.css') ?>">
</head>
<body>

<div class="center-page">

    <h2>Add New Course</h2>

    <form method="post" action="<?= base_url('index.php/admin/save_course') ?>">

        <input type="text"
               name="course_name"
               placeholder="Course Name"
               required>

        <textarea name="description"
                  placeholder="Course Description"
                  required></textarea>

        <button type="submit" style="width:100%;">
            Save Course
        </button>

    </form>

    <p style="text-align:center; margin-top:15px;">
        <a href="<?= base_url('index.php/admin/manage_courses') ?>">⬅ Back</a>
    </p>

</div>

</body>
</html>
