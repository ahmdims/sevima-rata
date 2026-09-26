# PRD — RATA: Ruang Ajar Tepat Level

| | |
|---|---|
| **Versi** | 1.0 (Hackathon SEMESTA 8) |
| **Tanggal** | 26 September 2026 |
| **Repo** | `sevima-rata` · tag `hackathonsemesta2026` |
| **Tema** | Empowering Youth for a Sustainable Future: Build with AI — SDG 4 Quality Education |
| **Deadline** | 16.45 WIB |

---

## 1. Ringkasan

**RATA** membantu guru menjalankan **asesmen diagnostik → pengelompokan siswa per level → materi berdiferensiasi** dalam hitungan menit, bukan hari. Guru cukup menulis topik. AI membuat soal diagnostik lengkap dengan distraktor yang dipetakan ke miskonsepsi. Siswa menjawab lewat HP dengan kode kelas. Sistem menghitung level tiap siswa, lalu AI membuat materi yang tepat untuk tiap kelompok.

**Tagline:** *Setiap anak belajar di level yang tepat.*

## 2. Problem Statement

Dalam satu kelas, rentang kemampuan siswa bisa sangat lebar. Guru mengajar sesuai kurikulum kelas, bukan sesuai kemampuan riil siswa, sehingga siswa yang tertinggal makin tertinggal. Kurikulum Merdeka mewajibkan **asesmen diagnostik** dan **pembelajaran berdiferensiasi**, tetapi guru dengan 30–40 siswa tidak punya waktu untuk:
1. menyusun soal diagnostik yang bisa membedakan level dan mengungkap miskonsepsi,
2. menganalisis jawaban per siswa,
3. menyiapkan 3–4 versi materi untuk kelompok berbeda.

**Bukti pendukung** (cantumkan sumber di slide):
- Skor PISA 2022 Indonesia jauh di bawah rata-rata OECD (Matematika 366, Membaca 359).
- Pendekatan *Teaching at the Right Level* (Pratham/J-PAL) terbukti meningkatkan kemampuan dasar secara signifikan di berbagai negara.
- Regulasi Kurikulum Merdeka tentang asesmen diagnostik dan pembelajaran berdiferensiasi.

## 3. Target Pengguna

| Persona | Deskripsi | Kebutuhan |
|---|---|---|
| **Bu Sari, guru kelas 5 SD** (utama) | Mengajar 32 siswa, melek HP tapi tidak teknis, waktu terbatas. | Tahu siswa mana yang tertinggal dan di bagian apa, lalu dapat materi siap pakai per kelompok. |
| **Dimas, siswa kelas 5** | Memakai HP orang tua dengan kuota terbatas. | Mengerjakan soal dengan cepat tanpa daftar akun, lalu tahu bagian mana yang perlu dilatih. |

## 4. Tujuan & Metrik Keberhasilan

| Tujuan | Metrik (untuk demo/slide) |
|---|---|
| Menghemat waktu guru | Membuat asesmen diagnostik < 1 menit (vs. ±2 jam manual). |
| Analisis instan | Level dan miskonsepsi tiap siswa muncul langsung setelah siswa submit. |
| Diferensiasi nyata | Materi berbeda untuk setiap level, dibuat dengan 1 klik. |
| Inklusif | Siswa cukup memakai kode kelas + nama. Halaman ringan dan mobile-first. |

**Non-goal** (tidak dikerjakan di hackathon): LMS lengkap, manajemen sekolah, login siswa, pembayaran, aplikasi native, dan multi-bahasa daerah.

## 5. Konsep Inti: Level & Miskonsepsi

- Setiap asesmen punya **4 level** berurutan untuk satu topik, misalnya Pecahan kelas 5:
  - L1 *Dasar*: mengenali pecahan dari gambar
  - L2 *Berkembang*: membandingkan pecahan
  - L3 *Cakap*: menjumlah pecahan berpenyebut sama
  - L4 *Mahir*: menjumlah pecahan berpenyebut beda / soal cerita
- Tiap level punya **3 soal pilihan ganda** (total 12 soal).
- Tiap **opsi salah** diberi label **miskonsepsi**, misalnya "menjumlah pembilang dan penyebut langsung (1/2+1/3=2/5)".
- **Penentuan level dilakukan secara deterministik, tanpa LLM:** level siswa adalah level tertinggi `n` di mana siswa benar ≥ 2 dari 3 soal di setiap level 1..n. Kalau L1 pun belum lulus, siswa masuk kelompok **"Pra-Dasar"**.
- **Miskonsepsi siswa** adalah daftar label dari opsi salah yang dipilih, diurutkan dari yang paling sering.

