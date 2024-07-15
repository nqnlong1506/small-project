<?php

class Task_model extends CI_Model
{

    public function list_task($user_id, $is_done = null)
    {
        $this->db->where('user_id', $user_id);
        if (!is_null($is_done))
        {
            $this->db->where('is_done', $is_done);
        }
        $query = $this->db->get('tasks');
        if ($query->num_rows() == 0) {
            return false;
        }

        // error_log(json_encode($query->result_array()));
        $tasks = $query->result_array();

        $query->free_result();

        return $tasks;
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
        $this->db->where('id', $task_data['id']);
        $this->db->update('tasks', $task_data);

        // Optionally check if the update was successful
        if ($this->db->affected_rows() > 0) {
            return true;
        } else {
            return false;
        }
    }
}
