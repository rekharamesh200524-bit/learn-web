<?php
defined('BASEPATH') OR exit('No direct script access allowed');

class Course_model extends CI_Model {

    // Get lessons of a course for a user
    public function get_course_lessons($course_id, $user_id)
    {
        return $this->db
            ->select('l.*, p.status')
            ->from('course_lessons l')
            ->join(
                'lesson_progress p',
                'p.lesson_id = l.lesson_id AND p.user_id = "'.$user_id.'"',
                'left'
            )
            ->where('l.course_id', $course_id)
            ->order_by('l.day_no', 'ASC')
            ->get()
            ->result();
    }

    // Get single lesson
    public function get_lesson($lesson_id)
    {
        return $this->db
            ->where('lesson_id', $lesson_id)
            ->get('course_lessons')
            ->row();
    }

    // Complete lesson & unlock next
    public function complete_lesson($user_id, $lesson_id, $course_id, $day_no)
    {
        // Mark lesson completed
        $this->db->where([
            'user_id'   => $user_id,
            'lesson_id' => $lesson_id
        ])->update('lesson_progress', ['status' => 2]);

        // Unlock next lesson
        $next_lesson = $this->db
            ->where([
                'course_id' => $course_id,
                'day_no'    => $day_no + 1
            ])
            ->get('course_lessons')
            ->row();

        if ($next_lesson) {
            $this->db->insert('lesson_progress', [
                'user_id'   => $user_id,
                'lesson_id' => $next_lesson->lesson_id,
                'status'    => 1
            ]);
        }
    }
}
