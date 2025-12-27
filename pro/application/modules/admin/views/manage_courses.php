<h2>Manage Courses</h2>
<a href="<?= base_url('index.php/admin/dashboard') ?>">⬅ Back</a>


<a href="<?= base_url('index.php/admin/add_course') ?>" class="btn">
    ➕ Add New Course
</a>

<br><br>



<br><br>


<table border="1" cellpadding="10">
    <tr>
        <th>Course Name</th>
        <th>Description</th>
        <th>Action</th>
    </tr>

    <?php foreach ($courses as $course): ?>
        <tr>
            <td><?= $course->course_name ?></td>
            <td><?= $course->description ?></td>
           <td>
    <a href="<?= base_url('index.php/admin/edit_course/'.$course->course_id) ?>">
        Edit Content
    </a>
    |
    <a href="<?= base_url('index.php/admin/delete_course/'.$course->course_id) ?>"
       onclick="return confirm('Are you sure you want to delete this course?')"
       style="color:red;">
        Delete
    </a>
</td>

        </tr>
    <?php endforeach; ?>
</table>

<br>

