<?php
defined('BASEPATH') OR exit('No direct script access allowed');

class Admin extends MX_Controller {

    public function __construct()
    {
        parent::__construct();

        $this->load->helper('url');
        $this->load->library('session');
        $this->load->library('email');
        $this->load->database();
        $this->load->model('Admin_model');

        //  Login check
        if (!$this->session->userdata('user_id')) {
            redirect('auth/login');
        }

        if (
            $this->session->userdata('role') !== 'master_admin' &&
            $this->session->userdata('role') !== 'dept_head'
        ) {
            redirect('user/dashboard');
        }
    }

    /*  DASHBOARD */

    public function dashboard()
    {
        $role    = $this->session->userdata('role');
        $user_id = $this->session->userdata('user_id');

        /*  MASTER ADMIN DASHBOARD  */
        if ($role === 'master_admin') {

            $data['total_users'] = $this->db->count_all('users');

            $data['active_users'] = $this->db
                ->where('status', 1)
                ->count_all_results('users');

            $data['inactive_users'] = $this->db
                ->where('status', 0)
                ->count_all_results('users');

            $data['today_logins'] = $this->db
                ->where('DATE(last_login)', date('Y-m-d'))
                ->count_all_results('users');

           $data['all_users'] = $this->db
    ->select('user_name, email, role, department, status, last_login, intern_duration')
    ->where('role !=', 'master_admin')   // 🔥 EXCLUDE MASTER ADMIN
    ->get('users')
    ->result();

            
            $data['role_counts'] = $this->db
                ->select('role, COUNT(*) as total')
                ->where('role !=', 'master_admin')
                ->group_by('role')
                ->get('users')
                ->result();

            $data['requests'] = $this->db
                ->where('approve_by', 'master')
                ->order_by('request_id', 'DESC')
                ->get('user_requests')
                ->result();
        }

        /*  DEPARTMENT HEAD DASHBOARD  */
      elseif ($role === 'dept_head') {

    $department = $this->session->userdata('department');

    // Approval requests
    $data['requests'] = $this->db
        ->where('approve_by', 'department')
        ->where('department_head_id', $user_id)
        ->order_by('request_id', 'DESC')
        ->get('user_requests')
        ->result();

    // 🔥 USER PROGRESS DATA
    $data['dept_users_progress'] =
        $this->Admin_model->get_department_users_progress($department);
}


        $this->load->view('dashboard', $data);
    }

    

