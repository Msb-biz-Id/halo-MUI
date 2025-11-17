# Business Requirements Document (BRD)

## Website Magang Clone Kemenag UI

### 1. Pendahuluan

Website ini bertujuan untuk menyediakan platform bagi pengguna yang ingin mengakses informasi keagamaan, menggunakan sistem help desk untuk pengajuan sertifikat halal secara mandiri, membaca fatwa, mengakses materi moderasi-toleransi, perpustakaan digital, forum diskusi, dan fitur chatbot AI. Sistem ini juga terintegrasi dengan WhatsApp untuk komunikasi langsung. Sistem ini akan memiliki beberapa peran pengguna dengan hak akses berbeda, termasuk Superadmin, Admin Konten, Admin Sertifikat, dan User. Sistem akan dibangun menggunakan PHP Native, MySQL, Apache Server, dengan frontend News5 dan backend Morvin_HTML_v1.2.0. Fitur keamanan tambahan seperti Multi-Factor Authentication (MFA) dan audit logging juga diimplementasikan.

### 2. Teknologi yang Digunakan

- **Backend**: PHP Native (OOP/MVC), MySQL, Apache Server
- **Frontend**: News5 Template (untuk tampilan UI)
- **Admin Panel**: Morvin_HTML_v1.2.0 Template
- **Database**: MySQL
- **Framework**: PHP Native dengan arsitektur OOP/MVC
- **AI Integration**: Google Gemini AI untuk chatbot dan WhatsApp bot
- **Authentication**: Multi-Factor Authentication (MFA) menggunakan TOTP

#### Additional Libraries:
- **PHPSpreadsheet**: Untuk export data laporan ke format Excel
- **PHPMailer**: Untuk pengiriman email notifikasi dan konfirmasi
- **Google Gemini PHP Client**: Untuk integrasi AI chatbot
- **OTPHP**: Untuk implementasi TOTP MFA

### 3. Deskripsi Sistem dan Fitur

#### 3.1 Frontend (Pengguna)

##### Beranda:
- Header dengan logo, menu navigasi (MegaMenu), ikon media sosial, tombol "Hubungi Kami".
- Slide pengenalan platform dan daftar mitra serta sponsor.
- Kolom utama untuk konten dan sidebar untuk iklan, artikel terbaru, dan terpopuler.
- Footer: Informasi kontak, sosial media, dan informasi lainnya.

##### Tanya Jawab Keagamaan:
- Sidebar Kiri: Menampilkan kategori topik keagamaan.
- Main Area Kanan: Menampilkan daftar pertanyaan dan jawaban berdasarkan kategori dengan urutan terbaru dan terpopuler.
- Sticky "WhatsApp": Pengguna dapat menghubungi MUI melalui WhatsApp.
- Setiap kategori memiliki daftar pertanyaan yang dapat di klik untuk melihat jawaban lengkap, gambar, atau video.
- Fitur pencarian untuk mempermudah navigasi.

##### Help Desk Pengajuan Sertifikat Halal:
- Sistem help desk terintegrasi untuk pengajuan sertifikat halal secara mandiri.
- Pengguna dapat membuat tiket pengajuan melalui website MUI pusat atau website ini.
- Formulir pengajuan dengan kategori produk, informasi perusahaan, dan dokumen pendukung.
- Tracking status pengajuan real-time dengan nomor tiket unik.
- Sistem notifikasi otomatis untuk update status pengajuan.
- Chat support terintegrasi untuk konsultasi selama proses pengajuan.
- Download sertifikat halal setelah approval melalui portal pengguna.
- Formulir Pengajuan: Nama perusahaan, alamat, kontak, kategori produk, deskripsi produk, dokumen legal, dan spesifikasi halal.

##### Informasi Fatwa:
- Menampilkan daftar fatwa yang dapat dipilih untuk melihat detailnya.
- Setiap fatwa dilengkapi dengan meta informasi (penulis, editor, dll) serta komentar (Facebook Comments atau internal comments).
- Halaman Detail: Menampilkan judul, konten, gambar unggulan, serta konten terkait lainnya.

##### Materi Moderasi-Toleransi:
- Menampilkan artikel dan video tentang moderasi dan toleransi.
- Artikel dapat berupa teks, foto, atau video.
- Detail Post: Menampilkan gambar unggulan, video, konten lengkap, penulis, dan editor, serta komentar.

##### Perpustakaan Digital:
- Filter: Memungkinkan pengguna untuk mencari buku berdasarkan kategori dan tahun.
- Grid Buku: Menampilkan cover buku dan tombol "Baca Sekarang" yang mengarah ke file buku dalam format PDF atau browser.
- Setiap buku memiliki metadata yang mencakup penulis, kategori, dan tahun penerbitan.

##### Forum Diskusi:
- Kategori forum untuk topik diskusi keagamaan.
- Topik dan posting dalam forum dengan fitur view count dan last post tracking.
- Pengguna dapat membuat topik baru dan berpartisipasi dalam diskusi.

##### Chat Internal:
- Sistem pesan internal antar pengguna dan admin.
- Fitur read/unread status untuk pesan.
- Memungkinkan komunikasi pribadi terkait pengajuan sertifikat atau pertanyaan lainnya.

##### Notifikasi:
- Sistem notifikasi real-time untuk pengguna.
- Notifikasi tentang status pengajuan sertifikat, konten baru, dll.
- Tautan ke halaman terkait dalam notifikasi.

##### Integrasi WhatsApp:
- Bot WhatsApp menggunakan Gemini AI untuk menjawab pertanyaan pengguna.
- Mode percakapan dengan history chat.
- Pengguna dapat menghubungi MUI melalui WhatsApp untuk informasi cepat.

##### Chatbot AI:
- Chatbot berbasis Gemini AI untuk menjawab pertanyaan keagamaan.
- Interface chat di website dengan history percakapan.
- Fitur untuk beralih mode atau topik.

##### Dashboard Pengguna:
- **Grup Menu Berdasarkan Fitur**:
  - **Help Desk Sertifikat**: Pengajuan baru, tracking status, riwayat pengajuan, dokumen terkirim.
  - **Forum & Komunikasi**: Topik forum aktif, pesan internal, notifikasi, diskusi terbaru.
  - **Konten & Edukasi**: Tanya jawab, fatwa, materi moderasi, perpustakaan digital.
  - **Akun & Pengaturan**: Profil lengkap, notifikasi, riwayat aktivitas, pengaturan privasi.
