<?php

defined('BASEPATH') OR exit('No direct script access allowed');

require APPPATH . '/libraries/REST_Controller.php';

/**
 * This is an example of a few basic user interaction methods you could use
 * all done with a hardcoded array
 *
 * @package         CodeIgniter
 * @subpackage      Rest Server
 * @category        Controller
 * @author          Phil Sturgeon, Chris Kacerguis
 * @license         MIT
 * @link            https://github.com/chriskacerguis/codeigniter-restserver
 */
class Update extends \Restserver\Libraries\REST_Controller {

    function __construct()
    {
        // Construct the parent class
        parent::__construct();

        $this->methods['tasks_get']['limit'] = 500; // 500 requests per hour per user/key
        $this->methods['tasks_post']['limit'] = 100; // 100 requests per hour per user/key
        $this->methods['tasks_delete']['limit'] = 50; // 50 requests per hour per user/key

        $this->load->model('Task_model');
        $this->load->model('User_model');
        $this->load->helper('json_response');
    }

    public function update_task_post()
    {
        // verify token
        $session_id = $this->input->get_request_header('session-id');
        if ($session_id == '') {
            $message = json_response(false, 'token required.');
            $this->response($message, \Restserver\Libraries\REST_Controller::HTTP_NETWORK_AUTHENTICATION_REQUIRED);

            return;
        }
        $session = json_decode($session_id, true);

        error_log(($session['email']));

        $expiered_at = new DateTime($session['expired_at']);
        $now = new DateTime(date('Y-m-d H:i:s', time()));

        if ($expiered_at < $now) {
            $message = json_response(false, 'token expired.');
            $this->response($message, \Restserver\Libraries\REST_Controller::HTTP_NETWORK_AUTHENTICATION_REQUIRED);

            return;
        } 
        $user = $this->User_model->user_by_email($session['email']);

        if (!$user) {
            $message = json_response(false, 'user not found.');
            $this->response($message, \Restserver\Libraries\REST_Controller::HTTP_NETWORK_AUTHENTICATION_REQUIRED);

            return;
        }
        // end verify token
        
        // validate task
        $id = $this->post('id');
        if ($id == null || $id == '') 
        {
            $message = json_response(false, 'id of task required.');
            $this->response($message, \Restserver\Libraries\REST_Controller::HTTP_BAD_REQUEST);
            
            return;
        }
        // end validate task

        // update task
        $task_data = [
            'id' => $id,
        ];

        if ($this->post('title') && $this->post('title') != '')
        {
            $task_data['title'] = $this->post('title');
        }

        if ($this->post('isDone')) 
        {
            $task_data['is_done'] = $this->post('isDone');
        } else 
        {
            $task_data['is_done'] = 0;
        }

        $updateTask = $this->Task_model->update_task($task_data);

        if (!$updateTask) 
        {
            $message = json_response(false, 'update task failed.');
            $this->response($message, \Restserver\Libraries\REST_Controller::HTTP_INTERNAL_SERVER_ERROR);

            return;
        }

        $message = json_response(true, 'update task successfully.', $id);

        $this->response($message, \Restserver\Libraries\REST_Controller::HTTP_OK);
    }
}
