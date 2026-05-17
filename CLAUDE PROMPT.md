# CLAUDE PROMPT — ForumKita (PRD Lengkap)

> Upload file ini ke Claude (claude.ai) dan ketik:
> **"Buatkan website ForumKita sesuai PRD di file ini."**

---

## Identitas Proyek

- **Nama Web:** ForumKita
- **Konsep:** Forum diskusi komunitas berbasis web, nuansa mirip Kaskus
- **Warna Utama:** Putih, Biru, aksen Kuning
- **Domain Demo:** https://demo-forumkita.arifsiddikm.com

---

## Tech Stack

- **Framework:** Laravel 13 (PHP 8.3), MVC biasa — tanpa Filament
- **Database:** MySQL
- **Frontend:** Tailwind CSS via CDN (`cdn.tailwindcss.com`) — **JANGAN pakai @apply**, semua custom CSS ditulis native di `<style>` tag
- **Rich Text Editor:** CKEditor 4 (untuk body thread, body reply)
- **Konfirmasi & Alert:** SweetAlert2
- **Chart:** Chart.js
- **Email:** PHPMailer — SMTP: `smtp.hostinger.com`, SSL, port 465
- **Upload Gambar:** inline CKEditor + thumbnail upload terpisah
- **Auth:** Custom (bukan Breeze/Jetstream)

---

## Struktur & Fitur Lengkap

### 1. Autentikasi

- Register: nama, username, email, password, konfirmasi password
- Login dengan email atau username + remember me
- Logout dengan konfirmasi SweetAlert
- Forgot Password: kirim link reset via email (PHPMailer)
- Reset Password via token
- Ban check saat login (tampilkan pesan alasan ban)

### 2. User & Profil

- Halaman profil publik (`/profil/{username}`): avatar, bio, lokasi, website, gender, reputasi, badge, list thread & reply user
- Edit profil: nama, bio, lokasi, website, gender, signature
- Upload avatar
- Ganti password
- Sistem poin reputasi
- Badge otomatis berdasarkan kondisi (jumlah thread, reply, reputasi)

### 3. Thread

- Buat thread: judul, body (CKEditor), kategori, tags (multi), thumbnail upload
- Edit thread (pemilik / admin)
- Hapus thread dengan konfirmasi SweetAlert (pemilik / admin)
- List thread di `/forum` — sort: default, hot, terbaru
- Filter per kategori (`/forum/kategori/{slug}`)
- Filter per tag (`/forum/tag/{slug}`)
- Halaman detail thread dengan list reply
- Like thread (toggle)
- Report thread (dengan alasan)
- Status thread: `is_pinned`, `is_hot`, `is_locked`, `is_solved`, `is_announcement`
- Field: `views_count`, `replies_count`, `likes_count`, `last_reply_at`, `thumbnail`

### 4. Reply / Komentar

- Tambah reply (CKEditor)
- Edit reply (pemilik)
- Hapus reply dengan konfirmasi SweetAlert (pemilik / admin)
- Like reply (toggle)
- Report reply (dengan alasan)
- Mark as Solution — tandai reply sebagai jawaban terbaik
- Quoted reply — kutip konten + username

### 5. Notifikasi

- Notifikasi in-app: tipe reply, like, mention, solution
- Badge notifikasi belum dibaca di navbar
- Halaman `/notifikasi` — mark as read satu per satu atau semua sekaligus

### 6. Pencarian & Direktori

- Pencarian global `/search` (thread + user)
- Halaman Members `/members`
- Halaman Leaderboard `/leaderboard` (ranking reputasi)

### 7. Halaman Statis

- Beranda (landing + list thread populer)
- About (`/about`)
- Terms of Service (`/tos`)
- Privacy Policy (`/privacy`)
- Kontak + form kirim pesan (`/contact`) — kirim email via PHPMailer

### 8. Admin Panel (`/webmin`)

