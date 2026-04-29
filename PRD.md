# PRD — Sistem Pengumuman Berbasis Web
**Versi:** 1.0  
**Tanggal:** April 2026  
**Status:** Ready for Development

---

## 1. Product Overview

### Ringkasan Produk
Website pengumuman berbasis web untuk sekolah.  
Admin membuat pengumuman kelulusan, peserta cek hasil menggunakan NISN.

### Tujuan
- Admin bisa kelola banyak pengumuman dari satu panel.
- Admin bisa atur kapan pengumuman boleh dibuka publik.
- Peserta cek kelulusan hanya dengan memasukkan NISN.
- Sistem ringan, bisa jalan di shared hosting Niagahoster.

### Target User

| Role  | Siapa                        | Jumlah       |
|-------|------------------------------|--------------|
| Admin | Guru / TU / Operator sekolah | 1–5 orang    |
| User  | Siswa / Orang tua            | 300–400 saat bersamaan |

### Tech Stack

| Komponen     | Pilihan              |
|--------------|----------------------|
| Backend      | Laravel 11           |
| Database     | MySQL 8              |
| Admin Panel  | Filament 3           |
| Frontend     | Blade + Tailwind CSS |
| Hosting      | Shared Hosting (cPanel) |

---

## 2. User Flow

### Flow Admin
```
Login ke /admin
  └── Buat Pengumuman baru (judul, deskripsi, tanggal_buka)
        └── Upload CSV peserta untuk pengumuman itu
              └── Preview data peserta yang terupload
                    └── Publish / aktifkan pengumuman
```

### Flow User (Peserta)
```
Buka halaman utama /
  └── Pilih pengumuman dari daftar
        ├── [Belum waktunya] → Tampil countdown atau pesan "Belum dibuka"
        └── [Sudah waktunya] → Tampil form input NISN
                                    └── Submit NISN
                                          ├── NISN ditemukan → Tampil nama + keterangan (LULUS/TIDAK LULUS)
                                          └── NISN tidak ada → Tampil pesan "Data tidak ditemukan"
```

---

## 3. Fitur Detail (Functional Requirements)

### Fitur 1: Multi Pengumuman

**Deskripsi:** Admin bisa membuat banyak pengumuman. Setiap pengumuman berdiri sendiri.

**Step-by-step:**
1. Admin login ke `/admin`.
2. Klik menu "Pengumuman" → "Tambah Baru".
3. Isi form:
   - Judul (contoh: "Pengumuman Kelulusan Kelas XII TA 2025/2026")
   - Deskripsi (opsional, contoh: "Silakan cek hasil kelulusan Anda.")
   - Tanggal & Jam Buka (contoh: `2026-05-20 08:00:00`)
   - Status: `draft` atau `published`
4. Simpan.
5. Pengumuman muncul di daftar.

**Output:**  
- Pengumuman tersimpan di tabel `announcements`.
- Jika status `published` dan tanggal sudah lewat → tampil di halaman publik.

---

### Fitur 2: Sistem Waktu Buka

**Deskripsi:** Pengumuman hanya bisa diakses setelah `tanggal_buka`.

**Logika:**
```
sekarang = now()
jika sekarang < tanggal_buka:
    → tampil pesan "Pengumuman belum dibuka"
    → opsional: tampil countdown timer
jika sekarang >= tanggal_buka:
    → tampil form input NISN
```

**Contoh kode Laravel (di Controller):**
```php
public function show(Announcement $announcement)
{
    $now = now();

    if ($now->lt($announcement->tanggal_buka)) {
        return view('announcement.countdown', [
            'announcement' => $announcement,
            'openAt' => $announcement->tanggal_buka,
        ]);
    }

    return view('announcement.check', [
        'announcement' => $announcement,
    ]);
}
```

---

### Fitur 3: Cek Hasil Berdasarkan NISN

**Deskripsi:** User input NISN, sistem cari di tabel peserta berdasarkan pengumuman yang dipilih.

**Input:** NISN (angka, 10 digit)  
**Output:** Nama + Keterangan

**Step-by-step:**
1. User buka halaman pengumuman yang sudah dibuka.
2. User isi kolom NISN lalu klik "Cek".
3. Sistem cari NISN di tabel `participants` dengan filter `announcement_id`.
4. Jika ditemukan → tampil kartu hasil.
5. Jika tidak ditemukan → tampil pesan error.

**Contoh kode Laravel (di Controller):**
```php
public function checkNisn(Request $request, Announcement $announcement)
{
    $request->validate([
        'nisn' => ['required', 'digits:10'],
    ]);

    $participant = $announcement->participants()
        ->where('nisn', $request->nisn)
        ->first();

    if (!$participant) {
        return back()->with('error', 'NISN tidak ditemukan dalam pengumuman ini.');
    }

    return view('announcement.result', [
        'participant' => $participant,
        'announcement' => $announcement,
    ]);
}
```

