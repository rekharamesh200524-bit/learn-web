<?php
defined('BASEPATH') OR exit('No direct script access allowed');

class Auth extends MX_Controller {

    // Default login page
    public function login()
    {
        $this->load->view('login');
    }

    // Login check
    public function login_check()
    {
        $email    = $this->input->post('email');
        $password = md5($this->input->post('password'));

        $user = $this->db->get_where('users', [
            'email'    => $email,
            'password' => $password,
            'status' => 1

        ])->row();

        if ($user) {
            $this->session->set_userdata([
                'user_id'   => $user->user_id,
                'user_name' => $user->user_name,
                'role'      => $user->role,
                'logged_in' => TRUE
            ]);

            if ($user->role == 'admin') {
                redirect('admin/dashboard');
            } else {
                redirect('user/dashboard');
            }
        } else {
            $data['error'] = 'Invalid login or not approved';
            $this->load->view('login', $data);
        }
    }

    // Register page
    public function register()
    {
        $this->load->view('register');
    }

   public function register_submit()
{
    // Validation rules
    $this->form_validation->set_rules(
        'user_name',
        'User Name',
        'required|min_length[3]'
    );

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

    $this->form_validation->set_rules(
        'department',
        'Department',
        'required'
    );

    $this->form_validation->set_rules(
        'password',
        'Password',
        'required|min_length[6]'
    );

    // Run validation
    if ($this->form_validation->run() == FALSE) {

        // ❌ Validation failed → show register page with errors
        $this->load->view('register');

    } else {

        // ✅ Validation passed → insert data
        $data = [
            'user_name'  => $this->input->post('user_name'),
            'email'      => $this->input->post('email'),
            'mobile'     => $this->input->post('mobile'),
            'department' => $this->input->post('department'),
            'password'   => md5($this->input->post('password')),
            'status'     => 'Pending'
        ];

        $this->db->insert('user_requests', $data);

        redirect('auth/waiting');
    }
}


    // ✅ THIS WAS MISSING
    public function waiting()
    {
        $this->load->view('waiting');
    }
}
