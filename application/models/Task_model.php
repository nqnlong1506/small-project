<?php

class Task_model extends CI_Model
{

    public function list_task($user_id, $is_done = -1)
    {
        $this->db->where('user_id', $user_id);
        if ($is_done >= 0)
        {
            $this->db->where('is_done', $is_done);
        }
        // $this->db->limit(5,5);
        $query = $this->db->get('tasks');
        if ($query->num_rows() == 0) {
            return false;
        }

        // error_log(json_encode($query->result_array()));
        $tasks = $query->result_array();

        $query->free_result();

        return $tasks;
    }

    public function task_by_id($task_id)
    {
        $query = $this->db->where('id', $task_id)->get('tasks');
        if ($query->num_rows() == 0)
        {
            return false;
        }

        $task = $query->row();

        $query->free_result();

        return $task;
    }

    public function get_task($task_id, $user_id)
    {
        $this->db->where('id', $task_id);
        $this->db->where('user_id', $user_id);
        $query = $this->db->get('tasks');
        if ($query->num_rows() == 0)
        {
            return false;
        }

        $task = $query->row();

        $query->free_result();

        return $task;
    }

    public function insert_task($task_data)
    {
        $insert = $this->db->insert('tasks', $task_data);

        if (!$insert) {
            return 0;
        }

        return $this->db->insert_id();
    }

    public function update_task($task_data)
    {
        $data = [
            'title' => $task_data['title'],
            'is_done'=> $task_data['is_done'],
        ];
        $this->db->where('id', $task_data['id']);
        $this->db->update('tasks', $data);
        // error_log('checking'. json_encode($this->db)); 

        // Optionally check if the update was successful
        if ($this->db->affected_rows() > 0) {
            return true;
        } else {
            return false;
        }
    }

    public function delete_task($task_id)
    {
        $this->db->where('id', $task_id)->delete('tasks');

        if ($this->db->affected_rows() > 0)
        {
            return true;
        } else 
        {
            return false;
        }
    }
}
