# 📝 Laravel Task Management App

<p align="center">
  <a href="https://laravel.com" target="_blank">
    <img src="https://raw.githubusercontent.com/laravel/art/master/logo-lockup/5%20SVG/2%20CMYK/1%20Full%20Color/laravel-logolockup-cmyk-red.svg" width="400" alt="Laravel Logo">
  </a>
</p>

## 📦 Fitur Aplikasi

Aplikasi ini adalah sistem manajemen tugas sederhana yang dibangun dengan Laravel. Fitur-fitur utama meliputi:

- 🔐 Autentikasi (Login, Register, Logout)
- ✅ CRUD Task (Buat, Lihat, Ubah, Hapus)
- 📊 Filtering tugas berdasarkan status
- 🧪 Unit dan Feature Testing

---

## 🛠️ Setup Proyek

```bash
# 1. Clone repository
git clone https://github.com/username/task-app.git
cd task-app

# 2. Install dependencies
composer install
npm install && npm run dev

# 3. Salin file .env dan generate app key
cp .env.example .env
php artisan key:generate

# 4. Setup database
# - Buat database dengan nama task_app di MySQL
# - Konfigurasi koneksi DB di file .env
php artisan migrate --seed

# 5. Jalankan server lokal
php artisan serve
```

---

## 🧾 Dokumentasi API

🔗 [Dokumentasi Postman](https://documenter.getpostman.com/view/14406697/2sB2qUoQg7)

Dokumentasi ini mencakup semua endpoint termasuk login, register, dan CRUD task lengkap dengan contoh respons.

---

## 🗃️ ERD (Entity Relationship Diagram)

```
+--------+         +-------+
| users  |         | tasks |
+--------+         +-------+
| id     |◄────────| user_id
| name   |         | title
| email  |         | description
| ...    |         | status
+--------+         | timestamps
                   +-------+
```

Relasi: Satu user dapat memiliki banyak task (One-to-Many)

---

## 📋 CRUD Task & Filtering

| HTTP Method | Endpoint           | Deskripsi                        |
|-------------|--------------------|----------------------------------|
| GET         | /tasks             | Menampilkan daftar tugas         |
| GET         | /tasks/create      | Form tambah tugas                |
| POST        | /tasks             | Simpan tugas baru                |
| GET         | /tasks/{id}/edit   | Form edit tugas                  |
| PUT         | /tasks/{id}        | Perbarui tugas                   |
| DELETE      | /tasks/{id}        | Hapus tugas                      |
| GET         | /tasks?status=done | Filter berdasarkan status tugas  |

---

## 📸 Screenshots

### 🔐 Halaman Login

<img src="Hasil 3.png" width="400" >

### 📋 Daftar Tugas

<img src="Hasil 1.png" width="400" >

### ✏️ Tambah User Admin

<img src="Hasil 2.png" width="400" >

---

## 👨‍💻 Kontribusi

Ingin menyumbang kode? Silakan kirim pull request. Pastikan semua pengujian lulus dan kode mengikuti standar Laravel.

---

## 🔒 Keamanan

Jika Anda menemukan celah keamanan, kirim email ke [taylor@laravel.com](mailto:taylor@laravel.com). Semua laporan akan ditangani dengan serius.

---

## 📄 Lisensi

Kode sumber proyek ini dilisensikan di bawah [MIT License](https://opensource.org/licenses/MIT).
