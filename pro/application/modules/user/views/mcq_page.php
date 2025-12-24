<!DOCTYPE html>
<html>
<head>
    <title>MCQ Test</title>
    <meta charset="utf-8">
    <link rel="stylesheet" href="<?= base_url('assets/css/style.css') ?>">
</head>
<body>

<div class="container">

    <!-- ================= HEADER ================= -->
    <h2>MCQ Test</h2>

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

            <!-- ================= QUESTIONS ================= -->
            <?php foreach ($questions as $q): ?>

                <div class="card">

                    <p><strong><?= htmlspecialchars($q->question) ?></strong></p>

                    <?php if (!empty($q->option_a)): ?>
                        <label>
                            <input type="radio"
                                   name="answers[<?= $q->question_id ?>]"
                                   value="A">
                            <?= htmlspecialchars($q->option_a) ?>
                        </label><br>
                    <?php endif; ?>

                    <?php if (!empty($q->option_b)): ?>
                        <label>
                            <input type="radio"
                                   name="answers[<?= $q->question_id ?>]"
                                   value="B">
                            <?= htmlspecialchars($q->option_b) ?>
                        </label><br>
                    <?php endif; ?>

                    <?php if (!empty($q->option_c)): ?>
                        <label>
                            <input type="radio"
                                   name="answers[<?= $q->question_id ?>]"
                                   value="C">
                            <?= htmlspecialchars($q->option_c) ?>
                        </label><br>
                    <?php endif; ?>

                    <?php if (!empty($q->option_d)): ?>
                        <label>
                            <input type="radio"
                                   name="answers[<?= $q->question_id ?>]"
                                   value="D">
                            <?= htmlspecialchars($q->option_d) ?>
                        </label><br>
                    <?php endif; ?>

                </div>

            <?php endforeach; ?>

            <!-- ================= SUBMIT ================= -->
            <button type="submit" class="btn" style="font-size:16px;">
                ✅ Submit MCQ
            </button>

        </form>

    <?php endif; ?>

</div>

</body>
</html>
