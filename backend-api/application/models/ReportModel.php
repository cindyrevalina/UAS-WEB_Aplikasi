<?php
defined('BASEPATH') OR exit('No direct script access allowed');

class ReportModel extends CI_Model {
    
    protected $table = 'reports';
    protected $primaryKey = 'id';

    public function __construct() {
        parent::__construct();
        $this->load->database();
    }

    /**
     * Get all reports
     */
    public function get_all() {
        return $this->db->get($this->table)->result_array();
    }

    /**
     * Get report by id
     */
    public function get_by_id($id) {
        return $this->db->where('id', $id)->get($this->table)->row_array();
    }

    /**
     * Insert new report
     */
    public function insert_report($data) {
        return $this->db->insert($this->table, $data);
    }

    /**
     * Update report
     */
    public function update($id, $data) {
        return $this->db->where('id', $id)->update($this->table, $data);
    }

    /**
     * Delete report
     */
    public function delete($id) {
        return $this->db->where('id', $id)->delete($this->table);
    }
}