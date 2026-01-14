# 🚨 Panduan Sistem Pesan Error & Notifikasi

Sistem ini menggunakan **SweetAlert2** untuk menampilkan semua jenis pesan dengan tampilan yang menarik dan user-friendly.

## 📋 Jenis Pesan yang Tersedia

### 1. **Success (Sukses)** ✅
```php
// Di Controller
set_success_message('Data berhasil disimpan');
// atau
return redirect_with_success('/users', 'User berhasil dibuat');
```

### 2. **Error (Kesalahan)** ❌
```php
// Di Controller
set_error_message('Gagal menyimpan data', 'Username sudah digunakan');
// atau
return back_with_error('Gagal memperbarui data');
```

### 3. **Warning (Peringatan)** ⚠️
```php
// Di Controller
set_warning_message('Data akan terhapus permanen');
```

### 4. **Info (Informasi)** ℹ️
```php
// Di Controller
set_info_message('Sistem akan maintenance pada jam 02:00');
```

### 5. **Permission Denied (Akses Ditolak)** 🚫
```php
// Di Controller
set_permission_denied_message('Anda tidak memiliki akses ke fitur ini');
```

### 6. **Database Error (Kesalahan Database)** 🗄️
```php
// Di Controller
set_db_error_message('Terjadi kesalahan database: Connection timeout');
```

### 7. **Validation Errors (Kesalahan Validasi)** 📝
```php
// Di Controller
if (!$this->validate($rules)) {
    return back_with_validation_errors($this->validator);
}
```

## 🔧 Helper Functions yang Tersedia

### Fungsi Pesan Dasar
- `set_success_message($message)`
- `set_error_message($message, $details = null)`
- `set_warning_message($message)`
- `set_info_message($message)`
- `set_permission_denied_message($message)`
- `set_db_error_message($message)`

### Fungsi Error Handling
- `handle_model_errors($model, $action)` - Handle error dari model
- `handle_database_exception($exception, $action)` - Handle exception database
- `set_validation_errors($validator)` - Set error validasi

### Fungsi Redirect dengan Pesan
- `redirect_with_success($url, $message)`
- `redirect_with_error($url, $message, $withInput = false)`
- `back_with_error($message)`
- `back_with_validation_errors($validator)`

## 🎨 Tampilan SweetAlert

### Success Message
- **Toast notification** di pojok kanan atas
- **Auto-hide** setelah 3 detik
- **Progress bar** menunjukkan waktu tersisa

### Error Message
- **Modal popup** di tengah layar
- **Tidak auto-hide** - user harus klik "Tutup"
- **Bisa menampilkan HTML** untuk list error

### Warning Message
- **Modal popup** dengan icon warning
- **Tombol "Mengerti"**

### Validation Errors
- **Modal popup** dengan list error
- **Format HTML** untuk readability yang baik
- **Width 500px** untuk ruang yang cukup

## 📱 Contoh Implementasi

### Di Controller

```php
<?php

namespace App\Controllers;

class ExampleController extends BaseController
{
    public function store()
    {
        $rules = [
            'name' => 'required|min_length[3]',
            'email' => 'required|valid_email|is_unique[users.email]'
        ];
        
        // Validasi input
        if (!$this->validate($rules)) {
            return back_with_validation_errors($this->validator);
        }
        
        try {
            $data = [
                'name' => $this->request->getPost('name'),
                'email' => $this->request->getPost('email')
            ];
            
            if ($this->userModel->insert($data)) {
                return redirect_with_success('/users', 'User berhasil dibuat');
            } else {
                handle_model_errors($this->userModel, 'pembuatan user');
                return redirect()->back()->withInput();
            }
        } catch (\Exception $e) {
            handle_database_exception($e, 'pembuatan user');
            return redirect()->back()->withInput();
        }
    }
    
    public function delete($id)
    {
        // Cek permission
        if (!has_permission('users.delete')) {
            set_permission_denied_message('Anda tidak memiliki akses untuk menghapus user');
            return redirect()->to('/users');
        }
        
        // Cek jika user ada
        $user = $this->userModel->find($id);
        if (!$user) {
            set_error_message('User tidak ditemukan');
            return redirect()->to('/users');
        }
        
        // Cek jika user tidak boleh dihapus
        if ($user['username'] === 'admin') {
            set_warning_message('User admin tidak dapat dihapus');
            return redirect()->to('/users');
        }
        
        try {
            if ($this->userModel->delete($id)) {
                return redirect_with_success('/users', 'User berhasil dihapus');
            } else {
                handle_model_errors($this->userModel, 'penghapusan user');
            }
        } catch (\Exception $e) {
            handle_database_exception($e, 'penghapusan user');
        }
        
        return redirect()->to('/users');
    }
}
```

### Di JavaScript (untuk AJAX)

```javascript
// Contoh penggunaan function yang tersedia di template
function handleAjaxResponse(response) {
    if (response.success) {
        showSuccess(response.message || 'Operasi berhasil');
    } else {
        showError(response.message || 'Operasi gagal', 'Error!', response.details);
    }
}

// Contoh untuk validasi error
function handleValidationErrors(errors) {
    showValidationError(errors, 'Form tidak valid!');
}
```

## 🌍 Bahasa Indonesia

Semua pesan sudah menggunakan **Bahasa Indonesia** untuk:
- Judul popup (Berhasil!, Gagal!, Peringatan!, dll)
- Tombol aksi (Tutup, Mengerti, Perbaiki, dll)
- Pesan standar (loading, konfirmasi, dll)

## 🔄 Fitur Tambahan

### Loading State
Semua konfirmasi menampilkan loading state saat memproses:
```javascript
Swal.fire({
    title: 'Memproses...',
    text: 'Sedang menyimpan data',
    allowOutsideClick: false,
    didOpen: () => {
        Swal.showLoading();
    }
});
```

### Konfirmasi Delete
```javascript
confirmDelete('/users/delete/1', 'user');
```

### Konfirmasi Action
```javascript
confirmAction('/users/activate/1', 'Aktifkan User', 'User akan diaktifkan', 'Ya, Aktifkan!');
```

## 🎯 Best Practices

1. **Selalu gunakan try-catch** untuk database operations
2. **Gunakan helper functions** untuk konsistensi
3. **Berikan pesan yang jelas dan informatif**
4. **Gunakan jenis pesan yang tepat** (error vs warning vs info)
5. **Log error ke file** untuk debugging
6. **Handle berbagai jenis database error** dengan pesan yang user-friendly

Sistem ini memastikan pengalaman pengguna yang konsisten dan informatif di seluruh aplikasi! 🚀