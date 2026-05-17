<?php

namespace Database\Seeders;

use App\Models\User;
use App\Models\Category;
use App\Models\Tag;
use App\Models\Thread;
use App\Models\Reply;
use App\Models\Like;
use App\Models\ForumNotification;
use Illuminate\Database\Seeder;
use Illuminate\Support\Facades\Hash;
use Illuminate\Support\Str;
use Carbon\Carbon;

class DatabaseSeeder extends Seeder
{
    public function run(): void
    {
        // ─── USERS ─────────────────────────────────────
        $admin = User::create([
            'name'=>'Administrator','username'=>'admin',
            'email'=>'admin@forumkita.id','password'=>Hash::make('Admin123!!'),
            'is_admin'=>true,'bio'=>'Admin resmi ForumKita.','location'=>'Jakarta, Indonesia',
            'reputation'=>9999,'gender'=>'male','email_verified_at'=>now(),'last_seen_at'=>now(),
        ]);
        $testUser = User::create([
            'name'=>'User Testing','username'=>'usertesting',
            'email'=>'user@forumkita.id','password'=>Hash::make('User123!!'),
            'bio'=>'Akun testing ForumKita.','reputation'=>150,
            'gender'=>'male','email_verified_at'=>now(),'last_seen_at'=>now()->subMinutes(2),
        ]);
        $members = [
            ['name'=>'Budi Santoso',    'username'=>'budisantoso',    'email'=>'budi@example.com',    'gender'=>'male',   'reputation'=>3450,'bio'=>'Software developer, coffee lover ☕'],
            ['name'=>'Siti Rahayu',     'username'=>'sitirahayu',     'email'=>'siti@example.com',    'gender'=>'female', 'reputation'=>2100,'bio'=>'UI/UX Designer'],
            ['name'=>'Ahmad Fauzi',     'username'=>'ahmadfauzi',     'email'=>'ahmad@example.com',   'gender'=>'male',   'reputation'=>890, 'bio'=>'Mahasiswa Teknik Informatika'],
            ['name'=>'Dewi Lestari',    'username'=>'dewilestari',    'email'=>'dewi@example.com',    'gender'=>'female', 'reputation'=>560, 'bio'=>'Content creator & marketer'],
            ['name'=>'Rizky Pratama',   'username'=>'rizkypratama',   'email'=>'rizky@example.com',   'gender'=>'male',   'reputation'=>1250,'bio'=>'Gamer | Programmer | Otaku'],
            ['name'=>'Nurul Hidayah',   'username'=>'nurulhidayah',   'email'=>'nurul@example.com',   'gender'=>'female', 'reputation'=>420, 'bio'=>'Suka baca, masak, traveling!'],
            ['name'=>'Eko Prasetyo',    'username'=>'ekoprasetyo',    'email'=>'eko@example.com',     'gender'=>'male',   'reputation'=>780, 'bio'=>'DevOps, Linux enthusiast'],
            ['name'=>'Fitri Handayani', 'username'=>'fitrihandayani', 'email'=>'fitri@example.com',   'gender'=>'female', 'reputation'=>310, 'bio'=>'Fresh graduate'],
            ['name'=>'Hendra Wijaya',   'username'=>'hendrawijaya',   'email'=>'hendra@example.com',  'gender'=>'male',   'reputation'=>5200,'bio'=>'Full-stack developer 10+ tahun'],
            ['name'=>'Indah Permata',   'username'=>'indahpermata',   'email'=>'indah@example.com',   'gender'=>'female', 'reputation'=>930, 'bio'=>'Fotografer & blogger'],
            ['name'=>'Joko Susilo',     'username'=>'jokosusilo',     'email'=>'joko@example.com',    'gender'=>'male',   'reputation'=>200, 'bio'=>'Petani digital'],
            ['name'=>'Kartika Sari',    'username'=>'kartikasari',    'email'=>'kartika@example.com', 'gender'=>'female', 'reputation'=>670, 'bio'=>'Ibu rumah tangga aktif forum'],
        ];
        $users = [$admin, $testUser];
        foreach ($members as $m) {
            $users[] = User::create(array_merge($m, [
                'password'=>Hash::make('Password123!!'),
                'email_verified_at'=>now(),
                'last_seen_at'=>now()->subMinutes(rand(0,10000)),
            ]));
        }

        // ─── CATEGORIES ─────────────────────────────────
        $catData = [
            ['name'=>'Diskusi Umum',    'slug'=>'diskusi-umum',    'icon'=>'fa-comments',     'color'=>'#2563EB','sort_order'=>1, 'description'=>'Diskusi berbagai topik umum'],
            ['name'=>'Teknologi & IT',  'slug'=>'teknologi-it',    'icon'=>'fa-laptop-code',  'color'=>'#7c3aed','sort_order'=>2, 'description'=>'Pemrograman, gadget, software'],
            ['name'=>'Gaming',          'slug'=>'gaming',          'icon'=>'fa-gamepad',      'color'=>'#dc2626','sort_order'=>3, 'description'=>'Game PC, console, mobile, esport'],
            ['name'=>'Otomotif',        'slug'=>'otomotif',        'icon'=>'fa-car',          'color'=>'#d97706','sort_order'=>4, 'description'=>'Motor, mobil, otomotif Indonesia'],
            ['name'=>'Lifestyle',       'slug'=>'lifestyle',       'icon'=>'fa-heart',        'color'=>'#db2777','sort_order'=>5, 'description'=>'Fashion, kesehatan, kuliner'],
            ['name'=>'Berita & Politik','slug'=>'berita-politik',  'icon'=>'fa-newspaper',    'color'=>'#0891b2','sort_order'=>6, 'description'=>'Berita dan diskusi politik'],
            ['name'=>'Olahraga',        'slug'=>'olahraga',        'icon'=>'fa-futbol',       'color'=>'#16a34a','sort_order'=>7, 'description'=>'Sepakbola, bulutangkis, dll'],
            ['name'=>'Hiburan',         'slug'=>'hiburan',         'icon'=>'fa-film',         'color'=>'#ca8a04','sort_order'=>8, 'description'=>'Film, musik, drakor, hiburan'],
            ['name'=>'Lowongan Kerja',  'slug'=>'lowongan-kerja',  'icon'=>'fa-briefcase',    'color'=>'#0f766e','sort_order'=>9, 'description'=>'Info loker dan tips karir'],
            ['name'=>'Jual Beli',       'slug'=>'jual-beli',       'icon'=>'fa-shopping-cart','color'=>'#ea580c','sort_order'=>10,'description'=>'Forum jual beli barang & jasa'],
        ];
        $cats = [];
        foreach ($catData as $c) { $cats[] = Category::create($c + ['is_active'=>true]); }

        // ─── TAGS ───────────────────────────────────────
        $tagNames = ['laravel','php','javascript','python','tips','review','tutorial','diskusi',
                     'rekomendasi','penting','gaming','mobile','linux','android','css','mysql',
                     'nodejs','react','karir','kesehatan','kuliner','travel','gadget','otomotif',
                     'film','musik','olahraga','sepakbola','drakor','investasi'];
        $tags = [];
        foreach ($tagNames as $t) { $tags[$t] = Tag::create(['name'=>$t,'slug'=>Str::slug($t)]); }

        // ─── THREAD FACTORY ─────────────────────────────
        $mkThread = function($data) use ($tags) {
            $t = Thread::create([
                'user_id'=>$data['user']->id, 'category_id'=>$data['cat']->id,
                'title'=>$data['title'], 'slug'=>Str::slug($data['title']),
                'body'=>$data['body'],
                'is_pinned'      =>$data['pinned']??false,
                'is_hot'         =>$data['hot']??false,
                'is_announcement'=>$data['announce']??false,
                'is_locked'      =>$data['locked']??false,
                'is_solved'      =>$data['solved']??false,
                'views_count'    =>$data['views']??rand(30,600),
                'likes_count'    =>$data['likes']??rand(0,40),
                'replies_count'  => 0,
                'last_reply_at'  =>now()->subMinutes(rand(10,43200)),
                'created_at'     =>now()->subDays(rand(1,90)),
                'updated_at'     =>now()->subDays(rand(0,10)),
            ]);
            if (!empty($data['tags'])) {
                $ids = collect($data['tags'])->map(fn($n)=>$tags[$n]->id??null)->filter()->toArray();
                if ($ids) $t->tags()->sync($ids);
            }
            return $t;
        };

        // ─── THREADS (50+ untuk pagination) ─────────────
        $threads = [];

        // === DISKUSI UMUM ===
        $threads[] = $mkThread(['user'=>$admin,'cat'=>$cats[0],'pinned'=>true,'announce'=>true,
            'title'=>'Selamat Datang di ForumKita! Baca Peraturan Forum',
            'body'=>'<p>Selamat datang di <strong>ForumKita</strong>! 🎉</p><h3>Peraturan Forum</h3><ul><li>✅ Hormati sesama member</li><li>✅ Gunakan bahasa sopan dan santun</li><li>✅ Posting di kategori yang sesuai</li><li>❌ Dilarang spam dan hoaks</li><li>❌ Dilarang konten SARA</li></ul><p>Selamat berdiskusi! 🙌</p>',
            'views'=>8432,'likes'=>156,'replies'=>3,'tags'=>['penting']]);

        $threads[] = $mkThread(['user'=>$users[9],'cat'=>$cats[0],'hot'=>true,
            'title'=>'Tips Nego Gaji Fresh Graduate — Pengalaman Dapat +20%',
            'body'=>'<p>Banyak fresh graduate yang takut nego gaji. Padahal itu hak kita! Berikut tips yang berhasil saya pakai:</p><ol><li>Riset standar gaji di JobStreet/LinkedIn</li><li>Tentukan range yang realistis berdasarkan market rate</li><li>Tonjolkan nilai tambah: portfolio, sertifikasi, pengalaman magang</li><li>Jangan reveal angka duluan, tanya budget perusahaan dulu</li><li>Nego bukan hanya soal gaji: remote, jam fleksibel, tunjangan juga bisa dijadikan leverage</li></ol><p>Saya berhasil dapat gaji 20% lebih tinggi dari penawaran awal. Semangat untuk teman-teman yang mau mulai karir!</p>',
            'views'=>3980,'likes'=>84,'replies'=>8,'tags'=>['karir','tips','diskusi']]);

        $threads[] = $mkThread(['user'=>$users[7],'cat'=>$cats[0],
            'title'=>'Rekomendasi Wisata Murah di Jogja yang Hidden Gem 2025',
            'body'=>'<p>Baru balik dari Jogja dan mau berbagi tempat wisata yang kurang terkenal tapi bagus banget!</p><ol><li><strong>Bukit Panguk Kediwung</strong> — sunrise di atas awan, tiket Rp 10.000</li><li><strong>Hutan Pinus Imogiri</strong> — instagrammable, gratis!</li><li><strong>Pantai Ngrenehan</strong> — sepi, seafood segar murah</li><li><strong>Embung Nglanggeran</strong> — danau di atas gunung api purba, view 360 derajat</li><li><strong>Pantai Timang</strong> — ada gondola manual, adrenalin!</li></ol><p>Semua tempat ini budget-friendly dan belum terlalu ramai turis. Worth it!</p>',
            'views'=>2640,'likes'=>67,'replies'=>5,'tags'=>['travel','rekomendasi','tips']]);

        $threads[] = $mkThread(['user'=>$users[11],'cat'=>$cats[0],
            'title'=>'Sharing: Cara Hemat Pengeluaran Bulanan ala Anak Kos',
            'body'=>'<p>Anak kos sering bokek di akhir bulan? Ini tips yang saya terapkan dan berhasil hemat 40% pengeluaran:</p><ul><li>Masak sendiri minimal 3x seminggu — hemat 300-500rb/bulan</li><li>Meal prep Minggu pagi untuk bekal seminggu</li><li>Pakai aplikasi tracking pengeluaran (Money Manager / Wallet)</li><li>Batasi jajan di luar maksimal Rp 50.000/hari</li><li>Beli kebutuhan bulanan di awal bulan sekaligus, hindari beli eceran</li><li>Manfaatkan promo Go/GrabFood di jam-jam tertentu</li></ul><p>Dari yang tadinya habis Rp 2.5 juta/bulan, sekarang cukup Rp 1.5 juta. Sisanya nabung!</p>',
            'views'=>1850,'likes'=>52,'replies'=>12,'tags'=>['tips','diskusi']]);

        $threads[] = $mkThread(['user'=>$users[3],'cat'=>$cats[0],
            'title'=>'Cari Teman Hangout di Jakarta Selatan — Join Yuk!',
            'body'=>'<p>Halo semuanya! Saya baru pindah ke Jakarta Selatan 3 bulan yang lalu dan masih kesulitan cari teman baru di sini.</p><p>Background saya: fresh grad, suka ngoding, gaming, dan nonton film. Kalau ada yang mau hangout bareng atau ketemuan untuk kegiatan positif, DM atau reply di sini ya!</p><p>Area: Kebayoran Baru - Blok M - Gandaria. Yuk kenalan! 😊</p>',
            'views'=>890,'likes'=>23,'replies'=>7,'tags'=>['diskusi']]);

        $threads[] = $mkThread(['user'=>$users[10],'cat'=>$cats[0],
            'title'=>'Pengalaman Kerja Remote Pertama — Suka Dukanya',
            'body'=>'<p>Sudah 6 bulan WFH penuh. Ini jujur suka dukanya:</p><h3>Sukanya</h3><ul><li>Hemat waktu, tidak ada commuting 2-3 jam/hari</li><li>Bisa kerja dari mana saja, lebih fleksibel</li><li>Produktivitas meningkat karena lingkungan lebih kondusif</li></ul><h3>Dukanya</h3><ul><li>Batas waktu kerja dan istirahat jadi blur</li><li>Kurang interaksi sosial, kadang lonely</li><li>Meeting online yang bisa jadi email itu nyata adanya</li></ul><p>Overall saya prefer WFH, asal disiplin manage waktu. Ada yang sudah lebih lama WFH? Bagikan pengalamannya!</p>',
            'views'=>1340,'likes'=>41,'replies'=>9,'tags'=>['karir','diskusi']]);

        // === TEKNOLOGI & IT ===
        $threads[] = $mkThread(['user'=>$users[2],'cat'=>$cats[1],'hot'=>true,
            'title'=>'Tips Belajar Laravel untuk Pemula — Roadmap 6 Bulan',
            'body'=>'<p>Sharing pengalaman belajar Laravel dari nol sampai bisa deploy aplikasi ke production!</p><h3>Roadmap</h3><ol><li>Kuasai PHP dasar + OOP (2-4 minggu)</li><li>Install Composer dan setup environment</li><li>Pelajari konsep MVC dan routing Laravel</li><li>Baca dokumentasi resmi laravel.com (sangat lengkap!)</li><li>Buat project CRUD sederhana untuk latihan</li><li>Pelajari Eloquent ORM dan relationship</li><li>Pelajari Blade template engine</li><li>Deploy ke shared hosting atau VPS</li></ol><p>Resource gratis: Laracasts, Kawan Koding, Web Programming Unpas. Semangat!</p>',
            'views'=>4521,'likes'=>87,'replies'=>8,'tags'=>['laravel','php','tutorial']]);

        $threads[] = $mkThread(['user'=>$users[4],'cat'=>$cats[1],
            'title'=>'React vs Vue vs Angular 2025 — Pilih yang Mana?',
            'body'=>'<p>Pertanyaan klasik! Ini perbandingan objektif dari pengalaman saya memakai ketiganya:</p><h3>React</h3><p>Library dari Meta. Paling populer, ekosistem terbesar, job market terluas. Learning curve lumayan tapi worth it.</p><h3>Vue.js</h3><p>Framework yang lebih opinionated, sintaks bersih, dokumentasi bagus ada bahasa Indonesia. Cocok untuk pemula.</p><h3>Angular</h3><p>Full framework dari Google, TypeScript by default. Cocok untuk enterprise project besar dengan tim besar.</p><p><strong>Rekomendasi saya:</strong> Cari kerja = React. Belajar nyaman = Vue. Corporate enterprise = Angular.</p>',
            'views'=>3210,'likes'=>64,'replies'=>9,'tags'=>['javascript','react','diskusi']]);

        $threads[] = $mkThread(['user'=>$users[6],'cat'=>$cats[1],
            'title'=>'Setup WSL2 + Ubuntu di Windows 11 untuk Dev Environment',
            'body'=>'<p>Panduan lengkap setup dev environment Linux di Windows menggunakan WSL2:</p><h3>Instalasi WSL2</h3><ol><li>PowerShell as Administrator: <code>wsl --install</code></li><li>Restart komputer</li><li>Set default: <code>wsl --set-default-version 2</code></li><li>Install Ubuntu dari Microsoft Store</li></ol><h3>Setup Tools</h3><ul><li>Install nvm untuk Node.js: <code>curl -o- https://raw.githubusercontent.com/nvm-sh/nvm/v0.39.0/install.sh | bash</code></li><li>Install PHP 8.2: <code>sudo apt install php8.2 php8.2-cli php8.2-mbstring php8.2-xml</code></li><li>Install Composer, Git, dan tools lainnya</li></ul><p>Dengan WSL2, workflow development di Windows jadi jauh lebih smooth!</p>',
            'views'=>2100,'likes'=>41,'replies'=>4,'tags'=>['linux','tutorial','tips']]);

        $threads[] = $mkThread(['user'=>$users[8],'cat'=>$cats[1],
            'title'=>'Belajar Docker untuk Pemula — dari Nol Sampai Deploy',
            'body'=>'<p>Docker adalah tool yang wajib dikuasai developer modern. Ini panduan dari nol:</p><h3>Apa itu Docker?</h3><p>Platform containerization yang memungkinkan kamu menjalankan aplikasi dalam environment yang terisolasi dan konsisten di semua sistem operasi.</p><h3>Konsep Dasar</h3><ul><li><strong>Image</strong>: Template read-only untuk membuat container</li><li><strong>Container</strong>: Instance yang berjalan dari sebuah image</li><li><strong>Dockerfile</strong>: Script untuk build custom image</li><li><strong>Docker Compose</strong>: Tool untuk manage multi-container app</li></ul><h3>Command Paling Sering Dipakai</h3><pre>docker pull nginx\ndocker run -d -p 80:80 nginx\ndocker ps\ndocker stop [container_id]\ndocker-compose up -d</pre>',
            'views'=>2890,'likes'=>58,'replies'=>6,'tags'=>['tutorial','tips']]);

        $threads[] = $mkThread(['user'=>$users[11],'cat'=>$cats[1],
            'title'=>'HELP: Error CORS React ke Laravel — Sudah 2 Hari Stuck!',
            'body'=>'<p>Halo, saya stuck dengan error CORS sudah 2 hari ini. Setup saya:</p><ul><li>Frontend: React + Vite di localhost:5173</li><li>Backend: Laravel 13 di localhost:8000</li></ul><p>Error: <code>Access to fetch blocked by CORS policy</code></p><p>Sudah dicoba: fruitcake/laravel-cors, set header manual di middleware, edit config/cors.php. Masih error terus. Ada yang bisa bantu? 🙏</p>',
            'views'=>342,'likes'=>3,'replies'=>0,'tags'=>['laravel','react']]);

        $threads[] = $mkThread(['user'=>$users[2],'cat'=>$cats[1],
            'title'=>'Review: Apakah Belajar Python di 2025 Masih Worth It?',
            'body'=>'<p>Python makin populer berkat AI/ML boom. Tapi apakah worth untuk dipelajari di 2025?</p><h3>Kenapa Python Worth It</h3><ul><li>Sintaks simple dan mudah dipelajari pemula</li><li>Ekosistem AI/ML terbesar: TensorFlow, PyTorch, scikit-learn</li><li>Django dan FastAPI untuk web development</li><li>Data science dan automation</li><li>Salary Python developer termasuk tinggi</li></ul><h3>Kapan Python Bukan Pilihan Terbaik</h3><ul><li>Mobile development (lebih baik pakai Flutter/React Native)</li><li>Frontend web (gunakan JavaScript)</li><li>Performance-critical system (pakai Go/Rust)</li></ul><p>Kesimpulan: Python sangat worth, terutama kalau tertarik di bidang AI, data science, atau automation.</p>',
            'views'=>1870,'likes'=>49,'replies'=>5,'tags'=>['python','diskusi','review']]);

        $threads[] = $mkThread(['user'=>$users[4],'cat'=>$cats[1],
            'title'=>'MySQL vs PostgreSQL — Kapan Pakai yang Mana?',
            'body'=>'<p>Dua database relasional paling populer. Ini perbedaan dan kapan menggunakan masing-masing:</p><h3>MySQL</h3><ul><li>Lebih mudah setup dan konfigurasi</li><li>Cocok untuk web application standard</li><li>Community dan dokumentasi sangat besar</li><li>Default pilihan untuk LAMP/LEMP stack</li></ul><h3>PostgreSQL</h3><ul><li>Lebih powerful, support JSON, full-text search native</li><li>ACID compliance lebih ketat</li><li>Cocok untuk data analytics dan complex query</li><li>Pilihan default Heroku dan beberapa cloud provider</li></ul><p>Untuk project startup: MySQL. Untuk data-heavy application: PostgreSQL.</p>',
            'views'=>1560,'likes'=>37,'replies'=>7,'tags'=>['mysql','diskusi']]);

        // === GAMING ===
        $threads[] = $mkThread(['user'=>$users[4],'cat'=>$cats[2],'hot'=>true,
            'title'=>'Review Valorant 2025 — Masih Worth It Dimainkan?',
            'body'=>'<p>Sudah 5 tahun main Valorant. Ini review jujur di 2025:</p><h3>✅ Kelebihan</h3><ul><li>Skill-based, tidak ada pay-to-win sama sekali</li><li>Agent baru terus bertambah dengan kit unik</li><li>Server Singapore, ping Indonesia oke (30-60ms)</li><li>F2P dengan monetisasi yang fair</li></ul><h3>❌ Kekurangan</h3><ul><li>Toxic community, terutama di rank bawah</li><li>Anti-cheat Vanguard makan resource sistem</li><li>Skin harganya overpriced banget</li></ul><p><strong>Verdict: 8/10</strong> — Masih FPS kompetitif terbaik di 2025.</p>',
            'views'=>5670,'likes'=>112,'replies'=>9,'tags'=>['gaming','review']]);

        $threads[] = $mkThread(['user'=>$users[8],'cat'=>$cats[2],
            'title'=>'Build PC Gaming Budget 10 Juta 2025 — Spesifikasi Terbaik',
            'body'=>'<p>Rakit PC gaming 10 juta yang kencang di 2025:</p><table><thead><tr><th>Komponen</th><th>Pilihan</th><th>Harga</th></tr></thead><tbody><tr><td>CPU</td><td>AMD Ryzen 5 5600</td><td>Rp 1.200.000</td></tr><tr><td>GPU</td><td>RX 6600 XT</td><td>Rp 2.800.000</td></tr><tr><td>RAM</td><td>16GB DDR4 3200MHz</td><td>Rp 450.000</td></tr><tr><td>SSD</td><td>NVMe 512GB + HDD 1TB</td><td>Rp 700.000</td></tr><tr><td>Mobo</td><td>B550M DS3H</td><td>Rp 900.000</td></tr><tr><td>PSU</td><td>650W 80+ Bronze</td><td>Rp 600.000</td></tr><tr><td>Case</td><td>Mid Tower ATX</td><td>Rp 350.000</td></tr></tbody></table><p>Total ~7 juta. Sisa untuk monitor 1080p 144Hz!</p>',
            'views'=>4120,'likes'=>93,'replies'=>11,'tags'=>['gaming','gadget','rekomendasi']]);

        $threads[] = $mkThread(['user'=>$users[5],'cat'=>$cats[2],
            'title'=>'Mobile Legends vs Honor of Kings 2025 — MLBB Sudah Kalah?',
            'body'=>'<p>Honor of Kings akhirnya masuk Indonesia dan langsung booming. Apakah MLBB sudah saatnya pensiun?</p><h3>Mobile Legends: Bang Bang</h3><ul><li>Sudah 8 tahun berdiri, komunitas Indonesia terbesar</li><li>Server stabil, update rutin</li><li>Meta berganti cepat, kadang terlalu sering</li></ul><h3>Honor of Kings</h3><ul><li>Grafis jauh lebih bagus</li><li>Gameplay lebih smooth dan balanced</li><li>Komunitas masih kecil di Indonesia</li><li>Hero design lebih kreatif</li></ul><p>Untuk sementara MLBB masih king di Indonesia karena komunitas. Tapi HoK perlahan naik. Kalian main yang mana?</p>',
            'views'=>3200,'likes'=>71,'replies'=>15,'tags'=>['gaming','mobile','diskusi']]);

        $threads[] = $mkThread(['user'=>$users[2],'cat'=>$cats[2],
            'title'=>'Rekomendasi Game Single Player Terbaik 2024-2025',
            'body'=>'<p>Bosan dengan game online yang toxic? Ini rekomendasi game single player yang worth dimainkan:</p><ol><li><strong>Elden Ring</strong> — Masterpiece FromSoftware, challenging tapi rewarding</li><li><strong>Baldur\'s Gate 3</strong> — RPG terbaik dekade ini, 100+ jam konten</li><li><strong>Cyberpunk 2077 (2.0)</strong> — Sudah dipatch besar-besaran, sekarang sangat bagus</li><li><strong>Dave the Diver</strong> — Indie gem, mix antara diving simulator dan sushi restaurant</li><li><strong>Hades 2</strong> — Roguelike terbaik yang ada saat ini</li></ol><p>Semua bisa dibeli dengan harga diskon di Steam kalau sabar nunggu sale!</p>',
            'views'=>2780,'likes'=>65,'replies'=>8,'tags'=>['gaming','rekomendasi','review']]);

        // === OTOMOTIF ===
        $threads[] = $mkThread(['user'=>$users[3],'cat'=>$cats[3],
            'title'=>'Honda Beat vs Yamaha Mio M3 2025 — Mana yang Lebih Worth?',
            'body'=>'<p>Dua motor matic terlaris Indonesia. Ini perbandingan objektifnya:</p><h3>Honda Beat 2025</h3><ul><li>Mesin 109cc, konsumsi BBM 60-65 km/liter</li><li>Bobot ringan (94 kg), mudah dikendarai</li><li>OTR Jakarta: Rp 18,7 juta</li><li>Suku cadang mudah dan murah di mana saja</li></ul><h3>Yamaha Mio M3</h3><ul><li>Mesin 113cc, handling lebih stabil di kecepatan tinggi</li><li>Desain lebih modern dan sporty</li><li>OTR Jakarta: Rp 19,2 juta</li><li>Blue Core engine lebih efisien di rpm tinggi</li></ul><p><strong>Kesimpulan:</strong> Macet kota tiap hari → Beat (lebih irit). Perjalanan jarak menengah → Mio M3.</p>',
            'views'=>3450,'likes'=>58,'replies'=>7,'tags'=>['otomotif','review','rekomendasi']]);

        $threads[] = $mkThread(['user'=>$users[8],'cat'=>$cats[3],
            'title'=>'Tips Perawatan Motor Injeksi Agar Awet 10 Tahun',
            'body'=>'<p>Motor injeksi butuh perawatan berbeda dari karburator. Ini tips agar awet:</p><ol><li><strong>Ganti oli rutin</strong> — tiap 2.000-3.000 km, jangan sampai telat</li><li><strong>Bersihkan throttle body</strong> — setiap 10.000 km atau setahun sekali</li><li><strong>Cek tekanan ban</strong> — minimal seminggu sekali, tekanan ideal 28-30 psi</li><li><strong>Bersihkan filter udara</strong> — setiap 8.000 km</li><li><strong>Ganti busi</strong> — setiap 8.000-10.000 km</li><li><strong>Isi bensin Pertamax atau lebih tinggi</strong> — motor injeksi butuh oktan minimal 90</li></ol><p>Dengan perawatan rutin, motor injeksi bisa awet 10-15 tahun!</p>',
            'views'=>1890,'likes'=>45,'replies'=>6,'tags'=>['otomotif','tips']]);

        // === LIFESTYLE ===
        $threads[] = $mkThread(['user'=>$users[5],'cat'=>$cats[4],'hot'=>true,
            'title'=>'Tips Hidup Sehat di Tengah Kesibukan Kerja — Sharing Pengalaman',
            'body'=>'<p>Setelah sakit parah akibat kurang jaga kesehatan, saya akhirnya serius berubah. Ini yang saya lakukan:</p><ol><li><strong>Minum 2 liter air putih per hari</strong> — pasang reminder di HP setiap 2 jam</li><li><strong>Olahraga 30 menit, 4x seminggu</strong> — tidak harus gym, jalan kaki dan bodyweight sudah cukup</li><li><strong>Tidur 7-8 jam</strong> — matikan HP jam 10 malam, ini yang paling susah!</li><li><strong>Meal prep Minggu</strong> — masak untuk seminggu, hemat waktu dan uang</li><li><strong>Batasi kafein</strong> — maksimal 2 cangkir kopi per hari, ganti dengan green tea</li></ol><p>Hasilnya dalam 3 bulan: berat badan turun 5kg, lebih fokus kerja, jarang sakit. Highly recommended!</p>',
            'views'=>2870,'likes'=>76,'replies'=>9,'tags'=>['kesehatan','tips']]);

        $threads[] = $mkThread(['user'=>$users[9],'cat'=>$cats[4],
            'title'=>'Review Aplikasi Fitness Terbaik di Indonesia 2025',
            'body'=>'<p>Sudah coba banyak aplikasi fitness. Ini ranking terbaik menurut saya:</p><ol><li><strong>Nike Training Club</strong> — Program latihan terstruktur, gratis semua fitur. Terbaik untuk pemula.</li><li><strong>MyFitnessPal</strong> — Pelacak kalori terlengkap. Database makanan Indonesia lumayan.</li><li><strong>Strava</strong> — Terbaik untuk runners dan cyclists. Komunitas aktif.</li><li><strong>Freeletics</strong> — HIIT workout yang brutal tapi efektif. Premium agak mahal.</li><li><strong>Fiton</strong> — Mix antara yoga, pilates, dan HIIT. Cocok untuk perempuan.</li></ol><p>Untuk pemula yang mau mulai olahraga: mulai dari Nike Training Club, gratis dan panduan lengkap!</p>',
            'views'=>1650,'likes'=>42,'replies'=>4,'tags'=>['kesehatan','review','rekomendasi']]);

        $threads[] = $mkThread(['user'=>$users[3],'cat'=>$cats[4],
            'title'=>'Kuliner Murah Meriah di Jakarta yang Wajib Dicoba',
            'body'=>'<p>Jakarta bukan cuma tempat macet dan mahal! Ini tempat makan enak dengan harga ramah kantong:</p><ol><li><strong>Nasi Goreng Kambing Kebon Sirih</strong> — Legendaris sejak 1958. Rp 35.000/porsi</li><li><strong>Soto Betawi Hj. Maemunah</strong> — Authentic, kuah santan creamy. Rp 30.000</li><li><strong>Bakso Atom Menteng</strong> — Ukuran besar, kuah gurih. Rp 25.000</li><li><strong>Gado-gado Boplo</strong> — Gado-gado klasik Betawi. Rp 20.000</li><li><strong>Es Krim Ragusa</strong> — Es krim tertua di Jakarta, wajib dicoba. Rp 15.000</li></ol><p>Jakarta punya banyak hidden gem kuliner. Mana yang paling kalian suka?</p>',
            'views'=>2100,'likes'=>53,'replies'=>11,'tags'=>['kuliner','rekomendasi']]);

        // === OLAHRAGA ===
        $threads[] = $mkThread(['user'=>$users[6],'cat'=>$cats[6],
            'title'=>'Timnas Indonesia di Kualifikasi Piala Dunia 2026 — Analisis',
            'body'=>'<p>Garuda terus berjuang di Kualifikasi Piala Dunia 2026 ronde ketiga! Mari kita analisis:</p><h3>Kekuatan</h3><ul><li>Naturalisasi pemain berkualitas: Marselino, Ragnar, Ivar Jenner</li><li>Pelatih STY yang sudah terbukti meningkatkan performa tim</li><li>Generasi muda yang bermain di liga Eropa</li></ul><h3>Kelemahan</h3><ul><li>Konsistensi masih jadi masalah</li><li>Lini belakang masih perlu penguatan</li><li>Pengalaman di level tinggi masih kurang</li></ul><p>Apakah Indonesia bisa lolos ke Piala Dunia 2026? Diskusikan!</p>',
            'views'=>4500,'likes'=>98,'replies'=>18,'tags'=>['olahraga','sepakbola','diskusi']]);

        $threads[] = $mkThread(['user'=>$users[10],'cat'=>$cats[6],
            'title'=>'Olahraga Terbaik untuk Pemula yang Malas — Mulai dari Mana?',
            'body'=>'<p>Mau olahraga tapi males? Ini olahraga yang mudah dimulai dan tidak perlu peralatan:</p><ol><li><strong>Jalan kaki 30 menit</strong> — Mulai dari sini. Mudah, bebas polusi (kalau pagi), bakar 150-200 kalori</li><li><strong>Bodyweight workout</strong> — Push up, sit up, squat. Bisa di kamar, no gym needed</li><li><strong>Bersepeda santai</strong> — Low impact, bagus untuk sendi, bisa sambil menikmati suasana</li><li><strong>Renang</strong> — Full body workout, tidak bikin sendi sakit</li><li><strong>Yoga</strong> — Fleksibilitas + mindfulness. Banyak konten gratis di YouTube</li></ol><p>Tips: mulai dari yang paling mudah dan konsisten 3x seminggu. Jangan langsung target tinggi!</p>',
            'views'=>1780,'likes'=>48,'replies'=>6,'tags'=>['olahraga','kesehatan','tips']]);

        // === HIBURAN ===
        $threads[] = $mkThread(['user'=>$users[10],'cat'=>$cats[7],
            'title'=>'Rekomendasi Film Indonesia 2025 yang Wajib Ditonton',
            'body'=>'<p>Film Indonesia makin berkembang pesat di 2025! Ini yang wajib ditonton:</p><ol><li><strong>Drama lokal</strong> — Angkat cerita budaya Indonesia dengan sinematografi indah</li><li><strong>Horror psikologis</strong> — Bukan sekadar jumpscare, tapi building tension yang keren</li><li><strong>Komedi keluarga</strong> — Cocok ditonton bareng semua anggota keluarga</li><li><strong>Romantis remaja</strong> — Cerita relate dengan kehidupan anak muda Indonesia</li></ol><p>Kalian ada rekomendasi film Indonesia 2025 yang bagus? Share di komentar!</p>',
            'views'=>198,'likes'=>5,'replies'=>0,'tags'=>['film','rekomendasi']]);

        $threads[] = $mkThread(['user'=>$users[5],'cat'=>$cats[7],
            'title'=>'Drakor Terbaik 2024-2025 yang Wajib di-Queue',
            'body'=>'<p>Pecinta drakor merapat! Ini rekomendasi drama Korea terbaik yang saya tonton:</p><ol><li><strong>Queen of Tears</strong> — Romansa yang emosional, chemistry Kim Soo-hyun dan Kim Ji-won luar biasa. Rating tertinggi 2024!</li><li><strong>Lovely Runner</strong> — Time travel romance yang lovable. Byeon Woo-seok instantly famous!</li><li><strong>My Mister</strong> — Slow burn tapi profound. Masterpiece yang recommended untuk semua umur.</li><li><strong>Crash Landing on You</strong> — Klasik yang masih relevan, cocok untuk yang baru mulai nonton drakor.</li></ol><p>Lagi nonton apa? Drop rekomendasinya di komentar ya!</p>',
            'views'=>2340,'likes'=>78,'replies'=>14,'tags'=>['drakor','rekomendasi','film']]);

        // === LOWONGAN KERJA ===
        $threads[] = $mkThread(['user'=>$admin,'cat'=>$cats[8],'pinned'=>true,
            'title'=>'[HIRING] Software Developer Laravel — Remote, Full Time',
            'body'=>'<p>Kami mencari <strong>Software Developer Laravel</strong> untuk bergabung dengan tim kami!</p><h3>Requirements</h3><ul><li>Pengalaman minimal 2 tahun dengan Laravel</li><li>Familiar dengan MySQL/PostgreSQL dan Redis</li><li>Paham REST API dan pengembangan berbasis Git</li><li>Mampu bekerja mandiri dan dalam tim</li><li>Diutamakan yang bisa berkontribusi ke Open Source</li></ul><h3>Yang Kami Tawarkan</h3><ul><li>Gaji kompetitif: Rp 8-15 juta/bulan</li><li>Remote penuh (WFH)</li><li>Laptop disediakan</li><li>Asuransi kesehatan BPJS + swasta</li><li>Tunjangan internet</li></ul><p>Kirim CV ke: <strong>careers@company.com</strong> dengan subject: "Laravel Dev - ForumKita"</p>',
            'views'=>2100,'likes'=>34,'replies'=>5,'tags'=>['karir','laravel']]);

        $threads[] = $mkThread(['user'=>$users[8],'cat'=>$cats[8],
            'title'=>'Tips Bikin CV yang Dilirik HRD — dari Sudut Pandang Recruiter',
            'body'=>'<p>Sebagai seseorang yang pernah ikut proses rekrutmen, ini tips bikin CV yang dilirik:</p><ol><li><strong>1 halaman untuk fresh grad</strong> — HRD baca ratusan CV, yang panjang sering dilewati</li><li><strong>Quantify achievements</strong> — Bukan "membantu project" tapi "memimpin project yang meningkatkan revenue 30%"</li><li><strong>Tulis skill yang relevan</strong> — Jangan listing semua software yang pernah disentuh</li><li><strong>Foto profesional</strong> — Pakai foto formal, background polos, ekspresi ramah</li><li><strong>Gunakan ATS-friendly format</strong> — Avoid table dan kolom, gunakan format yang bisa dibaca mesin</li><li><strong>Custom CV untuk setiap lamaran</strong> — Sesuaikan dengan job description yang ada</li></ol><p>Semoga membantu teman-teman yang lagi job hunting!</p>',
            'views'=>1560,'likes'=>67,'replies'=>8,'tags'=>['karir','tips']]);

        // === JUAL BELI ===
        $threads[] = $mkThread(['user'=>$users[11],'cat'=>$cats[9],
            'title'=>'[JUAL] MacBook Air M2 2022 — Mulus, Lengkap, Garansi Sisa',
            'body'=>'<p>Menjual MacBook Air M2 2022 dalam kondisi mulus!</p><h3>Spesifikasi</h3><ul><li>Apple M2 Chip (8-core CPU, 8-core GPU)</li><li>RAM: 8GB Unified Memory</li><li>Storage: 256GB SSD</li><li>Layar: 13.6" Liquid Retina, 2560x1664</li><li>Warna: Midnight (hitam)</li><li>Kondisi: 98% mulus, tidak ada goresan</li></ul><h3>Kelengkapan</h3><ul><li>Box original</li><li>Charger 30W original</li><li>Dokumentasi lengkap</li><li>Garansi Apple tersisa: 8 bulan</li></ul><p><strong>Harga: Rp 13.500.000 (nego tipis)</strong></p><p>Hub: DM atau WhatsApp di profil. Lokasi: Jakarta Selatan.</p>',
            'views'=>890,'likes'=>12,'replies'=>3,'tags'=>['gadget','rekomendasi']]);

        $threads[] = $mkThread(['user'=>$users[3],'cat'=>$cats[9],
            'title'=>'[BELI] Cari iPhone 14 Pro Max Second — Budget 12 Juta',
            'body'=>'<p>Dicari iPhone 14 Pro Max dalam kondisi bagus untuk diri sendiri!</p><h3>Kriteria yang Dicari</h3><ul><li>iPhone 14 Pro Max, storage minimal 256GB</li><li>Kondisi minimal 85%, tidak ada dead pixel</li><li>Baterai minimal 80%</li><li>iCloud sudah di-logout sebelumnya</li><li>Kelengkapan: minimal box dan charger</li></ul><p><strong>Budget: Rp 12.000.000</strong> (bisa nego kalau kondisi sesuai)</p><p>Lokasi buyer: Jakarta Timur. Bisa COD area Jabodetabek atau kirim via JNE/J&T.</p><p>Hubungi via DM!</p>',
            'views'=>560,'likes'=>4,'replies'=>2,'tags'=>['gadget']]);

        // === BERITA & POLITIK ===
        $threads[] = $mkThread(['user'=>$users[6],'cat'=>$cats[5],
            'title'=>'Perkembangan AI di Indonesia 2025 — Peluang atau Ancaman?',
            'body'=>'<p>AI semakin masif masuk ke berbagai sektor di Indonesia. Ini analisis saya:</p><h3>Peluang</h3><ul><li>Efisiensi operasional bisnis meningkat drastis</li><li>Munculnya profesi baru: AI Engineer, Prompt Engineer, ML Ops</li><li>Startup AI Indonesia mulai mendapat pendanaan besar</li><li>Pemerintah mulai serius dengan regulasi AI nasional</li></ul><h3>Ancaman</h3><ul><li>Beberapa pekerjaan repetitif mulai digantikan AI</li><li>Misinformasi dan deepfake semakin mudah dibuat</li><li>Kesenjangan digital antara yang bisa dan tidak bisa memanfaatkan AI</li></ul><p>Menurut kalian, bagaimana posisi Indonesia dalam perkembangan AI global?</p>',
            'views'=>2670,'likes'=>59,'replies'=>13,'tags'=>['diskusi','teknologi']]);

        // More threads for pagination (30+ total)
        $extraTitles = [
            ['cat'=>1,'user'=>2,'title'=>'Cara Optimasi Query MySQL untuk Performa Maksimal','tags'=>['mysql','tips']],
            ['cat'=>1,'user'=>4,'title'=>'Belajar TypeScript dari Nol — Worth It di 2025?','tags'=>['javascript','tutorial']],
            ['cat'=>1,'user'=>6,'title'=>'Git Workflow Best Practices untuk Tim Developer','tags'=>['tutorial','tips']],
            ['cat'=>1,'user'=>8,'title'=>'Redis vs Memcached — Pilih Cache yang Mana?','tags'=>['tips','diskusi']],
            ['cat'=>2,'user'=>5,'title'=>'PUBG Mobile vs Free Fire — Battle Royale Terbaik di HP?','tags'=>['gaming','mobile']],
            ['cat'=>2,'user'=>2,'title'=>'Rekomendasi Headset Gaming Budget 500rb-1 Juta','tags'=>['gaming','rekomendasi','gadget']],
            ['cat'=>3,'user'=>9,'title'=>'Review Honda PCX 160 2025 — King of Matic Premium?','tags'=>['otomotif','review']],
            ['cat'=>3,'user'=>11,'title'=>'Tips Beli Mobil Bekas yang Aman — Hindari Zonk!','tags'=>['otomotif','tips']],
            ['cat'=>4,'user'=>3,'title'=>'Skincare Routine untuk Pemula — Mulai dari Mana?','tags'=>['tips','rekomendasi']],
            ['cat'=>4,'user'=>5,'title'=>'Review Nasi Goreng Viral di TikTok — Worth the Hype?','tags'=>['kuliner','review']],
            ['cat'=>5,'user'=>6,'title'=>'Ranking Pemain Badminton Indonesia Terbaik 2025','tags'=>['olahraga','diskusi']],
            ['cat'=>7,'user'=>10,'title'=>'Album Musik Indonesia Terbaik 2024 — Review Lengkap','tags'=>['musik','review']],
            ['cat'=>8,'user'=>2,'title'=>'Tips Lolos Interview Kerja di Perusahaan Tech','tags'=>['karir','tips']],
            ['cat'=>9,'user'=>8,'title'=>'[JUAL] GPU RTX 4070 Super — Mulus Garansi Resmi','tags'=>['gadget']],
        ];

        foreach ($extraTitles as $ex) {
            $threads[] = $mkThread([
                'user'=>$users[$ex['user']],
                'cat'=>$cats[$ex['cat']],
                'title'=>$ex['title'],
                'body'=>'<p>'.str_repeat('Ini adalah thread diskusi tentang '.$ex['title'].'. Mari kita diskusikan bersama! Silakan share pendapat dan pengalaman kalian di kolom komentar. ', 3).'</p>',
                'tags'=>$ex['tags'],
                'views'=>rand(100,2000),'likes'=>rand(5,60),'replies'=>rand(0,10),
            ]);
        }

        // ─── REPLIES ────────────────────────────────────
        $replyMap = [
            [0,2,'<p>Siap kak admin! Aturannya sudah dibaca. Semangat ForumKita! 🙌</p>',false],
            [0,4,'<p>Noted! Semoga forum ini makin rame dan banyak diskusi berkualitas. GG!</p>',false],
            [0,5,'<p>Akhirnya ada forum lokal yang serius dikelola. Ditunggu fitur-fitur barunya admin!</p>',false],
            [1,2,'<p>Makasih sharingnya! Saya lagi di step 4, baca dokumentasi. Emang berat di awal tapi lama-lama paham.</p>',false],
            [1,8,'<p>Tambahin juga <strong>Laravel Sanctum</strong> untuk API auth dan Breeze untuk starter kit. Sekarang lebih gampang dari dulu!</p>',true],
            [1,1,'<p>Bisa rekomendasiin channel YouTube bahasa Indonesia selain Laracasts?</p>',false],
            [1,2,'<p>@usertesting Ada! Coba <strong>Kawan Koding</strong>, <strong>Web Programming Unpas</strong>, dan <strong>Parsinta</strong>. Bagus semua!</p>',false],
            [6,4,'<p>Laravel memang framework terbaik untuk web dev di PHP! Routing-nya elegant banget.</p>',false],
            [7,2,'<p>Tim Vue di sini! Sintaks jauh lebih intuitif, dokumentasi ada bahasa Indonesia, dan community supportive.</p>',false],
            [7,8,'<p>Di dunia kerja React masih raja. Kalau tujuannya cari kerja, React adalah pilihan paling safe.</p>',false],
            [7,6,'<p>Angular di enterprise gede masih banyak dipakai. TypeScript-nya bikin lebih structured dan maintainable.</p>',false],
            [12,4,'<p>Setuju soal toxic-nya. Tapi gameplay memang addictive. Ranked Immortal susah turun wkwkwk.</p>',false],
            [12,7,'<p>Saya sudah uninstall setelah kena ban unfair. Nyesel beli skin jutaan! Sekarang main CS2 aja.</p>',false],
            [12,4,'<p>@fitrihandayani Bisa appeal ke Riot Support! Teman saya berhasil dapat akunnya balik.</p>',false],
            [13,6,'<p>Build yang bagus! Pertimbangin juga Intel Arc A770, performa lumayan setelah update driver terbaru.</p>',false],
            [13,4,'<p>Untuk mobo, Gigabyte B550M AORUS Elite lebih bagus VRM-nya kalau mau OC CPU.</p>',true],
            [13,8,'<p>Jangan lupa ganti thermal paste! Pakai Thermal Grizzly Kryonaut buat suhu lebih adem.</p>',false],
            [16,3,'<p>Saya tambahkan: kurangi screen time 1 jam sebelum tidur. Kualitas tidur jadi jauh lebih baik!</p>',false],
            [16,9,'<p>Meal prep is game changer! Hemat waktu dan uang, plus makan lebih sehat karena masak sendiri.</p>',false],
            [1,2,'<p>Jangan lupa juga pelajari Laravel Queue dan Job untuk background processing. Penting banget di production!</p>',false],
            [6,8,'<p>Keren sharingnya! Saya tambahkan: pelajari juga Laravel Livewire untuk reactive UI tanpa perlu banyak JS.</p>',false],
        ];

        foreach ($replyMap as [$tIdx, $uIdx, $body, $isSol]) {
            if (!isset($threads[$tIdx]) || !isset($users[$uIdx])) continue;
            Reply::create([
                'thread_id'=>$threads[$tIdx]->id,
                'user_id'=>$users[$uIdx]->id,
                'body'=>$body,
                'is_solution'=>$isSol,
                'likes_count'=>rand(0,25),
                'created_at'=>$threads[$tIdx]->created_at->addMinutes(rand(10,1440)),
            ]);
        }

        // Sync actual replies_count
        foreach ($threads as $t) {
            $t->update(['replies_count' => $t->replies()->count()]);
        }

        // Mark solved threads
        foreach ($threads as $t) {
            if ($t->replies()->where('is_solution', true)->exists()) {
                $t->update(['is_solved' => true]);
            }
        }

        // ─── LIKES ──────────────────────────────────────
        foreach (array_slice($threads, 0, 25) as $thread) {
            $likers = collect($users)->random(min(rand(2,8), count($users)));
            foreach ($likers as $liker) {
                Like::firstOrCreate([
                    'user_id'=>$liker->id,
                    'likeable_id'=>$thread->id,
                    'likeable_type'=>Thread::class,
                ]);
            }
        }

        // ─── NOTIFICATIONS ───────────────────────────────
        foreach ([$admin, $testUser] as $target) {
            for ($n = 0; $n < 5; $n++) {
                $actor = $users[array_rand($users)];
                if ($actor->id === $target->id) continue;
                ForumNotification::create([
                    'user_id'=>$target->id,'actor_id'=>$actor->id,
                    'type'=>['reply','like','mention'][rand(0,2)],
                    'data'=>json_encode(['thread_slug'=>$threads[rand(0,5)]->slug]),
                    'read_at'=>rand(0,1) ? now() : null,
                    'created_at'=>now()->subHours(rand(1,72)),
                ]);
            }
        }

        $this->command->info('✅ Seeding selesai!');
        $this->command->info('   Admin : admin@forumkita.id  / Admin123!!');
        $this->command->info('   User  : user@forumkita.id   / User123!!');
        $this->command->info('   Member: Password123!!');
        $this->command->info('   Total : '.count($users).' users | '.count($cats).' kategori | '.count($threads).' threads');
    }
}
