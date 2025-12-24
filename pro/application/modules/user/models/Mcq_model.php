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
}
