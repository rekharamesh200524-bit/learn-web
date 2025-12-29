<?php
defined('BASEPATH') OR exit('No direct script access allowed');

class Mcq_model extends CI_Model {

    public function get_questions($course_id)
    {
        return $this->db
            ->where('course_id', $course_id)
            ->where('status', 1)
            ->get('mcq_questions')
            ->result();
    }

    public function get_correct_option($question_id)
    {
        return $this->db
            ->select('correct_option')
            ->where('question_id', $question_id)
            ->get('mcq_questions')
            ->row()
            ->correct_option;
    }

    public function save_result($data)
    {
        return $this->db->insert('mcq_results', $data);
    }
    public function get_questions_by_department($course_id, $department)
{
    return $this->db
        ->where('course_id', $course_id)
        ->where('department', $department)
        ->get('mcq_questions')
        ->result();
}
public function get_questions_by_day($course_id, $day_no)
{
    return $this->db
        ->where('course_id', $course_id)
        ->where('day_no', $day_no)
        ->get('mcq_questions')
        ->result();
}

public function get_total_mcq_days($course_id)
{
    return $this->db
        ->select('MAX(day_no) as total_days')
        ->where('course_id', $course_id)
        ->get('mcq_questions')
        ->row()
        ->total_days ?? 0;
}



}
