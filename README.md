# ForumKita — Web Forum Diskusi

Forum diskusi berbasis komunitas mirip Kaskus, dilengkapi admin panel untuk monitoring dan moderasi konten.

🌐 **Live Demo:** [demo-forumkita.arifsiddikm.com](https://demo-forumkita.arifsiddikm.com)

---

## Tech Stack

- **Backend:** PHP 8.3 + Laravel 13
- **Database:** MySQL
- **Frontend:** Tailwind CSS CDN · SweetAlert2 · Chart.js
- **Rich Text Editor:** CKEditor 4
- **Email:** PHPMailer (SMTP)

---

## Fitur

**Frontend Publik**
- Register, Login, Forgot Password (via email)
- Buat, edit, hapus thread dengan rich text editor
- Komentar / reply per thread
- Like thread & reply, tandai solusi terbaik
- Report thread & reply
- Filter kategori, tag, pencarian global
- Profil pengguna, leaderboard, halaman members
- Notifikasi in-app (reply, like, mention, solution)
- Badge & sistem reputasi

**Admin Panel** (`/webmin`)
- Dashboard statistik + chart aktivitas 30 hari
- Manajemen Pengguna (ban/unban, toggle admin, hapus)
- Manajemen Kategori (CRUD)
- Manajemen Thread (pin, hot, lock, announce, hapus)
- Manajemen Laporan (resolve / dismiss)
- Autofill login admin untuk testing

---

## Instalasi

```bash
# 1. Clone repo
git clone https://github.com/arifsiddikm/forumkita.git
cd forumkita

# 2. Install dependencies
composer install

# 3. Copy dan konfigurasi .env
cp file env to .env and setting your password
php artisan key:generate

# 4. Setup database
php artisan migrate
php artisan db:seed

# 5. Storage link
php artisan storage:link

# 6. Jalankan server
php artisan serve
```

Akses di `http://localhost:8000`

---

## Login Admin

```
URL   : http://localhost:8000/webmin
Email : admin@forumkita.com
Pass  : admin123
```

---

## Konfigurasi MySQL

Edit `.env`:
```env
DB_CONNECTION=mysql
DB_HOST=127.0.0.1
DB_PORT=3306
DB_DATABASE=forumkita
DB_USERNAME=root
DB_PASSWORD=
```

Lalu jalankan ulang:
```bash
php artisan migrate
php artisan db:seed
```

---

### Support me on

<a href="https://saweria.co/arifsiddikm" target="_blank"><img src="https://user-images.githubusercontent.com/26188697/180601310-e82c63e4-412b-4c36-b7b5-7ba713c80380.png" alt="Sawer me" height="41" width="174"></a>
