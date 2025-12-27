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
                ->select('user_name, email, role, department, status, last_login')
                ->get('users')
                ->result();

            // ❌ Exclude master admin from charts
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

        /* ===== INTERN DATE LOGIC ===== */
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

    /* ================= REJECT REQUEST ================= */

    public function reject($id)
    {
        $this->db
            ->where('request_id', $id)
            ->update('user_requests', ['status' => 'Rejected']);

        redirect('admin/dashboard');
    }

    /* ================= COURSES ================= */

    public function manage_courses()
{
    $role = $this->session->userdata('role');

    if ($role === 'master_admin') {
        // Master admin → see all
        $data['courses'] = $this->db->get('courses')->result();
    } else {
        // Dept head → see only their department
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

    /* 🔥 THIS IS THE FIXED METHOD 🔥 */
    public function save_course()
    {
        $role = $this->session->userdata('role');

        // FORCE department
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
            'department'  => $department, // ✅ ALWAYS SAVED
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
    $data['users'] = $this->Admin_model->get_users();
    $data['departments'] = $this->Admin_model->get_departments();
    $this->load->view('upload', $data);
}

}
