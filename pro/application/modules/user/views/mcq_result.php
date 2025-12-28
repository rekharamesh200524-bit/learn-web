<h2>📊 MCQ Result</h2>

<p><b>Total Questions:</b> <?= $result->total_questions ?></p>
<p><b>Attempted:</b> <?= $result->attempted ?></p>
<p><b>Correct:</b> <?= $result->correct_answers ?></p>
<p><b>Wrong:</b> <?= $result->wrong_answers ?></p>
<p><b>Score:</b> <?= $result->score ?>%</p>

<h3>
Result:
<?php if ($result->remark === 'Fail'): ?>
    <span style="color:red;">❌ FAIL</span>
<?php else: ?>
    <span style="color:green;">✅ <?= $result->remark ?></span>
<?php endif; ?>
</h3>

<a href="<?= site_url('user/course/'.$result->course_id) ?>">
    <button>⬅ Back to Course</button>
</a>
