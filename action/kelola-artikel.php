<?php
session_start();
require_once '../action/connect.php';

if ($_SERVER["REQUEST_METHOD"] == "POST") {
    $act = $_POST['act'];
    $id = $_POST['id'];
    $judul = $_POST['judul'];
    $tanggal = $_POST['tanggal'];
    $kategori = $_POST['kategori'];
    $deskripsi = $_POST['deskripsi'];
    $artikel = $_POST['artikel'];
    $sumber = '';
    $time = time();
    $target_dir_img = "../img/";
    $target_dir_sumber = "../sumber/";

    $alert = '';
    $color = '';

    // Cek apakah judul artikel sudah diisi
    if (empty($judul)) {
        $alert = "Pastikan Artikel memiliki judul";
        $color = "danger";
    } else {
        if ($act == 'baru' || $act == 'ubah') {
            $foto = null;

            // Jika mengubah artikel, ambil data foto dan sumber yang ada
            if ($act == 'ubah') {
                $sql = "SELECT foto, sumber FROM tbl_artikel WHERE id = ?";
                $stmt = $conn->prepare($sql);
                $stmt->bind_param("i", $id);
                $stmt->execute();
                $result = $stmt->get_result();
                $row = $result->fetch_assoc();
                $stmt->close();
                
                $foto = $row['foto'];
                $sumber = $row['sumber'];
            }

            // Proses upload foto
            if (!empty($_FILES["foto"]["name"])) {
                $ext = pathinfo($_FILES["foto"]["name"], PATHINFO_EXTENSION);
                $target_file = $target_dir_img . $time . '.' . $ext;
                if (move_uploaded_file($_FILES["foto"]["tmp_name"], $target_file)) {
                    $foto = $time . '.' . $ext; // Nama foto disimpan di database
                } else {
                    $alert = "Gagal mengupload foto."; // Pesan error jika upload gagal
                    $color = "danger";
                }
            } elseif ($act == 'ubah') {
                $foto = $row['foto']; // Pertahankan foto lama jika tidak ada foto baru
            }

            // Proses sumber
            if (isset($_FILES["sumber_file"]) && $_FILES["sumber_file"]["error"] == 0) {
                $fileTmpPath = $_FILES["sumber_file"]["tmp_name"];
                $fileName = $_FILES["sumber_file"]["name"];
                $uploadPath = $target_dir_sumber . $time . '_' . $fileName;

                if (move_uploaded_file($fileTmpPath, $uploadPath)) {
                    $sumber = $time . '_' . $fileName; // Menyimpan file sumber
                } else {
                    $alert = "Gagal mengupload file sumber."; // Pesan error jika upload sumber gagal
                    $color = "danger";
                }
            } elseif (isset($_POST['sumber_link']) && !empty($_POST['sumber_link'])) {
                $sumber = $_POST['sumber_link'];
            }

            if ($color != "danger") {
                // Query untuk INSERT atau UPDATE artikel ke database
                $sql = ($act == 'baru') ?
                    "INSERT INTO tbl_artikel(judul, tanggal, kategori, deskripsi, sumber, foto, artikel) VALUES (?, ?, ?, ?, ?, ?, ?)" :
                    "UPDATE tbl_artikel SET judul = ?, tanggal = ?, kategori = ?, deskripsi = ?, sumber = ?, foto = ?, artikel = ? WHERE id = ?";
                $stmt = $conn->prepare($sql);

                if ($act == 'baru') {
                    // Jika artikel baru, bind parameter dan eksekusi query
                    $stmt->bind_param("sssssss", $judul, $tanggal, $kategori, $deskripsi, $sumber, $foto, $artikel);
                    $pesan = 'ditambahkan';
                } else {
                    // Jika artikel diperbarui, bind parameter dan eksekusi query
                    $stmt->bind_param("sssssssi", $judul, $tanggal, $kategori, $deskripsi, $sumber, $foto, $artikel, $id);
                    $pesan = 'diperbarui';
                }

                if ($stmt->execute()) {
                    $alert = "Artikel berhasil " . $pesan . "."; // Pesan sukses
                    $color = "success";
                } else {
                    $alert = "Terjadi kesalahan pada database: " . $stmt->error; // Pesan error
                    $color = "danger";
                }
                $stmt->close();
            }
        } elseif ($act == 'hapus') {
            // Menghapus artikel dan file terkait
            $sql = "SELECT foto, sumber FROM tbl_artikel WHERE id = ?";
            $stmt = $conn->prepare($sql);
            $stmt->bind_param("i", $id);
            $stmt->execute();
            $result = $stmt->get_result();
            $row = $result->fetch_assoc();
            $stmt->close();

            if ($row) {
                $foto_path = $target_dir_img . $row['foto'];
                $sumber_path = $target_dir_sumber . $row['sumber'];

                // Hapus foto jika ada
                if (file_exists($foto_path) && !empty($row['foto'])) {
                    unlink($foto_path);
                }
                // Hapus file sumber jika ada
                if (file_exists($sumber_path) && !empty($row['sumber'])) {
                    unlink($sumber_path);
                }

                // Hapus artikel dari database
                $sql = "DELETE FROM tbl_artikel WHERE id = ?";
                $stmt = $conn->prepare($sql);
                $stmt->bind_param("i", $id);
                if ($stmt->execute()) {
                    $alert = "Artikel berhasil dihapus."; // Pesan sukses
                    $color = "success";
                } else {
                    $alert = "Terjadi kesalahan menghapus artikel: " . $stmt->error; // Pesan error
                    $color = "danger";
                }
                $stmt->close();
            } else {
                $alert = "Artikel tidak ditemukan."; // Jika artikel tidak ada
                $color = "warning";
            }
        }
    }

    // Menyimpan pesan alert dan warna ke session untuk digunakan di halaman lain
    $_SESSION['alert'] = ['message' => $alert, 'color' => $color];
    
    // Redirect ke halaman artikel setelah selesai
    header("Location: ../admin/index.php?page=artikel");
    exit();
}
?>
