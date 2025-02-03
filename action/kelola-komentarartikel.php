<?php
session_start();
require_once 'connect.php'; // Menghubungkan ke database

if (!isset($_SESSION["login"]) || $_SESSION["login"] != true) {
    header("Location: ../index.php");
    exit;
}

if (isset($_POST['act'])) {
    $action = $_POST['act'];
    $id = $_POST['id'] ?? null;

    switch ($action) {
        case 'hapus':
            // Hapus komentar
            if ($id) {
                $stmt = $conn->prepare("DELETE FROM tbl_komentarartikel WHERE id = ?");
                $stmt->bind_param("i", $id);
                
                if ($stmt->execute()) {
                    // Set alert untuk sukses
                    $_SESSION['alert'] = [
                        'title' => 'Sukses!',
                        'message' => 'Komentar berhasil dihapus.',
                        'type' => 'success'
                    ];
                } else {
                    // Set alert untuk gagal
                    $_SESSION['alert'] = [
                        'title' => 'Gagal!',
                        'message' => 'Komentar gagal dihapus.',
                        'type' => 'error'
                    ];
                }
                $stmt->close();
            }
            break;

        case 'ubah':
            // Implementasikan logika untuk mengubah komentar jika diperlukan
            // Ini bisa ditambahkan sesuai kebutuhan
            break;

        default:
            // Jika tidak ada tindakan yang dikenali
            break;
    }
    // Redirect kembali ke halaman komentar setelah aksi
    header("Location: ../admin/index.php?page=komentar");
    exit;
}
