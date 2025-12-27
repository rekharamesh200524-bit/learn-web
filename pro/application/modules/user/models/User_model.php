<?php
defined('BASEPATH') OR exit('No direct script access allowed');

class User_model extends CI_Model {

   

    
    public function get_courses()
    {
        return $this->db
            ->where('status', 1)
            ->order_by('course_order', 'ASC')
            ->get('courses')
            ->result();
    }

   
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
    
    $active = $this->db
        ->where('user_id', $user_id)
        ->where('completed', 0)
        ->get('course_progress')
        ->row();

    if ($active) {
        return false; 
    }

    
    return $this->db->insert('course_progress', [
        'user_id' => $user_id,
        'course_id' => $course_id,
        'lesson_completed' => 0,
        'mcq_completed' => 0,
        'completed' => 0
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

  
    public function mark_lessons_completed($user_id, $course_id)
    {
        return $this->db
            ->where('user_id', $user_id)
            ->where('course_id', $course_id)
            ->update('course_progress', [
                'lesson_completed' => 1
            ]);
    }
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

            
            ->where('upload_type', 'all')

            
            ->or_group_start()
                ->where('upload_type', 'department')
                ->where('department', $user->department)
            ->group_end()

           
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
