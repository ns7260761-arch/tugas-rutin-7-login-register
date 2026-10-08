<?php
require_once __DIR__ . '/functions.php';

// 8. Logout functionality
$_SESSION = [];
session_destroy();

// Hapus cookie remember me (jika ada)
if (isset($_COOKIE['remember_email'])) {
    setcookie('remember_email', '', time() - 3600, '/');
}

header('Location: login.php');
exit;
