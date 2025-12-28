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

            <?php foreach ($questions as $q): ?>

                <div class="card">

                    <p><strong><?= htmlspecialchars($q->question) ?></strong></p>

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

            <button type="submit" class="btn" style="font-size:16px;">
                ✅ Submit <?= isset($is_final_mcq) && $is_final_mcq ? 'Final' : 'Day '.$mcq_day ?> MCQ
            </button>

        </form>

    <?php endif; ?>

</div>

</body>
</html>
