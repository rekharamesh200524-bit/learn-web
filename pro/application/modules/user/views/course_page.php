
<h3>📅 Current Day: Day <?= $current_day ?></h3>
<!-- 🔙 BACK BUTTON -->
<div style="margin-bottom:15px;">
    <a href="<?= site_url('user/dashboard') ?>">
        <button style="padding:6px 14px;">
            ⬅ Back to Dashboard
        </button>
    </a>
</div>

<?php foreach ($grouped_lessons as $day => $lessons): ?>

    <div style="border:1px solid #ccc; padding:12px; margin:12px; border-radius:6px;">

        <h4>
            <?= $day <= $current_day ? '✅' : '🔒' ?>
            Day <?= $day ?>
        </h4>

        <?php if ($day <= $current_day): ?>

            <!-- ✅ UNLOCKED LESSONS -->
            <ul>
                <?php foreach ($lessons as $lesson): ?>
                    <li>
                        <a href="<?= site_url('user/lesson/'.$lesson->lesson_id) ?>">
                            <?= $lesson->lesson_title ?>
                        </a>
                    </li>
                <?php endforeach; ?>
            </ul>

            <!-- ✅ DAY-WISE MCQ BUTTON -->
            <?php if ($day == $current_day): ?>
                <a href="<?= site_url('user/mcq/'.$course_id) ?>">
                    <button style="margin-top:10px;">
                        ▶ Start Day <?= $day ?> MCQ
                    </button>
                </a>
            <?php else: ?>
                <p style="color:green;">✔ Day <?= $day ?> completed</p>
            <?php endif; ?>

        <?php else: ?>

            <!-- 🔒 LOCKED DAY -->
            <p style="color:#999;">
                Complete Day <?= $current_day ?> MCQ to unlock
            </p>

        <?php endif; ?>

    </div>

<?php endforeach; ?>