- **Quick Actions**: Shortcut untuk pengajuan sertifikat baru, membuat posting forum, cek status.
- **Recent Activity**: Timeline aktivitas terbaru di semua fitur.
- **Notifications Center**: Semua notifikasi terpusat dengan filter berdasarkan kategori.

##### Kelola Profil Lengkap:
- **Informasi Pribadi**: Nama lengkap, email, nomor telepon, tanggal lahir, jenis kelamin.
- **Informasi Perusahaan**: Nama perusahaan, alamat, NPWP, bidang usaha (untuk pemohon sertifikat).
- **Pengaturan Akun**: Ubah password, email notifikasi, preferensi bahasa.
- **Dokumen Profil**: Upload foto profil, KTP, dokumen perusahaan.
- **Riwayat Aktivitas**: Log semua aktivitas pengguna di platform.
- **Verifikasi Akun**: Sistem verifikasi email dan nomor telepon.

##### Lupa Password & Recovery:
- **Forgot Password Flow**: Form reset password dengan verifikasi email.
- **Security Questions**: Pertanyaan keamanan sebagai backup recovery.
- **Email Verification**: Link reset password dikirim ke email terdaftar.
- **Password Strength**: Validasi kekuatan password baru.
- **Recovery Audit**: Log semua aktivitas reset password untuk keamanan.

#### 3.2 Backend (Admin Panel)

##### Superadmin Panel:

- **Dashboard**: Menampilkan statistik pengunjung, jumlah konten, status pengajuan sertifikat, dan data pengguna.
- **Manajemen Pengguna**:
  - CRUD untuk pengguna, termasuk pembuatan dan pengelolaan role.
  - Pengelolaan akses pengguna melalui role-based access control (RBAC).
  - Manajemen password dan reset.
- **Manajemen Konten**:
  - CRUD untuk pertanyaan dan jawaban pada modul Tanya Jawab Keagamaan.
  - CRUD untuk konten Fatwa dan Materi Moderasi-Toleransi.
  - Pengelolaan kategori, tag, dan media (gambar, video).
- **Manajemen Help Desk Sertifikat**:
  - Dashboard help desk dengan tracking semua tiket pengajuan sertifikat.
  - Sistem ticketing dengan kategori, priority, dan SLA management.
  - Assignment tiket ke admin sertifikat yang relevan.
  - Chat internal untuk diskusi antar admin tentang pengajuan kompleks.
  - Workflow approval dengan multiple level review.
  - Generate dan kirim sertifikat halal dalam format PDF setelah approval.
  - Reporting dan analytics untuk efisiensi help desk.
- **Manajemen Forum dan Komunikasi**:
  - CRUD untuk kategori forum, topik, dan posting.
  - Moderasi konten forum.
  - Manajemen pesan internal antar pengguna.
  - Pengelolaan notifikasi sistem.
- **Manajemen WhatsApp dan Chatbot**:
  - Pengaturan bot WhatsApp dan mode AI.
  - Monitoring history chat pengguna.
  - Pengelolaan pengguna WhatsApp terdaftar.
- **Pengaturan Sistem**:
  - Pengaturan website (nama, deskripsi, logo, favicon).
  - Pengaturan email (template konfirmasi, template reset password).
  - Pengaturan keamanan (firewall, pengaturan login admin, MFA).

##### Admin Konten Panel:

- **Dashboard**: Statistik terkait dengan tanya jawab, fatwa, materi moderasi, dan buku perpustakaan.
- **Manajemen Konten**:
  - CRUD untuk tanya jawab keagamaan, fatwa, dan materi moderasi.
  - Pengelolaan kategori dan tag konten.
- **Pengelolaan Media**:
  - Pengelolaan file media yang digunakan dalam konten seperti gambar, video, dan dokumen.

##### Admin Sertifikat Panel:

- **Dashboard Help Desk**: Overview tiket aktif, SLA compliance, response time metrics, dan workload distribution.
- **Manajemen Tiket Sertifikat**:
  - Inbox tiket dengan filter berdasarkan status, priority, dan kategori produk.
  - Response management dengan template jawaban dan knowledge base integration.
  - Escalation system untuk tiket yang memerlukan approval level lebih tinggi.
  - Document verification workflow dengan checklist persyaratan.
  - Certificate generation dan delivery tracking.
  - Customer satisfaction survey dan feedback collection.

##### Manajemen Pengguna (Admin Superadmin):

- **User Roles Management**:
  - Membuat dan mengatur role pengguna dengan kontrol akses berbasis peran (RBAC).
  - Manajemen pengguna (lihat, edit, hapus).
  - Pengaturan hak akses untuk tiap peran (Superadmin, Admin Konten, Admin Sertifikat, User).
  - User dapat mengakses multiple fitur dengan satu akun (Help Desk Sertifikat, Forum, Komunikasi, dll).

### 4. Keamanan dan Perlindungan Data

#### Keamanan Data Pengguna

##### Enkripsi:
- **Password**: Menggunakan bcrypt atau argon2 untuk hashing password agar aman disimpan.
- **File Sensitif**: Menggunakan enkripsi untuk file yang diunggah, seperti dokumen pengajuan sertifikat halal (misalnya menggunakan AES-256).

##### Autentikasi dan Otorisasi:
- Implementasi Multi-Factor Authentication (MFA) menggunakan TOTP (Time-based One-Time Password) untuk admin dan superadmin.
- Sistem login dengan hash password menggunakan bcrypt.
- Role-Based Access Control (RBAC) dengan permissions yang dapat dikonfigurasi.
- **Forgot Password System**: Reset password melalui email dengan token aman dan kadaluarsa.
- **Email Verification**: Verifikasi akun melalui email untuk keamanan.
- **Security Questions**: Backup recovery menggunakan pertanyaan keamanan.
- **Account Lockout**: Proteksi brute force dengan lockout sementara.

##### Audit Trail:
- Semua aktivitas penting dicatat dalam audit log, seperti perubahan status pengajuan sertifikat atau pengeditan konten oleh admin.

##### Firewall dan Proteksi API:
- Penggunaan firewall untuk melindungi server dari potensi serangan luar.
- Pengaturan API rate-limiting untuk menghindari brute force attacks.

