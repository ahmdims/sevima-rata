# RATA Design System

> **Satu kalimat:** tenang dan terpercaya untuk guru, hangat dan tanpa rasa takut untuk siswa.
> **Sumber kebenaran kode:** token di [`resources/css/app.css`](../resources/css/app.css), komponen di [`resources/views/components/ui/`](../resources/views/components/ui), dan contoh hidup di **`/ui-kit`**.
> **Standar:** WCAG 2.2 level AA, target sentuh ≥ 44px (WCAG 2.5.8 / Apple HIG), dan grid 4px.

---

## 0. Referensi → Keputusan

| Referensi (`docs/ref/`) | Yang diambil | Yang tidak diambil |
|---|---|---|
| **1 · Zoromi** | Gradient *aurora* biru-lavender, heading **serif** yang manusiawi, tombol pill, banyak ruang kosong | Section gelap panjang ala landing marketing |
| **2 · Oliviera** | Angka besar sebagai fokus, label kecil kapital (*eyebrow*) | Tema gelap: tidak cocok untuk ruang kelas yang terang dan HP murah di bawah sinar matahari |
| **3 · Meridian** | **Progress bar bersegmen**, chip status "Pass", panel putih lembut | Visual isometrik yang dekoratif |
| **4 · Donezo** | **Kartu statistik unggulan** (satu kartu berwarna), **arsiran** untuk status kosong/pending, penanda aktif di sidebar | Palet hijau tunggal (hijau kita khususkan untuk level) |
| **5 & 7 · Shopeers** | Struktur dashboard: sidebar + kartu KPI + chart, border tipis, dan brand **biru** | Kepadatan tabel e-commerce |

---

## 1. Prinsip Desain

1. **Tenang dulu, baru menarik.** Latar terang, satu warna aksen per layar, dan banyak ruang kosong. Guru yang lelah butuh kejelasan, bukan dekorasi.
2. **Satu layar, satu tujuan.** Setiap layar punya satu aksi utama (tombol primary). Informasi lain disajikan bertahap (*progressive disclosure*).
3. **Level adalah titik mulai, bukan vonis.** Tidak ada merah, skor, atau kata "gagal" untuk menggambarkan kemampuan siswa.
4. **AI transparan, guru memegang kendali.** Konten AI selalu diberi label dan bisa ditinjau atau diedit sebelum dipakai.
5. **Ringan dan inklusif.** Mobile-first untuk siswa, bisa dipakai di HP murah, tidak bergantung pada warna saja, dan tetap bisa dipakai dengan keyboard atau pembaca layar.

---

## 2. Psikologi Pengguna

Setiap keputusan visual di bawah ini punya alasan perilaku. Dua audiens kita punya kebutuhan emosional yang berbeda.

| Audiens | Kondisi emosional | Yang harus dirasakan | Implikasi desain |
|---|---|---|---|
| **Guru** | Waktu sempit, beban administrasi, skeptis terhadap AI | *Terbantu, kompeten, memegang kendali* | Dashboard ringkas, angka besar yang langsung bisa ditindaklanjuti, label AI, bisa diedit |
| **Siswa** (SD/SMP) | Cemas saat "dites", mudah malu jika salah | *Aman, dihargai, ingin lanjut* | Tanpa skor, bahasa positif, satu soal per layar, progress yang terlihat, akhir yang merayakan |

### Prinsip yang diterapkan

