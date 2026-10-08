<?php
/**
 * functions.php
 * Kumpulan fungsi bantu untuk sistem Login/Register (Tugas Rutin 7)
 * Penyimpanan data menggunakan file JSON (data/users.json)
 */

if (session_status() === PHP_SESSION_NONE) {
    session_start();
}

define('USERS_FILE', __DIR__ . '/data/users.json');

/**
 * Membaca seluruh data user dari file JSON.
 * Jika file belum ada / kosong, kembalikan array kosong.
 */
function loadUsers(): array
{
    if (!file_exists(USERS_FILE)) {
        file_put_contents(USERS_FILE, json_encode([]));
    }

    $json = file_get_contents(USERS_FILE);
    $data = json_decode($json, true);

    return is_array($data) ? $data : [];
}

/**
 * Menyimpan array user ke file JSON.
 */
function saveUsers(array $users): bool
{
    $json = json_encode($users, JSON_PRETTY_PRINT | JSON_UNESCAPED_UNICODE);
    return file_put_contents(USERS_FILE, $json) !== false;
}

/**
 * Sanitasi input teks agar aman ditampilkan / disimpan.
 */
function sanitize(string $input): string
{
    return htmlspecialchars(trim($input), ENT_QUOTES, 'UTF-8');
}

/**
 * Cek apakah email sudah terdaftar (dipakai saat registrasi).
 */
function emailExists(string $email, array $users): bool
{
    foreach ($users as $user) {
        if (strtolower($user['email']) === strtolower($email)) {
            return true;
        }
    }
    return false;
}

/**
 * Cari user berdasarkan email. Return null jika tidak ditemukan.
 */
function findUserByEmail(string $email, array $users): ?array
{
    foreach ($users as $user) {
        if (strtolower($user['email']) === strtolower($email)) {
            return $user;
        }
    }
    return null;
}

/**
 * Cek apakah user sedang login (dipakai untuk proteksi dashboard).
 */
function isLoggedIn(): bool
{
    return isset($_SESSION['user_email']);
}

/**
 * Paksa redirect ke login.php jika belum login.
 * Dipanggil di baris paling atas halaman yang diproteksi (mis. dashboard.php).
 */
function requireLogin(): void
{
    if (!isLoggedIn()) {
        header('Location: login.php');
        exit;
    }
}

/**
 * Ambil dan hapus pesan flash (error/sukses) dari session,
 * supaya pesan hanya tampil sekali lalu hilang.
 */
function getFlash(string $key): ?string
{
    if (isset($_SESSION[$key])) {
        $msg = $_SESSION[$key];
        unset($_SESSION[$key]);
        return $msg;
    }
    return null;
}
