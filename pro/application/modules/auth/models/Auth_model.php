<?php
class Auth_model extends CI_Model {

    public function check_login($email, $password) {
        return $this->db
            ->where('email', $email)
            ->where('password', md5($password))
            ->where('status', 'active')
            ->get('users')
            ->row_array();
    }

    public function save_request($data) {
        $this->db->insert('user_requests', [
            'user_name'  => $data['user_name'],
            'email'      => $data['email'],
            'password'   => md5($data['password']),
            'department' => $data['department']
        ]);
    }
}
