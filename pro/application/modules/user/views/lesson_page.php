<h2>Day <?= $lesson->day_no ?>: <?= $lesson->lesson_title ?></h2>

<hr>

<p>
    <?= nl2br($lesson->lesson_content) ?>
</p>

<br>

<a href="javascript:history.back()">⬅ Back to Lessons</a>