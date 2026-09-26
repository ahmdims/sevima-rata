---
name: git-feature-flow
description: Workflow Git wajib untuk repo sevima-rata (github.com/ahmdims/sevima-rata). Master adalah branch utama; setiap fitur/perbaikan dikerjakan di branch sendiri dan baru di-merge ke master setelah fix dan terverifikasi. Gunakan SETIAP KALI akan mulai mengerjakan fitur, fix, atau dokumen, membuat commit, pindah branch, merge, atau push di repo ini.
---

# Git Feature Flow — sevima-rata

- **Remote:** `origin` → `https://github.com/ahmdims/sevima-rata` (public, topic `hackathonsemesta2026`)
- **Branch utama:** `master`. Jangan pakai atau membuat `main`.
- **Aturan inti:** tidak ada commit kerja langsung di `master`. Setiap fitur dikerjakan di branch sendiri dan baru masuk ke `master` setelah **fix**, yaitu jalan end-to-end, terverifikasi, dan disetujui user.

## Penamaan branch
Satu branch untuk satu unit kerja (satu fitur di PRD atau satu langkah di jadwal `docs/PRD.md` §13). Pakai kebab-case dan prefix sesuai jenisnya:

| Prefix | Untuk | Contoh |
|---|---|---|
| `feat/` | Fitur baru | `feat/student-flow`, `feat/ai-diagnostic` |
| `fix/` | Perbaikan bug | `fix/level-scoring-edge-case` |
| `chore/` | Setup, konfigurasi, dependensi | `chore/init-laravel` |
| `docs/` | README, PRD, narasi | `docs/readme-submission` |
| `test/` | Hanya menambah test | `test/level-scorer` |

## Alur kerja

### 1. Mulai fitur (selalu dari master terbaru)
```bash
git checkout master
git pull origin master
git checkout -b feat/<nama-fitur>
```
Sebelum membuat branch, cek `git status`. Jika ada perubahan yang belum di-commit, tanyakan ke user apakah perubahan itu dibawa, di-commit dulu, atau di-stash.

### 2. Kerjakan dengan commit kecil
- Format conventional commit: `feat: ...`, `fix: ...`, `chore: ...`, `docs: ...`, `test: ...`. Pesan singkat dalam Bahasa Inggris atau Indonesia, tapi konsisten.
- Commit setiap potongan kecil yang sudah jalan. Commit history menjadi bukti pengerjaan hackathon, jadi hindari satu commit raksasa.
- Stage file secara spesifik. Jangan pernah commit `.env`, `database/*.sqlite`, `vendor/`, `node_modules/`, atau `.claude/settings.local.json`.
- Push branch fitur ke remote agar progres ter-backup: `git push -u origin feat/<nama-fitur>`.

### 3. Verifikasi sebelum dianggap "fix"
Sebuah branch dianggap fix hanya jika:
1. `php artisan test` lulus (jika ada test terkait).
2. Alur fitur sudah dicoba di aplikasi yang berjalan (`php artisan serve`), termasuk `php artisan migrate:fresh --seed` jika ada perubahan migration atau seeder.
3. Tidak ada error di log dan tidak ada kode debug (`dd()`, `dump()`, `console.log`) yang tertinggal.
4. **User menyatakan fitur sudah oke.** Tanyakan secara eksplisit: "Fitur X sudah jalan (…bukti…). Merge ke master?"

Laporkan hasil verifikasi apa adanya. Jika ada yang gagal, perbaiki di branch yang sama. Jangan merge.

### 4. Merge ke master
```bash
git checkout master
git pull origin master
git merge --no-ff feat/<nama-fitur> -m "Merge branch 'feat/<nama-fitur>'"
git push origin master
git branch -d feat/<nama-fitur>
git push origin --delete feat/<nama-fitur>   # opsional; biarkan jika user ingin branch tetap terlihat
```
- Selalu pakai `--no-ff` agar setiap fitur terlihat sebagai satu kesatuan di riwayat `master`.
- Jika terjadi konflik, selesaikan, jelaskan ke user apa yang diselesaikan, lalu jalankan ulang verifikasi di master sebelum push.
- Setelah merge, cek sekali lagi bahwa aplikasi di master masih jalan.

## Larangan
- Tidak boleh `git push --force` ke `master`, `git reset --hard` pada commit yang sudah di-push, atau rewrite history `master`.
- Tidak boleh melewati hook (`--no-verify`).
- Tidak boleh merge ke master tanpa persetujuan user, meskipun test lulus.
- Tidak boleh commit kerja langsung di `master`. Satu-satunya pengecualian adalah commit awal repo yang sudah ada.

## Catatan inisialisasi Laravel
Root repo sudah berisi `docs/` dan `.claude/`, sehingga `laravel new .` / `composer create-project` akan menolak direktori tidak kosong. Di branch `chore/init-laravel`, buat project di folder sementara (scratchpad), lalu salin isinya ke root repo tanpa menimpa `docs/` dan `.claude/`. Setelah itu tambahkan `.claude/settings.local.json` ke `.gitignore`.

## Mode darurat mendekati deadline (16.45 WIB)
Alurnya tetap sama, tetapi branch dibuat lebih kecil dan merge lebih sering, supaya `master` selalu dalam kondisi siap-demo. Jika sebuah fitur belum fix saat waktunya merekam video, **jangan di-merge**. Demo dari master yang stabil.