- Halaman login admin tersendiri di `/webmin/login` — dengan tombol **Autofill** (isi form otomatis, login tetap manual klik)
- Middleware: cek `is_admin = true`
- **Dashboard:** statistik total users, threads, replies, laporan pending; chart aktivitas 30 hari (threads + replies + users); top kategori; latest users; latest reports
- **Manajemen Pengguna:** list (DataTables), detail, ban/unban (isi alasan), toggle admin, hapus — semua aksi konfirmasi SweetAlert
- **Manajemen Kategori:** CRUD lengkap (nama, slug, icon, warna, urutan, status aktif/nonaktif)
- **Manajemen Thread:** list, toggle pin/hot/lock/announce, hapus — konfirmasi SweetAlert
- **Manajemen Laporan:** list laporan masuk (thread & reply), resolve, dismiss — konfirmasi SweetAlert

---

## Database (Migrations)

Buatkan migrations berikut, **gabungkan `sessions`, `password_reset_tokens` ke dalam migration users**:

```
users (id, name, username, email, password, avatar, bio, lokasi, website, gender, signature, reputation, is_admin, is_banned, ban_reason, last_seen_at, remember_token, email_verified_at, timestamps)
password_reset_tokens (email, token, created_at)
sessions (id, user_id, ip_address, user_agent, payload, last_activity)

categories (id, name, slug, icon, color, urutan, is_active, timestamps)
tags (id, name, slug, timestamps)

threads (id, user_id, category_id, title, slug, body, thumbnail, views_count, replies_count, likes_count, last_reply_at, is_pinned, is_hot, is_locked, is_solved, is_announcement, timestamps)
thread_tags (thread_id, tag_id)

replies (id, thread_id, user_id, body, is_solution, quoted_reply_id, timestamps)

likes (id, user_id, likeable_id, likeable_type, timestamps) — polymorphic
reports (id, user_id, reportable_id, reportable_type, reason, status [pending/resolved/dismissed], timestamps) — polymorphic

forum_notifications (id, user_id, type, data json, read_at, timestamps)

badges (id, name, icon, description, condition_type, condition_value, timestamps)
user_badges (id, user_id, badge_id, timestamps)
```

---

## Keamanan

- CSRF protection di semua form
- Policy Laravel untuk Thread & Reply (owner / admin check)
- AdminMiddleware cek `is_admin`
- Validasi input backend di semua controller
- Ban check middleware saat login
- Sanitasi output XSS di blade views

---

## Aturan Frontend (WAJIB diikuti)

1. Tailwind CSS via CDN — JANGAN pakai `@apply`, semua CSS custom di `<style>` native
2. Semua form input, checkbox, radio, button **harus ada desain CSS-nya** — jangan sampai ada elemen tanpa styling
3. Sidebar admin harus terdesain rapi dengan warna dan icon
4. Setiap button harus konsisten desainnya (primary, danger, secondary)
5. Gunakan CKEditor 4 di semua textarea konten panjang (body thread, body reply)
6. Konfirmasi hapus/aksi penting pakai SweetAlert2
7. Logo SVG + Favicon SVG + meta tag SEO di setiap halaman
8. Layout responsive (mobile-friendly)
9. Nuansa warna: putih + biru dominan + aksen kuning

---

## File yang Harus Dihasilkan

Kirim dalam file ZIP, prioritaskan file berikut:

```
routes/web.php
app/Http/Controllers/ (semua controller)
app/Http/Middleware/ (AdminMiddleware, BanCheckMiddleware)
app/Models/ (semua model)
app/Policies/ (ThreadPolicy, ReplyPolicy)
database/migrations/
database/seeders/
resources/views/layouts/app.blade.php
resources/views/layouts/admin.blade.php
resources/views/auth/ (login, register, forgot, reset)
resources/views/threads/ (index, create, show, edit)
resources/views/profile/ (show, edit)
resources/views/notifications/index.blade.php
resources/views/admin/ (dashboard, users, categories, threads, reports)
.env (tanpa password/key sensitif — cukup struktur)
README.md
```

---

## Catatan Tambahan

- Jangan sertakan API key, password, atau data sensitif apapun
- Jika ada fitur payment/Midtrans — **tanyakan dulu** sebelum dibuatkan
- Untuk build awal cukup kirim file-file utama yang relevan, jangan kirim file default Laravel yang tidak diubah
- Setiap revisi kirim hanya file yang berubah saja