### 5. Fitur Pelaporan dan Statistik yang Lebih Komprehensif

#### Laporan Help Desk Sertifikat:
- Menyediakan laporan tentang jumlah tiket pengajuan per bulan, kategori produk yang paling banyak diajukan, dan tingkat keberhasilan pengajuan (approved/rejected).
- SLA compliance reports, average response time, dan resolution time metrics.
- Workload distribution antar admin sertifikat.
- Customer satisfaction ratings dan feedback analysis.

#### Statistik Interaksi Pengguna:
- Melihat halaman yang paling banyak diakses, waktu rata-rata yang dihabiskan oleh pengguna pada setiap jenis konten, dan feedback atau rating yang diberikan pada setiap konten.

#### Laporan Keuangan (jika pembayaran diterapkan):
- Melihat total pendapatan dan biaya terkait pengajuan sertifikat halal, serta laporan transaksi pengguna.

#### Visualisasi Data:
- Implementasi alat pelaporan seperti Grafana atau Power BI untuk memberikan visualisasi yang mudah dipahami terkait statistik dan pelaporan. Selain itu, menyediakan export data laporan ke format Excel menggunakan PHPSpreadsheet.

### 6. Pengelolaan Hak Akses yang Lebih Fleksibel

#### Attribute-Based Access Control (ABAC):
- Menambahkan level kontrol akses yang lebih fleksibel berdasarkan atribut konten atau pengajuan. Contohnya, admin hanya bisa mengelola konten di kategori tertentu atau sertifikat di wilayah tertentu.

#### Kontrol Akses Berdasarkan Peran dan Data:
- Akses ke data atau konten tertentu dapat dibatasi berdasarkan peran dan atribut pengguna. Misalnya, admin sertifikat hanya bisa melihat pengajuan sertifikat yang relevan dengan produk tertentu.
- User dapat mengakses multiple fitur (Help Desk Sertifikat, Forum, Komunikasi) dengan satu akun berdasarkan role mereka.

#### Hierarki Akses:
- Setiap admin dapat diberikan hak akses berbeda untuk mengelola data, berdasarkan level peran atau kategori spesifik, memungkinkan pembatasan yang lebih ketat.

### 7. Fitur Kolaborasi dan Komunikasi

#### Chat Internal:
- Menyediakan chat internal untuk komunikasi antar pengguna dan admin, memungkinkan mereka untuk berdiskusi tentang pengajuan sertifikat atau pertanyaan-pertanyaan terkait lainnya.

#### Forum Diskusi:
- Forum diskusi untuk pengguna agar dapat berdiskusi dan berbagi pengetahuan mengenai topik-topik agama, fatwa, atau moderasi.

#### Notifikasi Real-Time:
- Fitur push notification atau WhatsApp notification untuk menginformasikan pengguna tentang status terbaru pengajuan sertifikat atau konten yang baru dipublikasikan. Menggunakan PHPMailer untuk mengirim notifikasi email otomatis.

### 8. Fitur Pencarian yang Lebih Kuat

#### Full-Text Search:
- Implementasi full-text search pada MySQL untuk mempermudah pencarian di seluruh konten yang ada di website, termasuk tanya jawab, fatwa, dan materi moderasi.

#### Autocomplete:
- Menambahkan fitur autocomplete pada kolom pencarian untuk memberikan saran berdasarkan query yang dimasukkan oleh pengguna.

#### Pencarian Berdasarkan Kategori:
- Pengguna dapat memfilter hasil pencarian berdasarkan kategori atau tag untuk mempercepat pencarian informasi yang relevan.

### 9. Multibahasa dan Lokalitas

#### Multibahasa:
- Menyediakan opsi bahasa seperti Bahasa Indonesia dan Bahasa Inggris, serta menyediakan kemampuan untuk menambahkan bahasa lain jika diperlukan di masa depan.

#### Penyesuaian Konten Lokal:
- Mengatur format tanggal, waktu, dan mata uang berdasarkan lokasi pengguna. Sistem harus mendukung format lokal yang sesuai dengan negara tempat pengguna berada.

#### Manajemen Terjemahan:
- Menggunakan terjemahan berbasis file (seperti JSON atau XML) yang memungkinkan admin untuk menambahkan atau memperbarui terjemahan konten secara manual.

### 10. Scalability dan Performance

#### Caching:
- Menggunakan Redis atau Memcached untuk caching data yang sering diakses untuk mengurangi beban pada server dan mempercepat waktu respons.

#### Database Optimization:
- Melakukan indexing pada kolom-kolom yang sering digunakan untuk pencarian atau pengurutan data, seperti kolom created_at, status, dan kolom foreign key.

#### Load Balancing:
- Mengimplementasikan load balancing untuk mendistribusikan trafik secara merata ke beberapa server aplikasi, memastikan ketersediaan yang tinggi dan mencegah overload pada server tunggal.

#### Content Delivery Network (CDN):
- Menggunakan CDN untuk mengoptimalkan pengiriman konten statis seperti gambar, video, dan file PDF, sehingga mempercepat waktu muat halaman di seluruh dunia.

#### Auto-Scaling:
- Menyediakan auto-scaling pada server, di mana kapasitas server otomatis meningkat atau menurun sesuai dengan jumlah trafik yang diterima situs.

11. Pedoman Database

Database digunakan untuk menyimpan semua data aplikasi, termasuk pengguna, konten, pengajuan sertifikat, dan log audit. Gunakan MySQL sebagai database utama.

11.1 Struktur Database

Berikut adalah skema database lengkap berdasarkan fitur yang dijelaskan dalam BRD. Database dirancang untuk mendukung akses multi-fitur dengan satu akun pengguna melalui sistem role-based permissions yang fleksibel.

**Tabel roles**

| Column | Type | Description |
|--------|------|-------------|
| id | INT, PRIMARY KEY, AUTO_INCREMENT |  |
| name | VARCHAR(50), UNIQUE | contoh: 'superadmin', 'admin_konten', 'admin_sertifikat', 'user' |
| description | TEXT |  |
| permissions | TEXT | JSON string untuk permissions |

**Tabel users**