**Contoh output kartu hasil:**
```
┌─────────────────────────────────┐
│  Pengumuman Kelulusan XII 2026  │
│─────────────────────────────────│
│  Nama   : Ahmad Fauzi           │
│  NISN   : 0123456789            │
│  Kelas  : XII IPA 1             │
│  Status : ✅ LULUS              │
└─────────────────────────────────┘
```

---

### Fitur 4: Import Data Peserta via CSV

**Deskripsi:** Admin upload file CSV berisi data peserta untuk satu pengumuman.

**Format CSV yang diterima:**
```csv
nisn,nama,kelas,keterangan
0123456789,Ahmad Fauzi,XII IPA 1,LULUS
0987654321,Budi Santoso,XII IPS 2,TIDAK LULUS
1122334455,Citra Dewi,XII IPA 2,LULUS
```

**Step-by-step:**
1. Admin buka detail pengumuman.
2. Klik tab "Peserta" → "Import CSV".
3. Upload file `.csv`.
4. Sistem validasi format kolom (nisn, nama, kelas, keterangan).
5. Jika valid → data masuk ke tabel `participants` dengan `announcement_id` yang sesuai.
6. Jika ada error → tampil pesan baris mana yang bermasalah.

**Aturan validasi CSV:**
- Kolom wajib: `nisn`, `nama`, `kelas`, `keterangan`
- `nisn` harus unik per pengumuman
- `keterangan` bebas teks (LULUS / TIDAK LULUS / dll)
- Baris kosong diabaikan

**Contoh kode import (di Filament Action atau Controller):**
```php
$rows = array_map('str_getcsv', file($file->getRealPath()));
$header = array_shift($rows); // ambil baris pertama sebagai header

foreach ($rows as $index => $row) {
    $data = array_combine($header, $row);

    Participant::updateOrCreate(
        [
            'nisn'            => $data['nisn'],
            'announcement_id' => $announcementId,
        ],
        [
            'nama'        => $data['nama'],
            'kelas'       => $data['kelas'],
            'keterangan'  => $data['keterangan'],
        ]
    );
}
```

> **Catatan:** Gunakan `updateOrCreate` agar re-upload CSV tidak duplikasi data.

---

### Fitur 5: Admin Panel (Filament)

**Halaman yang tersedia di Filament:**

| Menu           | Fungsi                                         |
|----------------|------------------------------------------------|
| Pengumuman     | CRUD pengumuman (judul, deskripsi, tanggal_buka, status) |
| Peserta        | Lihat data peserta per pengumuman, Import CSV  |
| Login Admin    | Autentikasi admin (`/admin/login`)             |

---

## 4. Data Model

### Tabel: `announcements`

| Kolom         | Tipe Data         | Keterangan                          |
|---------------|-------------------|-------------------------------------|
| id            | BIGINT (PK)       | Auto increment                      |
| judul         | VARCHAR(255)      | Judul pengumuman                    |
| deskripsi     | TEXT (nullable)   | Deskripsi / instruksi               |
| tanggal_buka  | DATETIME          | Waktu pengumuman boleh diakses      |
| status        | ENUM('draft','published') | draft = tersembunyi, published = tampil |
| created_at    | TIMESTAMP         | Auto                                |
| updated_at    | TIMESTAMP         | Auto                                |

**Contoh isi data:**
```
id=1, judul="Kelulusan XII 2026", tanggal_buka="2026-05-20 08:00:00", status="published"
id=2, judul="Kelulusan X Try Out", tanggal_buka="2026-06-01 10:00:00", status="draft"
```

---

### Tabel: `participants`

| Kolom           | Tipe Data        | Keterangan                          |
|-----------------|------------------|-------------------------------------|
| id              | BIGINT (PK)      | Auto increment                      |
| announcement_id | BIGINT (FK)      | Relasi ke `announcements.id`        |
| nisn            | VARCHAR(20)      | Nomor Induk Siswa Nasional          |
| nama            | VARCHAR(255)     | Nama lengkap siswa                  |
| kelas           | VARCHAR(50)      | Kelas siswa (XII IPA 1, dll)        |
| keterangan      | VARCHAR(100)     | LULUS / TIDAK LULUS / teks bebas    |
| created_at      | TIMESTAMP        | Auto                                |
| updated_at      | TIMESTAMP        | Auto                                |

**Index:**
- UNIQUE(`nisn`, `announcement_id`) — satu NISN hanya sekali per pengumuman

