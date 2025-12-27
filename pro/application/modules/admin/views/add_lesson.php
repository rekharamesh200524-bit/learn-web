<!DOCTYPE html>
<html>
<head>
    <title>Add New Lesson</title>
    <link rel="stylesheet" href="<?= base_url('assets/css/style.css') ?>">
</head>
<body>

<div class="container">

    
    <div style="display:flex; justify-content:space-between; align-items:center; margin-bottom:20px;">
        <h2>Add New Lesson</h2>

        <a href="<?= base_url('index.php/admin/edit_course/'.$course_id) ?>" class="btn">
            ⬅ Back to Course
        </a>
    </div>

    <div class="section">

        <form method="post"
              action="<?= base_url('index.php/admin/save_lesson/'.$course_id) ?>">

            <label>Day Number</label>
            <input type="number"
                   name="day_no"
                   value="<?= $next_day_no ?>"
                   required>

            <label>Lesson Title</label>
            <input type="text"
                   name="lesson_title"
                   required>

            <label>Lesson Content</label>
            <textarea name="lesson_content"
                      rows="10"
                      required></textarea>

            <button type="submit" class="btn">
                ➕ Add Lesson
            </button>

        </form>

    </div>

</div>

</body>
</html>