| Column | Type | Description |
|--------|------|-------------|
| id | INT, PRIMARY KEY, AUTO_INCREMENT |  |
| username | VARCHAR(50), UNIQUE |  |
| email | VARCHAR(100), UNIQUE |  |
| password | VARCHAR(255) | hashed menggunakan bcrypt |
| role_id | INT, FOREIGN KEY ke roles.id |  |
| is_active | TINYINT(1), DEFAULT 1 |  |
| email_verified | TINYINT(1), DEFAULT 0 | Status verifikasi email |
| phone_verified | TINYINT(1), DEFAULT 0 | Status verifikasi nomor telepon |
| created_at | DATETIME |  |
| updated_at | DATETIME |  |
| mfa_secret | VARCHAR(255) | Secret untuk TOTP MFA |
| mfa_enabled | TINYINT(1), DEFAULT 0 | Status MFA aktif |
| full_name | VARCHAR(100) | Nama lengkap pengguna |
| phone | VARCHAR(20) | Nomor telepon |
| date_of_birth | DATE | Tanggal lahir |
| gender | ENUM('male', 'female') | Jenis kelamin |
| profile_picture | VARCHAR(255) | Path foto profil |
| company_name | VARCHAR(255) | Nama perusahaan |
| company_address | TEXT | Alamat perusahaan |
| company_npwp | VARCHAR(50) | NPWP perusahaan |
| business_field | VARCHAR(100) | Bidang usaha |
| id_card_path | VARCHAR(255) | Path file KTP |
| company_docs_path | JSON | Path dokumen perusahaan |
| security_question_1 | VARCHAR(255) | Pertanyaan keamanan 1 |
| security_answer_1 | VARCHAR(255) | Jawaban keamanan 1 (hashed) |
| security_question_2 | VARCHAR(255) | Pertanyaan keamanan 2 |
| security_answer_2 | VARCHAR(255) | Jawaban keamanan 2 (hashed) |
| last_login | DATETIME | Waktu login terakhir |
| password_reset_token | VARCHAR(255) | Token reset password |
| password_reset_expires | DATETIME | Kadaluarsa token reset |

**Tabel categories**

| Column | Type | Description |
|--------|------|-------------|
| id | INT, PRIMARY KEY, AUTO_INCREMENT |  |
| name | VARCHAR(100) |  |
| type | ENUM('tanya_jawab', 'fatwa', 'materi', 'buku') |  |
| created_at | DATETIME |  |

**Tabel questions_answers**

| Column | Type | Description |
|--------|------|-------------|
| id | INT, PRIMARY KEY, AUTO_INCREMENT |  |
| category_id | INT, FOREIGN KEY ke categories.id |  |
| question | TEXT |  |
| answer | TEXT |  |
| author_id | INT, FOREIGN KEY ke users.id |  |
| created_at | DATETIME |  |
| updated_at | DATETIME |  |
| is_published | TINYINT(1), DEFAULT 1 |  |
| view_count | INT, DEFAULT 0 |  |

**Tabel fatwas**

| Column | Type | Description |
|--------|------|-------------|
| id | INT, PRIMARY KEY, AUTO_INCREMENT |  |
| title | VARCHAR(255) |  |
| content | TEXT |  |
| author_id | INT, FOREIGN KEY ke users.id |  |
| editor_id | INT, FOREIGN KEY ke users.id, NULL |  |
| category_id | INT, FOREIGN KEY ke categories.id |  |
| featured_media_id | INT, FOREIGN KEY ke media.id, NULL |  |
| created_at | DATETIME |  |
| updated_at | DATETIME |  |
| is_published | TINYINT(1), DEFAULT 1 |  |
| view_count | INT, DEFAULT 0 |  |

**Tabel fatwa_comments**

| Column | Type | Description |
|--------|------|-------------|
| id | INT, PRIMARY KEY, AUTO_INCREMENT |  |
| fatwa_id | INT, FOREIGN KEY ke fatwas.id |  |
| user_id | INT, FOREIGN KEY ke users.id |  |
| comment | TEXT |  |
| created_at | DATETIME |  |

**Tabel materials**

| Column | Type | Description |
|--------|------|-------------|
| id | INT, PRIMARY KEY, AUTO_INCREMENT |  |
| title | VARCHAR(255) |  |
| content | TEXT |  |
| type | ENUM('text', 'video') |  |
| video_url | VARCHAR(255), NULL |  |
| author_id | INT, FOREIGN KEY ke users.id |  |
| editor_id | INT, FOREIGN KEY ke users.id, NULL |  |
| category_id | INT, FOREIGN KEY ke categories.id |  |
| featured_media_id | INT, FOREIGN KEY ke media.id, NULL |  |
| created_at | DATETIME |  |
| updated_at | DATETIME |  |
| is_published | TINYINT(1), DEFAULT 1 |  |
| view_count | INT, DEFAULT 0 |  |

**Tabel material_comments**

| Column | Type | Description |
|--------|------|-------------|
| id | INT, PRIMARY KEY, AUTO_INCREMENT |  |
| material_id | INT, FOREIGN KEY ke materials.id |  |
| user_id | INT, FOREIGN KEY ke users.id |  |
| comment | TEXT |  |
| created_at | DATETIME |  |

**Tabel books**

| Column | Type | Description |
|--------|------|-------------|
| id | INT, PRIMARY KEY, AUTO_INCREMENT |  |
| title | VARCHAR(255) |  |
| author | VARCHAR(100) |  |
| category_id | INT, FOREIGN KEY ke categories.id |  |
| year | YEAR |  |
| file_path | VARCHAR(255) |  |
| cover_image | VARCHAR(255), NULL |  |
| created_at | DATETIME |  |

**Tabel certificate_applications**

