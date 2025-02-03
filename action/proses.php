<?php
session_start();
require_once 'connect.php';

if ($_SERVER["REQUEST_METHOD"] == "POST" && isset($_POST['aksi'])) {
    $aksi = $_POST['aksi'];

    function sendJsonResponse($status, $message, $redirect = null) {
        echo json_encode([
            "status" => $status,
            "message" => $message,
            "redirect" => $redirect,
        ]);
        exit();
    }

    if ($aksi == "login") {
        $username = trim($_POST['username']);
        $password = trim($_POST['password']);

        // Query untuk mendapatkan data user
        $stmt = $conn->prepare("SELECT * FROM tbl_user WHERE user = ?");
        $stmt->bind_param("s", $username);
        $stmt->execute();
        $result = $stmt->get_result();

        if ($result->num_rows === 1) {
            $row = $result->fetch_assoc();

            // Verifikasi password
            if (password_verify($password, $row['password'])) {
                // Simpan data ke session
                $_SESSION['login'] = true;
                $_SESSION['username'] = $username;

                // Redirect sesuai role
                if ($username === "admin") {
                    sendJsonResponse("success", "Login sebagai admin berhasil!", "../admin/");
                } else {
                    sendJsonResponse("success", "Login sebagai pengguna berhasil!", "../index.php");
                }
            } else {
                sendJsonResponse("error", "Password salah.");
            }
        } else {
            sendJsonResponse("error", "Username tidak ditemukan.");
        }
    } elseif ($aksi == "daftar") {
        $username = trim($_POST['username']);
        $password = trim($_POST['password']);
        $repassword = trim($_POST['repassword']);

        if ($password !== $repassword) {
            sendJsonResponse("error", "Password dan konfirmasi tidak sama.");
        }

        // Cek apakah username sudah ada
        $stmt = $conn->prepare("SELECT * FROM tbl_user WHERE user = ?");
        $stmt->bind_param("s", $username);
        $stmt->execute();
        $result = $stmt->get_result();

        if ($result->num_rows > 0) {
            sendJsonResponse("error", "Username sudah terdaftar.");
        }

        // Hash password dan simpan
        $password_hash = password_hash($password, PASSWORD_DEFAULT);
        $stmt = $conn->prepare("INSERT INTO tbl_user (user, password) VALUES (?, ?)");
        $stmt->bind_param("ss", $username, $password_hash);

        if ($stmt->execute()) {
            sendJsonResponse("success", "Pendaftaran berhasil! Silakan login.");
        } else {
            sendJsonResponse("error", "Gagal mendaftar.");
        }
    } else {
        sendJsonResponse("error", "Aksi tidak valid.");
    }
}
?> 
