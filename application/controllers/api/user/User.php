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
class User extends \Restserver\Libraries\REST_Controller {

    function __construct()
    {
        // Construct the parent class
        parent::__construct();

        $this->methods['users_get']['limit'] = 500; // 500 requests per hour per user/key
        $this->methods['users_post']['limit'] = 100; // 100 requests per hour per user/key
        $this->methods['users_delete']['limit'] = 50; // 50 requests per hour per user/key

        $this->load->model('User_model');
        $this->load->helper('json_response');
    }

    public function list_users_get()
    {
        $users = $this->User_model->list_users();

        if (!$users) {
            $message = json_response(false, 'users not found.');
            $this->response($message, \Restserver\Libraries\REST_Controller::HTTP_BAD_REQUEST);

            return;
        }
        $message = json_response(true, 'list users.', $users);

        $this->response($message, \Restserver\Libraries\REST_Controller::HTTP_OK);
    }

    public function user_by_email_get($email)
    {
        $user = $this->User_model->user_by_email($email);

        if (!$user) {
            $message = json_response(false, 'user not found.');
            $this->response($message, \Restserver\Libraries\REST_Controller::HTTP_BAD_REQUEST);

            return;
        }
        $message = json_response(true, 'get user data', $user);

        $this->response($message, \Restserver\Libraries\REST_Controller::HTTP_OK);
    }
}
