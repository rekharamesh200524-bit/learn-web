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
        $this->load->model('user/Mcq_model');

        if (!$this->session->userdata('user_id')) {
            redirect('auth/login');
        }
    }

    /* ================= DASHBOARD ================= */

    public function dashboard()
    {
        $user_id    = $this->session->userdata('user_id');
        $department = $this->session->userdata('department');

        $data['courses'] = $this->User_model->get_courses_by_department($department);

        $completed = $this->User_model->get_completed_courses($user_id);
        $data['completed_course_ids'] = array_column($completed, 'course_id');

        $data['in_progress'] = $this->User_model->get_in_progress_course($user_id);
        $data['files']       = $this->User_model->get_user_files($user_id);

        $this->load->view('dashboard', $data);
    }

    /* ================= COURSE PAGE ================= */

    public function course($course_id)
    {
        $user_id = $this->session->userdata('user_id');

        $effective_days = $this->User_model->get_effective_course_days($user_id);
        $progress       = $this->User_model->get_course_progress($user_id, $course_id);

        $current_day = $progress ? $progress->current_day : 1;

        $data = [
            'course_id'       => $course_id,
            'grouped_lessons' => $this->User_model->get_grouped_lessons($course_id, $effective_days),
            'current_day'     => $current_day
        ];
       // echo "<pre>"; print_r($data); exit;
        $this->load->view('course_page', $data);
    }

    /* ================= START COURSE ================= */

    public function start_course($course_id)
    {
        $user_id = $this->session->userdata('user_id');

        $started = $this->User_model->start_course($user_id, $course_id);

        if (!$started) {
            $this->session->set_flashdata(
                'error',
                'Finish your current course before starting another.'
            );
        }

        redirect('user/course/'.$course_id);
    }

    /* ================= LESSON ================= */

    public function lesson($lesson_id)
    {
        $lesson = $this->User_model->get_lesson_by_id($lesson_id);

        if (!$lesson) {
            show_404();
        }

        $this->load->view('lesson_page', ['lesson' => $lesson]);
    }

    /* ================= MCQ PAGE ================= */

    public function mcq($course_id)
    {
        $user_id = $this->session->userdata('user_id');

        $progress = $this->User_model->get_course_progress($user_id, $course_id);
        if (!$progress) {
            redirect('user/course/'.$course_id);
            return;
        }

        $current_day = (int)$progress->current_day;

        $data = [
            'course_id'   => $course_id,
            'mcq_day'     => $current_day,
            'current_day' => $current_day,
            'questions'   => $this->Mcq_model->get_questions_by_day($course_id, $current_day)
        ];

        $this->load->view('mcq_page', $data);
    }

    /* ================= SUBMIT MCQ ================= */

    public function submit_mcq($course_id)
    {
        $user_id = $this->session->userdata('user_id');

       // echo "<pre>"; print_r($user_id);// exit;
        $progress = $this->User_model->get_course_progress($user_id, $course_id);
       // echo "<pre>progress"; print_r($progress);
 
        
        if (!$progress) {
            redirect('user/course/'.$course_id);
            return;
        }

        $current_day = (int)$progress->current_day;
       // echo "<pre>current_day"; print_r($current_day);
        $questions = $this->Mcq_model->get_questions_by_day($course_id, $current_day);
       //  echo "<pre>questions"; print_r($questions);
        $answers   = $this->input->post('answers');

        if (!is_array($answers) || count($answers) < count($questions)) {
            $this->session->set_flashdata('error', 'Answer all questions');
            redirect('user/mcq/'.$course_id);
            return;
        }

        $correct = 0;
        foreach ($questions as $q) {
            if (
                isset($answers[$q->question_id]) &&
                $this->Mcq_model->get_correct_option($q->question_id) === $answers[$q->question_id]
            ) {
                $correct++;
            }
        }

        $total = count($questions);
        $score = round(($correct / $total) * 100, 2);

        $remark = ($score >= 80) ? 'Excellent' : (($score >= 60) ? 'Pass' : 'Fail');
       //  echo "<pre>remark"; print_r($remark);
        $this->db->insert('mcq_results', [
            'user_id'         => $user_id,
            'course_id'       => $course_id,
            'total_questions' => $total,
            'correct_answers' => $correct,
            'score'           => $score,
            'remark'          => $remark
        ]);

        if ($remark === 'Fail') {
            $this->session->set_flashdata('error', '❌ You failed. Try again.');
            redirect('user/result/'.$course_id);
            return;
        }
        $max_days = $this->User_model->get_course_max_days($course_id);


       // echo $max_days;
//    echo "<pre>max_days"; print_r($max_days);
//    echo "<pre>current_day2"; print_r($current_day);// exit;
// ✅ COMPLETE COURSE ONLY ON LAST DAY
if ($current_day >= $max_days) {

    $this->db
        ->where('user_id', $user_id)
        ->where('course_id', $course_id)
        ->update('course_progress', [
            'lesson_completed' => 1,
            'mcq_completed'    => 1,
            'completed'        => 1,
            'current_day'      => $max_days
        ]);

} else {

    // ➕ Move to next day
    $this->db
        ->where('user_id', $user_id)
        ->where('course_id', $course_id)
        ->update('course_progress', [
            'current_day' => $current_day + 1
        ]);
}



        redirect('user/result/'.$course_id);
    }

    /* ================= RESULT ================= */

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
