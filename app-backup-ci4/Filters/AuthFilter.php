<?php

namespace App\Filters;

use CodeIgniter\Filters\FilterInterface;
use CodeIgniter\HTTP\RequestInterface;
use CodeIgniter\HTTP\ResponseInterface;
use Config\Services;

class AuthFilter implements FilterInterface
{
    public function before(RequestInterface $request, $arguments = null)
    {
        $authHeader = $request->getServer('HTTP_AUTHORIZATION');
        
        if (!$authHeader) {
            return Services::response()
                ->setJSON(['status' => 401, 'error' => 'Token tidak ditemukan, akses ditolak.'])
                ->setStatusCode(401);
        }

        $token = str_replace('Bearer ', '', $authHeader);
        
        // Validasi token ke database tabel users
        $db = \Config\Database::connect();
        $user = $db->table('users')->getWhere(['token' => $token])->getRow();

        if (!$user) {
            return Services::response()
                ->setJSON(['status' => 401, 'error' => 'Token tidak valid atau kedaluwarsa.'])
                ->setStatusCode(401);
        }

        // Simpan data user ke request agar bisa diakses di controller
        $request->user = $user;
    }

    public function after(RequestInterface $request, ResponseInterface $response, $arguments = null)
    {
        // Tidak diperlukan tindakan setelah request
    }
}