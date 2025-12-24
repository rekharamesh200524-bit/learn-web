<?php
defined('BASEPATH') OR exit('No direct script access allowed');

class Admin extends MX_Controller {

    public function __construct()
    {
        parent::__construct();

        // Load helpers, libraries, database
        $this->load->helper('url');
        $this->load->library('session');
        $this->load->database();
        $this->load->model('Admin_model');

        // 🔐 Login check
        if (!$this->session->userdata('user_id')) {
            redirect('auth/login');
        }

        // 🔐 Admin role check
        if ($this->session->userdata('role') !== 'admin') {
            redirect('user/dashboard');
        }
    }

    // ================= ADMIN DASHBOARD =================
    // Shows user approval requests
    public function dashboard()
    {
        $data['requests'] = $this->db
            ->order_by('request_id', 'DESC')
            ->get('user_requests')
            ->result();

        $this->load->view('dashboard', $data);
    }

    // ================= APPROVE USER =================
    public function approve($id)
    {
        $request = $this->db
            ->get_where('user_requests', ['request_id' => $id])
            ->row();

        if (!$request || $request->status !== 'Pending') {
            redirect('admin/dashboard');
            return;
        }

        // Insert approved user into users table
        $this->db->insert('users', [
            'user_name'  => $request->user_name,
            'email'      => $request->email,
            'mobile'     => $request->mobile,
            'department' => $request->department,
            'password'   => $request->password, // already md5
            'role'       => 'user',
            'status'     => 1
        ]);

        // Update request status
        $this->db
            ->where('request_id', $id)
            ->update('user_requests', ['status' => 'Approved']);

        redirect('admin/dashboard');
    }

    // ================= REJECT USER =================
    public function reject($id)
    {
        $this->db
            ->where('request_id', $id)
            ->update('user_requests', ['status' => 'Rejected']);

        redirect('admin/dashboard');
    }

    // ================= FILE UPLOAD PAGE =================
   public function upload()
{
    $data['users'] = $this->Admin_model->get_users();
     $data['departments'] = $this->Admin_model->get_departments();
    $this->load->view('upload', $data);
}