    public function approve($id)
    {
        $request = $this->db
            ->get_where('user_requests', ['request_id' => $id])
            ->row();

        if (!$request || $request->status !== 'Pending') {
            redirect('admin/dashboard');
            return;
        }
        

        /*  INTERN DATE LOGIC */
        $intern_duration = NULL;
        $start_date = NULL;
        $end_date = NULL;

        if ($request->role === 'intern') {
            $intern_duration = $request->intern_duration;
            $start_date = date('Y-m-d');

            switch ($intern_duration) {
                case '7_days':   $end_date = date('Y-m-d', strtotime('+7 days')); break;
                case '1_month':  $end_date = date('Y-m-d', strtotime('+1 month')); break;
                case '2_months': $end_date = date('Y-m-d', strtotime('+2 months')); break;
                case '3_months': $end_date = date('Y-m-d', strtotime('+3 months')); break;
                case '6_months': $end_date = date('Y-m-d', strtotime('+6 months')); break;
            }
        }

        // INSERT USER
        $this->db->insert('users', [
            'user_name'         => $request->user_name,
            'email'             => $request->email,
            'mobile'            => $request->mobile,
            'department'        => $request->department,
            'password'          => $request->password,
            'role'              => $request->role,
            'intern_duration'   => $intern_duration,
            'intern_start_date' => $start_date,
            'intern_end_date'   => $end_date,
            'status'            => 1
        ]);

        // UPDATE REQUEST
        $this->db
            ->where('request_id', $id)
            ->update('user_requests', ['status' => 'Approved']);

        // SEND EMAIL
        $this->email->from('yourgmail@gmail.com', 'Your Application');
        $this->email->to($request->email);
        $this->email->subject('Registration Approved');

        $this->email->message("
            <h3>Hello {$request->user_name},</h3>
            <p>Your registration has been <b style='color:green;'>approved</b>.</p>
            <p>
                <a href='".base_url('index.php/auth/login')."'>Login Now</a>
            </p>
        ");

        $this->email->send();

        redirect('admin/dashboard');
    }

    

    public function reject($id)
    {
        $this->db
            ->where('request_id', $id)
            ->update('user_requests', ['status' => 'Rejected']);

        redirect('admin/dashboard');
    }

    /*  COURSES  */

    public function manage_courses()
{
    $role = $this->session->userdata('role');

    if ($role === 'master_admin') {
       
        $data['courses'] = $this->db->get('courses')->result();
    } else {
        
        $department = $this->session->userdata('department');

        $data['courses'] = $this->db
            ->where('department', $department)
            ->get('courses')
            ->result();
    }

    $this->load->view('manage_courses', $data);
}


    public function add_course()
    {
        $this->load->view('add_course');
    }

    
    public function save_course()
    {
        $role = $this->session->userdata('role');

       
        if ($role === 'dept_head') {
            $department = $this->session->userdata('department');
        } else {
            $department = $this->input->post('department');
        }

        if (empty($department)) {
            show_error('Department not set. Please login again.');
        }

        $this->db->insert('courses', [
            'course_name' => $this->input->post('course_name'),
            'description' => $this->input->post('description'),
            'department'  => $department, 
            'status'      => 1
        ]);

        redirect('admin/manage_courses');
    }

    public function delete_course($course_id)
    {
        $this->db->where('course_id', $course_id)->delete('courses');
        redirect('admin/manage_courses');
    }
   public function upload()
{
    $role = $this->session->userdata('role');
    $department = $this->session->userdata('department');

    if ($role === 'master_admin') {

        
        $data['allow_all'] = true;
        $data['departments'] = $this->Admin_model->get_departments();
        $data['users'] = $this->Admin_model->get_users();

    } else {

        
        $data['allow_all'] = false;
        $data['departments'] = [$department]; // ONLY OWN DEPARTMENT
        $data['users'] = $this->Admin_model->get_users_by_department($department);
    }

    $this->load->view('upload', $data);
}
public function edit_course($course_id)
{
    $data['course'] = $this->db
        ->where('course_id', $course_id)
        ->get('courses')
        ->row();

    if (!$data['course']) {
        show_404();
    }

    $data['lessons'] = $this->db
        ->where('course_id', $course_id)
        ->order_by('lesson_id', 'ASC')
        ->get('course_lessons')
        ->result();

    $this->load->view('edit_course', $data);
}
public function edit_lesson($lesson_id)
{
    $data['lesson'] = $this->db
        ->where('lesson_id', $lesson_id)
        ->get('course_lessons')
        ->row();

    if (!$data['lesson']) {
        show_404();
    }

    $this->load->view('edit_lesson', $data);
}
public function add_lesson($course_id)
{
    $data['course_id'] = $course_id;

    
    $last = $this->db
        ->where('course_id', $course_id)
        ->order_by('day_no', 'DESC')
        ->get('course_lessons')
        ->row();

    $data['next_day_no'] = $last ? $last->day_no + 1 : 1;

    $this->load->view('add_lesson', $data);
}
public function manage_mcq($course_id)
{
    $data['course_id'] = $course_id;

    $data['questions'] = $this->db
        ->where('course_id', $course_id)
        ->get('mcq_questions')
        ->result();

    $this->load->view('manage_mcq', $data);
}

public function save_lesson($course_id)
{
    if (!$course_id) {
        show_404();
    }

    $data = [
        'course_id'       => $course_id,
        'day_no'          => $this->input->post('day_no'),
        'lesson_title'    => $this->input->post('lesson_title'),
        'lesson_content'  => $this->input->post('lesson_content'),
        'department'      => $this->input->post('department'),
        'status'          => 1
    ];

    $this->db->insert('course_lessons', $data);

    $this->session->set_flashdata('success', 'Lesson added successfully');

    redirect('admin/manage_courses');
}
public function update_lesson($lesson_id)
{
    if (!$lesson_id) {
        show_404();
    }

    $data = [
        'day_no'         => $this->input->post('day_no'),
        'lesson_title'   => $this->input->post('lesson_title'),
        'lesson_content' => $this->input->post('lesson_content'),
        'department'     => $this->input->post('department'),
        'status'         => $this->input->post('status')
    ];

    $this->db
        ->where('lesson_id', $lesson_id)
        ->update('course_lessons', $data);

    $this->session->set_flashdata('success', 'Lesson updated successfully');

    redirect('admin/manage_courses');
}
public function add_mcq($course_id)
{
    $data['course_id'] = $course_id;
    $this->load->view('admin/add_mcq', $data);
}
public function save_mcq()
{
    
   $course = $this->db
    ->where('course_id', $this->input->post('course_id'))
    ->get('courses')
    ->row();

$data = [
    'course_id'      => (int)$this->input->post('course_id'),
    'day_no'         => (int)$this->input->post('day_no'),
    'question'       => trim($this->input->post('question')),
    'option_a'       => trim($this->input->post('option_a')),
    'option_b'       => trim($this->input->post('option_b')),
    'option_c'       => trim($this->input->post('option_c')),
    'option_d'       => trim($this->input->post('option_d')),
    'correct_option' => strtoupper($this->input->post('correct_option')),
    'mcq_type'       => strtolower($this->input->post('mcq_type')),
    'status'         => 1,
    'department'     => $course->department   // ✅ ALWAYS CORRECT
];


$insert = $this->db->insert('mcq_questions', $data);

if (!$insert) {
    echo "<pre>";
    print_r($this->db->error());
    exit;
}


    
    redirect('admin/manage_mcq/'.$data['course_id']);
}

public function do_upload()
{
    $config['upload_path']   = './uploads/';
    $config['allowed_types'] = '*';
    $config['max_size']      = 20480; 

    $this->load->library('upload', $config);

    if (!$this->upload->do_upload('file')) {
        $this->session->set_flashdata(
            'error',
            $this->upload->display_errors()
        );
        redirect('admin/upload');
        return;
    }

    $fileData = $this->upload->data();

    $upload_type = $this->input->post('upload_type');

    $data = [
        'file_name'   => $fileData['file_name'],
        'file_path'   => 'uploads/' . $fileData['file_name'],
        'upload_type' => $upload_type,
        'department'  => $this->input->post('department'),
        'user_id'     => $this->input->post('user_id'),
        'created_at'  => date('Y-m-d H:i:s')
    ];

    $this->db->insert('uploads', $data);

   if ($this->upload->do_upload('file')) {

    

    $this->session->set_flashdata(
        'success',
        '✅ File uploaded successfully'
    );

    redirect('admin/upload');
}

}
public function delete_mcq($id)
{
    if (!$id) {
        show_404();
    }

    $mcq = $this->db
        ->where('question_id', $id)
        ->get('mcq_questions')
        ->row();

    if (!$mcq) {
        show_404();
    }

    $this->db
        ->where('question_id', $id)
        ->delete('mcq_questions');

    $this->session->set_flashdata(
        'success',
        'MCQ deleted successfully'
    );

    redirect('admin/manage_mcq/' . $mcq->course_id);
}

public function edit_mcq($question_id)
{
    $data['mcq'] = $this->db
        ->where('question_id', $question_id)
        ->get('mcq_questions')
        ->row();

    if (!$data['mcq']) {
        show_404();
    }

    $this->load->view('admin/edit_mcq', $data);
}
public function update_mcq($question_id)
{
    $data = [
        'day_no'         => (int)$this->input->post('day_no'),
        'question'       => trim($this->input->post('question')),
        'option_a'       => trim($this->input->post('option_a')),
        'option_b'       => trim($this->input->post('option_b')),
        'option_c'       => trim($this->input->post('option_c')),
        'option_d'       => trim($this->input->post('option_d')),
        'correct_option' => strtoupper($this->input->post('correct_option')),
        'mcq_type'       => strtolower($this->input->post('mcq_type')),
        'status'         => 1
    ];

    $this->db
        ->where('question_id', $question_id)
        ->update('mcq_questions', $data);

    redirect('admin/manage_mcq/'.$this->input->post('course_id'));
}
/* ================= ADD DEPARTMENT HEAD ================= */

public function add_dept_head()
{
    // Only master admin allowed
    if ($this->session->userdata('role') !== 'master_admin') {
        show_error('Unauthorized access');
    }

    $this->load->view('add_dept_head');
}

public function save_dept_head()
{
    if ($this->session->userdata('role') !== 'master_admin') {
        show_error('Unauthorized access');
    }

    $this->load->library('form_validation');

    $this->form_validation->set_rules('user_name', 'Name', 'required|min_length[3]');
    $this->form_validation->set_rules(
        'email',
        'Email',
        'required|valid_email|is_unique[users.email]'
    );
    $this->form_validation->set_rules('department', 'Department', 'required');
    $this->form_validation->set_rules('password', 'Password', 'required|min_length[6]');

    if ($this->form_validation->run() === FALSE) {
        $this->load->view('add_dept_head');
        return;
    }

    // ================= INSERT DEPT HEAD =================
    $data = [
        'user_name'  => $this->input->post('user_name'),
        'email'      => $this->input->post('email'),
        'department' => $this->input->post('department'),
        'password'   => md5($this->input->post('password')),
        'role'       => 'dept_head',
        'status'     => 1
    ];

    $this->db->insert('users', $data);

    // ================= SEND EMAIL =================
    $this->email->from('yourgmail@gmail.com', 'Your Application');
    $this->email->to($data['email']);
    $this->email->subject('Department Head Access Approved');

    $message = "
        <h3>Hello {$data['user_name']},</h3>

        <p>You have been <b style='color:green;'>approved as Department Head</b>
        for the <b>{$data['department']}</b> department.</p>

        <p>You are now eligible to login using the link below:</p>

        <p>
            <a href='".base_url('index.php/auth/login')."'
               style='padding:10px 15px;
                      background:#2563eb;
                      color:#ffffff;
                      text-decoration:none;
                      border-radius:6px;'>
                Login Now
            </a>
        </p>

        <p><b>Email:</b> {$data['email']}</p>

        <p>Regards,<br>
        System Admin</p>
    ";

    $this->email->message($message);

    if (!$this->email->send()) {
        log_message('error', $this->email->print_debugger());
    }

    // ================= SUCCESS MESSAGE =================
    $this->session->set_flashdata(
        'success',
        'Department Head added and email sent successfully.'
    );

    redirect('admin/dashboard');
}



}