| Column | Type | Description |
|--------|------|-------------|
| id | INT, PRIMARY KEY, AUTO_INCREMENT |  |
| ticket_number | VARCHAR(20), UNIQUE | Nomor tiket unik untuk tracking |
| user_id | INT, FOREIGN KEY ke users.id |  |
| company_name | VARCHAR(255) | Nama perusahaan pemohon |
| company_address | TEXT | Alamat perusahaan |
| contact_person | VARCHAR(100) | Nama contact person |
| email | VARCHAR(100) |  |
| phone | VARCHAR(20) |  |
| product_category | VARCHAR(100) | Kategori produk (makanan, minuman, kosmetik, dll) |
| product_name | VARCHAR(255) | Nama produk |
| product_description | TEXT | Deskripsi produk |
| certificate_type | VARCHAR(100) | Jenis sertifikat halal |
| documents | JSON | menyimpan path file dokumen |
| status | ENUM('pending', 'in_review', 'approved', 'rejected', 'need_info') |  |
| priority | ENUM('low', 'medium', 'high', 'urgent') | Priority tiket |
| assigned_to | INT, FOREIGN KEY ke users.id, NULL | Admin yang ditugaskan |
| submitted_at | DATETIME |  |
| reviewed_at | DATETIME, NULL |  |
| reviewer_id | INT, FOREIGN KEY ke users.id, NULL |  |
| estimated_completion | DATETIME, NULL | Estimasi waktu selesai |
| notes | TEXT | Catatan internal admin |
| updated_at | DATETIME, DEFAULT CURRENT_TIMESTAMP ON UPDATE CURRENT_TIMESTAMP |  |

**Tabel audit_logs**

| Column | Type | Description |
|--------|------|-------------|
| id | INT, PRIMARY KEY, AUTO_INCREMENT |  |
| user_id | INT, FOREIGN KEY ke users.id |  |
| action | VARCHAR(100) | contoh: 'create', 'update', 'delete' |
| table_name | VARCHAR(50) |  |
| record_id | INT |  |
| timestamp | DATETIME |  |

**Tabel settings**

| Column | Type | Description |
|--------|------|-------------|
| id | INT, PRIMARY KEY, AUTO_INCREMENT |  |
| key_name | VARCHAR(100), UNIQUE |  |
| value | TEXT |  |
| updated_at | DATETIME, DEFAULT CURRENT_TIMESTAMP ON UPDATE CURRENT_TIMESTAMP |  |

**Tabel media**

| Column | Type | Description |
|--------|------|-------------|
| id | INT, PRIMARY KEY, AUTO_INCREMENT |  |
| file_name | VARCHAR(255) |  |
| file_path | VARCHAR(255) |  |
| mime_type | VARCHAR(100) |  |
| alt_text | VARCHAR(255) |  |
| description | TEXT |  |
| uploaded_by | INT, FOREIGN KEY ke users.id |  |
| uploaded_at | DATETIME |  |
| updated_at | DATETIME, DEFAULT CURRENT_TIMESTAMP ON UPDATE CURRENT_TIMESTAMP |  |

**Tabel internal_messages**

| Column | Type | Description |
|--------|------|-------------|
| id | INT, PRIMARY KEY, AUTO_INCREMENT |  |
| sender_id | INT, FOREIGN KEY ke users.id |  |
| receiver_id | INT, FOREIGN KEY ke users.id |  |
| message | TEXT |  |
| is_read | TINYINT(1), DEFAULT 0 |  |
| created_at | DATETIME, DEFAULT CURRENT_TIMESTAMP |  |

**Tabel forum_categories**

| Column | Type | Description |
|--------|------|-------------|
| id | INT, PRIMARY KEY, AUTO_INCREMENT |  |
| name | VARCHAR(100), UNIQUE |  |
| description | TEXT |  |
| created_at | DATETIME, DEFAULT CURRENT_TIMESTAMP |  |

**Tabel forum_topics**

| Column | Type | Description |
|--------|------|-------------|
| id | INT, PRIMARY KEY, AUTO_INCREMENT |  |
| category_id | INT, FOREIGN KEY ke forum_categories.id |  |
| user_id | INT, FOREIGN KEY ke users.id |  |
| title | VARCHAR(255) |  |
| slug | VARCHAR(255), UNIQUE |  |
| views | INT, DEFAULT 0 |  |
| last_post_at | DATETIME, NULL |  |
| created_at | DATETIME, DEFAULT CURRENT_TIMESTAMP |  |
| updated_at | DATETIME, DEFAULT CURRENT_TIMESTAMP ON UPDATE CURRENT_TIMESTAMP |  |

**Tabel forum_posts**

| Column | Type | Description |
|--------|------|-------------|
| id | INT, PRIMARY KEY, AUTO_INCREMENT |  |
| topic_id | INT, FOREIGN KEY ke forum_topics.id |  |
| user_id | INT, FOREIGN KEY ke users.id |  |
| content | TEXT |  |
| created_at | DATETIME, DEFAULT CURRENT_TIMESTAMP |  |
| updated_at | DATETIME, DEFAULT CURRENT_TIMESTAMP ON UPDATE CURRENT_TIMESTAMP |  |

**Tabel notifications**

| Column | Type | Description |
|--------|------|-------------|
| id | INT, PRIMARY KEY, AUTO_INCREMENT |  |
| user_id | INT, FOREIGN KEY ke users.id |  |
| type | VARCHAR(50) |  |
| message | TEXT |  |
| link | VARCHAR(255), NULL |  |
| is_read | TINYINT(1), DEFAULT 0 |  |
| created_at | DATETIME, DEFAULT CURRENT_TIMESTAMP |  |

**Tabel whatsapp_users**

| Column | Type | Description |
|--------|------|-------------|
| id | INT, AUTO_INCREMENT PRIMARY KEY |  |
| whatsapp_id | VARCHAR(255), UNIQUE |  |
| current_mode | VARCHAR(50), DEFAULT 'gemini_ai' |  |
| gemini_history | JSON |  |
| created_at | DATETIME, DEFAULT CURRENT_TIMESTAMP |  |
| updated_at | DATETIME, DEFAULT CURRENT_TIMESTAMP ON UPDATE CURRENT_TIMESTAMP |  |

11.2 Relasi Database

- users.role_id -> roles.id
- questions_answers.category_id -> categories.id
- questions_answers.author_id -> users.id
- fatwas.author_id -> users.id
- fatwas.editor_id -> users.id
- fatwas.category_id -> categories.id
- fatwas.featured_media_id -> media.id
- fatwa_comments.fatwa_id -> fatwas.id
- fatwa_comments.user_id -> users.id
- materials.author_id -> users.id
- materials.editor_id -> users.id
- materials.category_id -> categories.id
- materials.featured_media_id -> media.id
- material_comments.material_id -> materials.id
- material_comments.user_id -> users.id
- books.category_id -> categories.id
- books.cover_media_id -> media.id (jika menggunakan media id)
- certificate_applications.user_id -> users.id
- certificate_applications.reviewer_id -> users.id
- certificate_applications.assigned_to -> users.id
- audit_logs.user_id -> users.id
- media.uploaded_by -> users.id
- internal_messages.sender_id -> users.id
- internal_messages.receiver_id -> users.id
- forum_topics.category_id -> forum_categories.id
- forum_topics.user_id -> users.id
- forum_posts.topic_id -> forum_topics.id
- forum_posts.user_id -> users.id
- notifications.user_id -> users.id

