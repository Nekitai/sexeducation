<?php
session_start();
require_once 'connect.php';

// Ambil data dari form
$vidio_id = $_POST['vidio_id'];
$nama = isset($_SESSION['username']) ? $_SESSION['username'] : $_POST['nama'];
$komentar = $_POST['komentar'];

// Simpan komentar ke database
$stmt = $conn->prepare("INSERT INTO tbl_komentarvidio (vidio_id, user, komentar, tanggal) VALUES (?,  ?, ?, NOW())");
$stmt->bind_param("iss", $vidio_id, $nama, $komentar);

if ($stmt->execute()) {
    header("Location: ../vidiopage.php?h=$vidio_id&a=Komentar berhasil dikirim&c=success");
} else {
    header("Location: ../vidiopage.php?h=$vidio_id&a=Gagal mengirim komentar&c=danger");
}

$stmt->close();
$conn->close();
?>