| Prinsip | Penerapan di RATA |
|---|---|
| **Growth mindset** (Dweck) | Level siswa memakai metafora tumbuhan: *Benih → Tunas → Tumbuh → Berbunga → Berbuah*. Copy memakai kata **"belum"** ("belum menguasai", bukan "tidak bisa"). Pujian ditujukan ke usaha dan strategi. |
| **Menghindari ancaman stereotip & kecemasan tes** | Tidak ada merah, persentase, atau peringkat untuk siswa. Merah (`danger`) **hanya** untuk error sistem. |
| **Positive-first feedback** (sandwich) | Halaman hasil: kekuatan dulu → satu langkah berikutnya → semangat. |
| **Goal-gradient effect** | Progress bar bersegmen per soal. Motivasi naik saat garis finis terlihat. |
| **Peak-end rule** | Akhir asesmen adalah momen "puncak": heading serif besar, sapaan dengan nama, dan warna aurora yang hangat. |
| **Hick's law** | Satu tombol primary per layar. Maksimal 4 pilihan jawaban. Menu guru hanya berisi item penting. |
| **Fitts's law** | Pilihan jawaban setinggi ≥ 56px selebar layar. Tombol aksi siswa `lg` (48px) dan full-width di HP. |
| **Cognitive load / Miller** | Informasi dipotong per kartu. Dashboard menampilkan 4 KPI, bukan 12. Miskonsepsi ditampilkan top-5. |
| **Labor illusion** | Loading AI menampilkan langkah yang sedang dikerjakan ("Memetakan miskonsepsi…"). Menunggu terasa lebih singkat dan hasilnya lebih dipercaya. |
| **Aesthetic-usability effect** | Visual yang rapi membuat produk terasa lebih mudah dan lebih dipercaya, termasuk oleh juri. |
| **Von Restorff (isolation)** | Hanya satu kartu statistik "featured" per baris, yaitu metrik yang paling penting. |
| **Psikologi warna** | **Biru** = kepercayaan dan fokus (brand). **Violet** = "keajaiban" AI, dibedakan dari aksi biasa. **Hijau-sian** = pertumbuhan. **Amber** = perhatian yang hangat, bukan bahaya. |
| **Otonomi (Self-Determination Theory)** | Guru bisa meninjau, mengedit, dan membuat ulang konten AI. Siswa tidak dipaksa login dan cukup memakai nama panggilan. |

---

## 3. Warna

Semua nilai sudah dicek dengan rumus kontras WCAG. Teks ≥ 4.5:1, komponen UI dan grafik ≥ 3:1.

### 3.1 Netral
| Token | Hex | Pakai untuk | Kontras |
|---|---|---|---|
| `canvas` | `#F5F7FB` | Latar halaman | — |
| `surface` | `#FFFFFF` | Kartu, panel, input | — |
| `subtle` | `#F8FAFC` | Hover baris, latar sekunder | — |
| `line` | `#E5E8F0` | Border kartu dan divider (dekoratif) | — |
| `field` | `#858FA3` | **Border input** | 3.25:1 ✓ (1.4.11) |
| `placeholder` | `#8A93A6` | Placeholder | 3.1:1 (bukan teks inti) |
| `muted` | `#5B6478` | Caption, meta, label sekunder | 5.9:1 ✓ |
| `ink-soft` | `#334155` | Teks isi sekunder | 10.4:1 ✓ |
| `ink` | `#0F172A` | Teks utama, tombol dark | 17.9:1 ✓ |

### 3.2 Brand, AI, Semantik
| Token | Hex | Aturan |
|---|---|---|
| `brand-600` | `#2553E8` | Aksi utama, link, status aktif. Teks putih di atasnya 6.0:1 ✓ |
| `brand-50` / `brand-700` | `#EEF4FF` / `#1D41C4` | Latar dan teks item aktif (7.3:1 ✓) |
| `brand-500` | `#3B6CF6` | **Focus ring** (4.5:1 ✓) |
| `ai-600` | `#7C3AED` | **Khusus** aksi dan konten AI (tombol "Buat dengan AI", label AI) |
| `success-*` | `#059669` / `#047857` | Konfirmasi sistem ("Tersimpan", "Dipublikasikan") |
| `warning-*` | `#D97706` / `#B45309` | Perhatian ("4 siswa belum mengerjakan") |
| `danger-*` | `#DC2626` / `#B91C1C` | **Hanya** error sistem & aksi destruktif. **Dilarang** untuk menilai siswa. |

### 3.3 Skala Level (ordinal, tanpa merah)
Hue bergerak **kuning → hijau → sian → indigo** (urutan mirip viridis), sehingga tetap terbaca urut bagi pengguna buta warna. Level juga **selalu** tampil sebagai teks, tidak pernah warna saja.