> Alasan teknis (untuk narasi): LLM dipakai untuk **generate konten**, sedangkan **penilaian bersifat deterministik** supaya hasilnya konsisten, bisa diaudit, cepat, dan gratis. Guru tidak boleh mendapat level berbeda untuk jawaban yang sama.

## 6. User Flow

```
GURU                                         SISWA
1. Buat kelas (nama, mapel, jenjang)
   → dapat kode kelas (mis. RATA-7K2)
2. Buat asesmen: isi topik + jenjang
   → AI generate 12 soal + miskonsepsi
3. Review/edit soal → "Publikasikan"
                                             4. Buka /join → kode kelas + nama
                                             5. Kerjakan 12 soal (1 soal per layar)
                                             6. Lihat hasil: level + umpan balik AI
7. Dashboard: siswa dikelompokkan per level,
   miskonsepsi terbanyak di kelas
8. Klik "Buat materi" per kelompok
   → AI generate materi + 3 latihan
9. Cetak/salin materi
```

## 7. Kebutuhan Fungsional

Prioritas: **P0** wajib ada untuk demo · **P1** sebaiknya ada · **P2** hanya jika ada waktu.

### 7.1 Guru
| ID | Fitur | Prioritas |
|---|---|---|
| F-01 | Membuat kelas dan otomatis mendapat kode kelas unik (6 karakter) | P0 |
| F-02 | Membuat asesmen dari input topik + jenjang (+ catatan opsional); AI generate 4 level × 3 soal dalam format JSON terstruktur | P0 |
| F-03 | Melihat pratinjau soal beserta kunci jawaban dan label miskonsepsi | P0 |
| F-04 | Mengedit teks soal/opsi sebelum publikasi | P1 |
| F-05 | Membuat ulang satu soal ("regenerate") | P2 |
| F-06 | Dashboard hasil: jumlah siswa per level (bar), daftar siswa per kelompok, top-5 miskonsepsi kelas | P0 |
| F-07 | Membuat materi per kelompok: penjelasan konsep sesuai level, contoh, 3 latihan + kunci, dan tips mengajar untuk guru | P0 |
| F-08 | Cetak materi (print-friendly CSS) / salin ke clipboard | P1 |
| F-09 | Login guru (Laravel Breeze) | P2 — untuk demo cukup satu akun guru hasil seeder |

### 7.2 Siswa
| ID | Fitur | Prioritas |
|---|---|---|
| S-01 | Masuk dengan kode kelas + nama tanpa akun | P0 |
| S-02 | Mengerjakan soal satu per satu, tampil mobile-first dengan progress bar | P0 |
| S-03 | Halaman hasil: level dalam bahasa ramah anak (bukan skor), daftar yang perlu dilatih | P0 |
| S-04 | Umpan balik personal dari AI (2–3 kalimat, menyemangati, menyebut miskonsepsi utama) | P1 |
| S-05 | Urutan soal diacak | P2 |

### 7.3 Sistem
| ID | Kebutuhan | Prioritas |
|---|---|---|
| X-01 | Output LLM divalidasi terhadap skema (jumlah level/soal, satu jawaban benar, setiap opsi salah punya miskonsepsi). Jika tidak valid, retry maksimal 2x. | P0 |
| X-02 | **Mode demo/fallback:** jika API gagal atau `LLM_FAKE=true`, pakai data contoh (topik Pecahan kelas 5) dari seeder/fixture JSON | P0 |
| X-03 | Seeder: 1 guru, 1 kelas, 1 asesmen Pecahan, dan ±12 siswa dengan jawaban bervariasi agar dashboard langsung terisi | P0 |
| X-04 | Menampilkan loading state saat LLM bekerja (bisa 5–20 detik) | P0 |

## 8. Kebutuhan Non-Fungsional
- **Mobile-first** untuk halaman siswa. Halaman ringan (tanpa SPA berat), target < 200 KB per halaman di luar font.
- **Bahasa Indonesia** di seluruh UI dan output AI.
- **Keamanan dasar:** API key hanya di `.env` (jangan di-commit), CSRF aktif, validasi input, dan escape output (konten AI di-render sebagai Markdown yang disanitasi).
- **Privasi anak:** hanya menyimpan nama panggilan tanpa data pribadi lain. Konten AI dibatasi agar ramah anak.
- **Keandalan demo:** semua alur utama bisa dijalankan tanpa internet memakai `LLM_FAKE=true`.

