<?php

namespace App\Controllers;

use App\Models\UserModel;
use CodeIgniter\RESTful\ResourceController;

class AuthController extends ResourceController
{
    protected $format = 'json';

    /**
     * == PROSES LOGIN ADMINISTRATOR ==
     * Endpoint: POST /login
     */
    public function login()
    {
        // 1. Ambil data JSON yang dikirim oleh Axios frontend SPA
        $json = $this->request->getJSON();

        // Validasi input awal jika data kosong
        if (empty($json->username) || empty($json->password)) {
            return $this->fail('Username dan password wajib diisi!', 400);
        }

        $username = $json->username;
        $password = $json->password;

        // 2. Cari user berdasarkan username di database
        $userModel = new UserModel();
        $user = $userModel->where('username', $username)->first();

        if (!$user) {
            return $this->failUnauthorized('Kredensial login salah atau akun tidak ditemukan!');
        }

        // 3. Verifikasi Password
        // Menggunakan password_verify jika menggunakan hash, atau teks biasa jika disesuaikan untuk dummy data uas
        $passwordValid = false;
        if (password_verify($password, $user['password']) || $password === $user['password']) {
            $passwordValid = true;
        }

        if (!$passwordValid) {
            return $this->failUnauthorized('Kredensial login salah atau kata sandi tidak cocok!');
        }

        // 4. Generate Token Baru (Sesuai Kriteria Token-Based Security UAS)
        // Kita buat token random yang unik setiap kali admin berhasil login
        $generatedToken = bin2hex(random_bytes(32));

        // Simpan token baru tersebut ke kolom `token` di tabel `users` database
        $userModel->update($user['id'], [
            'token' => $generatedToken
        ]);

        // 5. Kirim respon sukses beserta token ke Frontend SPA untuk disimpan di localStorage
        return $this->respond([
            'status'   => 200,
            'error'    => null,
            'messages' => 'Autentikasi Berhasil!',
            'token'    => $generatedToken, // Token ini yang akan dibaca oleh Axios
            'user'     => [
                'id'           => $user['id'],
                'username'     => $user['username'],
                'nama_lengkap' => $user['nama_lengkap']
            ]
        ], 200);
    }

    /**
     * == PROSES LOGOUT ADMINISTRATOR ==
     * Endpoint: POST /logout (Memerlukan Token Header untuk validasi)
     */
    public function logout()
    {
        // Ambil token dari HTTP Header 'Authorization: Bearer <token>'
        $authHeader = $this->request->getServer('HTTP_AUTHORIZATION');
        
        if ($authHeader) {
            $token = str_replace('Bearer ', '', $authHeader);
            
            // Hapus token yang ada di database agar sesi tersebut hangus secara server-side
            $userModel = new UserModel();
            $user = $userModel->where('token', $token)->first();
            
            if ($user) {
                $userModel->update($user['id'], ['token' => null]);
            }
        }

        return $this->respond([
            'status'   => 200,
            'error'    => null,
            'messages' => 'Sesi berhasil dihapus, logout sukses!'
        ], 200);
    }
}