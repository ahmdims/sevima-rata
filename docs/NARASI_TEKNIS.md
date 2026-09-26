# Narasi Teknis — RATA (Ruang Ajar Tepat Level)

> Draf untuk PDF submission (maks. 500 kata, 5 field). Sesuaikan judul field dengan form resmi.

## 1. Problem Statement
Dalam satu kelas, kemampuan siswa sangat beragam, tetapi guru mengajar dengan satu tingkat yang sama sehingga siswa yang tertinggal makin tertinggal. Skor PISA 2022 Indonesia (Matematika 366, Membaca 359) jauh di bawah rata-rata OECD. Kurikulum Merdeka mewajibkan asesmen diagnostik dan pembelajaran berdiferensiasi, namun guru dengan 30–40 siswa tidak punya waktu untuk menyusun soal diagnostik, menganalisis jawaban per siswa, lalu menyiapkan materi berbeda untuk tiap kelompok.

## 2. Solusi
RATA menerapkan pendekatan *Teaching at the Right Level* dengan bantuan AI:
1. Guru menulis topik, lalu AI menyusun 12 soal dalam 4 level. Setiap pilihan salah dipetakan ke miskonsepsi yang spesifik.
2. Siswa mengerjakan dari HP cukup dengan kode kelas dan nama, satu soal per layar.
3. Dashboard langsung mengelompokkan siswa per level dan menampilkan miskonsepsi terbanyak.
4. Satu klik menghasilkan materi berdiferensiasi untuk tiap kelompok: konsep, contoh, latihan bertahap, dan tips mengajar.

Pekerjaan berhari-hari menjadi beberapa menit.

## 3. Keputusan Teknis
- **LLM untuk menghasilkan konten, logika deterministik untuk menilai.** Level dihitung dengan aturan tetap (lulus ≥2 dari 3 soal di setiap level secara berurutan) di `LevelScorer`, dan aturan ini diuji unit test. Jawaban yang sama selalu menghasilkan level yang sama, cepat, gratis, dan bisa diaudit guru.
- **Output AI divalidasi terhadap skema.** Yang dicek: jumlah level dan soal, kunci jawaban ada di opsi, dan setiap opsi salah punya miskonsepsi. Jika tidak valid, AI diminta memperbaiki dengan menyertakan daftar error (retry). Jika tetap gagal, sistem memakai fallback sehingga demo tidak pernah macet (`LLM_FAKE`).
- **Stack ringan:** Laravel 12 + Blade + Tailwind v4 + Alpine.js + SQLite, tanpa SPA. Halaman siswa tetap ringan untuk HP murah dan kuota terbatas. Claude API dipanggil lewat SDK resmi PHP melalui satu service.
- **Design system berbasis psikologi dan WCAG 2.2 AA.** Tidak ada merah, skor, atau kata "gagal" untuk siswa. Level memakai metafora pertumbuhan (Benih sampai Berbuah). Kontras warna diverifikasi dengan script lint.

## 4. Tantangan & Cara Mengatasi
- **Output LLM tidak konsisten** (format JSON, kunci salah, distraktor acak). Diatasi dengan prompt berskema, validator, retry berbasis pesan error, dan label miskonsepsi yang diseragamkan agar bisa dihitung.
- **Latensi AI 10–30 detik.** Loading menampilkan langkah yang sedang dikerjakan (*labor illusion*), dan hasil AI disimpan ke database sehingga tidak dipanggil ulang.
- **Siswa tanpa akun.** Kepemilikan attempt dijaga lewat sesi perangkat.
- **Waktu 8 jam.** Setiap fitur dikerjakan di branch terpisah dengan 25 test otomatis.

## 5. Dampak & Pengembangan Lanjut
Guru mendapat peta kemampuan kelas dan materi siap pakai dalam hitungan menit, sehingga pembelajaran berdiferensiasi benar-benar bisa dijalankan. Siswa belajar di level yang tepat tanpa rasa takut dinilai. Pengembangan berikutnya:
- asesmen ulang untuk mengukur kemajuan
- dukungan bahasa daerah
- mode offline (PWA) untuk daerah 3T
- integrasi dengan LMS/SIAKAD SEVIMA
