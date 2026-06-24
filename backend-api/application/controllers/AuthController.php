<?php
defined('BASEPATH') OR exit('No direct script access allowed');

class AuthController extends MY_Controller {

    public function __construct() {
        parent::__construct();
        $this->load->model('UserModel');
    }

    /**
     * == PROSES LOGIN ADMINISTRATOR ==
     * Endpoint: POST /login
     */
    public function login() {
        // 1. Ambil data JSON yang dikirim oleh Axios frontend SPA
        $json = json_decode($this->input->raw_input_stream);

        // Validasi input awal jika data kosong
        if (empty($json->username) || empty($json->password)) {
            return $this->jsonResponse([
                'status' => 400,
                'error' => 'Username dan password wajib diisi!',
                'messages' => null
            ], 400);
        }

        $username = $json->username;
        $password = $json->password;

        // 2. Cari user berdasarkan username di database
        $user = $this->UserModel->get_by_username($username);

        if (!$user) {
            return $this->jsonResponse([
                'status' => 401,
                'error' => 'Kredensial login salah atau akun tidak ditemukan!',
                'messages' => null
            ], 401);
        }

        // 3. Verifikasi Password
        // Menggunakan password_verify jika menggunakan hash, atau teks biasa jika disesuaikan untuk dummy data uas
        $passwordValid = false;
        if (password_verify($password, $user['password']) || $password === $user['password']) {
            $passwordValid = true;
        }

        if (!$passwordValid) {
            return $this->jsonResponse([
                'status' => 401,
                'error' => 'Kredensial login salah atau kata sandi tidak cocok!',
                'messages' => null
            ], 401);
        }

        // 4. Generate Token Baru (Sesuai Kriteria Token-Based Security UAS)
        // Kita buat token random yang unik setiap kali admin berhasil login
        $generatedToken = bin2hex(random_bytes(32));

        // Simpan token baru tersebut ke kolom `token` di tabel `users` database
        $this->UserModel->update($user['id'], [
            'token' => $generatedToken
        ]);

        // 5. Kirim respon sukses beserta token ke Frontend SPA untuk disimpan di localStorage
        return $this->jsonResponse([
            'status' => 200,
            'error' => null,
            'messages' => 'Autentikasi Berhasil!',
            'token' => $generatedToken, // Token ini yang akan dibaca oleh Axios
            'user' => [
                'id' => $user['id'],
                'username' => $user['username'],
                'nama_lengkap' => $user['nama_lengkap']
            ]
        ], 200);
    }

    /**
     * == PROSES LOGOUT ADMINISTRATOR ==
     * Endpoint: POST /logout (Memerlukan Token Header untuk validasi)
     */
    public function logout() {
        // Ambil token dari HTTP Header 'Authorization: Bearer <token>'
        $authHeader = $this->input->server('HTTP_AUTHORIZATION');
        
        if ($authHeader) {
            $token = str_replace('Bearer ', '', $authHeader);
            
            // Hapus token yang ada di database agar sesi tersebut hangus secara server-side
            $user = $this->UserModel->get_by_token($token);
            
            if ($user) {
                $this->UserModel->update($user['id'], ['token' => null]);
            }
        }

        return $this->jsonResponse([
            'status' => 200,
            'error' => null,
            'messages' => 'Sesi berhasil dihapus, logout sukses!'
        ], 200);
    }
}