# Tugas Rutin 7 — Sistem Login/Register (PHP Native + JSON)

## Cara Menjalankan
1. Pastikan PHP sudah terpasang (cek dengan `php -v`).
2. Buka terminal di folder ini, lalu jalankan:
   ```
   php -S localhost:8000
   ```
3. Buka browser ke `http://localhost:8000/register.php`, buat akun, lalu login lewat `http://localhost:8000/login.php`.
4. Pastikan folder `data/` bisa ditulis (writable) supaya `users.json` bisa diperbarui.

Bisa juga dijalankan lewat XAMPP/Laragon: taruh folder ini di `htdocs`, lalu akses `http://localhost/tugas-rutin-7/register.php`.

## Struktur File
- `functions.php` — fungsi bantu (baca/tulis JSON, sanitasi, cek login, dll)
- `register.php` — form & proses registrasi
- `login.php` — form & proses login (+ fitur Remember Me)
- `dashboard.php` — halaman yang diproteksi, hanya bisa diakses setelah login
- `logout.php` — menghapus session & cookie
- `style.css` — styling halaman
- `data/users.json` — "database" JSON tempat data user disimpan

## Checklist Requirements
1. ✅ Form registrasi dengan validasi (nama, email, password) — `register.php`
2. ✅ Validasi email dengan `filter_var()` — `register.php`
3. ✅ Password di-hash dengan `password_hash()` — `register.php`
4. ✅ Data disimpan di file JSON — `functions.php` (`saveUsers()`), `data/users.json`
5. ✅ Cek duplikasi email saat registrasi — `functions.php` (`emailExists()`)
6. ✅ Sistem login dengan session — `login.php` (`password_verify()` + `$_SESSION`)
7. ✅ Dashboard yang diproteksi (redirect jika belum login) — `dashboard.php` (`requireLogin()`)
8. ✅ Logout functionality (`session_destroy()`) — `logout.php`
9. ✅ Sanitasi input dengan `htmlspecialchars()` — `functions.php` (`sanitize()`), dipakai di semua form
10. ✅ Pesan error & sukses yang jelas — flash message lewat session di `register.php` & `login.php`

### Bonus yang sudah ditambahkan
- ⭐ **Remember Me** dengan cookie (30 hari) — checkbox di `login.php`
- ⭐ Tampilan CSS yang rapi & modern — `style.css`

### Ide bonus tambahan (opsional, belum dibuat)
- Halaman edit profile (ubah nama/password)

## Catatan
- Password **tidak pernah** disimpan dalam bentuk asli — selalu di-hash dengan `password_hash()`.
- Setiap kali ada error, pesan ditampilkan dalam bahasa yang jelas (mis. "Email sudah terdaftar. Silakan login.").
