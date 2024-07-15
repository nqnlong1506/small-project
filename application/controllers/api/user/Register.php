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
class Register extends \Restserver\Libraries\REST_Controller {

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

    public function register_post()
    {
        $email = $this->post('email');
        if ($email == null || $email == '') 
        {
            $message = json_response(false, 'email required.');
            $this->response($message, \Restserver\Libraries\REST_Controller::HTTP_BAD_REQUEST);   

            return;
        }

        $password = $this->post('password');
        if ($password == null || $password == '') 
        {
            $message = json_response(false, 'password required.');
            $this->response($message, \Restserver\Libraries\REST_Controller::HTTP_BAD_REQUEST);

            return;
        }

        $firstname = $this->post('firstname');
        if ($firstname == null || $firstname == '') 
        {
            $message = json_response(false, 'firstname required.');
            $this->response($message, \Restserver\Libraries\REST_Controller::HTTP_BAD_REQUEST);

            return;
        }

        $lastname = $this->post('lastname');
        if ($lastname == null || $lastname == '') 
        {
            $message = json_response(false, 'lastname required.');
            $this->response($message, \Restserver\Libraries\REST_Controller::HTTP_BAD_REQUEST);

            return;
        }

        if ($this->User_model->check_existing_user($email)) 
        {
            $message = json_response(false, 'user existed.');
            $this->response($message, \Restserver\Libraries\REST_Controller::HTTP_BAD_REQUEST);

            return;
        }

        $user_data = [
            'email'=> $email,
            'password' => password_hash($password, PASSWORD_DEFAULT),
            'firstname'=> $firstname,
            'lastname'=> $lastname
        ];

        $id = $this->User_model->register_user($user_data);

        if ($id == 0) 
        {
            $message = json_response(false, 'register failed.');
            $this->response($message, \Restserver\Libraries\REST_Controller::HTTP_INTERNAL_SERVER_ERROR);

            return;
        }

        $message = json_response(true, 'register successfully.', $id);

        $this->response($message, \Restserver\Libraries\REST_Controller::HTTP_OK);
    }
}