    // ================= HANDLE FILE UPLOAD =================
    public function do_upload()
    {
        if (!isset($_FILES['file']) || $_FILES['file']['error'] != 0) {
            $this->session->set_flashdata(
                'error',
                'No file selected or upload error'
            );
            redirect('admin/upload');
            return;
        }

        // Upload directory
        $upload_path = './uploads/';
        if (!is_dir($upload_path)) {
            mkdir($upload_path, 0777, true);
        }

        $file_name = time() . '_' . $_FILES['file']['name'];
        $file_tmp  = $_FILES['file']['tmp_name'];

        if (!move_uploaded_file($file_tmp, $upload_path . $file_name)) {
            $this->session->set_flashdata(
                'error',
                'File upload failed'
            );
            redirect('admin/upload');
            return;
        }

        // Prepare DB data
        $data = [
            'file_name'   => $file_name,
            'file_path'   => 'uploads/' . $file_name,
            'upload_type' => $this->input->post('upload_type'),
            'department'  => $this->input->post('department') ?: NULL,
            'user_id'     => $this->input->post('user_id') ?: NULL
        ];

        // Save upload record
        $this->Admin_model->upload_file($data);

        $this->session->set_flashdata(
            'success',
            'File uploaded and saved successfully!'
        );

        redirect('admin/upload');
    }
    public function manage_courses()
{
    $data['courses'] = $this->db->get('courses')->result();
    $this->load->view('manage_courses', $data);
}
public function edit_course($course_id)
{
    $data['course'] = $this->db
        ->where('course_id', $course_id)
        ->get('courses')
        ->row();

    $data['lessons'] = $this->db
        ->where('course_id', $course_id)
        ->order_by('day_no')
        ->get('course_lessons')
        ->result();

    $this->load->view('edit_course', $data);
}
public function edit_lesson($lesson_id)
{
    // Get lesson
    $data['lesson'] = $this->db
        ->where('lesson_id', $lesson_id)
        ->get('course_lessons')
        ->row();

    if (!$data['lesson']) {
        show_404();
    }

    $this->load->view('edit_lesson', $data);
}
public function update_lesson($lesson_id)
{
    $lesson = $this->db
        ->where('lesson_id', $lesson_id)
        ->get('course_lessons')
        ->row();

    if (!$lesson) {
        show_404();
    }

    $this->db
        ->where('lesson_id', $lesson_id)
        ->update('course_lessons', [
            'lesson_title'   => $this->input->post('lesson_title'),
            'lesson_content' => $this->input->post('lesson_content')
        ]);

    redirect('admin/edit_course/'.$lesson->course_id);
}
public function add_lesson($course_id)
{
    $data['course_id'] = $course_id;

    // Get next day number automatically
    $last_lesson = $this->db
        ->where('course_id', $course_id)
        ->order_by('day_no', 'DESC')
        ->get('course_lessons')
        ->row();

    $data['next_day_no'] = $last_lesson ? $last_lesson->day_no + 1 : 1;

    $this->load->view('add_lesson', $data);
}
public function save_lesson($course_id)
{
    $this->db->insert('course_lessons', [
        'course_id'      => $course_id,
        'day_no'         => $this->input->post('day_no'),
        'lesson_title'   => $this->input->post('lesson_title'),
        'lesson_content' => $this->input->post('lesson_content'),
        'status'         => 1
    ]);

    redirect('admin/edit_course/'.$course_id);
}
public function manage_mcq($course_id)
{
    $data['course_id'] = $course_id;

    $data['questions'] = $this->db
        ->where('course_id', $course_id)
        ->order_by('question_id', 'ASC')
        ->get('mcq_questions')
        ->result();

    $this->load->view('manage_mcq', $data);
}
public function edit_mcq($question_id)
{
    $data['question'] = $this->db
        ->where('question_id', $question_id)
        ->get('mcq_questions')
        ->row();

    if (!$data['question']) {
        show_404();
    }

    $this->load->view('edit_mcq', $data);
}
public function update_mcq($question_id)
{
    $question = $this->db
        ->where('question_id', $question_id)
        ->get('mcq_questions')
        ->row();

    if (!$question) {
        show_404();
    }

    $this->db
        ->where('question_id', $question_id)
        ->update('mcq_questions', [
            'question'        => $this->input->post('question'),
            'option_a'        => $this->input->post('option_a'),
            'option_b'        => $this->input->post('option_b'),
            'option_c'        => $this->input->post('option_c'),
            'option_d'        => $this->input->post('option_d'),
            'correct_option'  => $this->input->post('correct_option')
        ]);

    redirect('admin/manage_mcq/'.$question->course_id);
}
public function add_mcq($course_id)
{
    $data['course_id'] = $course_id;
    $this->load->view('add_mcq', $data);
}
public function save_mcq($course_id)
{
    $this->db->insert('mcq_questions', [
        'course_id'      => $course_id,
        'question'       => $this->input->post('question'),
        'option_a'       => $this->input->post('option_a'),
        'option_b'       => $this->input->post('option_b'),
        'option_c'       => $this->input->post('option_c'),
        'option_d'       => $this->input->post('option_d'),
        'correct_option' => $this->input->post('correct_option'),
        'status'         => 1
    ]);

    redirect('admin/manage_mcq/'.$course_id);
}
public function delete_mcq($question_id)
{
    $question = $this->db
        ->where('question_id', $question_id)
        ->get('mcq_questions')
        ->row();

    if (!$question) {
        show_404();
    }

    $course_id = $question->course_id;

    $this->db
        ->where('question_id', $question_id)
        ->delete('mcq_questions');

    redirect('admin/manage_mcq/'.$course_id);
}





}