## 9. Tech Stack

| Lapisan | Pilihan | Alasan |
|---|---|---|
| Backend | **Laravel 11/12** (PHP 8.2, sudah ada di Laragon) | Scaffolding cepat, Eloquent, migration dan seeder. |
| Frontend | **Blade + Tailwind CSS + Alpine.js** | Tanpa build SPA, ringan untuk HP murah, cepat dikerjakan sendirian. |
| Database | **SQLite** | Nol konfigurasi, mudah dijalankan juri dari repo. |
| AI | **Claude API** (`claude-haiku-4-5` untuk kecepatan, `claude-sonnet-5` bila perlu kualitas lebih) lewat SDK resmi `anthropic-ai/sdk` | Dukungan output JSON terstruktur dan Bahasa Indonesia yang baik. Model diatur lewat `.env` (`LLM_MODEL`). |
| Chart | Bar chart CSS murni atau Chart.js via CDN | Cukup untuk distribusi level. |

## 10. Model Data

```
classrooms   id, name, subject, grade, code (unique), timestamps
assessments  id, classroom_id, topic, grade, notes, status [draft|published], level_labels (json), timestamps
questions    id, assessment_id, level (1-4), order, stem, options (json: [{key, text, misconception|null}]), answer_key, explanation
attempts     id, assessment_id, student_name, level (0-4, null jika belum selesai), misconceptions (json), feedback (text, nullable), finished_at
answers      id, attempt_id, question_id, chosen_key, is_correct
materials    id, assessment_id, level (0-4), content_md, timestamps
```

## 11. Rancangan Integrasi AI

Semua pemanggilan LLM dilakukan lewat satu service, misalnya `app/Services/LlmClient.php` dan `app/Services/RataAi.php`, dengan 3 fungsi:

| Fungsi | Input | Output (JSON) |
|---|---|---|
| `generateDiagnostic(topic, grade, notes)` | topik, jenjang | `{levels:[{level, label, description, questions:[{stem, options:[{key,text,misconception}], answer_key, explanation}]}]}` |
| `generateMaterial(assessment, level, topMisconceptions, studentCount)` | level target + miskonsepsi kelompok | `{title, concept_md, examples_md, exercises:[{q, answer}], teacher_tips_md}` |
| `generateFeedback(studentName, level, misconceptions)` | hasil siswa | `{message}` |

**Aturan prompt:**
- System prompt berisi peran ("guru ahli pedagogi & perancang asesmen diagnostik Kurikulum Merdeka"), jenjang, bahasa Indonesia, dan larangan konten tidak ramah anak.
- Minta **JSON saja** sesuai skema. Parse dan validasi di PHP. Jika gagal, retry sambil menyertakan pesan error. Jika tetap gagal, pakai fallback.
- Distraktor harus merepresentasikan **miskonsepsi yang umum dan spesifik**, bukan jawaban acak.
- Simpan hasil ke DB agar LLM tidak dipanggil ulang saat halaman di-refresh.

## 12. Halaman / Route

| Route | Halaman |
|---|---|
| `GET /` | Landing singkat: masalah, solusi, dan tombol "Masuk sebagai Guru" / "Saya Siswa" |
| `GET /guru` | Daftar kelas & asesmen |
| `POST /guru/kelas` | Buat kelas |
| `GET /guru/asesmen/buat` · `POST /guru/asesmen` | Form topik → generate |
| `GET /guru/asesmen/{id}` | Pratinjau/edit soal, tombol publikasi |
| `GET /guru/asesmen/{id}/hasil` | Dashboard kelompok & miskonsepsi |
| `POST /guru/asesmen/{id}/materi/{level}` | Generate materi kelompok |
| `GET /join` · `POST /join` | Input kode kelas + nama |
| `GET /kerjakan/{attempt}` · `POST /kerjakan/{attempt}` | Soal satu per satu |
| `GET /hasil/{attempt}` | Hasil siswa |

## 13. Rencana Kerja 8 Jam

