<?php
defined('BASEPATH') OR exit('No direct script access allowed');

class User extends MX_Controller {

    public function __construct()
    {
        parent::__construct();

        
        $this->load->database();
        $this->load->helper('url');
        $this->load->library('session');

       
        $this->load->model('user/User_model');
        $this->load->model('user/Course_model');


        
        if (!$this->session->userdata('user_id')) {
            redirect('auth/login'); 
        }
    }

    
    public function dashboard()
    {
        $user_id = $this->session->userdata('user_id');

        $data['courses'] = $this->User_model->get_courses();

        $completed = $this->User_model->get_completed_courses($user_id);
        $data['completed_course_ids'] = array_column($completed, 'course_id');

        $data['in_progress'] = $this->User_model->get_in_progress_course($user_id);
        $data['files'] = $this->User_model->get_user_files($user_id);

        $this->load->view('dashboard', $data);
    }

    
    public function course($course_id)
    {
        $user_id = $this->session->userdata('user_id');

        $data['course_id'] = $course_id;
        $data['lessons']   = $this->User_model->get_lessons($course_id);

        $this->load->view('course_page', $data);
    }

    
 public function start_course($course_id)
{
    $user_id = $this->session->userdata('user_id');

    $started = $this->User_model->start_course($user_id, $course_id);

    if (!$started) {
        $this->session->set_flashdata(
            'error',
            'Finish your current course before unlocking another one.'
        );
    }

    redirect('user/dashboard');
}


    
    public function complete_course($course_id)
    {
        $user_id = $this->session->userdata('user_id');

        $this->User_model->mark_course_completed($user_id, $course_id);
        redirect('user/dashboard');
    }


    
    public function lesson($lesson_id)
    {
        $lesson = $this->User_model->get_lesson_by_id($lesson_id);

        if (!$lesson) {
            show_404();
        }

        $data['lesson'] = $lesson;
        $this->load->view('lesson_page', $data);
    }
    public function mcq($course_id)
{
    $this->load->model('user/Mcq_model');

    $data['course_id'] = $course_id;
    $data['questions'] = $this->Mcq_model->get_questions($course_id);

    $this->load->view('mcq_page', $data);
}


public function submit_mcq($course_id)
{
    $this->load->model('user/Mcq_model');
    $user_id = $this->session->userdata('user_id');

   
    $questions = $this->Mcq_model->get_questions($course_id);
    $total_questions = count($questions);

    $answers = $this->input->post('answers');

    
    if (!is_array($answers)) {
        $this->session->set_flashdata(
            'error',
            'You must answer all questions before submitting.'
        );
        redirect('user/mcq/'.$course_id);
        return;
    }

    $attempted = count($answers);

    
    if ($attempted < $total_questions) {
        $this->session->set_flashdata(
            'error',
            'Please answer ALL questions. You missed '
            . ($total_questions - $attempted) . ' question(s).'
        );
        redirect('user/mcq/'.$course_id);
        return;
    }

   
    $correct = 0;

    foreach ($answers as $question_id => $selected_option) {
        $correct_option = $this->Mcq_model->get_correct_option($question_id);

        if ($correct_option === $selected_option) {
            $correct++;
        }
    }

    $wrong = $total_questions - $correct;
    $percentage = round(($correct / $total_questions) * 100, 2);

    // Remarks logic
    if ($percentage >= 80) {
        $remark = 'Excellent';
    } elseif ($percentage >= 60) {
        $remark = 'Pass';
    } else {
        $remark = 'Fail';
    }

   
    $this->db->insert('mcq_results', [
        'user_id'           => $user_id,
        'course_id'         => $course_id,
        'total_questions'   => $total_questions,
        'attempted'         => $attempted,
        'correct_answers'   => $correct,
        'wrong_answers'     => $wrong,
        'score'             => $percentage,
        'remark'            => $remark
    ]);
    // 
if ($remark !== 'Fail') {
    $this->db
        ->where('user_id', $user_id)
        ->where('course_id', $course_id)
        ->update('course_progress', [
            'mcq_completed' => 1,
            'completed'     => 1
        ]);
}


    redirect('user/result/'.$course_id);
}
public function result($course_id)
{
    $user_id = $this->session->userdata('user_id');

    $data['result'] = $this->db
        ->where('user_id', $user_id)
        ->where('course_id', $course_id)
        ->order_by('id', 'DESC')
        ->get('mcq_results')
        ->row();

    if (!$data['result']) {
        show_404();
    }

    $this->load->view('mcq_result', $data);
}

}