11.3 Dukungan Multi-Fitur per Akun

Database dirancang untuk mendukung akses multi-fitur dengan satu akun pengguna melalui mekanisme berikut:

- **Role-Based Access Control (RBAC)**: Tabel `roles` dengan kolom `permissions` (JSON) yang dapat menyimpan array permissions untuk multiple fitur
- **Flexible Permissions**: User dengan role 'user' dapat mengakses Help Desk Sertifikat, Forum, dan fitur Komunikasi secara bersamaan
- **Unified User Identity**: Satu record di tabel `users` dapat berinteraksi dengan semua fitur melalui foreign key relationships
- **Cross-Feature Data Integrity**: Foreign key constraints memastikan data konsisten di semua fitur

**Contoh Permissions JSON untuk Role User:**
```json
{
  "features": [
    "help_desk_certificates",
    "forum_access",
    "internal_messaging",
    "notifications",
    "content_access"
  ],
  "certificate_permissions": {
    "create_application": true,
    "view_own_applications": true,
    "download_certificates": true
  },
  "forum_permissions": {
    "create_topics": true,
    "create_posts": true,
    "moderate_own_content": true
  }
}
```

11.4 Indeks dan Optimisasi

- Tambahkan indeks pada kolom yang sering digunakan untuk pencarian: users.email, users.username, users.role_id, users.email_verified, users.phone_verified, users.company_name, users.business_field, users.last_login, categories.type, questions_answers.category_id, fatwas.category_id, fatwas.featured_media_id, materials.category_id, materials.featured_media_id, books.category_id, certificate_applications.status, certificate_applications.priority, certificate_applications.assigned_to, certificate_applications.ticket_number, certificate_applications.company_name, audit_logs.timestamp, internal_messages.sender_id, internal_messages.receiver_id, forum_topics.category_id, forum_topics.user_id, forum_topics.last_post_at, forum_posts.topic_id, forum_posts.user_id, notifications.user_id, notifications.is_read, notifications.created_at, media.uploaded_by
- Gunakan InnoDB sebagai engine untuk mendukung foreign key dan transaksi
- Lakukan normalisasi hingga 3NF untuk menghindari redundansi data


11.5 Backup dan Recovery

- Lakukan backup database secara berkala menggunakan mysqldump
- Simpan backup di lokasi aman
- Untuk recovery, restore dari backup file

12. Pedoman Alur

Pedoman alur mencakup proses pengembangan, deployment, dan maintenance aplikasi. Ini dirancang agar AI agent dapat mengikuti langkah-langkah sistematis dalam membangun dan mengelola sistem.

12.1 Struktur Proyek

Gunakan arsitektur MVC dengan struktur folder sebagai berikut:

```
 /Projek
├── /app
│   ├── /controllers
│   │   ├── AuthController.php
│   │   ├── BookController.php
│   │   ├── CertificateController.php
│   │   ├── ChatbotController.php
│   │   ├── FatwaController.php
│   │   ├── ForumController.php
│   │   ├── HomeController.php
│   │   ├── InternalChatController.php
│   │   ├── LanguageController.php
│   │   ├── MaterialController.php
│   │   ├── NotificationController.php
│   │   ├── QaController.php
│   │   ├── RobotsController.php
│   │   ├── SearchController.php
│   │   ├── SitemapController.php
│   │   ├── WhatsappController.php
│   │   └── /Admin
│   │       ├── AuditLogController.php
│   │       ├── BookController.php
│   │       ├── CertificateController.php
│   │       ├── DashboardController.php
│   │       ├── FatwaController.php
│   │       ├── MaterialController.php
│   │       ├── MediaController.php
│   │       ├── MfaController.php
│   │       ├── QuestionAnswerController.php
│   │       ├── RoleController.php
│   │       ├── SettingController.php
│   │       ├── TranslationController.php
│   │       └── UserController.php
│   ├── /models
│   │   ├── AuditLog.php
│   │   ├── Book.php
│   │   ├── Category.php
│   │   ├── CertificateApplication.php
│   │   ├── Fatwa.php
│   │   ├── FatwaComment.php
│   │   ├── ForumCategory.php
│   │   ├── ForumPost.php
│   │   ├── ForumTopic.php
│   │   ├── InternalMessage.php
│   │   ├── Material.php
│   │   ├── MaterialComment.php
│   │   ├── Media.php
│   │   ├── Notification.php
│   │   ├── QuestionAnswer.php
│   │   ├── Role.php
│   │   ├── Setting.php
│   │   ├── Translation.php
│   │   ├── User.php
│   │   └── WhatsappUser.php
│   ├── /views
│   │   ├── /frontend
│   │   │   ├── home.php
│   │   │   ├── auth/
│   │   │   ├── book/
│   │   │   ├── certificate/
│   │   │   ├── chatbot/
│   │   │   ├── fatwa/
│   │   │   ├── forum/
│   │   │   ├── internal_chat/
│   │   │   ├── material/
│   │   │   ├── notifications/
│   │   │   ├── qa/
│   │   │   └── search/
│   │   ├── /admin
│   │   ├── /emails
│   │   ├── /errors
│   │   └── /layouts
│   ├── /core
│   ├── /lang
│   ├── /helpers.php
│   └── /routes.php
├── /config
│   └── config.php
├── /db
│   ├── schema.sql
│   └── /migrations
├── /public
│   ├── index.php
│   ├── manifest.json
│   ├── robots.txt
│   └── service-worker.js
├── /vendor
├── composer.json
├── composer.lock
├── deployment_instructions.md
└── README.md
```

12.2 Persiapan Lingkungan Pengembangan