**Contoh isi data:**
```
id=1, announcement_id=1, nisn="0123456789", nama="Ahmad Fauzi", kelas="XII IPA 1", keterangan="LULUS"
id=2, announcement_id=1, nisn="0987654321", nama="Budi Santoso", kelas="XII IPS 2", keterangan="TIDAK LULUS"
```

---

### Tabel: `users` (Admin)

| Kolom      | Tipe Data    | Keterangan         |
|------------|--------------|--------------------|
| id         | BIGINT (PK)  | Auto increment     |
| name       | VARCHAR(255) | Nama admin         |
| email      | VARCHAR(255) | Email login        |
| password   | VARCHAR(255) | Bcrypt hash        |
| created_at | TIMESTAMP    | Auto               |
| updated_at | TIMESTAMP    | Auto               |

> Gunakan tabel `users` bawaan Laravel. Filament otomatis pakai ini.

---

### Relasi Antar Tabel

```
announcements (1) ───< (many) participants
     │
     └── announcement_id di participants adalah FK ke announcements.id
         ON DELETE CASCADE (hapus pengumuman → hapus semua pesertanya)
```

---

### Migration Penting

```php
// participants table
Schema::create('participants', function (Blueprint $table) {
    $table->id();
    $table->foreignId('announcement_id')->constrained()->cascadeOnDelete();
    $table->string('nisn', 20);
    $table->string('nama');
    $table->string('kelas', 50);
    $table->string('keterangan', 100);
    $table->timestamps();

    $table->unique(['nisn', 'announcement_id']); // cegah duplikat
});
```

---

## 5. Logic Flow

### Alur Cek NISN (Pseudocode)

```
REQUEST: POST /pengumuman/{id}/cek
INPUT: nisn

1. Validasi input
   - nisn harus ada
   - nisn harus 10 digit angka

2. Ambil data pengumuman berdasarkan {id}
   - Jika tidak ada → 404

3. Cek apakah pengumuman sudah dibuka
   - Jika now() < tanggal_buka → redirect ke halaman countdown

4. Cari peserta
   SELECT * FROM participants
   WHERE nisn = '{input_nisn}'
   AND announcement_id = {id}
   LIMIT 1

5. Jika tidak ditemukan → tampil error "NISN tidak ditemukan"
6. Jika ditemukan → tampil kartu hasil (nama, kelas, keterangan)
```

---

### Alur Import CSV (Pseudocode)

```
REQUEST: POST /admin/announcements/{id}/import
INPUT: file CSV

1. Validasi file
   - Ekstensi harus .csv
   - Ukuran maks 2MB

2. Baca baris pertama sebagai header
   - Cek kolom: nisn, nama, kelas, keterangan harus ada

3. Loop setiap baris
   - Lewati baris kosong
   - updateOrCreate berdasarkan (nisn + announcement_id)

4. Tampil ringkasan: "X data berhasil diimport, Y baris dilewati"
```

---

## 6. UI Pages

### Halaman Publik

#### `/` — Halaman Utama (Daftar Pengumuman)
- Header: nama sekolah / logo
- Daftar card pengumuman yang status = `published`
- Setiap card: judul, deskripsi singkat, tombol "Lihat Pengumuman"
- Jika tidak ada pengumuman aktif: tampil pesan "Belum ada pengumuman."

---

#### `/pengumuman/{id}` — Detail Pengumuman
**Kondisi A — Belum Dibuka:**
- Judul pengumuman
- Pesan: "Pengumuman akan dibuka pada [tanggal & jam]"
- Countdown timer (opsional, JavaScript sederhana)

**Kondisi B — Sudah Dibuka:**
- Judul pengumuman
- Deskripsi
- Form: kolom NISN + tombol "Cek Kelulusan"

---

#### `/pengumuman/{id}/cek` — Hasil Cek NISN
**Jika ditemukan:**
- Card hasil: nama, kelas, keterangan (dengan warna hijau/merah)
- Tombol "Cek NISN Lain"

**Jika tidak ditemukan:**
- Pesan: "NISN tidak ditemukan dalam pengumuman ini."
- Tombol "Coba Lagi"

---

### Halaman Admin (Filament)

| URL                               | Fungsi                    |
|-----------------------------------|---------------------------|
| `/admin/login`                    | Login admin               |
| `/admin/announcements`            | Daftar semua pengumuman   |
| `/admin/announcements/create`     | Buat pengumuman baru      |
| `/admin/announcements/{id}/edit`  | Edit pengumuman           |
| `/admin/participants`             | Lihat semua peserta       |

> Import CSV bisa dijadikan Custom Action di halaman detail Announcement di Filament.

---

## 7. Edge Cases