| Level | Guru | Siswa | `level-n` (bar) | `-soft` (latar badge) | `-ink` (teks badge) |
|---|---|---|---|---|---|
| 0 | Pra-Dasar | 🌰 Benih | `#D97706` | `#FEF3C7` | `#92400E` (6.4:1) |
| 1 | Dasar | 🌱 Tunas | `#65A30D` | `#ECFCCB` | `#3F6212` (6.5:1) |
| 2 | Berkembang | 🌿 Tumbuh | `#059669` | `#D1FAE5` | `#065F46` (6.8:1) |
| 3 | Cakap | 🌸 Berbunga | `#0891B2` | `#CFFAFE` | `#155E75` (6.5:1) |
| 4 | Mahir | 🍎 Berbuah | `#4F46E5` | `#E0E7FF` | `#3730A3` (8.1:1) |

Emoji bersifat opsional dan hanya untuk halaman siswa. Nama level ada di `config('rata.level_names')` (guru) dan `config('rata.level_growth')` (siswa).

### 3.4 Aturan pemakaian
- Maksimal **satu** warna aksen dominan per layar (biru **atau** violet AI).
- Proporsi kira-kira **70% netral · 20% putih kartu · 10% aksen**.
- Warna level **hanya** untuk data level, bukan untuk dekorasi.
- Dilarang menulis hex langsung di Blade. Tambahkan token di `@theme` bila benar-benar perlu.

---

## 4. Tipografi

| Peran | Kelas | Ukuran / tebal | Catatan |
|---|---|---|---|
| Display (emosional) | `font-display text-5xl sm:text-7xl` | Instrument Serif 400 | Hero dan **hasil siswa** saja. Kata kunci dimiringkan + `text-brand-600`. |
| Judul halaman | `text-2xl sm:text-3xl font-semibold tracking-tight` | 24–30 / 600 | Lewat `<x-ui.page-header>` |
| Judul bagian | `text-xl font-semibold` | 20 / 600 | |
| Judul kartu | `text-base font-semibold` | 16 / 600 | Lewat `<x-ui.card title>` |
| Isi | `text-base text-ink-soft` | 16 / 400, lh 1.5 | Lebar maks `max-w-prose` (±65 karakter) |
| Isi kecil / UI | `text-sm` | 14 / 400–500 | Label, tombol md, sel tabel |
| Caption | `text-xs text-muted` | 12 / 400 | Hint, meta |
| Eyebrow | `eyebrow` | 12 / 600, uppercase, tracking 0.12em | Label kelompok (ref Oliviera) |
| Angka KPI | `text-4xl font-semibold tabular-nums` | 36 / 600 | Selalu `tabular-nums` |

Aturan: maksimal 3 ukuran teks per kartu. Soal untuk siswa minimal `text-lg`. Heading memakai `text-balance`.

---

## 5. Ruang, Layout, Bentuk

- **Grid 4px.** Spasi yang dipakai: `1 2 3 4 5 6 8 10 12 16 20 24` (Tailwind). Jarak antar-kartu `gap-4` (HP) sampai `gap-8` (desktop). Padding kartu `p-5 sm:p-6`.
- **Layout guru:** sidebar 256px (sticky di `lg`, drawer di HP) dan konten `max-w-7xl`.
- **Layout siswa:** satu kolom `max-w-md`, latar `bg-aurora`, satu fokus per layar.
- **Breakpoint:** desain mulai dari 375px, lalu `sm` 640, `lg` 1024.
- **Radius:** input dan ikon-box `rounded-xl` (12px), kartu `rounded-2xl` (16px), tombol/badge/chip `rounded-full`.
- **Elevasi:** `shadow-card` (default kartu), `shadow-raised` (kartu featured, dropdown), `shadow-overlay` (modal). Tidak pernah memakai bayangan hitam pekat.

## 6. Ikon

