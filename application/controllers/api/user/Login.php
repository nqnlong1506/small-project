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
class Login extends \Restserver\Libraries\REST_Controller {

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

    public function login_post()
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
        
        $login = $this->User_model->login_user( $email, $password );
        if (!$login)
        {
            $message = json_response(false, 'login failed.');
            $this->response($message, \Restserver\Libraries\REST_Controller::HTTP_INTERNAL_SERVER_ERROR);

            return;
        }

        $message = json_response(true, 'login successfully.', ['token' => $login->email]);

        $this->response($message, \Restserver\Libraries\REST_Controller::HTTP_OK);
    }
}
