<!DOCTYPE html>
<html>
<head>
    <title>MCQ Test</title>
    <meta charset="utf-8">
    <link rel="stylesheet" href="<?= base_url('assets/css/style.css') ?>">
</head>
<body>

<div class="container">

    <h2>
        <?= isset($is_final_mcq) && $is_final_mcq
            ? 'Final Course MCQ'
            : 'Day '.$mcq_day.' MCQ Test'; ?>
    </h2>

    <!-- 🔒 FUTURE DAY BLOCK -->
    <?php if ($mcq_day > $current_day): ?>
        <div class="error">
            🔒 This MCQ is locked.<br>
            Complete Day <?= $current_day ?> MCQ to unlock.
        </div>
        <?php exit; ?>
    <?php endif; ?>

    <!-- ❌ ERROR MESSAGE -->
    <?php if ($this->session->flashdata('error')): ?>
        <div class="error">
            <?= $this->session->flashdata('error'); ?>
        </div>
    <?php endif; ?>

    <?php if (empty($questions)): ?>

        <div class="section">
            <p>No MCQ questions found.</p>
        </div>

    <?php else: ?>

     <form method="post"
      action="<?= base_url('index.php/user/submit_mcq/'.$course_id) ?>">

    <?php foreach ($questions as $index => $q): ?>

        <div class="card question" style="display:none;">

            <p>
                <strong>
                    Q<?= $index + 1 ?>. <?= htmlspecialchars($q->question) ?>
                </strong>
            </p>

            <?php foreach (['A','B','C','D'] as $opt): ?>
                <?php $field = 'option_'.strtolower($opt); ?>
                <?php if (!empty($q->$field)): ?>
                    <label>
                        <input type="radio"
                               name="answers[<?= $q->question_id ?>]"
                               value="<?= $opt ?>">
                        <?= htmlspecialchars($q->$field) ?>
                    </label><br>
                <?php endif; ?>
            <?php endforeach; ?>

        </div>

    <?php endforeach; ?>

    <!-- NAVIGATION BUTTONS -->
    <div style="display:flex; justify-content:space-between; margin-top:20px;">

        <button type="button" class="btn" id="prevBtn" onclick="prevQuestion()">
            ⬅ Previous
        </button>

        <button type="button" class="btn" id="nextBtn" onclick="nextQuestion()">
            Next ➡
        </button>

    </div>

    <!-- FINAL SUBMIT -->
    <div style="margin-top:20px; text-align:center;">
        <button type="submit"
                class="btn"
                id="submitBtn"
                style="display:none; font-size:16px;">
            ✅ Submit <?= isset($is_final_mcq) && $is_final_mcq ? 'Final' : 'Day '.$mcq_day ?> MCQ
        </button>
    </div>

</form>


    <?php endif; ?>

</div>
<script>
    let currentQuestion = 0;
    const questions = document.querySelectorAll('.question');
    const prevBtn = document.getElementById('prevBtn');
    const nextBtn = document.getElementById('nextBtn');
    const submitBtn = document.getElementById('submitBtn');

    function showQuestion(index) {
        questions.forEach((q, i) => {
            q.style.display = i === index ? 'block' : 'none';
        });

        prevBtn.style.display = index === 0 ? 'none' : 'inline-block';
        nextBtn.style.display = index === questions.length - 1 ? 'none' : 'inline-block';
        submitBtn.style.display = index === questions.length - 1 ? 'inline-block' : 'none';
    }

    function nextQuestion() {
        if (currentQuestion < questions.length - 1) {
            currentQuestion++;
            showQuestion(currentQuestion);
        }
    }

    function prevQuestion() {
        if (currentQuestion > 0) {
            currentQuestion--;
            showQuestion(currentQuestion);
        }
    }

    // Show first question
    showQuestion(currentQuestion);
</script>

</body>
</html>