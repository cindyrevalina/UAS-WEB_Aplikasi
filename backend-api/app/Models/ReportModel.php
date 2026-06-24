<?php

namespace App\Models;

use CodeIgniter\Model;

class ReportModel extends Model
{
    protected $table            = 'reports';
    protected $primaryKey       = 'id';
    protected $useAutoIncrement = true;
    protected $returnType       = 'array';
    
    // Pastikan field di tabel reports lamamu sudah terdaftar semua di sini
    protected $allowedFields    = ['user_id', 'category_id', 'judul_laporan', 'isi_laporan', 'status'];
}