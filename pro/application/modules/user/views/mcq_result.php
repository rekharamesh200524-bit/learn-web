<!DOCTYPE html>
<html>
<head>
    <title>MCQ Result</title>
    <style>
        body { font-family: Arial; }
        .result-box {
            width: 400px;
            padding: 20px;
            border: 1px solid #ccc;
        }
        .pass { color: green; }
        .fail { color: red; }
    </style>
</head>
<body>

<h2>MCQ Result</h2>

<div class="result-box">

<p><b>Total Questions:</b> <?= $result->total_questions ?></p>
<p><b>Attempted Questions:</b> <?= $result->attempted ?></p>
<p><b>Correct Answers:</b> <?= $result->correct_answers ?></p>
<p><b>Wrong Answers:</b> <?= $result->wrong_answers ?></p>

<hr>

<p><b>Score:</b> <?= $result->score ?>%</p>

<p>
<b>Remark:</b>
<span class="<?= ($result->remark == 'Fail') ? 'fail' : 'pass' ?>">
    <?= $result->remark ?>
</span>
</p>

</div>

<br>

<a href="<?= base_url('index.php/user/dashboard') ?>">
    ⬅ Back to Dashboard
</a>

</body>
</html>