| Waktu (perkiraan) | Target | Branch |
|---|---|---|
| Jam 0:00–0:30 | Laravel, SQLite, Tailwind, topic repo `hackathonsemesta2026`, README awal | `chore/init-laravel` |
| 0:30–1:30 | Migration, model, relasi, dan seeder data demo (Pecahan kelas 5, 12 siswa) | `feat/data-model-seeder` |
| 1:30–2:30 | Alur siswa: join → kerjakan → hasil, beserta logika level deterministik | `feat/student-flow` |
| 2:30–3:30 | Dashboard guru: distribusi level, kelompok, top miskonsepsi | `feat/teacher-dashboard` |
| 3:30–4:30 | **Istirahat 1 jam** | |
| 4:30–5:30 | Integrasi LLM: generateDiagnostic + validasi + fallback, form buat asesmen | `feat/ai-diagnostic` |
| 5:30–6:15 | generateMaterial + halaman materi print-friendly, lalu generateFeedback (P1) | `feat/ai-materials` |
| 6:15–6:45 | Polish UI, landing page, uji alur end-to-end, README lengkap | `docs/readme-polish` |
| 6:45–8:00 | **Rekam demo video, buat slide (≤10), tulis narasi teknis (≤500 kata), submit sebelum 16.45** | |

> **Git workflow:** repo `github.com/ahmdims/sevima-rata`, branch utama `master`. Setiap baris di atas dikerjakan di branch-nya sendiri, di-commit kecil-kecil, lalu di-merge ke `master` dengan `--no-ff` setelah fix dan terverifikasi. Detailnya ada di skill `git-feature-flow`.

## 14. Cut List (bila terlambat, potong dari atas)
1. F-09 login guru → pakai satu guru hasil seeder
2. S-05 acak soal
3. F-05 regenerate satu soal
4. F-04 edit soal → cukup pratinjau
5. S-04 umpan balik AI → pakai teks template per level
6. F-08 cetak → cukup tampilkan di layar

**Jangan dipotong:** F-02 (AI generate soal), F-06 (dashboard kelompok), F-07 (AI materi per kelompok), X-02 (fallback demo). Keempatnya adalah inti cerita.

## 15. Skenario Demo Video (≤ 5 menit)
1. **0:00–0:40 Masalah:** satu kelas punya kemampuan yang beragam; guru tidak punya waktu berdiferensiasi (tampilkan data PISA dan kewajiban Kurikulum Merdeka).
2. **0:40–1:40 Guru membuat asesmen:** ketik "Pecahan, kelas 5" → soal diagnostik 4 level lengkap dengan label miskonsepsi → publikasi.
3. **1:40–2:40 Siswa mengerjakan** dari tampilan HP (DevTools mobile), 2 siswa dengan pola jawaban berbeda → halaman hasil ramah anak.
4. **2:40–3:50 Dashboard guru:** siswa terkelompok otomatis, miskonsepsi terbanyak → klik "Buat materi" untuk kelompok L1 dan L3, bandingkan hasilnya.
5. **3:50–4:40 Keputusan teknis:** LLM untuk generate, logika deterministik untuk menilai; validasi skema + fallback; alasan memilih Laravel + SQLite ringan.
6. **4:40–5:00 Dampak & penutup.**

## 16. Kerangka Slide Deck (≤ 10 slide)
1. Judul: RATA — Setiap anak belajar di level yang tepat
2. Problem statement + data
3. Kenapa solusi yang ada belum cukup
4. Solusi: RATA dalam 3 langkah
5. Demo alur (screenshot)
6. Cara kerja AI + penilaian deterministik (diagram)
7. Tech stack & arsitektur
8. Value: untuk guru, siswa, dan sekolah (+ keterkaitan SDG 4)
9. Roadmap: bahasa daerah, mode offline/PWA, integrasi LMS/SEVIMA
10. Penutup + link repo & video

## 17. Narasi Teknis (≤ 500 kata, 5 field)
Isi sesuai 5 field di form submission. Draf yang disiapkan:
1. **Problem Statement:** masalah, siapa yang terdampak, dan bukti datanya.
2. **Solusi:** apa yang dibuat dan alur utamanya.
3. **Keputusan Teknis:** stack, pemisahan LLM (generate) vs. logika deterministik (menilai), validasi skema, fallback.
4. **Tantangan & Cara Mengatasi:** output LLM tidak konsisten (diatasi dengan skema + retry), latensi (loading state + simpan hasil).
5. **Dampak & Pengembangan Lanjut.**

## 18. Risiko & Mitigasi
| Risiko | Mitigasi |
|---|---|
| API LLM lambat, gagal, atau kuota habis saat demo | `LLM_FAKE=true` + fixture JSON; rekam video dengan data yang sudah di-generate |
| Output JSON rusak atau soal salah kunci | Validasi skema, retry, dan pratinjau guru sebelum publikasi |
| Waktu habis | Ikuti rencana kerja dan cut list; video/slide mulai paling lambat jam 6:45 |
| Dituduh memakai project lama | Repo dibuat saat hackathon; commit kecil dan bertahap |