- **Heroicons** (`blade-ui-kit/blade-heroicons`). Pakai *outline* `heroicon-o-*` di UI umum, *mini* `heroicon-m-*` di badge dan field (16–20px), dan *solid* `heroicon-s-*` hanya untuk status terpilih.
- Ukuran: 16px (`size-4`) di tombol sm/md, 20px (`size-5`) di menu dan tombol lg, 24px (`size-6`) di ikon-box besar.
- Ikon dekoratif wajib diberi `aria-hidden="true"`. Tombol yang hanya berisi ikon wajib punya `aria-label`.
- Ikon semantik tetap: `sparkles` = AI, `academic-cap` = pedagogi, `user-group` = kelompok, `light-bulb` = miskonsepsi, `hand-raised` = perlu pendampingan, `rocket-launch` = pengayaan.

## 7. Gerak

- Durasi 150ms (hover/press), 200–350ms (masuk/keluar), dan 500ms (bar data tumbuh). Easing `--ease-out-soft`.
- `animate-fade-up` untuk kartu yang muncul. Tombol memakai `active:scale-[0.98]` sebagai umpan balik sentuh.
- **`prefers-reduced-motion` dihormati secara global** (animasi dimatikan di `app.css`).
- Tidak ada animasi yang berulang terus kecuali indikator loading.

---

## 8. Komponen (`<x-ui.*>`)

| Komponen | Props utama | Kapan dipakai | Aksesibilitas |
|---|---|---|---|
| `button` | `variant` primary·secondary·ghost·dark·ai·danger, `size` sm·md·lg, `href`, `icon`, `icon-right`, `loading` | Semua aksi. `ai` khusus aksi yang memanggil AI. | `loading` → `aria-busy` + disabled |
| `card` | `title`, `description`, `padding`, slot `actions` | Wadah setiap kelompok informasi | `<section>` + heading |
| `page-header` | `title`, `description`, `eyebrow`, slot `actions` | Bagian atas setiap halaman guru | `<h1>` tunggal |
| `stat-card` | `label`, `value`, `hint`, `icon`, `featured` | KPI dashboard. Maks. **1 featured** per baris. | Angka `tabular-nums` |
| `badge` | `tone` neutral·brand·ai·success·warning·danger, `icon` | Status singkat | Selalu berisi teks |
| `level-badge` | `level` 0–4, `audience` guru·siswa | Setiap tampilan level | Teks + warna (1.4.1) |
| `level-distribution` | `counts` [level ⇒ n], `pending` | Chart kelompok di dashboard | `role="img"` + `aria-label` lengkap, angka di atas bar |
| `progress` | `value`, `max`, `segments`, `tone`, `label`, `show-value` | Progress soal (bersegmen) dan persentase | `role="progressbar"` + aria-value* |
| `field` + `input`/`select`/`textarea` | `label`, `for`, `hint`, `required` / `name`, `value` | Semua form | Label terhubung, error → `aria-invalid` + `aria-describedby` |
| `alert` | `tone` info·success·warning·danger·ai, `title` | Umpan balik sistem | `role="status"` / `alert` |
| `flash` | — | Otomatis di layout: `session('status')` & `$errors` | — |
| `empty-state` | `icon`, `title`, `description`, slot `action` | Daftar kosong: selalu beri langkah berikutnya | — |
| `ai-loading` | `title`, `steps`, `interval` | Selama menunggu LLM | `role="status"` `aria-live="polite"` |
| `ai-label` | `text` | Di dekat **setiap** konten buatan AI | — |
| `choice` | `name`, `value`, `key`, `checked` | Pilihan jawaban siswa | Radio asli (`sr-only`), fokus terlihat, ≥ 56px |
| `avatar` | `name`, `size` | Daftar siswa | Inisial, `aria-hidden` (nama tertulis di sebelahnya) |

Utilitas CSS (`card`, `btn-*`, `input`, `eyebrow`, `bg-aurora`, `bg-hatch`, `skeleton`) hanya dipakai bila tidak ada komponen yang cocok.

---

## 9. Pola Halaman

