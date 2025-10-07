# ThreadLoop

ThreadLoop adalah platform media sosial modern yang dibangun dengan Laravel dan Livewire, memungkinkan pengguna untuk berbagi konten, berinteraksi, dan terhubung dengan pengguna lain di seluruh dunia.

## 🚀 Fitur Utama

### 📝 Posting Konten

-   **Text Posts**: Berbagi pemikiran dan ide dalam bentuk teks
-   **Image Posts**: Upload dan berbagi gambar dengan caption
-   **Video Posts**: Berbagi video dengan autoplay functionality
-   **Media Management**: Integrasi dengan Cloudinary untuk penyimpanan media yang optimal

### 👥 Sistem Sosial

-   **Follow/Unfollow**: Ikuti pengguna lain untuk melihat konten mereka
-   **User Profiles**: Profil pengguna dengan username unik dan foto profil
-   **Search**: Cari pengguna dan konten dengan mudah
-   **Public Sharing**: Bagikan post secara publik dengan link khusus

### 💬 Interaksi

-   **Comments**: Berikan komentar pada post pengguna lain
-   **Likes**: Suka dan tidak suka post
-   **Save Posts**: Simpan post favorit untuk dilihat nanti
-   **Notifications**: Notifikasi real-time untuk aktivitas sosial

### 🔐 Keamanan & Autentikasi

-   **Laravel Fortify**: Sistem autentikasi lengkap
-   **Two-Factor Authentication**: Keamanan tambahan dengan 2FA
-   **Email Verification**: Verifikasi email untuk keamanan akun
-   **Password Management**: Reset dan ubah password dengan aman

### ⚙️ Pengaturan Pengguna

-   **Profile Settings**: Edit profil, username, dan foto profil
-   **Appearance Settings**: Kustomisasi tampilan aplikasi
-   **Password Settings**: Kelola keamanan password
-   **Two-Factor Settings**: Konfigurasi autentikasi dua faktor

## 🛠️ Teknologi yang Digunakan

### Backend

-   **Laravel 12**: Framework PHP modern
-   **Livewire**: Full-stack framework untuk aplikasi dinamis
-   **Laravel Fortify**: Autentikasi dan otorisasi
-   **Livewire Flux**: Komponen UI yang indah dan responsif
-   **Cloudinary**: Manajemen media dan CDN

### Frontend

-   **Tailwind CSS 4**: Framework CSS utility-first
-   **Vite**: Build tool modern dan cepat
-   **Livewire Volt**: Syntax yang lebih sederhana untuk Livewire

### Database

-   **SQLite**: Database ringan untuk development
-   **Eloquent ORM**: ORM yang powerful untuk Laravel

### Testing

-   **Pest**: Testing framework yang elegan
-   **Laravel Testing**: Testing tools untuk Laravel

## 📋 Persyaratan Sistem

-   PHP 8.2 atau lebih tinggi
-   Composer
-   Node.js dan npm
-   SQLite (atau MySQL/PostgreSQL untuk production)

## 🚀 Instalasi

### 1. Clone Repository

```bash
git clone <repository-url>
cd thread-loop
```

### 2. Install Dependencies

```bash
# Install PHP dependencies
composer install

# Install Node.js dependencies
npm install
```

### 3. Environment Setup

```bash
# Copy environment file
cp .env.example .env

# Generate application key
php artisan key:generate
```

### 4. Database Setup

```bash
# Create SQLite database (jika belum ada)
touch database/database.sqlite

# Run migrations
php artisan migrate

# Seed database dengan data sample
php artisan db:seed
```

### 5. Build Assets

```bash
# Build untuk production
npm run build

# Atau jalankan dalam mode development
npm run dev
```

### 6. Jalankan Aplikasi

```bash
# Jalankan server development
php artisan serve

# Atau gunakan composer script untuk menjalankan semua service
composer run dev
```

Aplikasi akan tersedia di `http://localhost:8000`

## 🗄️ Struktur Database

### Tabel Utama

-   **users**: Data pengguna dengan username dan profil
-   **posts**: Konten yang dibagikan pengguna (text, image, video)
-   **comments**: Komentar pada post
-   **post_likes**: Relasi like antara user dan post
-   **follows**: Relasi follow antar pengguna
-   **saved_posts**: Post yang disimpan pengguna
-   **notifications**: Notifikasi sistem

### Fitur Database

-   **Performance Indexes**: Optimasi query untuk performa yang lebih baik
-   **Weighted Feed Algorithm**: Algoritma feed berdasarkan likes dan comments
-   **Efficient Relationships**: Relasi yang dioptimasi untuk menghindari N+1 queries

## 🧪 Testing

```bash
# Jalankan semua test
composer run test

# Atau menggunakan artisan
php artisan test
```

## 📱 Fitur Unggulan

### Feed Algorithm

ThreadLoop menggunakan algoritma feed yang cerdas yang mempertimbangkan:

-   Jumlah likes (bobot 70%)
-   Jumlah comments (bobot 30%)
-   Waktu posting

### Media Management

-   Upload otomatis ke Cloudinary
-   Optimasi gambar dan video
-   CDN global untuk performa terbaik

### Real-time Features

-   Notifikasi real-time
-   Infinite scroll untuk feed
-   Autoplay video yang responsif

## 🔧 Konfigurasi

### Cloudinary Setup

Tambahkan konfigurasi Cloudinary di `.env`:

```env
CLOUDINARY_URL=cloudinary://your-cloudinary-url
```

### Queue Configuration

Untuk notifikasi real-time, pastikan queue worker berjalan:

```bash
php artisan queue:work
```

## 📈 Performance

-   **Database Optimization**: Index yang tepat untuk query cepat
-   **Eager Loading**: Menghindari N+1 queries
-   **Caching**: Cache untuk data yang sering diakses
-   **CDN**: Cloudinary CDN untuk media yang cepat

## 🤝 Kontribusi

1. Fork repository
2. Buat feature branch (`git checkout -b feature/AmazingFeature`)
3. Commit perubahan (`git commit -m 'Add some AmazingFeature'`)
4. Push ke branch (`git push origin feature/AmazingFeature`)
5. Buat Pull Request

## 📄 Lisensi

Proyek ini menggunakan lisensi MIT. Lihat file `LICENSE` untuk detail lebih lanjut.

## 👨‍💻 Pengembang

Dibuat dengan ❤️ menggunakan Laravel dan Livewire.

## 📞 Support

Jika Anda mengalami masalah atau memiliki pertanyaan, silakan buat issue di repository ini.

---

**ThreadLoop** - Connect, Share, and Discover! 🌟