### NISN tidak ditemukan
- **Kondisi:** User input NISN yang tidak ada di database untuk pengumuman itu.
- **Tindakan:** Tampil pesan "NISN tidak ditemukan. Pastikan Anda memasukkan NISN yang benar."
- **Jangan tampilkan:** Data peserta lain, pesan teknis error.

---

### Pengumuman belum dibuka
- **Kondisi:** User akses URL pengumuman sebelum `tanggal_buka`.
- **Tindakan:** Redirect ke halaman countdown. Form cek NISN disembunyikan.
- **Keamanan:** Validasi waktu dilakukan di server, bukan hanya di JavaScript.

---

### Pengumuman berstatus `draft`
- **Kondisi:** Pengumuman masih draft, diakses via URL langsung.
- **Tindakan:** Tampil halaman 404 atau "Pengumuman tidak tersedia."

---

### CSV error saat import
| Masalah                         | Tindakan                                        |
|----------------------------------|-------------------------------------------------|
| Kolom tidak lengkap              | Tampil pesan "Format CSV tidak sesuai. Wajib ada kolom: nisn, nama, kelas, keterangan." |
| NISN duplikat di file yang sama  | Gunakan updateOrCreate, data ter-update         |
| File bukan CSV                   | Validasi ekstensi, tolak file                   |
| Baris kosong di tengah file      | Lewati baris kosong, lanjut import              |
| Encoding file tidak UTF-8        | Tampil pesan "Gunakan file CSV dengan encoding UTF-8." |

---

### 300–400 User Bersamaan
- Query cek NISN sudah ada index pada kolom `nisn` + `announcement_id`.
- Halaman cek NISN tidak perlu session khusus, stateless.
- Tidak ada websocket atau polling, jadi aman untuk shared hosting.
- Aktifkan **OPcache** di PHP dan **query cache** di MySQL jika tersedia.

---

## 8. Deployment Notes (Shared Hosting / cPanel)

### Langkah Deploy

1. **Upload file Laravel** ke folder `public_html/namaproyek/` atau subfolder.
2. **Set Document Root** ke folder `public/` via cPanel → Subdomain / Addon Domain.
3. **Buat database MySQL** di cPanel → MySQL Database Wizard.
4. **Isi `.env`:**
   ```env
   APP_ENV=production
   APP_DEBUG=false
   APP_URL=https://domain-kamu.com

   DB_HOST=localhost
   DB_DATABASE=nama_database
   DB_USERNAME=nama_user
   DB_PASSWORD=password_db
   ```
5. **Jalankan via SSH** (jika tersedia):
   ```bash
   php artisan migrate --force
   php artisan config:cache
   php artisan route:cache
   php artisan view:cache
   php artisan storage:link
   ```
6. **Jika tidak ada SSH**, gunakan Script Manager di cPanel atau jalankan via route sementara yang langsung di-disable setelah selesai.

---

### Hal Penting

| Hal                        | Penjelasan                                                    |
|----------------------------|---------------------------------------------------------------|
| PHP versi                  | Pastikan minimal PHP 8.2 (setting di cPanel → PHP Selector)  |
| `storage/` writable        | Pastikan folder `storage/` dan `bootstrap/cache/` bisa ditulis |
| `.env` tidak public        | Jangan upload `.env` ke `public/` folder                     |
| File upload CSV            | Set max upload di PHP: `upload_max_filesize = 10M`           |
| Session                    | Gunakan `SESSION_DRIVER=file` (default, aman untuk shared hosting) |
| Cache                      | Gunakan `CACHE_DRIVER=file` (tidak perlu Redis)              |
| Queue                      | Tidak dipakai (tidak ada email / job async)                  |
| Timezone                   | Set `APP_TIMEZONE=Asia/Jakarta` di `.env`                    |

---

### Struktur Folder Yang Perlu Diperhatikan

```
laravel-project/
├── app/
├── public/          ← ini yang jadi Document Root di cPanel
│   ├── index.php
│   └── storage/     ← hasil dari php artisan storage:link
├── storage/
├── .env             ← JANGAN taruh di public/
└── ...
```

---

### Checklist Sebelum Go Live

- [ ] `APP_DEBUG=false` di `.env`
- [ ] Database sudah di-migrate
- [ ] Storage link sudah dibuat
- [ ] Config, route, dan view sudah di-cache
- [ ] Data admin sudah dibuat (`php artisan make:filament-user`)
- [ ] Test cek NISN dengan data nyata
- [ ] Test import CSV dengan file real
- [ ] Test halaman countdown dengan tanggal yang belum lewat

---

*Dokumen ini siap digunakan sebagai acuan coding langsung. Tidak perlu tambahan arsitektur kompleks.*