**Dashboard guru (hasil asesmen)**
1. `page-header`: judul asesmen, kode kelas sebagai badge, aksi "Bagikan kode".
2. Baris 4 `stat-card`: *Siswa mengerjakan* (featured), *Perlu pendampingan*, *Siap pengayaan*, *Miskonsepsi utama*.
3. `level-distribution` (kiri, 2/3) + top-5 miskonsepsi (kanan, 1/3).
4. Kartu per kelompok: `level-badge`, daftar `avatar` + nama, lalu tombol `variant="ai"` **"Buat materi"**.

**Alur siswa**: *Join* (kode + nama, satu tombol lg) → *Soal* (progress bersegmen, soal `text-lg`, 3–4 `choice`, tombol "Lanjut" lg full-width) → *Hasil* (display serif, `level-badge audience="siswa"`, kekuatan → langkah berikutnya).

**Alur AI**: tombol `ai` → `ai-loading` dengan langkah → hasil dengan `ai-label` → aksi "Edit" / "Buat ulang" / "Publikasikan".

**Status kosong**: selalu `empty-state` + satu aksi. **Error AI**: `alert tone="danger"` + tombol "Coba lagi" + "Pakai soal contoh".

---

## 10. Konten & Copywriting

**Suara:** seperti rekan guru yang suportif. Hangat, ringkas, dan jelas. Memakai Bahasa Indonesia baku yang santai. Guru disapa "Anda"; siswa disapa "kamu" dengan nama panggilannya.

| Hindari | Gunakan |
|---|---|
| "Skor kamu 4/12" | "Kamu sudah jago membandingkan pecahan!" |
| "Salah", "Gagal", "Nilai rendah" | "Belum tepat", "Yuk, latihan lagi bagian ini" |
| "Level terendah" | "Benih: titik mulai perjalananmu 🌰" |
| "Error 500" | "AI sedang sibuk. Coba lagi, atau pakai soal contoh." |
| "Generate" | "Buat dengan AI" |
| "Submit" | "Kirim jawaban" / "Lanjut" |
| "Data tidak ditemukan" | "Belum ada asesmen. Buat yang pertama — hanya ±1 menit." |

Aturan:
- Tombol memakai kata kerja + objek ("Buat materi", bukan "OK").
- Pesan error berisi apa yang terjadi, lalu cara memperbaikinya.
- Angka untuk guru, cerita untuk siswa.

---

## 11. Checklist Aksesibilitas (WCAG 2.2 AA)

- [ ] Kontras teks ≥ 4.5:1, komponen UI/grafik ≥ 3:1. Pakai token saja, karena semua token sudah lolos.
- [ ] Informasi tidak disampaikan dengan warna saja (level, status, jawaban terpilih).
- [ ] Target sentuh ≥ 44px (siswa: tombol `lg`, `choice`).
- [ ] Fokus keyboard selalu terlihat (`:focus-visible` ring brand). Tautan "Lewati ke konten" tersedia di layout guru.
- [ ] Setiap input punya `<label>`, dan error dihubungkan lewat `aria-describedby`.
- [ ] Satu `<h1>` per halaman, dan urutan heading tidak melompat.
- [ ] Ikon dekoratif `aria-hidden`, dan tombol ikon punya `aria-label`.
- [ ] Konten dinamis (loading AI) memakai `aria-live`.
- [ ] Menghormati `prefers-reduced-motion`.
- [ ] Nyaman di lebar 375px tanpa scroll horizontal.

## 12. Do & Don't

| ✅ Do | ❌ Don't |
|---|---|
| Satu tombol primary per layar | Dua tombol biru bersebelahan |
| `variant="ai"` untuk aksi yang memanggil AI | Violet untuk hal yang bukan AI |
| `level-badge` untuk setiap level | Menulis level dengan warna/kelas manual |
| Merah hanya untuk error sistem | Merah untuk jawaban atau level siswa |
| Heading serif untuk momen emosional | Serif di tabel, form, atau label |
| `empty-state` dengan aksi | Halaman kosong tanpa arahan |
| Angka + label di chart | Chart tanpa angka yang mengandalkan warna/legend |
| Token (`text-muted`, `bg-brand-50`) | Hex manual, `text-gray-400`, atau `bg-blue-500` |
