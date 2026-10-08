<?php
require_once __DIR__ . '/functions.php';

// 7. Dashboard yang diproteksi — redirect ke login jika belum login
requireLogin();

$userName  = $_SESSION['user_name'] ?? 'Pengguna';
$userEmail = $_SESSION['user_email'] ?? '';
?>
<!DOCTYPE html>
<html lang="id">
<head>
<meta charset="UTF-8">
<meta name="viewport" content="width=device-width, initial-scale=1.0">
<title>Dashboard - Tugas Rutin 7</title>
<link rel="stylesheet" href="style.css">
</head>
<body>
<div class="dashboard-container">
    <nav class="navbar">
        <span class="navbar-title">Dashboard</span>
        <a href="logout.php" class="btn-logout">Logout</a>
    </nav>

    <div class="dashboard-content">
        <div class="welcome-card">
            <h1>Selamat datang, <?= sanitize($userName) ?> 👋</h1>
            <p>Anda berhasil login menggunakan akun berikut:</p>
            <ul class="user-info">
                <li><strong>Nama</strong>: <?= sanitize($userName) ?></li>
                <li><strong>Email</strong>: <?= sanitize($userEmail) ?></li>
            </ul>
            <p class="note">Halaman ini hanya bisa diakses oleh pengguna yang sudah login (session-protected).</p>
        </div>
    </div>
</div>
</body>
</html>
