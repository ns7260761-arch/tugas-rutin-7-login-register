<?php
require_once __DIR__ . '/functions.php';

// Jika sudah login, langsung ke dashboard
if (isLoggedIn()) {
    header('Location: dashboard.php');
    exit;
}

if ($_SERVER['REQUEST_METHOD'] === 'POST') {
    $name     = sanitize($_POST['name'] ?? '');
    $email    = sanitize($_POST['email'] ?? '');
    $password = $_POST['password'] ?? ''; // jangan disanitize sebelum di-hash

    $errors = [];

    // 1. Validasi field tidak boleh kosong
    if ($name === '' || $email === '' || $password === '') {
        $errors[] = 'Semua field wajib diisi.';
    }

    // 2. Validasi format email
    if ($email !== '' && !filter_var($email, FILTER_VALIDATE_EMAIL)) {
        $errors[] = 'Format email tidak valid.';
    }

    // Validasi panjang password minimal
    if ($password !== '' && strlen($password) < 6) {
        $errors[] = 'Password minimal 6 karakter.';
    }

    $users = loadUsers();

    // 5. Cek duplikasi email
    if ($email !== '' && emailExists($email, $users)) {
        $errors[] = 'Email sudah terdaftar. Silakan login.';
    }

    if (empty($errors)) {
        // 3. Hash password
        $hashedPassword = password_hash($password, PASSWORD_DEFAULT);

        // 4. Simpan data ke file JSON
        $users[] = [
            'id'         => uniqid('user_'),
            'name'       => $name,
            'email'      => $email,
            'password'   => $hashedPassword,
            'created_at' => date('Y-m-d H:i:s'),
        ];

        if (saveUsers($users)) {
            $_SESSION['success'] = 'Registrasi berhasil! Silakan login.';
            header('Location: login.php');
            exit;
        } else {
            $errors[] = 'Gagal menyimpan data. Coba lagi.';
        }
    }

    if (!empty($errors)) {
        $_SESSION['error'] = implode('<br>', $errors);
    }
}

$errorMsg = getFlash('error');
?>
<!DOCTYPE html>
<html lang="id">
<head>
<meta charset="UTF-8">
<meta name="viewport" content="width=device-width, initial-scale=1.0">
<title>Register - Tugas Rutin 7</title>
<link rel="stylesheet" href="style.css">
</head>
<body>
<div class="auth-container">
    <div class="auth-card">
        <h1>📝 Register</h1>
        <p class="subtitle">Buat akun baru</p>

        <?php if ($errorMsg): ?>
            <div class="alert alert-error"><?= $errorMsg ?></div>
        <?php endif; ?>

        <form method="POST" action="register.php" novalidate>
            <div class="form-group">
                <label for="name">Nama</label>
                <input type="text" id="name" name="name" placeholder="Nama lengkap"
                       value="<?= isset($_POST['name']) ? sanitize($_POST['name']) : '' ?>">
            </div>

            <div class="form-group">
                <label for="email">Email</label>
                <input type="email" id="email" name="email" placeholder="nama@email.com"
                       value="<?= isset($_POST['email']) ? sanitize($_POST['email']) : '' ?>">
            </div>

            <div class="form-group">
                <label for="password">Password</label>
                <input type="password" id="password" name="password" placeholder="Minimal 6 karakter">
            </div>

            <button type="submit" class="btn-primary">Daftar</button>
        </form>

        <p class="switch-link">Sudah punya akun? <a href="login.php">Login di sini</a></p>
    </div>
</div>
</body>
</html>
