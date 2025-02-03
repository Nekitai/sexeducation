<?php
// Durasi timeout dalam detik (contoh: 900 detik = 15 menit)
$timeout_duration = 900;

// Periksa apakah waktu terakhir aktivitas ada dalam sesi
if (isset($_SESSION['last_activity'])) {
    // Hitung waktu tidak aktif
    $time_inactive = time() - $_SESSION['last_activity'];

    // Jika waktu tidak aktif melebihi batas timeout, logout pengguna
    if ($time_inactive > $timeout_duration) {
        session_unset();
        session_destroy();
        header("Location: ../index.php?message=session_expired");
        exit();
    }
}

// Perbarui waktu aktivitas terakhir
$_SESSION['last_activity'] = time();
?>
