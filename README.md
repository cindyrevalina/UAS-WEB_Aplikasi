#  E-Report - Sistem Pengaduan Masyarakat Berbasis Web

##  Deskripsi Proyek

**E-Report** merupakan Sistem Informasi Pengaduan Masyarakat berbasis web yang dikembangkan untuk memudahkan masyarakat dalam menyampaikan laporan terkait permasalahan fasilitas umum, lingkungan, maupun pelayanan publik secara digital.

Sistem ini dibangun menggunakan konsep **Decoupled Architecture (Arsitektur Terpisah)**, yaitu memisahkan Backend dan Frontend sehingga pengembangan aplikasi menjadi lebih fleksibel, terstruktur, dan mudah untuk dikembangkan lebih lanjut.

Backend menggunakan **CodeIgniter 4** sebagai REST API Server yang bertugas mengelola data dan proses bisnis aplikasi, sedangkan Frontend menggunakan **VueJS** sebagai Single Page Application (SPA) yang memberikan pengalaman pengguna yang cepat tanpa proses reload halaman. Tampilan antarmuka didukung oleh **TailwindCSS** sehingga menghasilkan desain modern, responsif, dan user-friendly.

Melalui aplikasi ini masyarakat dapat mengirim laporan disertai foto bukti, memantau perkembangan status laporan, dan memperoleh informasi penanganan secara transparan. Administrator dapat melakukan validasi, pengelolaan, dan penyelesaian laporan secara terpusat melalui dashboard administrasi.

---

#  Tujuan Sistem

Tujuan pengembangan E-Report adalah:

* Mempermudah proses pengaduan masyarakat secara online.
* Meningkatkan efektivitas pengelolaan laporan oleh petugas.
* Menyediakan transparansi dalam proses penanganan pengaduan.
* Mengurangi proses pelaporan manual.
* Mendukung digitalisasi pelayanan publik.

---

#  Fitur Utama

##  Autentikasi Pengguna

### Masyarakat

* Registrasi akun.
* Login menggunakan email dan password.
* Mengirim laporan pengaduan.
* Melihat riwayat laporan yang telah dibuat.

### Administrator

* Login administrator.
* Mengelola seluruh data laporan.
* Mengubah status pengaduan.
* Menghapus laporan yang tidak valid.

---

##  Sistem Pengaduan

Masyarakat dapat membuat laporan baru dengan mengisi:

* Judul laporan.
* Deskripsi atau kronologi kejadian.
* Foto bukti pendukung.

Setiap laporan akan tersimpan ke database dan dapat dipantau perkembangannya oleh pengguna.

---

## 📷 Upload Bukti Foto

Sistem mendukung pengunggahan foto menggunakan objek **FormData**.

Format file yang didukung:

<img width="1600" height="765" alt="image" src="https://github.com/user-attachments/assets/f3ba82c4-9075-4955-ad5b-c65409abcf0b" />

<img width="1600" height="765" alt="image" src="https://github.com/user-attachments/assets/bcad89c8-b3ca-4b49-a881-26aea070174e" />

<img width="1600" height="758" alt="image" src="https://github.com/user-attachments/assets/24d79512-03d4-46b5-aaab-27c00407b9ce" />

<img width="1600" height="764" alt="image" src="https://github.com/user-attachments/assets/f31a259f-e0b2-40a2-9fa5-820547e30bd3" />


Validasi yang diterapkan:

* Validasi ukuran file.
* Validasi tipe file.
* Penyimpanan file secara aman pada server.

---

##  Manajemen Status Pengaduan

Status laporan dapat diperbarui oleh administrator sesuai proses penanganan.

| Status  | Keterangan                 |
| ------- | -------------------------- |
| Pending | Laporan baru diterima      |
| Proses  | Laporan sedang ditangani   |
| Selesai | Laporan telah diselesaikan |

Perubahan status dilakukan secara langsung melalui dashboard administrator.

---

##  Dashboard Administrator

Dashboard administrator menyediakan:

* Daftar seluruh laporan masyarakat.
* Foto bukti laporan.
* Judul laporan.
* Deskripsi laporan.
* Status laporan.
* Tombol aksi pengelolaan laporan.
* Informasi total laporan yang masuk.

Dashboard dirancang menggunakan TailwindCSS dengan tampilan modern dan responsif.

---

#  Arsitektur Sistem

Aplikasi menggunakan pendekatan **Client-Server Architecture (Decoupled Architecture)**.

## Backend API

Teknologi:

* PHP 8+
* CodeIgniter 4
* MySQL

Alamat Server:

http://localhost:8080

Fungsi:

* Menyediakan REST API.
* Mengelola autentikasi.
* Mengelola data pengaduan.
* Mengelola upload gambar.
* Mengelola status laporan.

