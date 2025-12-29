<?php
defined('BASEPATH') OR exit('No direct script access allowed');

class User_model extends CI_Model {

    /* =============================
       COURSES (DEPARTMENT BASED)
    ============================= */

    // ✅ Only show courses of logged-in user's department
    public function get_courses_by_department($department)
    {
        return $this->db
            ->where('department', $department)
            ->where('status', 1)
            ->order_by('course_order', 'ASC')
            ->get('courses')
            ->result();
    }

    /* =============================
       COURSE PROGRESS
    ============================= */

    public function get_completed_courses($user_id)
    {
        return $this->db
            ->select('course_id')
            ->where('user_id', $user_id)
            ->where('completed', 1)
            ->get('course_progress')
            ->result_array();
    }

 public function get_in_progress_course($user_id)
{
    return $this->db
        ->where('user_id', $user_id)
        ->where('completed', 0)
        ->get('course_progress')
        ->row();
}

public function start_course($user_id, $course_id)
{
    // 🔒 Check if ANOTHER course is still active
    $active = $this->db
        ->where('user_id', $user_id)
        ->where('completed', 0)
        ->where('course_id !=', $course_id) // ✅ IMPORTANT
        ->get('course_progress')
        ->row();

    if ($active) {
        return false; // another course still running
    }

    // 🔁 If this course already exists and is completed → allow reopen
    $existing = $this->db
        ->where('user_id', $user_id)
        ->where('course_id', $course_id)
        ->get('course_progress')
        ->row();

    if ($existing) {
        return true;
    }

    // ✅ Start new course
    return $this->db->insert('course_progress', [
        'user_id'        => $user_id,
        'course_id'      => $course_id,
        'current_day'    => 1,
        'completed'      => 0,
        'mcq_completed'  => 0
    ]);
}



    public function mark_course_completed($user_id, $course_id)
    {
        return $this->db
            ->where('user_id', $user_id)
            ->where('course_id', $course_id)
            ->update('course_progress', [
                'mcq_completed' => 1,
                'completed'     => 1
            ]);
    }

 
  


    /* =============================
       FILES (UPLOAD SYSTEM)
    ============================= */

    public function get_user_files($user_id)
    {
        $user = $this->db
            ->where('user_id', $user_id)
            ->get('users')
            ->row();

        if (!$user) {
            return [];
        }

        return $this->db
            ->group_start()

                // All users
                ->where('upload_type', 'all')

                // Department specific
                ->or_group_start()
                    ->where('upload_type', 'department')
                    ->where('department', $user->department)
                ->group_end()

                // Individual
                ->or_group_start()
                    ->where('upload_type', 'individual')
                    ->where('user_id', $user_id)
                ->group_end()

            ->group_end()
            ->order_by('created_at', 'DESC')
            ->get('uploads')
            ->result();
    }
   public function get_course_progress($user_id, $course_id)
{
    return $this->db
        ->where('user_id', $user_id)
        ->where('course_id', $course_id)
        ->get('course_progress')
        ->row();
}



   public function get_current_day($user_id, $course_id)
{
    $progress = $this->db
        ->where('user_id', $user_id)
        ->where('course_id', $course_id)
        ->get('course_progress')
        ->row();

    if (!$progress) {
        return 1; // course not started yet
    }

    return max(1, (int)$progress->current_day);
}
public function get_lesson_by_id($lesson_id)
{
    return $this->db
        ->where('lesson_id', $lesson_id)
        ->get('course_lessons')
        ->row();
}
public function get_effective_course_days($user_id)
{
    $user = $this->db->where('user_id', $user_id)->get('users')->row();

    if (!$user || !$user->intern_duration) {
        return 10;
    }

    return ($user->intern_duration === '7_days') ? 7 : 10;
}

public function get_grouped_lessons($course_id, $effective_days)
{
    $lessons = $this->db
        ->where('course_id', $course_id)
        ->order_by('lesson_id', 'ASC')
        ->get('course_lessons')
        ->result();

    $total = count($lessons);
    if ($total === 0) return [];

    $per_day = ceil($total / $effective_days);

    $grouped = [];
    $day = 1;
    $count = 0;

    foreach ($lessons as $lesson) {
        $grouped[$day][] = $lesson;
        $count++;

        if ($count >= $per_day && $day < $effective_days) {
            $day++;
            $count = 0;
        }
    }

    return $grouped;
}

   


}