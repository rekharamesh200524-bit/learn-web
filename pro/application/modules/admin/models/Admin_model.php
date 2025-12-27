<?php
defined('BASEPATH') OR exit('No direct script access allowed');

class Admin_model extends CI_Model {

    

    public function get_pending_requests()
    {
        return $this->db
            ->where('status', 'Pending')
            ->order_by('request_id', 'DESC')
            ->get('user_requests')
            ->result();
    }

    public function get_request_by_id($id)
    {
        return $this->db
            ->where('request_id', $id)
            ->get('user_requests')
            ->row();
    }

    public function update_request_status($id, $status)
    {
        return $this->db
            ->where('request_id', $id)
            ->update('user_requests', ['status' => $status]);
    }

    

    public function insert_user($data)
    {
        return $this->db->insert('users', $data);
    }

    public function get_users()
{
    return $this->db
        ->where('status', 1)
        ->get('users')
        ->result();
}

    

    

   

    public function upload_file($data)
    {
        return $this->db->insert('uploads', $data);
    }

    public function get_uploads()
    {
        return $this->db
            ->order_by('id', 'DESC')
            ->get('uploads')
            ->result();
    }
    public function get_departments()
{
    return $this->db
        ->select('department')
        ->where('department IS NOT NULL', null, false)
        ->group_by('department')
        ->get('users')
        ->result();
}

}
