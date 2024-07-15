<?php

class User_model extends CI_Model
{
    public function insert_user($user_data)
    {
        return $this->db->insert('users', $user_data);
    }

    public function check_existing_user($email)
    {
        // $query = $this->db->get('users');
        if ($email == 'nqnl-dev') {
            return true;
        }

        // $this->db->where('country' => $this->input->post('country'));
        $this->db->where('email', $email);
        $query = $this->db->get('users');

        if ($query->num_rows() > 0) {
            return true;
        }

        return false;
    }

    public function register_user($user_data)
    {
        $insert = $this->db->insert('users', $user_data);

        if (!$insert) {
            return 0;
        }

        return $this->db->insert_id();
    }

    public function login_user($email, $password)
    {
        $this->db->where('email', $email);
        $query = $this->db->get('users');
        $user = $query->row();

        if ($user && password_verify($password, $user->password)) {
            return $user;
        } else {
            return false;
        }
    }

    public function list_users()
    {
        $this->db->select('id');
        $this->db->select('email');
        $this->db->select('firstname');
        $this->db->select('lastname');
        $this->db->select('created_at');
        $query = $this->db->get('users');

        if ($query->num_rows() == 0) {
            return false;
        }

        // error_log(json_encode($query->result_array()));
        $users = $query->result_array();

        $query->free_result();

        return $users;
    }

    public function user_by_email($email) {
        $this->db->select('id');
        $this->db->select('email');
        $this->db->select('firstname');
        $this->db->select('lastname');
        $this->db->select('created_at');
        $this->db->from('users');
        $this->db->where('email', $email);
        $query = $this->db->get();

        if ($query->num_rows() == 0) {
            return false;
        }

        $user = $query->row();

        $query->free_result();

        return $user;
    }
}
