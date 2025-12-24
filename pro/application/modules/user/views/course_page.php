<h2>Course ID: <?= $course_id ?></h2>

<h3>Lessons</h3>

<?php foreach ($lessons as $lesson): ?>
    <div style="border:1px solid #ccc; padding:10px; margin:10px;">
        <a href="<?= base_url('index.php/user/lesson/'.$lesson->lesson_id) ?>">
            Day <?= $lesson->day_no ?>: <?= $lesson->lesson_title ?>
        </a>
        
    </div>
    
<?php endforeach; ?>

<p><b>Complete all lessons to unlock the MCQ test.</b></p>
<hr>

<h3>Course Test</h3>

<a href="<?= base_url('index.php/user/mcq/'.$course_id) ?>">
    <button style="padding:10px 20px; font-size:16px;">
        Start MCQ Test
    </button>
</a>
