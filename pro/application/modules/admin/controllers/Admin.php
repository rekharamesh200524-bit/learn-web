<?php
defined('BASEPATH') OR exit('No direct script access allowed');

class Admin extends MX_Controller {

    public function __construct()
    {
        parent::__construct();

        $this->load->helper('url');
        $this->load->library('session');
        $this->load->library('email'); // ✅ EMAIL LIBRARY
        $this->load->database();
        $this->load->model('Admin_model');

        // 🔐 Login check
        if (!$this->session->userdata('user_id')) {
            redirect('auth/login');
        }

        // 🔐 Allow only master admin & department head
        if (
            $this->session->userdata('role') !== 'master_admin' &&
            $this->session->userdata('role') !== 'dept_head'
        ) {
            redirect('user/dashboard');
        }
    }

    /* ================= DASHBOARD ================= */

    public function dashboard()
    {
        $role    = $this->session->userdata('role');
        $user_id = $this->session->userdata('user_id');

        /* ===== MASTER ADMIN DASHBOARD ===== */
        if ($role === 'master_admin') {

            // COUNTS
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

            // ALL USERS LIST
            $data['all_users'] = $this->db
                ->select('user_name, email, role, department, status, last_login')
                ->get('users')
                ->result();

            // ROLE CHART DATA (❌ EXCLUDE MASTER ADMIN)
            $data['role_counts'] = $this->db
                ->select('role, COUNT(*) as total')
                ->where('role !=', 'master_admin')
                ->group_by('role')
                ->get('users')
                ->result();

            // REQUESTS ONLY FOR MASTER APPROVAL (DEPT HEADS)
            $data['requests'] = $this->db
                ->where('approve_by', 'master')
                ->order_by('request_id', 'DESC')
                ->get('user_requests')
                ->result();
        }

        /* ===== DEPARTMENT HEAD DASHBOARD ===== */
        elseif ($role === 'dept_head') {

            $data['requests'] = $this->db
                ->where('approve_by', 'department')
                ->where('department_head_id', $user_id)
                ->order_by('request_id', 'DESC')
                ->get('user_requests')
                ->result();
        }

        $this->load->view('dashboard', $data);
    }

    /* ================= APPROVE REQUEST ================= */

    public function approve($id)
    {
        $request = $this->db
            ->get_where('user_requests', ['request_id' => $id])
            ->row();

        if (!$request || $request->status !== 'Pending') {
            redirect('admin/dashboard');
            return;
        }

        $role = $this->session->userdata('role');

        // 🔐 SECURITY CHECK
        if ($request->approve_by === 'master' && $role !== 'master_admin') {
            show_error('Not authorized');
        }

        if ($request->approve_by === 'department' && $role !== 'dept_head') {
            show_error('Not authorized');
        }

        /* ===== INTERN DATE LOGIC ===== */
        $intern_duration = NULL;
        $start_date = NULL;
        $end_date = NULL;

        if ($request->role === 'intern') {

            $intern_duration = $request->intern_duration;
            $start_date = date('Y-m-d');

            switch ($intern_duration) {
                case '7_days':
                    $end_date = date('Y-m-d', strtotime('+7 days'));
                    break;
                case '1_month':
                    $end_date = date('Y-m-d', strtotime('+1 month'));
                    break;
                case '2_months':
                    $end_date = date('Y-m-d', strtotime('+2 months'));
                    break;
                case '3_months':
                    $end_date = date('Y-m-d', strtotime('+3 months'));
                    break;
                case '6_months':
                    $end_date = date('Y-m-d', strtotime('+6 months'));
                    break;
            }
        }

        // ✅ INSERT USER
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

        // ✅ UPDATE REQUEST STATUS
        $this->db
            ->where('request_id', $id)
            ->update('user_requests', ['status' => 'Approved']);

        /* ================= SEND APPROVAL EMAIL ================= */

        $this->email->from('yourgmail@gmail.com', 'Your Application');
        $this->email->to($request->email);
        $this->email->subject('Registration Approved');

        $message = "
            <h3>Hello {$request->user_name},</h3>

            <p>Your registration has been
            <b style='color:green;'>approved</b>.</p>

            <p>You can now login using the link below:</p>

            <p>
                <a href='".base_url('index.php/auth/login')."'
                   style='padding:10px 15px;
                          background:#2563eb;
                          color:#ffffff;
                          text-decoration:none;
                          border-radius:5px;'>
                    Login Now
                </a>
            </p>

            <p>Regards,<br>Your Team</p>
        ";

        $this->email->message($message);

        if (!$this->email->send()) {
            log_message('error', $this->email->print_debugger());
        }

        redirect('admin/dashboard');
    }

    /* ================= REJECT REQUEST ================= */

    public function reject($id)
    {
        $this->db
            ->where('request_id', $id)
            ->update('user_requests', ['status' => 'Rejected']);

        redirect('admin/dashboard');
    }

    /* ================= FILE UPLOAD ================= */

    public function upload()
    {
        $data['users'] = $this->Admin_model->get_users();
        $data['departments'] = $this->Admin_model->get_departments();
        $this->load->view('upload', $data);
    }

    public function do_upload()
    {
        if (!isset($_FILES['file']) || $_FILES['file']['error'] != 0) {
            $this->session->set_flashdata('error', 'Upload error');
            redirect('admin/upload');
            return;
        }

        $upload_path = './uploads/';
        if (!is_dir($upload_path)) {
            mkdir($upload_path, 0777, true);
        }

        $file_name = time().'_'.$_FILES['file']['name'];
        move_uploaded_file($_FILES['file']['tmp_name'], $upload_path.$file_name);

        $this->Admin_model->upload_file([
            'file_name'   => $file_name,
            'file_path'   => 'uploads/'.$file_name,
            'upload_type' => $this->input->post('upload_type'),
            'department'  => $this->input->post('department'),
            'user_id'     => $this->input->post('user_id')
        ]);

        $this->session->set_flashdata('success', 'Upload successful');
        redirect('admin/upload');
    }

    /* ================= COURSES ================= */

    public function manage_courses()
    {
        $data['courses'] = $this->db->get('courses')->result();
        $this->load->view('manage_courses', $data);
    }

    public function add_course()
    {
        $this->load->view('add_course');
    }

    public function save_course()
    {
        $this->db->insert('courses', [
            'course_name' => $this->input->post('course_name'),
            'description' => $this->input->post('description'),
            'status' => 1
        ]);

        redirect('admin/manage_courses');
    }

    public function delete_course($course_id)
    {
        $this->db->where('course_id', $course_id)->delete('courses');
        redirect('admin/manage_courses');
    }
}