---

## Frontend SPA

Teknologi:

* VueJS
* Axios
* TailwindCSS
* JavaScript

Alamat Aplikasi:

http://localhost/E-Report/frontend-spa/

Fungsi:

* Menampilkan antarmuka pengguna.
* Mengelola state aplikasi.
* Mengakses API Backend.
* Menampilkan data secara dinamis tanpa reload halaman.

---

#  Struktur Database

Nama Database:

```sql
e_report
```

Database terdiri dari empat tabel utama.

## 1. users

Menyimpan data akun masyarakat.

| Field      | Tipe Data |
| ---------- | --------- |
| id         | INT       |
| nama       | VARCHAR   |
| email      | VARCHAR   |
| password   | VARCHAR   |
| created_at | TIMESTAMP |
| updated_at | TIMESTAMP |

---

## 2. admin

Menyimpan data administrator.

| Field      | Tipe Data |
| ---------- | --------- |
| id         | INT       |
| username   | VARCHAR   |
| email      | VARCHAR   |
| password   | VARCHAR   |
| created_at | TIMESTAMP |
| updated_at | TIMESTAMP |

---

## 3. pengaduan

Menyimpan data laporan masyarakat.

| Field             | Tipe Data |
| ----------------- | --------- |
| id_pengaduan      | INT       |
| id_user           | INT       |
| judul             | VARCHAR   |
| deskripsi         | TEXT      |
| foto              | VARCHAR   |
| status            | ENUM      |
| tanggal_pengaduan | DATE      |
| created_at        | TIMESTAMP |
| updated_at        | TIMESTAMP |

---

## 4. tanggapan

Menyimpan respon atau tindak lanjut terhadap laporan.

| Field             | Tipe Data |
| ----------------- | --------- |
| id_tanggapan      | INT       |
| id_pengaduan      | INT       |
| id_admin          | INT       |
| isi_tanggapan     | TEXT      |
| tanggal_tanggapan | DATE      |
| created_at        | TIMESTAMP |
| updated_at        | TIMESTAMP |

---

#  Relasi Database

```text
users
   │
   └───< pengaduan
                │
                └───< tanggapan >─── admin
```

Keterangan:

* Satu pengguna dapat membuat banyak pengaduan.
* Satu pengaduan dapat memiliki beberapa tanggapan.
* Administrator dapat memberikan banyak tanggapan.

---

#  Keamanan Sistem

Sistem menerapkan beberapa mekanisme keamanan:

### Password Hashing

* Bcrypt Encryption.

### Validasi Input

* Validasi form pada frontend dan backend.

### REST API Security

* JSON Response Standard.
* Request Validation.
* Error Handling.

### CORS Protection

Backend dikonfigurasi menggunakan CORS agar komunikasi antara Frontend dan Backend berjalan aman.

---

#  Desain Antarmuka

Konsep desain yang digunakan:

* Modern User Interface
* Responsive Design
* Soft Pastel Color
* Clean Layout
* User Friendly Experience

Warna utama:

* Biru Gradient
* Putih
* Kuning (Pending)
* Oranye (Proses)
* Hijau (Selesai)
* Merah Muda (Hapus)

---

#  Alur Sistem

1. Pengguna melakukan login.
2. Pengguna membuat laporan pengaduan.
3. Pengguna mengunggah foto bukti.
4. Data dikirim ke Backend API menggunakan FormData.
5. Data tersimpan ke database MySQL.
6. Administrator memverifikasi laporan.
7. Status laporan diubah menjadi Proses.
8. Setelah penanganan selesai status diubah menjadi Selesai.
9. Pengguna dapat memantau perkembangan laporan secara real-time.

---

#  Teknologi yang Digunakan

## Backend

* PHP 8+
* CodeIgniter 4
* MySQL
* REST API

## Frontend

* VueJS
* TailwindCSS
* Axios
* JavaScript

## Tools

* XAMPP
* Composer
* Visual Studio Code
* Postman
* GitHub

---

#  Tampilan Sistem

## Dashboard Administrator

Dashboard administrator digunakan untuk mengelola seluruh laporan masyarakat yang masuk ke sistem. Administrator dapat melihat bukti foto, membaca laporan, memperbarui status, serta menghapus laporan yang tidak valid.

---

# 👨‍💻 Pengembang

Proyek ini dikembangkan sebagai Tugas Ujian Akhir Semester (UAS) Mata Kuliah Web 2 dengan menerapkan konsep Decoupled Architecture menggunakan CodeIgniter 4 sebagai Backend API dan VueJS sebagai Frontend SPA.

# Link YouTube
https://youtu.be/Zer0LA2X13Y?si=WZJdtgkyiZ1Tlpom
