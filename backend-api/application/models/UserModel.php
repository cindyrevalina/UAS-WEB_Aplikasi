<?php
defined('BASEPATH') OR exit('No direct script access allowed');

class UserModel extends CI_Model {
    
    protected $table = 'users';
    protected $primaryKey = 'id';

    public function __construct() {
        parent::__construct();
        $this->load->database();
    }

    /**
     * Get user by username
     */
    public function get_by_username($username) {
        return $this->db->where('username', $username)->get($this->table)->row_array();
    }

    /**
     * Get user by token
     */
    public function get_by_token($token) {
        return $this->db->where('token', $token)->get($this->table)->row_array();
    }

    /**
     * Update user
     */
    public function update($id, $data) {
        return $this->db->where('id', $id)->update($this->table, $data);
    }

    /**
     * Get all users
     */
    public function get_all() {
        return $this->db->get($this->table)->result_array();
    }

    /**
     * Get user by id
     */
    public function get_by_id($id) {
        return $this->db->where('id', $id)->get($this->table)->row_array();
    }
}