# CodeIgniter 4 RBAC Boilerplate

![CodeIgniter](https://img.shields.io/badge/CodeIgniter-4.7.2-orange)
![Bootstrap](https://img.shields.io/badge/Bootstrap-5.3.0-purple)
![License](https://img.shields.io/badge/License-MIT-green)

# Login Page

![login-page](login.png)

## Deskripsi Produk

Ini adalah **boilerplate aplikasi web** yang dibangun menggunakan **CodeIgniter 4.7.2**, framework PHP yang ringan dan cepat. Produk ini dirancang khusus untuk mempermudah mahasiswa dalam menyelesaikan tugas akhir mereka, sehingga mereka bisa fokus pada pengembangan fitur inti, bukan pada konfigurasi dasar.

## 🚀 Fitur-fitur Utama

### 1. **Arsitektur Modern dengan Struktur Folder yang Lebih Rapi**

- CodeIgniter 4 menggunakan struktur folder yang lebih terorganisir
- Semua kode aplikasi berada di dalam folder `app/`
- File statis (CSS, JS, gambar) dipindahkan ke folder `public/`
- Membuat aplikasi lebih aman dan alur kerja pengembang jadi lebih jelas

### 2. **Autentikasi Pengguna yang Kuat dan Fleksibel**

Boilerplate ini sudah dilengkapi dengan sistem autentikasi yang aman dan siap pakai:

- ✅ **Login & Registrasi**: Fungsi dasar untuk pendaftaran akun dan login oleh shield
- ✅ **Proteksi Halaman**: Menggunakan Filters untuk melindungi routes atau controller tertentu
- ✅ **Session Management**: Mengelola sesi pengguna dengan cara yang aman dan efisien
- ✅ **Role-Based Access Control (RBAC)**: Sistem role admin dan user

### 3. **Database Migration dan Seeding**

Fitur unggulan dari CI4 yang membantu dalam pengemban database:

- ✅ **Migrations**: Membuat dan mengubah struktur tabel database
- ✅ **Seeders**: Mengisi data awal (sample data) ke dalam tabel
- ✅ **CLI Commands**: Menjalankan perintah dengan `php spark`

### 4. **CRUD (Create, Read, Update, Delete) Data Mahasiswa**

- ✅ Modul CRUD lengkap sebagai contoh implementasi
- ✅ Menggunakan Model bawaan CI4 untuk berinteraksi dengan database
- ✅ Validasi data terintegrasi
- ✅ Flash messages untuk feedback user

### 5. **Desain Responsif dengan Bootstrap 5**

- ✅ Terintegrasi dengan Bootstrap 5
- ✅ Desain yang bersih, modern, dan responsif
- ✅ View layouts untuk elemen yang bisa dipakai ulang
- ✅ Font Awesome icons
- ✅ Dashboard yang informatif

### 6. **Tools Pengembangan yang Lengkap**

- ✅ **Debugging Toolbar**: Toolbar bawaan CI4 untuk debugging
- ✅ **Spark Commands**: Server lokal dengan `php spark serve`
- ✅ **Error Handling**: Penanganan error yang baik
- ✅ **Development Environment**: Konfigurasi mudah untuk development

## 🛠️ Instalasi

### Prasyarat

- PHP 8.1 atau lebih tinggi
- Composer
- PostgreSQL
- Web server (Apache/Nginx) atau gunakan built-in server

### Langkah Instalasi

1. **Clone repository**

   ```bash
   git clone https://github.com/mdestafadilah/codeigniter4-rbac-shield.git
   cd codeigniter4-rbac-shield
   ```

2. **Install dependencies**

   ```bash
   composer install
   ```

3. **Konfigurasi environment**

   ```bash
   cp env .env
   ```

   Edit file `.env` dan sesuaikan konfigurasi database untuk PostgreSQL:

   ```
   database.default.hostname = 127.0.0.1
   database.default.database = nama_database
   database.default.username = username_db
   database.default.password = password_db
   database.default.DBDriver = Postgre
   database.default.port     = 5432
   database.default.charset  = utf8
   ```

4. **Buat database dan jalankan migrations**

   ```bash
   // All Migration!
   php spark migrate
   // If Single File Migration
   php spark migrate:file "app\Database\Migrations\2025-11-19-204424_LogActivity.php"
   ```

5. **Jalankan seeders untuk data contoh**

   ```bash
   php spark db:seed DatabaseSeeder
   ```

6. **Jalankan server**

   ```bash
   php spark serve
   ```

7. **Akses aplikasi**
   Buka browser dan akses: `http://localhost:8080`

8. **Checking Server Production Connection**

   ```bash
   $host = 'localhost';
   $db = 'nama_database';
   $user = 'username_db';
   $pass = 'password_db';
   $port = '5432';

   $db_handle = pg_connect("host={$host} port={$port} dbname={$db} user={$user} password={$pass}");

   if ($db_handle) {
      echo "\nConnection attempt succeeded. \n\n";
   } else {
      echo "\nConnection attempt failed. \n\n";
   }

   echo "Connection Information\n";
   echo "======================\n\n";

   echo "DATABASE NAME:" . pg_dbname($db_handle) . "\n";
   echo "HOSTNAME: " . pg_host($db_handle) . "\n";
   echo "PORT: " . pg_port($db_handle) . "\n\n";
   exit;
   ```

## 👤 Akun Demo

### Admin

- **Username**: `[EMAIL_ADDRESS]`
- **Password**: `admin123`

## 📁 Struktur Proyek

```
app/
├── Controllers/        # Controller files
│   ├── AuthController.php
│   ├── MahasiswaController.php
│   └── Home.php
├── Models/            # Model files
│   ├── UserModel.php
│   └── MahasiswaModel.php
├── Views/             # View files
│   ├── layouts/
│   ├── auth/
│   ├── mahasiswa/
│   └── dashboard.php
├── Database/
│   ├── Migrations/    # Database migrations
│   └── Seeds/         # Database seeders
├── Filters/           # Custom filters
│   └── AuthFilter.php
└── Config/            # Configuration files
    ├── Routes.php
    └── Filters.php
```

## 🎯 Fitur yang Tersedia

### Autentikasi & Autorisasi

- [x] Login dan Logout
- [x] Registrasi user baru
- [x] Session management
- [x] Role-based access control
- [x] Password hashing

## 🚀 Development Commands

```bash
# Menjalankan server development
php spark serve

# Membuat migration baru
php spark make:migration CreateTableName

# Menjalankan migrations
php spark migrate

# Rollback migrations
php spark migrate:rollback

# Membuat seeder
php spark make:seeder SeederName

# Menjalankan seeder
php spark db:seed DatabaseSeeder

# Membuat controller
php spark make:controller ControllerName

# Membuat model
php spark make:model ModelName

# Membuat filter
php spark make:filter FilterName

# Generate Key Secret
php -r 'echo base64_encode(random_bytes(32));'

# Single Migration
php spark migrate:file "app\Database\Migrations\2025-11-19-204424_LogActivity.php"
```

## 🎨 Kustomisasi

### Menambah Role Baru

1. Update enum di migration `users` table
2. Tambahkan kondisi di `AuthFilter.php`
3. Update validasi di `UserModel.php`

### Menambah Modul CRUD Baru

1. Buat migration untuk tabel baru
2. Buat model dengan validation rules
3. Buat controller dengan method CRUD
4. Buat views untuk UI
5. Tambahkan routes di `Config/Routes.php`

## 📝 Database Schema

### Users Table

```sql
- id (INT, PRIMARY KEY, AUTO_INCREMENT)
- username (VARCHAR 100, UNIQUE)
- email (VARCHAR 100, UNIQUE)
- password (VARCHAR 255)
- role (ENUM: 'admin', 'user')
- created_at (DATETIME)
- updated_at (DATETIME)
```

## 🔒 Security Features

- ✅ Shield Powered!
- ✅ Password hashing dengan PHP `password_hash()`
- ✅ Session-based authentication
- ✅ CSRF protection (dapat diaktifkan)
- ✅ Input validation dan sanitization
- ✅ SQL injection protection melalui Query Builder
- ✅ XSS protection dengan `esc()` helper

## 🤝 Kontribusi

1. Fork repository ini
2. Buat branch fitur baru (`git checkout -b feature/AmazingFeature`)
3. Commit perubahan (`git commit -m 'Add some AmazingFeature'`)
4. Push ke branch (`git push origin feature/AmazingFeature`)
5. Buat Pull Request

## 📄 License

Distributed under the MIT License. See `LICENSE` for more information.

## 📞 Support

Jika Anda mengalami masalah atau memiliki pertanyaan:

- 📧 Email: [mdestafadilah@gmail.com](mailto:[mdestafadilah@gmail.com])
- 🐛 Issues: [GitHub Issues](https://github.com/mdestafadilah/codeigniter4-rbac-shield/issues)
- 💬 WhatsApp: [https://wa.me/6283898973731](https://wa.me/6283898973731)

## 🙏 Acknowledgments

- [CodeIgniter 4](https://codeigniter.com/) - The PHP framework
- [Bootstrap 5](https://getbootstrap.com/) - CSS framework
- [Font Awesome](https://fontawesome.com/) - Icons
- [PostgreSQL](https://www.postgresql.org/docs/) - Database PostgreSQL
- [AntiGravity](https://antigravityide.com/) - IDE Editor

---

**Happy Coding! 🚀**

> Developed by [mdestafadilah](https://github.com/mdestafadilah/codeigniter4-rbac-shield)
> Baseon [Muhammad Seman](https://github.com/muhammad-seman/codeigniter4_RBAC_boilerplate)

```

```