1. Install PHP versi 7.4 atau lebih tinggi
2. Install MySQL 5.7+
3. Install Apache atau web server lainnya
4. Install Composer untuk manajemen dependencies
5. Alternatif: Install XAMPP/LAMP stack (sudah termasuk PHP dan MySQL)
6. Clone repository dari Git
7. Jalankan `composer install` untuk menginstall dependencies (PHPSpreadsheet, PHPMailer)
8. Buat database baru di MySQL
9. Import skema database dari file SQL
10. Konfigurasi koneksi database di config/database.php

12.3 Proses Pengembangan

1. **Setup Awal**
   - Buat file konfigurasi database
   - Implementasi koneksi PDO
   - Buat class dasar untuk Model, Controller, View

2. **Implementasi Autentikasi**
    - Buat sistem login/logout dengan session management
    - Implementasi RBAC berdasarkan roles dengan JSON permissions
    - Hash password menggunakan password_hash() dengan bcrypt
    - Sistem forgot password dengan email verification
    - Multi-Factor Authentication (MFA) untuk admin
    - Account verification dan security questions

3. **Frontend Development**
    - Integrasi template News5 untuk halaman publik
    - Implementasi fitur beranda, tanya jawab, fatwa, materi, perpustakaan, sertifikat, forum, chat internal, notifikasi, chatbot AI
    - Implementasi dashboard pengguna dengan grup menu untuk multiple fitur (Help Desk, Forum, Komunikasi)
    - Sistem kelola profil lengkap dengan upload dokumen dan verifikasi
    - Forgot password flow dengan email verification dan security questions
    - Integrasi WhatsApp bot
    - Tambahkan validasi input dan sanitasi

4. **Admin Panel Development**
    - Integrasi template Morvin_HTML_v1.2.0
    - Implementasi dashboard untuk setiap role
    - CRUD untuk semua entitas (users, content, certificates, forum, notifications)
    - Implementasi MFA untuk admin

5. **Fitur Tambahan**
    - Implementasi pencarian full-text
    - Upload file dengan validasi
    - Generate PDF untuk sertifikat
    - Audit logging
    - Export laporan ke Excel menggunakan PHPSpreadsheet
    - Pengiriman email notifikasi menggunakan PHPMailer
    - Implementasi forum diskusi
    - Sistem chat internal
    - Notifikasi real-time
    - Integrasi WhatsApp dengan Gemini AI
    - Chatbot AI di website
    - Multi-Factor Authentication (MFA)
    - Optimisasi SEO (meta tags, sitemap, robots.txt)
    - Implementasi Schema Markup untuk rich snippets

6. **Testing**
   - Test manual untuk setiap fitur
   - Validasi keamanan (SQL injection, XSS)
   - Test responsivitas UI

12.4 Standar Coding

- Gunakan PSR-1 dan PSR-2 untuk style code
- Gunakan namespace jika memungkinkan
- Dokumentasi kode dengan PHPDoc
- Gunakan prepared statements untuk query database
- Validasi dan sanitasi semua input user
- Gunakan session untuk manajemen state

12.5 Version Control

- Gunakan Git untuk version control
- Branching strategy: main, develop, feature branches
- Commit messages yang deskriptif
- Pull request untuk review code

12.6 Deployment

1. Upload semua file ke server hosting
2. Konfigurasi virtual host Apache
3. Buat database di server production
4. Import data dari development
5. Update konfigurasi database
6. Set permission file yang benar
7. Test aplikasi di production

12.7 Maintenance

- Monitor log error
- Update dependencies jika ada
- Backup database reguler
- Security updates
- Performance monitoring

### 13. SEO dan Schema Data

#### Optimisasi SEO (Search Engine Optimization)

Untuk meningkatkan visibilitas website di mesin pencari seperti Google, implementasikan praktik SEO berikut di seluruh aplikasi:

##### On-Page SEO:
- **Meta Tags**: Setiap halaman harus memiliki meta title, meta description, dan meta keywords yang relevan.
- **Heading Tags**: Gunakan H1, H2, H3 secara hierarkis untuk struktur konten.
- **URL Structure**: URL yang clean dan deskriptif, misalnya `/tanya-jawab/kategori/pertanyaan-id`.
- **Alt Text**: Semua gambar harus memiliki alt text yang deskriptif.
- **Internal Linking**: Link internal antar halaman untuk meningkatkan navigasi dan PageRank.
- **Mobile-Friendly**: Pastikan website responsif dan mobile-friendly.

##### Off-Page SEO:
- **Backlinks**: Strategi untuk mendapatkan backlinks berkualitas dari situs terpercaya.
- **Social Media Sharing**: Tombol share untuk Facebook, Twitter, WhatsApp, dll.

##### Technical SEO:
- **Sitemap XML**: Generate sitemap otomatis untuk semua halaman.
- **Robots.txt**: Konfigurasi untuk mengarahkan crawler mesin pencari.
- **Page Speed**: Optimasi loading speed dengan minifikasi CSS/JS, compress images.
- **HTTPS**: Pastikan website menggunakan SSL certificate.
- **Canonical URLs**: Hindari duplicate content dengan canonical tags.

##### Content SEO:
- **Keyword Research**: Identifikasi keyword relevan untuk konten keagamaan, fatwa, dll.
- **Quality Content**: Konten yang informatif, akurat, dan up-to-date.
- **Blog/Content Marketing**: Regular posting artikel tentang topik keagamaan.

#### Schema Markup (Structured Data)

Implementasikan schema.org markup untuk memberikan konteks tambahan kepada mesin pencari, yang dapat menghasilkan rich snippets di hasil pencarian.

##### Schema Types yang Digunakan:
- **Organization**: Untuk informasi MUI sebagai organisasi.
- **WebSite**: Untuk metadata website utama.
- **Article/BlogPosting**: Untuk fatwa, materi moderasi, artikel.
- **FAQPage**: Untuk halaman tanya jawab.
- **Book**: Untuk entri perpustakaan digital.
- **Product/Service**: Untuk sertifikat halal.
- **BreadcrumbList**: Untuk navigasi breadcrumb.
- **SearchAction**: Untuk search box di Google.

##### Implementasi:
- Gunakan JSON-LD format untuk schema markup.
- Integrasikan di template views untuk setiap jenis konten.
- Validasi menggunakan Google Structured Data Testing Tool.

