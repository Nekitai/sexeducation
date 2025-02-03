<?php
session_start();
require_once 'connect.php';

// Ambil data dari form
$artikel_id = $_POST['artikel_id'];
$nama = isset($_SESSION['username']) ? $_SESSION['username'] : $_POST['nama'];
$komentar = $_POST['komentar'];

// Simpan komentar ke database
$stmt = $conn->prepare("INSERT INTO tbl_komentarartikel (artikel_id, user, komentar, tanggal) VALUES (?,  ?, ?, NOW())");
$stmt->bind_param("iss", $artikel_id, $nama, $komentar);

if ($stmt->execute()) {
    header("Location: ../artikelpage.php?h=$artikel_id&a=Komentar berhasil dikirim&c=success");
} else {
    header("Location: ../artikelpage.php?h=$artikel_id&a=Gagal mengirim komentar&c=danger");
}

$stmt->close();
$conn->close();
?>