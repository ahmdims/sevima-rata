# Brainstorming Ide — Hackathon SEMESTA 8

> **Tema:** Empowering Youth for a Sustainable Future: Build with AI
> **Studi kasus:** SDG 4 — *Quality Education* ("Ensure inclusive and equitable quality education and promote lifelong learning")
> **Format:** Individual · 8 jam (termasuk 1 jam istirahat) · AI & vibe coding boleh · deadline **16.45 WIB, 26 Sep 2026**
> **Dinilai dari:** demo video (≤5 menit), repo GitHub public (commit history), slide deck (≤10 slide), narasi teknis PDF (≤500 kata, 5 field). **Tidak ada presentasi langsung**, jadi produk harus bisa dipahami dari video dan slide saja.

---

## 1. Kriteria Memilih Ide

| Kriteria | Kenapa penting | Bobot |
|---|---|---|
| **Masalah nyata & bisa dibuktikan** | Panitia menekankan "pastikan masalahnya nyata". Harus bisa dikutip data/kebijakan. | 30% |
| **AI jadi inti, bukan tempelan** | Tema "Build with AI". Kalau AI-nya dicopot, produknya harus ikut runtuh. | 20% |
| **Bisa selesai dalam ±6 jam coding** | Individual; sisa waktu dipakai untuk video, slide, dan narasi. | 25% |
| **Mudah didemokan dalam 5 menit** | Juri menilai lewat video. Alurnya harus terlihat "wow" dalam 1–2 menit pertama. | 15% |
| **Selaras dengan "inclusive & equitable"** | Kata kunci SDG 4: pemerataan, inklusif, dan belajar sepanjang hayat. | 10% |

---

## 2. Masalah Nyata yang Bisa Diangkat

> ⚠️ Verifikasi ulang angka sebelum dipakai di slide, lalu cantumkan sumbernya.

1. **Kesenjangan kemampuan dasar dalam satu kelas.** Skor PISA 2022 Indonesia (Matematika 366, Membaca 359, Sains 383) jauh di bawah rata-rata OECD. Dalam satu kelas ada siswa yang belum lancar operasi dasar dan ada yang sudah jauh di depan, tetapi guru mengajar dengan satu tingkat yang sama.
2. **Kurikulum Merdeka mewajibkan asesmen diagnostik dan pembelajaran berdiferensiasi.** Pada praktiknya, guru dengan 30–40 siswa per kelas tidak punya waktu membuat soal diagnostik, menganalisis hasil per siswa, lalu menyiapkan materi berbeda untuk tiap kelompok.
3. **Beban administrasi guru** (modul ajar, RPP, asesmen, rapor) menyita waktu mengajar.
4. **Literasi dasar rendah di jenjang SD.** Banyak siswa kelas atas SD belum lancar membaca pemahaman.
5. **Aksesibilitas materi untuk siswa disabilitas** (tunanetra/tunarungu) masih minim di sekolah reguler/inklusi.
6. **Mismatch lulusan SMK dengan kebutuhan industri.** Lulusan SMK termasuk penyumbang pengangguran terbuka tertinggi (data BPS), sementara pemuda tidak tahu skill apa yang harus dipelajari.
7. **Akses bimbel/tutor timpang.** Siswa di daerah 3T atau dari keluarga berpenghasilan rendah tidak punya akses les privat.

---

## 3. Daftar Ide

### Ide A — **RATA: Ruang Ajar Tepat Level** ⭐ *(direkomendasikan)*
Asesmen diagnostik berbasis AI yang otomatis **mengelompokkan siswa sesuai level kemampuannya** (pendekatan *Teaching at the Right Level* / TaRL), **mendeteksi miskonsepsi** dari jawaban yang salah, lalu **membuat materi remedial/pengayaan berbeda untuk tiap kelompok**.
- **User:** guru SD/SMP (utama), siswa (menjawab lewat kode kelas tanpa login).
- **AI dipakai untuk:** membuat soal diagnostik lengkap dengan distraktor yang dipetakan ke miskonsepsi, membuat materi per level, dan memberi umpan balik personal ke siswa.
- **Nilai jual:** mengubah pekerjaan guru yang biasanya berhari-hari (buat soal → koreksi → analisis → buat materi berdiferensiasi) menjadi beberapa menit. Langsung menjawab kewajiban Kurikulum Merdeka dan berbasis pendekatan TaRL yang sudah teruji secara riset (J-PAL/Pratham).
- **Nama "RATA"** sejalan dengan nama repo `sevima-rata` dan pesan *pemerataan* kualitas belajar.

