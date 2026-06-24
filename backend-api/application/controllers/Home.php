<?php
defined('BASEPATH') OR exit('No direct script access allowed');

class Home extends MY_Controller {
    
    public function index() {
        return $this->jsonResponse([
            'status' => 200,
            'message' => 'CodeIgniter 3 Server Running',
            'time' => date('Y-m-d H:i:s')
        ], 200);
    }
}
