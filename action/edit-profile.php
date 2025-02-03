<?php
session_start();
require_once '../action/connect.php'; // Pastikan file ini berisi koneksi ke database

if ($_SERVER['REQUEST_METHOD'] === 'POST') {
    $username = trim($_POST['username']);
    $old_password = $_POST['old_password'];
    $password = $_POST['password'];
    $password_confirmation = $_POST['password_confirmation'];

    try {
        // Validasi pengguna
        $query = "SELECT password FROM tbl_user WHERE user = ?";
        $stmt = $conn->prepare($query); // $conn adalah koneksi ke database
        $stmt->bind_param('s', $_SESSION['username']);
        $stmt->execute();
        $result = $stmt->get_result();

        if ($result->num_rows === 0) {
            throw new Exception('Pengguna tidak ditemukan.');
        }

        $user = $result->fetch_assoc();

        // Verifikasi kata sandi lama
        if (!password_verify($old_password, $user['password'])) {
            throw new Exception('Kata sandi lama tidak sesuai.');
        }

        // Update username jika berbeda
        if (!empty($username) && $username !== $_SESSION['username']) {
            $check_query = "SELECT user FROM tbl_user WHERE user = ?";
            $check_stmt = $conn->prepare($check_query);
            $check_stmt->bind_param('s', $username);
            $check_stmt->execute();

            if ($check_stmt->get_result()->num_rows > 0) {
                throw new Exception('Nama pengguna baru sudah digunakan.');
            }

            $update_query = "UPDATE tbl_user SET user = ? WHERE user = ?";
            $update_stmt = $conn->prepare($update_query);
            $update_stmt->bind_param('ss', $username, $_SESSION['username']);
            $update_stmt->execute();
            $_SESSION['username'] = $username; // Update session username
        }

        // Update password jika diisi
        if (!empty($password)) {
            if ($password !== $password_confirmation) {
                throw new Exception('Konfirmasi kata sandi baru tidak cocok.');
            }
            $hashed_password = password_hash($password, PASSWORD_BCRYPT);
            $update_password_query = "UPDATE tbl_user SET password = ? WHERE user = ?";
            $update_password_stmt = $conn->prepare($update_password_query);
            $update_password_stmt->bind_param('ss', $hashed_password, $username);
            $update_password_stmt->execute();
        }

        $_SESSION['alert'] = [
            'type' => 'success',
            'title' => 'Berhasil',
            'message' => 'Profil berhasil diperbarui.',
        ];
        header("Location: ../index.php");
        exit();
    } catch (Exception $e) {
        $_SESSION['alert'] = [
            'type' => 'error',
            'title' => 'Kesalahan',
            'message' => $e->getMessage(),
        ];
        header("Location: ../edit-profile.php");
        exit();
    }
} else {
    header("Location: ../edit-profile.php");
    exit();
}
?>
