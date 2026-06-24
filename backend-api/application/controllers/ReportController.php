<?php
defined('BASEPATH') OR exit('No direct script access allowed');

class ReportController extends MY_Controller {

    public function __construct() {
        parent::__construct();
        $this->load->model('ReportModel');
    }

    /**
     * 1. MEMBACA DATA (Menampilkan Laporan + Gabungan Gambar)
     * Endpoint: GET /reports
     */
    public function index() {
        // Mengambil semua kolom dari tabel reports, dan hanya mengambil bukti_gambar dari tabel report_images
        $this->db->select('reports.id, reports.user_id, reports.category_id, reports.judul_laporan, reports.isi_laporan, reports.status, reports.created_at, report_images.bukti_gambar');
        $this->db->from('reports');
        $this->db->join('report_images', 'report_images.report_id = reports.id', 'left');
        $this->db->order_by('reports.id', 'DESC');
        $query = $this->db->get();
        $data = $query->result_array();

        return $this->jsonResponse($data, 200);
    }

    /**
     * 2. MENYIMPAN DATA (Menyimpan Teks ke tabel reports & Gambar ke tabel report_images)
     * Endpoint: POST /reports
     */
    public function create() {
        // Tangkap input teks dari form data
        $dataReport = [
            'user_id'       => $this->input->post('user_id') ?? 1,
            'category_id'   => $this->input->post('category_id'),
            'judul_laporan' => $this->input->post('judul_laporan'),
            'isi_laporan'   => $this->input->post('isi_laporan'),
            'status'        => 'Pending',
            'created_at'    => date('Y-m-d H:i:s')
        ];

        // Simpan data teks ke tabel utama 'reports' terlebih dahulu
        $this->db->insert('reports', $dataReport);
        
        // Ambil ID dari laporan yang baru saja tersimpan di atas
        $newReportId = $this->db->insert_id();

        // Cek apakah ada file gambar yang ikut diunggah
        $img = $_FILES['bukti_gambar'] ?? null;
        
        if ($img && $img['error'] === 0) {
            // Berikan nama acak unik agar file tidak bentrok di folder uploads
            $newName = uniqid() . '_' . basename($img['name']);
            
            // Pindahkan file fisik gambar ke folder public/uploads/ 
            if (move_uploaded_file($img['tmp_name'], APPPATH . '../public/uploads/' . $newName)) {
                // Masukkan catatan nama gambar ke tabel baru 'report_images'
                $this->db->insert('report_images', [
                    'report_id'    => $newReportId,
                    'bukti_gambar' => $newName
                ]);
            }
        }

        return $this->jsonResponse([
            'status'  => 201,
            'message' => 'Laporan aduan beserta bukti gambar berhasil tersimpan!'
        ], 201);
    }
}