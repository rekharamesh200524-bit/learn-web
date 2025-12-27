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
        // ❌ Only one active course allowed
        $active = $this->db
            ->where('user_id', $user_id)
            ->where('completed', 0)
            ->get('course_progress')
            ->row();

        if ($active) {
            return false;
        }

        return $this->db->insert('course_progress', [
            'user_id'           => $user_id,
            'course_id'         => $course_id,
            'lesson_completed'  => 0,
            'mcq_completed'     => 0,
            'completed'         => 0
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
       LESSONS (COURSE BASED)
    ============================= */

    // ✅ Lessons belong ONLY to course
    public function get_lessons($course_id)
    {
        return $this->db
            ->where('course_id', $course_id)
            ->order_by('lesson_id', 'ASC')
            ->get('course_lessons')
            ->result();
    }

    public function get_lesson_by_id($lesson_id)
    {
        return $this->db
            ->where('lesson_id', $lesson_id)
            ->get('course_lessons')
            ->row();
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
}
