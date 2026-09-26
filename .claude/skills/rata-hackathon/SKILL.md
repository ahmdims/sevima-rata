---
name: rata-hackathon
description: Panduan membangun RATA (Ruang Ajar Tepat Level), project Hackathon SEMESTA 8 bertema SDG 4 Quality Education. Gunakan setiap kali mengerjakan kode, fitur, prompt AI, UI, README, slide, video demo, atau narasi teknis di repo sevima-rata, atau saat perlu memutuskan scope/prioritas fitur karena waktu hackathon terbatas (deadline 16.45 WIB).
---

# RATA — Panduan Pengerjaan Hackathon

RATA adalah aplikasi asesmen diagnostik berbasis AI. Guru mengetik topik, AI membuat soal 4 level dengan distraktor berlabel miskonsepsi, siswa menjawab lewat kode kelas, sistem mengelompokkan siswa per level, lalu AI membuat materi berbeda untuk tiap kelompok (pendekatan Teaching at the Right Level).

**Sumber kebenaran:** `docs/PRD.md` (fitur, prioritas, model data, route, jadwal) dan `docs/BRAINSTORMING.md` (alasan pemilihan ide). Baca PRD sebelum menambah atau mengubah fitur. Jika permintaan user bertentangan dengan PRD, ikuti user, lalu tawarkan update PRD.

## Konteks lomba yang memengaruhi setiap keputusan
- **Individual, 8 jam, deadline 16.45 WIB.** Waktu adalah batasan utama. Selalu pilih solusi paling sederhana yang berhasil.
- **Juri menilai dari video demo ≤5 menit, repo public, slide ≤10, dan narasi teknis ≤500 kata.** Tidak ada presentasi. Fitur yang tidak terlihat di demo nilainya kecil.
- **Commit history adalah bukti pengerjaan.** Semua kode harus baru. Jangan menyalin project lama.
- **"AI boleh, tapi harus paham fundamental."** Kode harus bisa dijelaskan. Hindari abstraksi berlebihan.

## Tech stack (jangan diganti tanpa alasan kuat)
- Laravel (PHP 8.2 di Laragon), Blade + Tailwind + Alpine.js, SQLite.
- LLM: Claude API via `Http::` Laravel. Konfigurasi lewat `.env`: `ANTHROPIC_API_KEY`, `LLM_MODEL` (default `claude-haiku-4-5-20251001`), `LLM_FAKE`.
- Tidak memakai SPA/React, queue worker, Redis, atau Docker. Semua itu menambah waktu setup tanpa menambah nilai demo.

## Prioritas
Kerjakan **P0 dulu sampai alur end-to-end jalan**, baru lanjut ke P1. Urutan P0: data model + seeder → alur siswa + level deterministik → dashboard guru → AI generate soal (+ fallback) → AI generate materi.

Jika waktu mepet, ikuti **Cut List** di PRD §14. Empat hal ini tidak boleh dipotong: AI generate soal, dashboard kelompok, AI materi per kelompok, dan fallback demo.

Jika user meminta fitur di luar PRD, ingatkan dampaknya ke waktu secara singkat, lalu kerjakan jika user tetap mau.

## Aturan domain
- **Level dihitung deterministik, tanpa LLM.** Level = level tertinggi `n` di mana siswa benar ≥2 dari 3 soal di setiap level 1..n. Jika tidak ada, level 0 ("Pra-Dasar"). Taruh logika ini di satu tempat (mis. `app/Services/LevelScorer.php`) dan buat unit test-nya. Ini poin yang akan dijelaskan di narasi teknis.
- Miskonsepsi siswa = label dari opsi salah yang dipilih, diurutkan berdasarkan frekuensi.
- Ke siswa, tampilkan level dengan bahasa ramah anak ("Kamu sudah jago membandingkan pecahan! Yuk latihan menjumlah pecahan"), bukan skor atau angka gagal.
- Siswa hanya menyimpan nama panggilan, tanpa akun dan tanpa data pribadi lain.

## Aturan integrasi AI
- Semua pemanggilan LLM lewat service terpusat (`app/Services/LlmClient.php` untuk HTTP, `app/Services/RataAi.php` untuk tiga fungsi: `generateDiagnostic`, `generateMaterial`, `generateFeedback`). Controller tidak boleh memanggil API langsung.
- Minta output **JSON saja** sesuai skema di PRD §11. Setelah itu:
  1. Ambil blok JSON dari respons, lalu `json_decode`.
  2. Validasi strukturnya: 4 level × 3 soal, `answer_key` ada di opsi, setiap opsi salah punya `misconception`.
  3. Jika tidak valid, retry maksimal 2x sambil menyertakan pesan error validasi.
  4. Jika tetap gagal atau `LLM_FAKE=true`, pakai fixture dari `database/fixtures/*.json`.
- Simpan hasil AI ke DB. Jangan memanggil ulang LLM saat halaman di-refresh.
- Prompt memakai Bahasa Indonesia, menyebut jenjang, dan meminta konten ramah anak. Distraktor harus berupa miskonsepsi umum yang spesifik, bukan jawaban acak.
- Tampilkan loading state (Alpine) karena pemanggilan bisa memakan 5–20 detik. Set timeout HTTP ±60 detik.
- Jangan pernah commit API key. Pastikan `.env` ada di `.gitignore` dan `.env.example` berisi placeholder.

## Konvensi kode
- Nama route dan tampilan dalam Bahasa Indonesia sesuai PRD §12 (`/guru`, `/join`, `/kerjakan/{attempt}`, `/hasil/{attempt}`). Nama class/method dalam Bahasa Inggris.
- Controller tipis, logika di Service. Validasi pakai `$request->validate()`.
- Render konten Markdown dari AI dengan `Str::markdown($md, ['html_input' => 'strip'])`.
- Halaman siswa mobile-first. Uji di lebar 375px.
- Seeder harus membuat dashboard langsung terisi (1 kelas, asesmen Pecahan kelas 5, ±12 siswa dengan pola jawaban bervariasi) agar demo bisa dimulai dari `php artisan migrate:fresh --seed`.

## Git & commit
- Repo: `https://github.com/ahmdims/sevima-rata`, branch utama `master`.
- **Wajib ikuti skill `git-feature-flow`:** setiap fitur dikerjakan di branch sendiri (`feat/…`, `fix/…`, `chore/…`, `docs/…`) dan di-merge ke `master` dengan `--no-ff` hanya setelah terverifikasi dan disetujui user.
- Commit kecil setiap satu unit kerja selesai dan berjalan. Pakai format conventional: `feat:`, `fix:`, `chore:`, `docs:`, `test:`.

## Menjelang deadline (mulai jam ke-6:45)
Hentikan fitur baru dan bantu menyiapkan submission:
1. **README**: deskripsi, screenshot, cara menjalankan (`composer install`, `cp .env.example .env`, `php artisan key:generate`, `touch database/database.sqlite`, `php artisan migrate:fresh --seed`, `npm install && npm run build`, `php artisan serve`), penjelasan `LLM_FAKE`, dan tech stack.
2. **Repo**: nama `sevima-rata`, public, tag/topic `hackathonsemesta2026`.
3. **Slide ≤10**: gunakan kerangka di PRD §16.
4. **Narasi teknis ≤500 kata, 5 field**: gunakan kerangka di PRD §17. Hitung jumlah kata.
5. **Video ≤5 menit**: ikuti skenario di PRD §15. Sebelum merekam, jalankan `migrate:fresh --seed`.
6. Submit di `sevi.ma/semesta2026-submission` sebelum **16.45 WIB**.
