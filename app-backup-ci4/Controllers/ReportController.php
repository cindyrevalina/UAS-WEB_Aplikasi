<?php

namespace App\Controllers;

use App\Models\ReportModel;
use CodeIgniter\RESTful\ResourceController;

class ReportController extends ResourceController
{
    protected $modelName = 'App\Models\ReportModel';
    protected $format    = 'json';

    /**
     * 1. MEMBACA DATA (Menampilkan Laporan + Gabungan Gambar)
     * Endpoint: GET /reports
     */
    public function index()
    {
    $reportModel = new \App\Models\ReportModel();
    
    // Mengambil semua kolom dari tabel reports, dan hanya mengambil bukti_gambar dari tabel report_images
    $data = $reportModel->select('reports.id, reports.user_id, reports.category_id, reports.judul_laporan, reports.isi_laporan, reports.status, reports.created_at, report_images.bukti_gambar')
                        ->join('report_images', 'report_images.report_id = reports.id', 'left')
                        ->orderBy('reports.id', 'DESC')
                        ->findAll();

    return $this->respond($data, 200);
    }

    /**
     * 2. MENYIMPAN DATA (Menyimpan Teks ke tabel reports & Gambar ke tabel report_images)
     * Endpoint: POST /reports
     */
    public function create()
    {
        // Tangkap input teks dari form data
        $dataReport = [
            'user_id'       => $this->request->getPost('user_id') ?? 1,
            'category_id'   => $this->request->getPost('category_id'),
            'judul_laporan' => $this->request->getPost('judul_laporan'),
            'isi_laporan'   => $this->request->getPost('isi_laporan'),
            'status'        => 'Pending'
        ];

        // Simpan data teks ke tabel utama 'reports' terlebih dahulu
        $reportModel = new ReportModel();
        $reportModel->save($dataReport);
        
        // Ambil ID dari laporan yang baru saja tersimpan di atas
        $newReportId = $reportModel->getInsertID();

        // Cek apakah ada file gambar yang ikut diunggah
        $img = $this->request->getFile('bukti_gambar');
        
        if ($img && $img->isValid() && !$img->hasMoved()) {
            // Berikan nama acak unik agar file tidak bentrok di folder uploads
            $newName = $img->getRandomName();
            
            // Pindahkan file fisik gambar ke folder public/uploads/ yang kita buat di Langkah 2
            $img->move(ROOTPATH . 'public/uploads', $newName);
            
            // Masukkan catatan nama gambar ke tabel baru 'report_images'
            $db = \Config\Database::connect();
            $db->table('report_images')->insert([
                'report_id'    => $newReportId,
                'bukti_gambar' => $newName
            ]);
        }

        return $this->respondCreated([
            'status'  => 201,
            'message' => 'Laporan aduan beserta bukti gambar berhasil tersimpan!'
        ]);
    }
}