##### Contoh Schema untuk Artikel Fatwa:
```json
{
  "@context": "https://schema.org",
  "@type": "Article",
  "headline": "Judul Fatwa",
  "author": {
    "@type": "Person",
    "name": "Nama Penulis"
  },
  "publisher": {
    "@type": "Organization",
    "name": "MUI",
    "logo": {
      "@type": "ImageObject",
      "url": "https://www.mui.or.id/logo.png"
    }
  },
  "datePublished": "2023-01-01",
  "dateModified": "2023-01-01",
  "mainEntityOfPage": {
    "@type": "WebPage",
    "@id": "https://www.mui.or.id/fatwa/id"
  }
}
```

##### Contoh Schema untuk Tanya Jawab (FAQ):
```json
{
  "@context": "https://schema.org",
  "@type": "FAQPage",
  "mainEntity": [
    {
      "@type": "Question",
      "name": "Pertanyaan Keagamaan",
      "acceptedAnswer": {
        "@type": "Answer",
        "text": "Jawaban lengkap untuk pertanyaan tersebut..."
      }
    }
  ]
}
```

##### Contoh Schema untuk Materi Moderasi:
```json
{
  "@context": "https://schema.org",
  "@type": "Article",
  "headline": "Judul Materi Moderasi",
  "description": "Deskripsi materi tentang moderasi dan toleransi",
  "author": {
    "@type": "Person",
    "name": "Nama Penulis"
  },
  "publisher": {
    "@type": "Organization",
    "name": "MUI"
  },
  "datePublished": "2023-01-01",
  "articleSection": "Moderasi Beragama",
  "mainEntityOfPage": {
    "@type": "WebPage",
    "@id": "https://www.mui.or.id/materi/id"
  }
}
```

##### Contoh Schema untuk Buku Perpustakaan:
```json
{
  "@context": "https://schema.org",
  "@type": "Book",
  "name": "Judul Buku",
  "author": {
    "@type": "Person",
    "name": "Nama Penulis"
  },
  "publisher": {
    "@type": "Organization",
    "name": "Penerbit"
  },
  "datePublished": "2023",
  "isbn": "978-1234567890",
  "genre": "Keagamaan",
  "inLanguage": "id",
  "mainEntityOfPage": {
    "@type": "WebPage",
    "@id": "https://www.mui.or.id/buku/id"
  }
}
```

##### Contoh Schema untuk Help Desk Sertifikat Halal:
```json
{
  "@context": "https://schema.org",
  "@type": "Service",
  "name": "Layanan Pengajuan Sertifikat Halal",
  "description": "Sistem help desk untuk pengajuan sertifikat halal secara mandiri",
  "provider": {
    "@type": "Organization",
    "name": "Majelis Ulama Indonesia",
    "url": "https://www.mui.or.id"
  },
  "serviceType": "Halal Certification Application",
  "areaServed": "Indonesia",
  "availableChannel": {
    "@type": "ServiceChannel",
    "availableLanguage": "Indonesian",
    "serviceUrl": "https://www.mui.or.id/help-desk"
  },
  "mainEntityOfPage": {
    "@type": "WebPage",
    "@id": "https://www.mui.or.id/help-desk-sertifikat"
  }
}
```

##### Contoh Schema untuk Forum Diskusi:
```json
{
  "@context": "https://schema.org",
  "@type": "DiscussionForumPosting",
  "headline": "Judul Topik Forum",
  "text": "Konten posting forum",
  "author": {
    "@type": "Person",
    "name": "Nama Pengguna"
  },
  "datePublished": "2023-01-01",
  "mainEntityOfPage": {
    "@type": "WebPage",
    "@id": "https://www.mui.or.id/forum/topic/id"
  }
}
```

##### Advanced SEO Features (seperti RankMath):

###### Content Analysis & Optimization:
- **Keyword Optimization**: Analisis kepadatan keyword, readability score, dan saran optimasi konten.
- **SEO Score**: Penilaian keseluruhan SEO untuk setiap halaman/post dengan rekomendasi perbaikan.
- **Content Length Check**: Monitoring panjang konten optimal untuk SEO.
- **Internal Linking Suggestions**: Rekomendasi link internal untuk meningkatkan PageRank.

###### Meta Management:
- **Dynamic Meta Titles**: Generate otomatis meta title berdasarkan template dan variables.
- **Meta Description Generator**: Auto-generate deskripsi meta dengan panjang optimal.
- **Open Graph & Twitter Cards**: Optimasi untuk sharing di social media.
- **Canonical URLs**: Automatic canonical URL management untuk menghindari duplicate content.

###### Technical SEO:
- **XML Sitemap**: Auto-generate sitemap XML untuk semua content types.
- **Robots.txt Editor**: Interface untuk mengelola robots.txt file.
- **404 Error Monitoring**: Tracking dan redirect management untuk broken links.
- **Redirection Manager**: 301/302 redirect dengan import/export functionality.

###### Schema Markup Automation:
- **Auto Schema Generation**: Otomatis generate schema markup untuk semua content types.
- **Rich Snippets Testing**: Built-in validation untuk schema markup.
- **Custom Schema Builder**: Interface untuk membuat custom schema types.

###### Performance & Speed:
- **Image Optimization**: Auto-compress dan lazy loading untuk images.
- **CSS/JS Minification**: Minify assets untuk faster loading.
- **CDN Integration**: Support untuk berbagai CDN providers.

###### Analytics & Reporting:
- **Keyword Rank Tracking**: Monitor posisi keyword di search results.
- **Traffic Analytics**: Integration dengan Google Analytics dan Search Console.
- **SEO Performance Dashboard**: Comprehensive dashboard untuk monitoring SEO metrics.

###### Local SEO:
- **Google My Business Integration**: Sync dengan GMB untuk local search.
- **Local Schema Markup**: Auto-generate local business schema.
- **Location-based Content**: Optimasi untuk local search queries.

##### Monitoring dan Analytics:
- **Google Analytics**: Untuk tracking traffic dan behavior pengguna.
- **Google Search Console**: Untuk monitoring indexing dan search performance.
- **SEO Tools**: Menggunakan tools seperti Ahrefs, SEMrush untuk analisis kompetitor dan keyword.

Integrasikan SEO dan schema data di semua aspek pengembangan: dari struktur URL, meta tags di views, hingga optimasi database untuk query cepat.