### Ide B — **ModulKu: Asisten Guru Penyusun Modul Ajar**
Guru memasukkan CP/TP, lalu AI membuat modul ajar Kurikulum Merdeka lengkap (tujuan, kegiatan, asesmen, rubrik) dan mengekspornya ke PDF/DOCX.
- Plus: masalahnya nyata dan mudah dibuat.
- Minus: **pasarnya sudah ramai** (banyak generator modul ajar gratis). Diferensiasinya lemah dan kurang "wow".

### Ide C — **BacaBareng: Pendamping Literasi Membaca Nyaring**
Anak membaca teks dengan suara keras, lalu speech-to-text menilai kelancaran (kata per menit, kata yang salah). AI kemudian membuat pertanyaan pemahaman dan memilih teks berikutnya sesuai level.
- Plus: dampaknya sangat terasa dan demonya menarik.
- Minus: STT untuk anak dan untuk Bahasa Indonesia **kurang akurat dan berisiko gagal saat demo**. Web Speech API tidak konsisten antar-browser.

### Ide D — **InklusiAI: Konverter Materi Aksesibel**
Upload materi (PDF/foto), lalu AI mengubahnya menjadi versi bahasa sederhana, audio (TTS), deskripsi gambar untuk tunanetra, dan ringkasan visual.
- Plus: sangat kuat di aspek "inclusive".
- Minus: siapa user yang membayar atau memakainya secara rutin kurang jelas. Validasi masalahnya juga sulit tanpa data lapangan.

### Ide E — **JejakKarier: Peta Skill untuk Pemuda/SMK**
Pemuda memasukkan minat dan skill, lalu AI membandingkannya dengan data lowongan dan menampilkan skill gap, roadmap belajar 8 minggu dengan sumber gratis, serta proyek portofolio.
- Plus: selaras dengan "Empowering Youth" dan Tech Career Academy.
- Minus: lebih dekat ke SDG 8 (pekerjaan) daripada SDG 4. Kualitas outputnya bergantung pada data lowongan yang tidak tersedia secara real-time.

### Ide F — **TutorSaku: Tutor Sokratik Hemat Kuota**
Siswa memfoto soal, lalu AI **tidak memberi jawaban** melainkan membimbing dengan pertanyaan bertahap (metode Sokratik). Antarmukanya ringan dan hemat kuota.
- Plus: menjawab ketimpangan akses les.
- Minus: sangat mirip produk besar yang sudah ada (Photomath, fitur tutor ChatGPT/Gemini). Sulit menonjol.

---

## 4. Skoring

| Ide | Masalah nyata (30) | AI inti (20) | Feasible 6 jam (25) | Demoable (15) | Inklusif (10) | **Total** |
|---|---|---|---|---|---|---|
| **A. RATA** | 28 | 18 | 21 | 13 | 9 | **89** |
| B. ModulKu | 24 | 15 | 24 | 9 | 5 | 77 |
| C. BacaBareng | 27 | 16 | 13 | 14 | 8 | 78 |
| D. InklusiAI | 22 | 18 | 18 | 12 | 10 | 80 |
| E. JejakKarier | 22 | 16 | 19 | 12 | 6 | 75 |
| F. TutorSaku | 22 | 18 | 20 | 12 | 7 | 79 |

## 5. Keputusan

**Pilih Ide A: RATA.** Alasannya:
1. **Masalahnya punya landasan kuat**: data PISA, kewajiban asesmen diagnostik dalam Kurikulum Merdeka, dan riset TaRL yang terbukti meningkatkan hasil belajar.
2. **AI benar-benar inti**. Tanpa AI, guru harus menyusun soal, memetakan miskonsepsi, dan menulis materi 3–4 level secara manual.
3. **Ada bagian deterministik** (penentuan level dan pengelompokan dihitung tanpa LLM), jadi keputusan teknisnya bisa dijelaskan dengan menarik di narasi teknis: *"LLM untuk generate, logika deterministik untuk menilai"*. Ini menunjukkan peserta paham fundamental, bukan sekadar vibe coding.
4. **Demonya kuat dan singkat**: guru membuat asesmen → 3 "siswa" menjawab dari HP → dashboard langsung mengelompokkan → klik "Buat materi" → materi per kelompok muncul.

**Rencana cadangan** bila waktu mepet: potong fitur umpan balik personal siswa dan ekspor PDF (lihat bagian *Cut List* di [PRD.md](PRD.md)).

Detail produk ada di **[PRD.md](PRD.md)**. Panduan untuk AI assistant ada di skill `.claude/skills/rata-hackathon/SKILL.md`.
