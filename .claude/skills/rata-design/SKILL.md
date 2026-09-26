---
name: rata-design
description: Design system RATA: token warna, tipografi, komponen Blade <x-ui.*>, pola halaman, copywriting, dan aturan psikologi pengguna (growth mindset, tanpa merah untuk siswa). Gunakan SETIAP KALI membuat atau mengubah view Blade, layout, komponen UI, CSS/Tailwind, teks antarmuka, halaman siswa/guru, chart, atau tampilan konten AI di repo sevima-rata, termasuk saat user meminta "buat halaman", "percantik", "ubah tampilan", atau "tambah tombol".
---

# RATA Design System — Panduan Konsistensi

Tujuan emosional: **tenang dan terpercaya untuk guru, hangat dan tanpa rasa takut untuk siswa.**

Sumber kebenaran:
- `docs/DESIGN.md`: alasan, token, komponen, psikologi, copy, dan checklist. **Baca bagian terkait sebelum membuat halaman baru.**
- `resources/css/app.css`: token `@theme` dan utilitas dasar.
- `resources/views/components/ui/*.blade.php`: UI kit.
- `/ui-kit`: contoh hidup semua komponen. Jika menambah komponen atau varian, **tambahkan juga di sini**.

## Aturan wajib

1. **Pakai komponen `<x-ui.*>` dulu.** Jika tidak ada yang cocok, baru pakai utilitas (`card`, `btn-primary btn-md`, `input`, `eyebrow`). Jika pola baru dipakai ≥2 kali, jadikan komponen baru di `components/ui/`, lalu tambahkan ke `/ui-kit` dan tabel komponen di `docs/DESIGN.md` §8.
2. **Warna hanya dari token.** Pakai `ink`, `ink-soft`, `muted`, `line`, `field`, `canvas`, `surface`, `subtle`, `brand-*`, `ai-*`, `success-*`, `warning-*`, `danger-*`, `level-{0-4}[-soft|-ink]`. Dilarang memakai hex manual, `text-[#…]`, atau palet default Tailwind (`gray-`, `blue-`, `red-`, dst.). Token baru ditambahkan di `@theme` dan harus lolos cek kontras.
3. **Satu tombol primary per layar.** Aksi yang memanggil AI memakai `variant="ai"` (violet). Violet tidak dipakai untuk hal lain.
4. **Level siswa:**
   - Selalu tampilkan lewat `<x-ui.level-badge>` / `<x-ui.level-distribution>`. Jangan membuat badge level manual.
   - Di halaman siswa pakai `audience="siswa"` (Benih/Tunas/Tumbuh/Berbunga/Berbuah).
   - **Jangan pernah** memakai merah, skor, persentase, peringkat, atau kata "gagal/salah" untuk kemampuan siswa. `danger` hanya untuk error sistem dan aksi destruktif.
5. **Konten AI** selalu disertai `<x-ui.ai-label />`. Selama menunggu LLM tampilkan `<x-ui.ai-loading :steps="[…]">` dengan langkah yang relevan. Beri guru opsi Edit / Buat ulang.
6. **Halaman siswa:**
   - memakai `<x-layouts.siswa>`, satu fokus per layar
   - tombol aksi `size="lg"` dan `w-full`
   - pilihan jawaban pakai `<x-ui.choice>`, soal minimal `text-lg`
   - progress pakai `<x-ui.progress :segments="n">`
7. **Halaman guru:** memakai `<x-layouts.guru title="…">` lalu `<x-ui.page-header>`. KPI pakai `<x-ui.stat-card>`, maksimal **satu `featured`** per baris. Daftar kosong selalu pakai `<x-ui.empty-state>` dengan aksi.
8. **Tipografi:**
   - `font-display` (serif) hanya untuk hero dan momen emosional (hasil siswa)
   - UI lainnya memakai Inter
   - angka memakai `tabular-nums`
   - maksimal 3 ukuran teks per kartu
9. **Aksesibilitas (WCAG 2.2 AA):**
   - setiap input dibungkus `<x-ui.field>`
   - ikon dekoratif `aria-hidden="true"`, tombol ikon punya `aria-label`
   - informasi tidak disampaikan dengan warna saja
   - target sentuh ≥ 44px
   - satu `<h1>` per halaman
   - layout harus nyaman di 375px tanpa scroll horizontal
10. **Ikon:** Heroicons. `heroicon-o-*` untuk UI, `heroicon-m-*` di badge dan field, `heroicon-s-*` hanya untuk status terpilih. Ikon semantik: `sparkles`=AI, `light-bulb`=miskonsepsi, `user-group`=kelompok, `hand-raised`=perlu pendampingan, `rocket-launch`=pengayaan.

## Copywriting

Guru disapa "Anda", siswa disapa "kamu" + nama panggilan. Gunakan kata kerja + objek di tombol ("Buat materi", "Kirim jawaban"). Pesan error berisi apa yang terjadi dan cara memperbaikinya. Untuk siswa urutannya: **kekuatan → satu langkah berikutnya → semangat**, dengan framing "belum". Tabel "Hindari → Gunakan" ada di `docs/DESIGN.md` §10.

## Cheatsheet komponen

```blade
<x-ui.page-header eyebrow="Kelas 5A" title="Pecahan" description="28 dari 32 siswa sudah mengerjakan">
    <x-slot:actions><x-ui.button variant="secondary" icon="share">Bagikan kode</x-ui.button></x-slot:actions>
</x-ui.page-header>

<x-ui.stat-card featured label="Siswa mengerjakan" value="28" icon="user-group" hint="dari 32 siswa" />
<x-ui.card title="Distribusi level"><x-ui.level-distribution :counts="$counts" :pending="$pending" /></x-ui.card>
<x-ui.button variant="ai" icon="sparkles" :loading="$busy">Buat materi</x-ui.button>

<x-ui.field label="Topik" for="topic" hint="Contoh: Pecahan" required>
    <x-ui.input name="topic" placeholder="Tulis topik" />
</x-ui.field>

<x-ui.level-badge :level="$attempt->level" audience="siswa" />
<x-ui.choice name="answer" value="b" key="B">1/3</x-ui.choice>
<x-ui.alert tone="danger" title="AI tidak merespons">Coba lagi, atau pakai soal contoh.</x-ui.alert>
```

## Verifikasi sebelum menyatakan UI selesai

1. `python .claude/skills/rata-design/scripts/lint_design.py` → harus **exit 0**. Script ini mengecek kontras semua pasangan token dan mencari warna di luar token di Blade.
2. `npm run build` sukses dan `php artisan test` lulus.
3. Lihat hasilnya secara visual. Jalankan `php artisan serve`, lalu screenshot dengan Edge headless:
   ```bash
   "/c/Program Files (x86)/Microsoft/Edge/Application/msedge.exe" --headless=new --disable-gpu --hide-scrollbars \
     --window-size=1440,2000 --virtual-time-budget=4000 --screenshot="<scratchpad>\\page.png" http://127.0.0.1:8000/<path>
   ```
   Viewport minimum Edge headless adalah ±500px. Untuk mengecek tampilan **375px**, render halaman di dalam `<iframe style="width:375px">` pada file HTML sementara di `public/`, lalu hapus file itu setelahnya.
4. Cocokkan dengan checklist `docs/DESIGN.md` §11 (aksesibilitas) dan §12 (Do & Don't).
