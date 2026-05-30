# Product Requirements Document
## Sistem Manajemen Tugas Perkuliahan
### Berbasis Yii Framework · Laragon · Bootstrap 5 · REST API · SOAP · Holidays API

---

**Versi:** 2.0 — Multi-Role  
**Mata Kuliah:** Pemrograman Berbasis Platform  
**Status:** Draft Final  
**Tanggal:** 2026  

---

## Daftar Isi

1. [Ringkasan Proyek](#1-ringkasan-proyek)
2. [Tech Stack](#2-tech-stack)
3. [Arsitektur Sistem](#3-arsitektur-sistem)
4. [Mekanisme Autentikasi & Manajemen Akun](#4-mekanisme-autentikasi--manajemen-akun)
5. [Entitas & Struktur Database](#5-entitas--struktur-database)
6. [Role & Hak Akses](#6-role--hak-akses)
7. [Fitur per Role](#7-fitur-per-role)
8. [Logika Status & Indikator Deadline](#8-logika-status--indikator-deadline)
9. [Mekanisme Sinkronisasi & OOP](#9-mekanisme-sinkronisasi--oop)
10. [Integrasi API Eksternal — Holidays API](#10-integrasi-api-eksternal--holidays-api)
11. [REST API Endpoint](#11-rest-api-endpoint)
12. [SOAP Web Service](#12-soap-web-service)
13. [Spesifikasi Tampilan Frontend](#13-spesifikasi-tampilan-frontend)
14. [Catatan Implementasi OOP](#14-catatan-implementasi-oop)

---

## 1. Ringkasan Proyek

Website manajemen tugas perkuliahan yang memungkinkan **dosen (Admin)** mengelola data akademik dan tugas, serta **mahasiswa (User)** melihat dan mengumpulkan tugas secara mandiri. Sistem dibangun di atas arsitektur MVC Yii Framework dengan prinsip OOP, dilengkapi REST API, SOAP Web Service, dan integrasi API Hari Libur Nasional untuk validasi deadline.

Proyek ini merupakan pengembangan dari versi sebelumnya yang hanya memiliki satu role (Admin/Dosen). Pada versi 2.0 ini ditambahkan role kedua yaitu User (Mahasiswa) beserta 2 entitas baru: tabel `mahasiswa` dan tabel `pengumpulan_tugas`.

---

## 2. Tech Stack

| Komponen | Teknologi |
|---|---|
| Framework Backend | Yii 2.x |
| Local Server | Laragon |
| Frontend UI | Bootstrap 5 |
| Database | MySQL |
| Web Service | REST API + SOAP |
| External API | libur.deno.dev (Holidays API) |
| Bahasa | PHP |
| ORM | Yii ActiveRecord |

---

## 3. Arsitektur Sistem

Sistem menggunakan arsitektur **MVC (Model-View-Controller)** bawaan Yii Framework:

- **Model** — menangani logika bisnis, relasi antar tabel, validasi, dan kalkulasi (progress pengumpulan, status kumpul, pengecekan hari libur)
- **View** — tampilan menggunakan Bootstrap 5, responsif mobile-friendly
- **Controller** — mengatur alur request-response, autentikasi role, dan distribusi data ke view

Autentikasi menggunakan sistem login bawaan Yii dengan penambahan kolom `role` pada tabel `user` default Yii. Role bernilai `admin` diarahkan ke panel dosen, role bernilai `user` diarahkan ke dashboard mahasiswa.

---

## 4. Mekanisme Autentikasi & Manajemen Akun

### 4.1 Halaman Login

Sistem hanya memiliki **satu halaman login** yang digunakan oleh semua role. Tidak ada halaman login terpisah untuk admin maupun mahasiswa. Setelah login berhasil, sistem secara otomatis membaca nilai kolom `role` pada tabel `user` dan mengarahkan pengguna ke halaman yang sesuai.

```
Buka /site/login
        ↓
Masukkan username & password
        ↓
Yii memverifikasi kredensial di tabel `user`
        ↓
Cek kolom `role`
        ├── role = 'admin'  →  redirect ke /admin/dashboard  (Panel Dosen)
        └── role = 'user'   →  redirect ke /mahasiswa/dashboard  (Dashboard Mahasiswa)
```

### 4.2 Perbedaan Cara Mendapatkan Akun

Sistem membedakan cara pembuatan akun berdasarkan role:

| Aspek | Admin (Dosen) | User (Mahasiswa) |
|---|---|---|
| Cara mendapat akun | Di-*seed* langsung ke database | Mendaftar sendiri lewat halaman registrasi |
| Tombol "Daftar" | Tidak ada / tidak perlu | Tersedia di halaman login |
| Siapa yang buat | Developer / pengelola sistem | Mahasiswa itu sendiri |

### 4.3 Akun Admin — Di-seed Langsung ke Database

Akun dosen/admin **tidak dibuat melalui form registrasi**. Akun admin sudah disiapkan sejak awal dengan cara di-insert langsung ke tabel `user` bawaan Yii, baik melalui SQL manual, migration seeder, maupun GUI database (phpMyAdmin/TablePlus).

**Alasan pendekatan ini:**
- Jumlah dosen terbatas dan sudah diketahui sebelumnya
- Mencegah siapapun bisa mendaftar sebagai admin secara bebas
- Lebih aman karena akun admin dikontrol penuh oleh pengelola sistem

**Contoh SQL untuk insert akun admin:**

```sql
INSERT INTO `user` (username, email, password_hash, auth_key, role, status, created_at, updated_at)
VALUES (
    'admin',
    'admin@kampus.ac.id',
    '$2y$13$xxxxxxxxxxxxxxxxxxxxxxxxxxxxxxxxxxxxxxxxxxxxxxxxxxxx',  -- hasil generatePasswordHash()
    'randomAuthKeyString',
    'admin',
    10,   -- 10 = aktif (STATUS_ACTIVE di Yii)
    UNIX_TIMESTAMP(),
    UNIX_TIMESTAMP()
);
```

> **Catatan:** Password hash di atas tidak boleh diisi teks biasa. Hash harus dibuat menggunakan method Yii:
>
> ```php
> Yii::$app->security->generatePasswordHash('passwordRahasia123');
> ```
>
> Jalankan perintah ini di console Yii atau file PHP sementara, lalu salin hasilnya ke kolom `password_hash`.

### 4.4 Akun Mahasiswa — Registrasi Mandiri

Mahasiswa mendaftar sendiri melalui halaman registrasi yang tersedia di `/site/register`. Setelah akun `user` terbuat, mahasiswa melengkapi profil akademiknya (NIM, nama, kelas/jurusan) yang tersimpan di tabel `mahasiswa` dengan relasi ke `user.id`.

**Alur registrasi mahasiswa:**

```
Buka /site/register
        ↓
Isi form: username, email, password
        ↓
Record baru dibuat di tabel `user` dengan role = 'user'
        ↓
Mahasiswa melengkapi profil (NIM, nama, kelas) → tersimpan di tabel `mahasiswa`
        ↓
Login → redirect ke /mahasiswa/dashboard
```

### 4.5 Proteksi Halaman Berdasarkan Role

Setiap controller dilindungi menggunakan `AccessControl` behavior Yii, sehingga:
- Mahasiswa yang mencoba mengakses URL admin akan ditolak dan diarahkan ke halaman 403
- Admin yang mencoba mengakses URL mahasiswa juga ditolak
- Pengguna yang belum login pada halaman manapun akan diarahkan ke halaman login

```php
// Contoh di AdminController atau SiteController
public function behaviors()
{
    return [
        'access' => [
            'class' => AccessControl::class,
            'rules' => [
                [
                    'allow' => true,
                    'roles' => ['@'],   // hanya user yang sudah login
                    'matchCallback' => function ($rule, $action) {
                        return Yii::$app->user->identity->role === 'admin';
                    },
                ],
            ],
        ],
    ];
}
```

---

## 5. Entitas & Struktur Database

### 5.1 Tabel Existing (Versi Sebelumnya)

#### Tabel `user` (Bawaan Yii — Dimodifikasi)

| Kolom | Tipe | Keterangan |
|---|---|---|
| `id` | INT | Primary Key, Auto Increment |
| `username` | VARCHAR(255) | Username unik |
| `email` | VARCHAR(255) | Email unik |
| `password_hash` | VARCHAR(255) | Hash password (bcrypt) |
| `auth_key` | VARCHAR(32) | Key untuk remember-me |
| `role` | VARCHAR(20) | Nilai: `'admin'` atau `'user'` |
| `status` | SMALLINT | 10 = aktif, 9 = tidak aktif |
| `created_at` | INT | Unix timestamp |
| `updated_at` | INT | Unix timestamp |

> **Catatan:** Kolom `role` adalah tambahan dari skema default Yii. Kolom ini yang menjadi penentu ke halaman mana user diarahkan setelah login.

#### Tabel `dosen`

| Kolom | Tipe | Keterangan |
|---|---|---|
| `id` | INT | Primary Key, Auto Increment |
| `nip` | VARCHAR(20) | Nomor Induk Pegawai, Unique |
| `nama_dosen` | VARCHAR(100) | Nama lengkap dosen |

#### Tabel `matkul`

| Kolom | Tipe | Keterangan |
|---|---|---|
| `id` | INT | Primary Key, Auto Increment |
| `kode_matkul` | VARCHAR(10) | Kode mata kuliah, Unique |
| `nama_matkul` | VARCHAR(100) | Nama mata kuliah |
| `dosen_id` | INT | Foreign Key → `dosen.id` |
| `sks` | INT | Jumlah SKS |

#### Tabel `tugas`

| Kolom | Tipe | Keterangan |
|---|---|---|
| `id` | INT | Primary Key, Auto Increment |
| `judul_tugas` | VARCHAR(200) | Judul tugas |
| `deskripsi_tugas` | TEXT | Deskripsi / instruksi tugas |
| `dosen_id` | INT | Foreign Key → `dosen.id` |
| `matkul_id` | INT | Foreign Key → `matkul.id` |
| `deadline` | DATE | Tanggal batas pengumpulan |
| `is_holiday` | TINYINT(1) | Flag hari libur (otomatis dari API) |
| `holiday_name` | VARCHAR(100) | Nama hari libur jika ada (otomatis dari API) |

> **Catatan:** Kolom `is_holiday` dan `holiday_name` ditambahkan pada versi ini untuk mendukung integrasi Holidays API. Nilai diisi otomatis saat dosen menginput atau mengedit deadline.

---

### 5.2 Tabel Baru (Versi 2.0)

#### Tabel `mahasiswa`

| Kolom | Tipe | Keterangan |
|---|---|---|
| `id` | INT | Primary Key, Auto Increment |
| `nim` | VARCHAR(20) | Nomor Induk Mahasiswa, Unique |
| `nama_mahasiswa` | VARCHAR(100) | Nama lengkap mahasiswa |
| `kelas_jurusan` | VARCHAR(50) | Kelas dan jurusan |
| `user_id` | INT | Foreign Key → tabel `user` bawaan Yii |

#### Tabel `pengumpulan_tugas`

| Kolom | Tipe | Keterangan |
|---|---|---|
| `id` | INT | Primary Key, Auto Increment |
| `tugas_id` | INT | Foreign Key → `tugas.id` |
| `mahasiswa_id` | INT | Foreign Key → `mahasiswa.id` |
| `file_tugas` | TEXT | Path file PDF/ZIP yang diunggah |
| `link_tugas` | TEXT | URL Google Drive / link alternatif |
| `waktu_kumpul` | TIMESTAMP | Waktu pengumpulan, diisi otomatis (TimestampBehavior) |
| `status_kumpul` | ENUM('Tepat Waktu','Terlambat') | Dinilai otomatis via `beforeSave()` |

> **Catatan:** Minimal salah satu dari `file_tugas` atau `link_tugas` harus diisi. Sistem menerima keduanya namun tidak mewajibkan keduanya sekaligus.

---

### 5.3 Diagram Relasi Antar Tabel

```
user (Yii default)
 └── mahasiswa (user_id → user.id)
      └── pengumpulan_tugas (mahasiswa_id → mahasiswa.id)
           └── tugas (tugas_id → tugas.id)
                ├── dosen (dosen_id → dosen.id)
                └── matkul (matkul_id → matkul.id)
                     └── dosen (dosen_id → dosen.id)
```

---

## 6. Role & Hak Akses

| Fitur | Admin (Dosen) | User (Mahasiswa) |
|---|---|---|
| CRUD Dosen | Ya | Tidak |
| CRUD Matkul | Ya | Tidak |
| CRUD Tugas | Ya | Tidak |
| Lihat Daftar Tugas | Ya | Ya |
| Lihat Detail Tugas | Ya | Ya |
| Kumpul Tugas | Tidak | Ya |
| Lihat Progress Pengumpulan | Ya | Tidak |
| Lihat Siapa yang Sudah Kumpul | Ya | Tidak |
| Dashboard Ringkasan | Ya (panel admin) | Ya (dashboard mahasiswa) |

---

## 7. Fitur per Role

### 7.1 Role Admin — Dosen

#### CRUD Dosen
- Tambah dosen: input nama dosen dan NIP
- Edit dan hapus data dosen
- Data dosen muncul sebagai dropdown di form matkul dan tugas

#### CRUD Matkul
- Tambah matkul: input kode matkul, nama matkul, pilih dosen (dropdown dari tabel `dosen`), jumlah SKS
- Edit dan hapus mata kuliah

#### CRUD Tugas
- Tambah tugas: input judul tugas, deskripsi, pilih dosen (dropdown), pilih matkul (dropdown), input deadline
- Saat input deadline → sistem otomatis hit Holidays API → simpan `is_holiday` dan `holiday_name` ke database
- Edit dan hapus tugas

#### Halaman Index Tugas (Tabel)

Kolom yang ditampilkan pada tabel index tugas:

| No | Judul Tugas | Dosen | Matkul | Deadline | Progress | Status | Aksi |
|---|---|---|---|---|---|---|---|
| 1 | Tugas 1 | Dr. Budi | PBO | 15 Jun 2025 | 12 / 52 | Pending | Show · Edit · Hapus |

Keterangan kolom:
- **Deadline** — tampil dengan indikator warna (lihat bagian 8)
- **Progress** — dihitung dinamis via `COUNT()`, format `X / 52 Mahasiswa`
- **Status** — badge otomatis: kuning/oranye untuk Pending, hijau untuk Selesai
- **Aksi** — tiga tombol: Show (detail), Edit, Hapus

#### Halaman Show Tugas (Detail)

Saat dosen mengklik tombol **Show**, sistem menampilkan:

1. Judul Tugas
2. Deskripsi Tugas
3. Nama Dosen
4. Nama Matkul
5. Deadline (dengan indikator hari libur jika berlaku)
6. Tabel Mahasiswa yang Sudah Mengumpulkan:

| No | Nama Mahasiswa | NIM | Waktu Kumpul | Status Kumpul |
|---|---|---|---|---|
| 1 | Andi Pratama | 21001001 | 10 Jun 2025 08:30 | Tepat Waktu |
| 2 | Sari Dewi | 21001002 | 16 Jun 2025 10:00 | Terlambat |

> Data diurutkan berdasarkan `waktu_kumpul ASC` (yang paling awal kumpul tampil di paling atas).

---

### 7.2 Role User — Mahasiswa

#### Dashboard Mahasiswa
- Card ringkasan **Tugas Belum Dikumpul**: jumlah tugas yang belum pernah dikumpulkan oleh mahasiswa yang login
- Card ringkasan **Tugas Sudah Dikumpul**: jumlah tugas yang sudah pernah dikumpulkan

#### Daftar Tugas (Task List)
- Menampilkan semua tugas dari seluruh matkul
- Setiap tugas menampilkan: judul, nama matkul, nama dosen, dan deadline dengan indikator warna
- Indikasi visual apakah tugas sudah dikumpulkan atau belum

#### Detail & Form Pengumpulan Tugas
- Tampil: judul tugas, deskripsi lengkap, nama dosen, nama matkul, batas waktu
- Form pengumpulan: upload file PDF/ZIP **atau** input link drive
- Validasi: jika mahasiswa sudah pernah mengumpulkan tugas ini, form tidak ditampilkan — diganti pesan konfirmasi beserta data pengumpulan sebelumnya
- Setelah submit: sistem otomatis menentukan `status_kumpul` berdasarkan perbandingan `waktu_kumpul` dengan `deadline` tugas

---

## 8. Logika Status & Indikator Deadline

### 8.1 Status Tugas (Dilihat Admin)

Status tugas ditentukan secara otomatis, tidak diubah manual oleh dosen.

```
IF COUNT(pengumpulan_tugas WHERE tugas_id = X) < COUNT(mahasiswa)
    → Status: "Pending"   (badge kuning/oranye)
ELSE
    → Status: "Selesai"   (badge hijau)
```

### 8.2 Indikator Warna Deadline

Konversi dari kode Laravel ke Yii menggunakan `strtotime()` atau Carbon-equivalent Yii:

```php
// Di Yii, gunakan DateTime atau strtotime untuk kalkulasi
$deadline = new DateTime($model->deadline);
$today    = new DateTime(date('Y-m-d'));
$diff     = (int) $today->diff($deadline)->days;
$sign     = $deadline >= $today ? 1 : -1;
$diff     = $diff * $sign;
```

| Kondisi | Warna | Teks |
|---|---|---|
| `$diff < 0` | Merah (`text-danger`) | `{tanggal} — Terlambat {abs($diff)} hari` |
| `$diff === 0` | Kuning (`text-warning`) | `{tanggal} — Hari ini!` |
| `$diff <= 3` | Kuning (`text-warning`) | `{tanggal} — {$diff} hari lagi` |
| `$diff > 3` | Hijau (`text-success`) | `{tanggal} — {$diff} hari lagi` |

Contoh implementasi di View Yii (PHP):

```php
<?php
$deadline  = new DateTime($model->deadline);
$today     = new DateTime(date('Y-m-d'));
$interval  = $today->diff($deadline);
$diff      = $deadline >= $today ? (int)$interval->days : -(int)$interval->days;

if ($diff < 0) {
    echo '<span class="text-danger">'
       . Yii::$app->formatter->asDate($model->deadline, 'dd MMM yyyy')
       . ' — Terlambat ' . abs($diff) . ' hari</span>';
} elseif ($diff === 0) {
    echo '<span class="text-warning">'
       . Yii::$app->formatter->asDate($model->deadline, 'dd MMM yyyy')
       . ' — Hari ini!</span>';
} elseif ($diff <= 3) {
    echo '<span class="text-warning">'
       . Yii::$app->formatter->asDate($model->deadline, 'dd MMM yyyy')
       . ' — ' . $diff . ' hari lagi</span>';
} else {
    echo '<span class="text-success">'
       . Yii::$app->formatter->asDate($model->deadline, 'dd MMM yyyy')
       . ' — ' . $diff . ' hari lagi</span>';
}

if ($model->is_holiday) {
    echo '<div class="mt-1">
            <span class="badge bg-warning text-dark rounded-2">
                Hari Libur: ' . Html::encode($model->holiday_name) . '
            </span>
          </div>';
}
?>
```

### 8.3 Status Kumpul Mahasiswa

| Kondisi | Status |
|---|---|
| `waktu_kumpul <= deadline` | Tepat Waktu |
| `waktu_kumpul > deadline` | Terlambat |

---

## 9. Mekanisme Sinkronisasi & OOP

### 9.1 Alur Proses Pengumpulan

1. Mahasiswa login → buka halaman detail tugas → isi form pengumpulan (file atau link)
2. Mahasiswa klik tombol **Kirim**
3. Controller `PengumpulanTugasController::actionCreate()` menerima request
4. Model `PengumpulanTugas::beforeSave()` dijalankan otomatis → hitung `status_kumpul`
5. `TimestampBehavior` Yii mengisi `waktu_kumpul` otomatis dengan waktu server saat ini
6. Data baru tersimpan ke tabel `pengumpulan_tugas`
7. Di sisi admin, kolom progress **otomatis berubah** karena menggunakan COUNT() langsung dari database

### 9.2 Tanggung Jawab Setiap Model (OOP)

#### Model `Tugas`

```php
// Relasi ke pengumpulan
public function getPengumpulans()
{
    return $this->hasMany(PengumpulanTugas::class, ['tugas_id' => 'id']);
}

// Hitung progress pengumpulan (tidak pakai angka statis)
public function getProgressPengumpulan()
{
    $totalMahasiswa  = Mahasiswa::find()->count();
    $sudahKumpul     = PengumpulanTugas::find()
                         ->where(['tugas_id' => $this->id])
                         ->count();
    return $sudahKumpul . ' / ' . $totalMahasiswa . ' Mahasiswa';
}

// Cek apakah tugas sudah selesai (semua kumpul)
public function getIsSelesai()
{
    $totalMahasiswa = Mahasiswa::find()->count();
    $sudahKumpul    = PengumpulanTugas::find()
                        ->where(['tugas_id' => $this->id])
                        ->count();
    return $sudahKumpul >= $totalMahasiswa;
}

// Cek apakah deadline adalah hari libur (via Holidays API)
public function checkHoliday()
{
    // implementasi lihat bagian 10
}
```

#### Model `PengumpulanTugas`

```php
public function behaviors()
{
    return [
        TimestampBehavior::class, // otomatis isi waktu_kumpul
    ];
}

public function beforeSave($insert)
{
    if (parent::beforeSave($insert) && $insert) {
        $tugas = Tugas::findOne($this->tugas_id);
        $deadline      = new DateTime($tugas->deadline . ' 23:59:59');
        $waktuKumpul   = new DateTime(); // sekarang

        $this->status_kumpul = ($waktuKumpul <= $deadline)
            ? 'Tepat Waktu'
            : 'Terlambat';
    }
    return true;
}
```

### 9.3 Prinsip OOP yang Diterapkan

| Prinsip | Implementasi |
|---|---|
| **Encapsulation** | Logika bisnis (hitung progress, cek holiday, kalkulasi status) dikapsulasi di dalam Model, bukan di Controller atau View |
| **Inheritance** | Model extends `yii\db\ActiveRecord`, Controller extends `yii\web\Controller` |
| **Polymorphism** | Method `behaviors()` di-override di setiap model sesuai kebutuhan |
| **Single Responsibility** | Controller hanya urus alur request-response; kalkulasi ada di Model |

---

## 10. Integrasi API Eksternal — Holidays API

### 10.1 Sumber API

```
GET https://libur.deno.dev/api?year={YYYY}
```

### 10.2 Kapan API Dipanggil

API dipanggil **satu kali** saat dosen menginput atau mengedit field `deadline` pada form tugas. Hasilnya langsung disimpan ke kolom `is_holiday` dan `holiday_name` di tabel `tugas`, sehingga tidak ada hit API ulang setiap kali halaman dirender.

### 10.3 Implementasi di Model Tugas

```php
public function checkHoliday()
{
    $year     = date('Y', strtotime($this->deadline));
    $cacheKey = 'holidays_' . $year;

    // Cek cache dulu supaya tidak hit API terus
    $holidays = Yii::$app->cache->get($cacheKey);
    if ($holidays === false) {
        $url      = 'https://libur.deno.dev/api?year=' . $year;
        $response = file_get_contents($url);
        $holidays = json_decode($response, true);
        // Cache selama 30 hari (liburan nasional tidak berubah di tengah tahun)
        Yii::$app->cache->set($cacheKey, $holidays, 60 * 60 * 24 * 30);
    }

    $deadlineStr        = date('Y-m-d', strtotime($this->deadline));
    $this->is_holiday   = 0;
    $this->holiday_name = null;

    foreach ($holidays as $holiday) {
        if (isset($holiday['date']) && $holiday['date'] === $deadlineStr) {
            $this->is_holiday   = 1;
            $this->holiday_name = $holiday['name'] ?? 'Hari Libur';
            break;
        }
    }
}
```

### 10.4 Kapan `checkHoliday()` Dipanggil

```php
// Di Model Tugas
public function beforeSave($insert)
{
    if (parent::beforeSave($insert)) {
        if ($this->isAttributeChanged('deadline') || $insert) {
            $this->checkHoliday();
        }
        return true;
    }
    return false;
}
```

---

## 11. REST API Endpoint

Semua endpoint menggunakan format JSON. Base controller mengextends `yii\rest\ActiveController`.

### 11.1 Endpoint Tugas

| Method | Endpoint | Deskripsi | Role |
|---|---|---|---|
| GET | `/api/tugas` | List semua tugas | Admin |
| GET | `/api/tugas/{id}` | Detail tugas + progress | Admin |
| POST | `/api/tugas` | Tambah tugas baru | Admin |
| PUT | `/api/tugas/{id}` | Edit tugas | Admin |
| DELETE | `/api/tugas/{id}` | Hapus tugas | Admin |

### 11.2 Endpoint Mahasiswa

| Method | Endpoint | Deskripsi | Role |
|---|---|---|---|
| GET | `/api/mahasiswa` | List semua mahasiswa | Admin |
| GET | `/api/mahasiswa/{id}` | Detail mahasiswa | Admin |

### 11.3 Endpoint Pengumpulan

| Method | Endpoint | Deskripsi | Role |
|---|---|---|---|
| GET | `/api/pengumpulan` | List semua pengumpulan | Admin |
| GET | `/api/pengumpulan?tugas_id={id}` | Pengumpulan per tugas | Admin |
| POST | `/api/pengumpulan` | Submit pengumpulan tugas | User |

### 11.4 Konfigurasi URL Rule di `config/web.php`

```php
'urlManager' => [
    'enablePrettyUrl' => true,
    'enableStrictParsing' => true,
    'showScriptName' => false,
    'rules' => [
        ['class' => 'yii\rest\UrlRule', 'controller' => 'api/tugas'],
        ['class' => 'yii\rest\UrlRule', 'controller' => 'api/mahasiswa'],
        ['class' => 'yii\rest\UrlRule', 'controller' => 'api/pengumpulan'],
    ],
],
```

---

## 12. SOAP Web Service

### 12.1 Endpoint

```
GET /soap/tugas?wsdl    → WSDL definition
POST /soap/tugas        → SOAP request
```

### 12.2 Method yang Di-expose

| Method | Parameter | Return | Keterangan |
|---|---|---|---|
| `getTugasByMatkul` | `int $matkul_id` | Array tugas | Ambil tugas berdasarkan matkul |
| `getPengumpulanByTugas` | `int $tugas_id` | Array pengumpulan | Ambil daftar pengumpulan per tugas |

### 12.3 Implementasi di Yii

```php
// controllers/SoapController.php
class SoapController extends \yii\web\Controller
{
    public function actionTugas()
    {
        $server = new \SoapServer(null, [
            'uri' => Yii::$app->request->absoluteUrl,
        ]);
        $server->setObject(new TugasSoapService());
        $server->handle();
        Yii::$app->response->isSent = true;
    }
}

// services/TugasSoapService.php
class TugasSoapService
{
    public function getTugasByMatkul(int $matkul_id): array
    {
        return Tugas::find()
            ->where(['matkul_id' => $matkul_id])
            ->asArray()
            ->all();
    }

    public function getPengumpulanByTugas(int $tugas_id): array
    {
        return PengumpulanTugas::find()
            ->where(['tugas_id' => $tugas_id])
            ->joinWith('mahasiswa')
            ->asArray()
            ->all();
    }
}
```

---

## 13. Spesifikasi Tampilan Frontend

### 13.1 Umum

- Semua halaman menggunakan **Bootstrap 5**
- Responsif mobile-friendly menggunakan Bootstrap grid (`col-md-*`, `col-sm-*`, `col-12`)
- Sidebar atau navbar berbeda antara role Admin dan role User
- Warna badge status tugas: `badge bg-warning text-dark` untuk Pending, `badge bg-success` untuk Selesai
- Warna badge status kumpul: `badge bg-success` untuk Tepat Waktu, `badge bg-danger` untuk Terlambat

### 13.2 Halaman Login

```
[Form Login — satu halaman untuk semua role]
  - Input: Username
  - Input: Password
  - Tombol: Login
  - Link: "Belum punya akun? Daftar di sini" (hanya untuk mahasiswa)

Catatan: Tidak ada tombol "Daftar" untuk admin.
Akun admin sudah tersedia di database sejak awal.
```

### 13.3 Halaman Admin (Dosen)

**Index Tugas** — kolom tabel:

```
No | Judul Tugas | Dosen | Matkul | Deadline (warna) | Progress (X/52) | Status (badge) | Aksi (Show|Edit|Hapus)
```

**Show Tugas** — struktur halaman:

```
[Card: Informasi Tugas]
  - Judul Tugas
  - Deskripsi
  - Dosen Pengampu
  - Mata Kuliah
  - Deadline + indikator warna + badge hari libur (jika ada)

[Card: Daftar Mahasiswa yang Sudah Mengumpulkan]
  [Tabel]
  No | Nama Mahasiswa | NIM | Waktu Kumpul | Status Kumpul (badge)
```

### 13.4 Halaman User (Mahasiswa)

**Dashboard Mahasiswa** — layout:

```
[Row: 2 Card Ringkasan]
  [Card: Belum Dikumpul]   [Card: Sudah Dikumpul]
  Angka besar              Angka besar

[Section: Daftar Tugas]
  List/tabel tugas + deadline + tombol "Lihat Detail"
```

**Detail & Form Pengumpulan** — struktur:

```
[Card: Detail Tugas]
  - Judul, Deskripsi, Dosen, Matkul, Deadline

[Card: Form Pengumpulan]  (tampil jika belum pernah kumpul)
  - Input: Upload File (PDF/ZIP)
  - Input: Link Drive (alternatif)
  - Tombol: Kirim Tugas

[Alert: Sudah Dikumpulkan]  (tampil jika sudah pernah kumpul)
  - Waktu kumpul
  - Status kumpul (Tepat Waktu / Terlambat)
```

---

## 14. Catatan Implementasi OOP

### 14.1 Struktur Model yang Dianjurkan

Setiap tabel memiliki Model Yii ActiveRecord sendiri dengan:
- Method `rules()` untuk validasi input
- Method `attributeLabels()` untuk label form
- Relasi `hasOne()` / `hasMany()` ke tabel terkait
- Business logic dikapsulasi dalam method model (bukan controller)

### 14.2 Penggunaan Yii Behaviors

```php
// Otomatis isi waktu_kumpul tanpa intervensi manual
use yii\behaviors\TimestampBehavior;
use yii\db\Expression;

public function behaviors()
{
    return [
        [
            'class'              => TimestampBehavior::class,
            'createdAtAttribute' => 'waktu_kumpul',
            'updatedAtAttribute' => false,
            'value'              => new Expression('NOW()'),
        ],
    ];
}
```

### 14.3 Caching API Response

Gunakan komponen `cache` bawaan Yii untuk menyimpan respons Holidays API per tahun kalender, sehingga API hanya dipanggil satu kali per tahun dan tidak membebani server eksternal.

```php
// Aktifkan di config/web.php
'components' => [
    'cache' => [
        'class' => 'yii\caching\FileCache',
    ],
]
```

### 14.4 Validasi Duplikasi Pengumpulan

Tambahkan validasi di Model `PengumpulanTugas` untuk mencegah mahasiswa mengumpulkan tugas yang sama lebih dari sekali:

```php
public function rules()
{
    return [
        [['tugas_id', 'mahasiswa_id'], 'unique',
            'targetAttribute' => ['tugas_id', 'mahasiswa_id'],
            'message'         => 'Kamu sudah pernah mengumpulkan tugas ini.'],
    ];
}
```

---

*Dokumen ini merupakan spesifikasi teknis untuk pengembangan Sistem Manajemen Tugas Perkuliahan v2.0. Semua keputusan implementasi yang tidak tercakup di sini mengikuti konvensi Yii Framework.*
