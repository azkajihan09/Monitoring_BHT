# DOKUMENTASI SISTEM BHT REMINDER
## Pengingat Otomatis untuk Batas Waktu Upaya Hukum

---

## 📋 DAFTAR ISI
1. [Pengenalan Sistem](#pengenalan-sistem)
2. [Struktur File dan Folder](#struktur-file-folder)
3. [Cara Kerja Sistem (MVC Pattern)](#cara-kerja-sistem)
4. [Database Schema](#database-schema)
5. [Fitur-Fitur Utama](#fitur-fitur-utama)
6. [Panduan Penggunaan](#panduan-penggunaan)
7. [Troubleshooting](#troubleshooting)
8. [Pengembangan Lanjutan](#pengembangan-lanjutan)

---

## 🎯 PENGENALAN SISTEM

### Apa itu Sistem BHT Reminder?
Sistem **BHT (Berita Hukum Tertulis) Reminder** adalah aplikasi web yang dirancang khusus untuk:

1. **Mengingat secara otomatis** perkara-perkara yang mendekati batas waktu pembuatan BHT
2. **Membuat laporan rekap** perkara berdasarkan status BHT
3. **Menampilkan dashboard visual** dengan grafik dan statistik BHT

### Mengapa Sistem Ini Penting?
- ⏰ **Mencegah keterlambatan** pembuatan BHT
- 📊 **Monitoring performa** pengolahan BHT
- 🎯 **Meningkatkan efisiensi** kerja administrasi pengadilan
- 📈 **Analisis data** untuk evaluasi kinerja

---

## 📁 STRUKTUR FILE DAN FOLDER

```
Monitoring_BHT/
├── application/
│   ├── controllers/
│   │   ├── Bht_reminder.php          # Controller utama
│   │   └── Bht_reminder_test.php     # Controller untuk testing
│   ├── models/
│   │   └── M_bht_reminder.php        # Model untuk operasi database
│   ├── views/
│   │   ├── v_bht_reminder.php        # View utama dashboard
│   │   └── template/
│   │       ├── new_header.php        # Template header
│   │       ├── new_sidebar.php       # Template sidebar
│   │       └── new_footer.php        # Template footer
│   └── config/
│       └── routes.php                # Routing configuration
└── README_BHT_SYSTEM.md             # Dokumentasi ini
```

### Penjelasan Setiap File:

#### 🎮 **Controller** (`Bht_reminder.php`)
**Fungsi**: Mengatur alur logika aplikasi
- Menerima request dari user
- Memanggil Model untuk data
- Mengirim data ke View
- Menangani AJAX requests

#### 🗃️ **Model** (`M_bht_reminder.php`)
**Fungsi**: Mengelola operasi database
- Query pengingat otomatis
- Laporan rekap statistik
- Data untuk chart dan grafik
- CRUD operations

#### 🎨 **View** (`v_bht_reminder.php`)
**Fungsi**: Menampilkan antarmuka user
- HTML struktur halaman
- CSS untuk styling
- JavaScript untuk interaktivitas
- Chart.js untuk grafik

---

## ⚙️ CARA KERJA SISTEM (MVC PATTERN)

### Konsep Dasar MVC:
```
USER REQUEST → CONTROLLER → MODEL → DATABASE
                    ↓           ↑
                  VIEW    ←   DATA
```

### Alur Kerja Step by Step:

#### 1. **User mengakses URL**
```
http://localhost/Monitoring_BHT/index.php/bht_reminder
```

#### 2. **CodeIgniter Routing** 
```php
// routes.php
$route['bht-reminder'] = 'bht_reminder/index';
```

#### 3. **Controller Processing**
```php
// Bht_reminder.php
public function index()
{
    // Load Model
    $reminders = $this->M_bht_reminder->get_automatic_reminders(3);
    
    // Prepare data
    $data['reminders'] = $reminders;
    
    // Load Views
    $this->load->view('template/new_header', $data);
    $this->load->view('v_bht_reminder', $data);
    $this->load->view('template/new_footer');
}
```

#### 4. **Model Data Processing**
```php
// M_bht_reminder.php
public function get_automatic_reminders($days_before = 3)
{
    // SQL Query untuk mencari perkara yang mendekati deadline
    $query = $this->db->query("
        SELECT nomor_perkara, tanggal_putusan, 
               DATE_ADD(tanggal_putusan, INTERVAL 14 DAY) as batas_bht,
               DATEDIFF(DATE_ADD(tanggal_putusan, INTERVAL 14 DAY), CURDATE()) as sisa_hari
        FROM perkara p
        LEFT JOIN perkara_putusan pp ON p.perkara_id = pp.perkara_id
        WHERE pp.tanggal_bht IS NULL
        AND DATEDIFF(DATE_ADD(pp.tanggal_putusan, INTERVAL 14 DAY), CURDATE()) <= ?
    ", [$days_before]);
    
    return $query->result();
}
```

#### 5. **View Rendering**
```php
// v_bht_reminder.php
<?php foreach ($reminders as $reminder): ?>
    <tr>
        <td><?= $reminder->nomor_perkara ?></td>
        <td><?= $reminder->sisa_hari ?> hari</td>
    </tr>
<?php endforeach; ?>
```

---

## 🗄️ DATABASE SCHEMA

### Tabel yang Digunakan:

#### 1. **Tabel `perkara`**
```sql
CREATE TABLE perkara (
    perkara_id INT PRIMARY KEY,
    nomor_perkara VARCHAR(100),
    jenis_perkara_nama VARCHAR(100),
    created_at TIMESTAMP
);
```

#### 2. **Tabel `perkara_putusan`**
```sql
CREATE TABLE perkara_putusan (
    perkara_id INT,
    tanggal_putusan DATE,
    tanggal_bht DATE,
    FOREIGN KEY (perkara_id) REFERENCES perkara(perkara_id)
);
```

#### 3. **Tabel `perkara_penetapan`** (Opsional)
```sql
CREATE TABLE perkara_penetapan (
    perkara_id INT,
    panitera_pengganti_text VARCHAR(200),
    FOREIGN KEY (perkara_id) REFERENCES perkara(perkara_id)
);
```

### Logika Bisnis Database:
- **Batas BHT**: 14 hari setelah tanggal putusan
- **Status Prioritas**:
  - `TERLAMBAT`: Lebih dari 14 hari
  - `URGENT`: 0-3 hari tersisa
  - `WARNING`: 4-7 hari tersisa
  - `NORMAL`: Lebih dari 7 hari tersisa

---

## 🔧 FITUR-FITUR UTAMA

### 1. **📅 Pengingat Otomatis**
- Deteksi perkara mendekati deadline
- Kategorisasi berdasarkan prioritas
- Notifikasi visual dengan badge warna
- Auto-refresh setiap 5 menit

### 2. **📊 Dashboard Visual**
- **Line Chart**: Trend bulanan BHT
- **Pie Chart**: Distribusi jenis perkara
- **Cards**: Statistik ringkas
- **Table**: Daftar pengingat detail

### 3. **📋 Laporan Rekap**
- Total perkara putus per bulan
- Jumlah BHT selesai vs belum
- Persentase ketepatan waktu
- Rata-rata hari penyelesaian

### 4. **🔄 AJAX Real-time**
- Filter data tanpa reload halaman
- Update grafik dinamis
- Mark reminder sebagai handled
- Export laporan

### 5. **📤 Export Data**
- Format Excel (menggunakan PHPExcel)
- Format PDF (dalam pengembangan)
- Custom date range

---

## 📖 PANDUAN PENGGUNAAN

### Akses Sistem:

#### URL Utama:
```
http://localhost/Monitoring_BHT/index.php/bht_reminder
```

#### URL Alternatif (dengan routing):
```
http://localhost/Monitoring_BHT/index.php/bht-reminder
```

### Fitur-Fitur Interface:

#### 1. **Cards Statistik** (Bagian Atas)
- **Total Perkara Putus**: Jumlah perkara yang sudah diputus bulan ini
- **BHT Selesai**: Jumlah BHT yang sudah dibuat
- **BHT Belum**: Perkara yang belum ada BHT-nya
- **Pengingat Aktif**: Jumlah perkara yang perlu perhatian

#### 2. **Tabel Pengingat** (Bagian Tengah)
- **Filter Button**: Semua, Urgent, Warning, Terlambat
- **Action Button**: 
  - 👁️ **View Detail**: Lihat detail perkara
  - ✅ **Mark Handled**: Tandai sudah ditangani

#### 3. **Charts** (Bagian Bawah)
- **Line Chart**: Trend bulanan dengan dropdown tahun
- **Pie Chart**: Distribusi jenis perkara dengan legend

#### 4. **Export** (Sidebar Kanan)
- **Excel**: Download laporan Excel
- **PDF**: Download laporan PDF (coming soon)

### Cara Menggunakan Filter:
1. Klik tombol filter di atas tabel
2. **Semua**: Tampilkan semua pengingat
3. **Urgent**: Hanya yang sisa waktu ≤ 3 hari
4. **Warning**: Yang sisa waktu 4-7 hari
5. **Terlambat**: Yang sudah lewat deadline

### Cara Mark Handled:
1. Klik tombol ✅ di kolom Aksi
2. Masukkan catatan (opsional)
3. Klik OK untuk konfirmasi
4. Data akan ter-update otomatis

---

## 🐛 TROUBLESHOOTING

### Masalah Umum dan Solusi:

#### 1. **Error 500 - Internal Server Error**
**Penyebab**: 
- Model tidak ter-load
- Database connection error
- PHP syntax error

**Solusi**:
```bash
# Cek log error
tail -f /xampp/htdocs/Monitoring_BHT/application/logs/log-*.php

# Test dengan controller sederhana
http://localhost/Monitoring_BHT/index.php/bht_reminder_test
```

#### 2. **Chart Tidak Muncul**
**Penyebab**:
- Chart.js belum ter-load
- Data kosong
- JavaScript error

**Solusi**:
```javascript
// Buka browser console (F12)
// Cek error JavaScript
console.log('Chart Data:', chartData);

// Pastikan Chart.js loaded
if (typeof Chart === 'undefined') {
    console.error('Chart.js tidak ditemukan!');
}
```

#### 3. **Data Kosong/Dummy**
**Penyebab**:
- Tabel database kosong
- Query error

**Solusi**:
```sql
-- Cek isi tabel
SELECT COUNT(*) FROM perkara;
SELECT COUNT(*) FROM perkara_putusan;

-- Insert data dummy untuk testing
INSERT INTO perkara (perkara_id, nomor_perkara, jenis_perkara_nama) 
VALUES (1, '0001/Test/2025', 'Cerai Talak');

INSERT INTO perkara_putusan (perkara_id, tanggal_putusan) 
VALUES (1, '2025-10-15');
```

#### 4. **Template Error**
**Penyebab**:
- File template tidak ditemukan
- Path salah

**Solusi**:
```php
// Cek file template exist
file_exists(APPPATH . 'views/template/new_header.php');

// Test tanpa template
echo "Test tanpa template berhasil!";
```

### Debug Mode:
```php
// Aktifkan di application/config/config.php
$config['log_threshold'] = 4; // Enable all logs

// Tampilkan error
ini_set('display_errors', 1);
error_reporting(E_ALL);
```

---

## 🚀 PENGEMBANGAN LANJUTAN

### Fitur yang Bisa Ditambahkan:

#### 1. **Notification System**
```php
// Real-time notification menggunakan WebSocket
public function send_notification($perkara_id, $message) {
    // Push notification ke admin
    // Email notification
    // SMS gateway integration
}
```

#### 2. **Advanced Filtering**
```javascript
// Filter berdasarkan:
// - Tanggal range
// - Jenis perkara
// - Hakim
// - Status prioritas
```

#### 3. **Dashboard Customization**
```php
// User preferences
// Custom dashboard layout
// Widget configuration
// Theme selection
```

#### 4. **API Integration**
```php
// RESTful API untuk mobile app
// JSON response format
// Authentication dengan JWT
// Rate limiting
```

#### 5. **Advanced Analytics**
```javascript
// Machine learning prediction
// Trend analysis
// Performance metrics
// Benchmark comparison
```

### Struktur Database Lanjutan:
```sql
-- Tabel notification history
CREATE TABLE bht_notifications (
    id INT AUTO_INCREMENT PRIMARY KEY,
    perkara_id INT,
    notification_type ENUM('reminder', 'urgent', 'overdue'),
    sent_at TIMESTAMP,
    recipient_email VARCHAR(100),
    status ENUM('sent', 'failed', 'pending')
);

-- Tabel user preferences
CREATE TABLE user_preferences (
    user_id INT,
    reminder_days_before INT DEFAULT 3,
    email_notifications BOOLEAN DEFAULT TRUE,
    dashboard_layout JSON
);

-- Tabel activity log
CREATE TABLE bht_activity_log (
    id INT AUTO_INCREMENT PRIMARY KEY,
    perkara_id INT,
    action_type VARCHAR(50),
    performed_by VARCHAR(100),
    performed_at TIMESTAMP,
    details TEXT
);
```

---

## 📚 REFERENSI DAN PEMBELAJARAN

### CodeIgniter Resources:
- [CodeIgniter 3 User Guide](https://codeigniter.com/userguide3/)
- [MVC Pattern Explanation](https://codeigniter.com/userguide3/overview/mvc.html)
- [Database Library](https://codeigniter.com/userguide3/database/index.html)

### Frontend Libraries:
- [Chart.js Documentation](https://www.chartjs.org/docs/)
- [AdminLTE Template](https://adminlte.io/docs/3.0/)
- [Bootstrap 4](https://getbootstrap.com/docs/4.6/)
- [jQuery](https://api.jquery.com/)

### Best Practices:
1. **Security**: Always validate input, use prepared statements
2. **Performance**: Use database indexing, caching
3. **Maintainability**: Follow MVC pattern, comment your code
4. **User Experience**: Responsive design, loading indicators

---

## 👨‍💻 CREDIT DAN KONTRIBUSI

**Dikembangkan oleh**: GitHub Copilot  
**Framework**: CodeIgniter 3  
**Template**: AdminLTE 3  
**Tanggal**: Oktober 2025  

**Untuk pembelajaran dan pengembangan sistem monitoring BHT Pengadilan Agama**

---

## 📞 SUPPORT

Jika ada pertanyaan atau masalah dalam implementasi:

1. **Check Documentation**: Baca dokumentasi ini dengan teliti
2. **Test Step by Step**: Gunakan controller test untuk debugging
3. **Check Logs**: Selalu periksa log error
4. **Ask Questions**: Diskusikan dengan tim development

**Happy Coding! 🎉**
