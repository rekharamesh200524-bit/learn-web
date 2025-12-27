<?php
defined('BASEPATH') OR exit('No direct script access allowed');

class Auth extends MX_Controller {

    /* ================= LOGIN ================= */
   
    public function login()
    {
        $this->load->view('login');
    }

    public function login_check()
    {
        $email    = $this->input->post('email');
        $password = md5($this->input->post('password'));

        $user = $this->db->get_where('users', [
            'email'    => $email,
            'password' => $password,
            'status'   => 1
        ])->row();

        if (!$user) {
            $data['error'] = 'Invalid login or not approved';
            $this->load->view('login', $data);
            return;
        }

        // ✅ INTERN EXPIRY CHECK (ONLY IF COLUMN EXISTS)
        if ($user->role === 'intern' && !empty($user->intern_end_date)) {
            if (date('Y-m-d') > $user->intern_end_date) {
                $data['error'] = 'Your internship period has expired.';
                $this->load->view('login', $data);
                return;
            }
        }

        // ✅ SAVE SESSION
        $this->session->set_userdata([
            'user_id'   => $user->user_id,
            'user_name' => $user->user_name,
            'role'      => $user->role,
            'department'=> $user->department,
            'logged_in' => TRUE
        ]);
        $this->db->where('user_id', $user->user_id)
         ->update('users', ['last_login' => date('Y-m-d H:i:s')]);


        // ✅ REDIRECT BASED ON ROLE
        if ($user->role === 'master_admin' || $user->role === 'dept_head') {
            redirect('admin/dashboard');
        } else {
            redirect('user/dashboard');
        }
    }

    /* ================= REGISTER ================= */

    public function register()
    {
        $this->load->view('register');
    }

    public function register_submit()
    {
        $this->load->library('form_validation');

        $this->form_validation->set_rules('user_name', 'User Name', 'required|min_length[3]');
        $this->form_validation->set_rules(
            'email',
            'Email',
            'required|valid_email|is_unique[user_requests.email]|is_unique[users.email]'
        );
        $this->form_validation->set_rules(
            'mobile',
            'Mobile Number',
            'required|regex_match[/^[6-9][0-9]{9}$/]|is_unique[user_requests.mobile]|is_unique[users.mobile]'
        );
        $this->form_validation->set_rules('department', 'Department', 'required');
        $this->form_validation->set_rules('password', 'Password', 'required|min_length[6]');
        $this->form_validation->set_rules('role', 'Role', 'required');

        if ($this->input->post('role') === 'intern') {
            $this->form_validation->set_rules('intern_duration', 'Intern Duration', 'required');
        }

        if ($this->form_validation->run() == FALSE) {
            $this->load->view('register');
            return;
        }

        $role       = $this->input->post('role');
        $department = $this->input->post('department');

        // 🔍 FIND DEPARTMENT HEAD
        $dept_head = $this->db
            ->where('role', 'dept_head')
            ->where('department', $department)
            ->get('users')
            ->row();

        $data = [
            'user_name'  => $this->input->post('user_name'),
            'email'      => $this->input->post('email'),
            'mobile'     => $this->input->post('mobile'),
            'department' => $department,
            'password'   => md5($this->input->post('password')),
            'role'       => $role,
            'intern_duration' => ($role === 'intern')
                                  ? $this->input->post('intern_duration')
                                  : NULL,
            'status'     => 'Pending',
            'created_at' => date('Y-m-d H:i:s'),

            // ✅ CORE APPROVAL LOGIC
            'approve_by' => ($role === 'dept_head') ? 'master' : 'department',
            'department_head_id' => ($role === 'dept_head')
                                    ? NULL
                                    : ($dept_head ? $dept_head->user_id : NULL)
        ];

        $this->db->insert('user_requests', $data);

        $this->session->set_userdata('registered_email', $data['email']);

        redirect('auth/waiting');
    }

    /* ================= WAITING PAGE ================= */

    public function waiting()
    {
        $email = $this->session->userdata('registered_email');

        if (!$email) {
            $this->load->view('waiting', ['request' => null]);
            return;
        }

        $data['request'] = $this->db
            ->where('email', $email)
            ->order_by('request_id', 'DESC')
            ->get('user_requests')
            ->row();

        $this->load->view('waiting', $data);
    }

    /* ================= FORGOT PASSWORD ================= */

    public function forgot_password()
    {
        $this->load->view('forgot_password');
    }

    public function send_reset_link()
    {
        $email = $this->input->post('email');

        $user = $this->db->get_where('users', ['email' => $email])->row();
        if (!$user) {
            $this->load->view('forgot_password', ['error' => 'Email not found']);
            return;
        }

        $token  = bin2hex(random_bytes(32));
        $expiry = date('Y-m-d H:i:s', strtotime('+30 minutes'));

        $this->db->where('email', $email)->update('users', [
            'reset_token'  => $token,
            'token_expiry' => $expiry
        ]);

        $this->load->library('email');
        $this->email->from('yourgmail@gmail.com', 'Your App');
        $this->email->to($email);
        $this->email->subject('Password Reset');

        $link = base_url("index.php/auth/reset_password/$token");
        $this->email->message("<p>Reset link:</p><a href='$link'>$link</a>");

        $this->email->send();

        echo "Password reset link sent.";
    }

    public function reset_password($token)
    {
        $user = $this->db
            ->where('reset_token', $token)
            ->where('token_expiry >=', date('Y-m-d H:i:s'))
            ->get('users')
            ->row();

        if (!$user) show_error('Invalid or expired reset link');

        $this->load->view('reset_password', ['token' => $token]);
    }

    public function update_password()
    {
        $token    = $this->input->post('token');
        $password = md5($this->input->post('password'));

        $this->db->where('reset_token', $token)->update('users', [
            'password'     => $password,
            'reset_token'  => NULL,
            'token_expiry' => NULL
        ]);

        redirect('auth/login');
    }
}
