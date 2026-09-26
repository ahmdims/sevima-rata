# RATA — Ruang Ajar Tepat Level

> **Setiap anak belajar di level yang tepat.**
> Project Hackathon SEMESTA 8 · Tech Career Academy by SEVIMA · Tema *Empowering Youth for a Sustainable Future: Build with AI* · SDG 4 Quality Education

RATA membantu guru menjalankan **asesmen diagnostik → pengelompokan siswa per level → materi berdiferensiasi** dalam hitungan menit, bukan hari. Pendekatannya mengikuti *Teaching at the Right Level* (TaRL).

1. **Guru menulis topik.** AI membuat 12 soal diagnostik dalam 4 level, dan setiap pilihan salah diberi label miskonsepsi.
2. **Siswa mengerjakan dari HP** cukup dengan kode kelas dan nama. Level dihitung **secara deterministik** (tanpa AI) supaya konsisten dan bisa diaudit.
3. **Dashboard guru** mengelompokkan siswa per level dan menampilkan miskonsepsi terbanyak. AI lalu membuat materi yang berbeda untuk tiap kelompok.

Detail produk: [docs/PRD.md](docs/PRD.md) · Proses pemilihan ide: [docs/BRAINSTORMING.md](docs/BRAINSTORMING.md)

## Tech Stack

| Lapisan | Teknologi |
|---|---|
| Backend | Laravel 12 (PHP 8.2+) |
| Frontend | Blade, Tailwind CSS v4, Alpine.js (tanpa SPA, ringan untuk HP) |
| Database | SQLite |
| AI | Claude API (`claude-haiku-4-5-20251001`, bisa diganti lewat `LLM_MODEL`) |

## Menjalankan Secara Lokal

Prasyarat: PHP 8.2+, Composer, Node.js 20+.

```bash
git clone https://github.com/ahmdims/sevima-rata.git
cd sevima-rata
composer run setup   # install dependensi, buat .env, APP_KEY, database SQLite + data demo, build aset
composer run dev     # php artisan serve + Vite
```

Buka http://localhost:8000.

<details>
<summary>Langkah manual (tanpa <code>composer run setup</code>)</summary>

```bash
composer install
cp .env.example .env
php artisan key:generate
touch database/database.sqlite
php artisan migrate:fresh --seed
npm install && npm run build
php artisan serve
```
</details>

### Konfigurasi AI

| Variabel `.env` | Default | Keterangan |
|---|---|---|
| `ANTHROPIC_API_KEY` | — | API key Claude. |
| `LLM_MODEL` | `claude-haiku-4-5-20251001` | Model yang dipakai. |
| `LLM_FAKE` | `true` | `true` = memakai data contoh tanpa memanggil API, sehingga demo tetap jalan tanpa API key atau internet. |
| `LLM_TIMEOUT` | `60` | Timeout request (detik). |
| `LLM_MAX_RETRIES` | `2` | Jumlah retry jika output AI tidak lolos validasi skema. |

Untuk memakai AI sungguhan, isi `ANTHROPIC_API_KEY` dan set `LLM_FAKE=false`.

## Testing

```bash
php artisan test
```

## Struktur Penting

```
config/rata.php                 Konfigurasi LLM, aturan level, nama level
resources/css/app.css           Design token (warna, font, warna tiap level)
resources/views/components/     Layout (base, guru, siswa) & komponen UI
docs/                           PRD, brainstorming, materi tema hackathon
```

## Alur Pengembangan

Branch utama `master`. Setiap fitur dikerjakan di branch sendiri (`feat/…`, `fix/…`, `chore/…`, `docs/…`) dan di-merge ke `master` dengan `--no-ff` setelah terverifikasi.
