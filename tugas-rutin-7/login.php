<?php
require_once __DIR__ . '/functions.php';

// Auto-login lewat cookie "remember me" (bonus)
if (!isLoggedIn() && isset($_COOKIE['remember_email'])) {
    $users = loadUsers();
    $rememberedUser = findUserByEmail($_COOKIE['remember_email'], $users);
    if ($rememberedUser) {
        $_SESSION['user_email'] = $rememberedUser['email'];
        $_SESSION['user_name']  = $rememberedUser['name'];
    }
}

if (isLoggedIn()) {
    header('Location: dashboard.php');
    exit;
}

if ($_SERVER['REQUEST_METHOD'] === 'POST') {
    $email    = sanitize($_POST['email'] ?? '');
    $password = $_POST['password'] ?? '';
    $remember = isset($_POST['remember']);

    $errors = [];

    if ($email === '' || $password === '') {
        $errors[] = 'Email dan password wajib diisi.';
    }

    if (empty($errors)) {
        $users = loadUsers();
        $user  = findUserByEmail($email, $users);

        // 6. Sistem login: verifikasi password terhadap hash
        if ($user && password_verify($password, $user['password'])) {
            $_SESSION['user_email'] = $user['email'];
            $_SESSION['user_name']  = $user['name'];

            // Bonus: Remember Me dengan cookie (30 hari)
            if ($remember) {
                setcookie('remember_email', $user['email'], time() + (30 * 24 * 60 * 60), '/');
            }

            header('Location: dashboard.php');
            exit;
        } else {
            $errors[] = 'Email atau password salah.';
        }
    }

    if (!empty($errors)) {
        $_SESSION['error'] = implode('<br>', $errors);
    }
}

$errorMsg   = getFlash('error');
$successMsg = getFlash('success');
?>
<!DOCTYPE html>
<html lang="id">
<head>
<meta charset="UTF-8">
<meta name="viewport" content="width=device-width, initial-scale=1.0">
<title>Login - Tugas Rutin 7</title>
<link rel="stylesheet" href="style.css">
</head>
<body>
<div class="auth-container">
    <div class="auth-card">
        <h1>🔐 Login</h1>
        <p class="subtitle">Masuk ke akun Anda</p>

        <?php if ($errorMsg): ?>
            <div class="alert alert-error"><?= $errorMsg ?></div>
        <?php endif; ?>
        <?php if ($successMsg): ?>
            <div class="alert alert-success"><?= $successMsg ?></div>
        <?php endif; ?>

        <form method="POST" action="login.php" novalidate>
            <div class="form-group">
                <label for="email">Email</label>
                <input type="email" id="email" name="email" placeholder="nama@email.com"
                       value="<?= isset($_POST['email']) ? sanitize($_POST['email']) : '' ?>">
            </div>

            <div class="form-group">
                <label for="password">Password</label>
                <input type="password" id="password" name="password" placeholder="Password">
            </div>

            <div class="form-group checkbox-group">
                <label><input type="checkbox" name="remember"> Ingat saya</label>
            </div>

            <button type="submit" class="btn-primary">Login</button>
        </form>

        <p class="switch-link">Belum punya akun? <a href="register.php">Daftar di sini</a></p>
    </div>
</div>
</body>
</html>
