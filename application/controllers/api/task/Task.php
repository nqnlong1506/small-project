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
class Task extends \Restserver\Libraries\REST_Controller {

    function __construct()
    {
        // Construct the parent class
        parent::__construct();

        $this->methods['tasks_get']['limit'] = 500; // 500 requests per hour per user/key
        $this->methods['tasks']['limit'] = 100; // 100 requests per hour per user/key
        $this->methods['tasks_delete']['limit'] = 50; // 50 requests per hour per user/key

        $this->load->model('Task_model');
        $this->load->model('User_model');
        $this->load->helper('json_response');
    }

    public function list_tasks_get()
    {
        // verify token
        $session_id = $this->input->get_request_header('session-id');
        $session = json_decode($session_id, true);

        error_log(($session['email']));

        $expiered_at = new DateTime($session['expried_at']);
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

        $is_done = $this->input->get('isdone', true);
        error_log($is_done);
        if ($is_done) {
            $tasks = $this->Task_model->list_task($user->id, $is_done);
        } else {
            error_log("checking here");
            $tasks = $this->Task_model->list_task($user->id);
        }

        if (!$tasks) {
            $message = json_response(false, 'tasks not found.');
            $this->response($message, \Restserver\Libraries\REST_Controller::HTTP_BAD_REQUEST);

            return;
        } 
        $message = json_response(true, 'list tasks.', $tasks);
        $this->response($message, \Restserver\Libraries\REST_Controller::HTTP_OK);
    }
}
