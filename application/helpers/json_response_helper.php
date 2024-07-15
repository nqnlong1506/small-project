<?php
if (!defined('BASEPATH')) exit('No direct script access allowed');

if (!function_exists('json_response')) {
    function json_response($success, $message, $data = null) {  
        if (is_null($data)) {
            return [
                'success'=> $success,
                'message'=> $message
            ];
        }
        
        return array(
            'success'=> $success,
            'message'=> $message,
            'data'=> $data
        );
    }
}