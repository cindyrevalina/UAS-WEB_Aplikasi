<?php
defined('BASEPATH') OR exit('No direct script access allowed');

/**
 * BaseController provides a convenient place for loading components
 * and performing functions that are needed by all your controllers.
 */
class BaseController extends CI_Controller {

    public function __construct() {
        parent::__construct();
        
        // Set CORS headers for all responses
        header('Access-Control-Allow-Origin: *');
        header('Access-Control-Allow-Headers: X-API-KEY, Origin, X-Requested-With, Content-Type, Accept, Access-Control-Request-Method, Authorization');
        header('Access-Control-Allow-Methods: GET, POST, OPTIONS, PUT, DELETE');
        header('Content-Type: application/json; charset=utf-8');
        
        // Handle preflight OPTIONS request
        if ($this->input->server('REQUEST_METHOD') === 'OPTIONS') {
            http_response_code(200);
            exit();
        }
    }

    /**
     * Send JSON response
     */
    protected function jsonResponse($data, $status = 200) {
        http_response_code($status);
        echo json_encode($data);
        exit();
    }
}
