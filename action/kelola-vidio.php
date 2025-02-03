<?php
@session_start();
require_once '../action/connect.php';

if ($_SERVER['REQUEST_METHOD'] === 'POST') {
    $act = $_POST['act'];
    $id = $_POST['id'] ?? null;
    $judul = $_POST['judul'] ?? '';
    $tanggal = $_POST['tanggal'] ?? '';
    $kategori = $_POST['kategori'] ?? '';
    $deskripsi = $_POST['deskripsi'] ?? '';
    $video_link = $_POST['video_link'] ?? '';
    $video_lama = $_POST['video_lama'] ?? '';
    $foto_lama = $_POST['foto_lama'] ?? '';
    $sumber_link = $_POST['sumber_link'] ?? '';
    $sumber_file = null;
    $foto = null;
    $video_file = null;

    try {
        // Aktifkan laporan error untuk debugging
        ini_set('display_errors', 1);
        ini_set('display_startup_errors', 1);
        error_reporting(E_ALL);

        // Handle Foto Upload
        if (!empty($_FILES['foto']['name'])) {
            $foto = 'foto_' . time() . '_' . basename($_FILES['foto']['name']);
            $target_foto = '../img/' . $foto;

            // Pindahkan file
            if (!move_uploaded_file($_FILES['foto']['tmp_name'], $target_foto)) {
                throw new Exception('Gagal mengunggah foto.');
            }

            // Hapus foto lama jika ada
            if ($act === 'ubah' && !empty($foto_lama) && file_exists('../img/' . $foto_lama)) {
                unlink('../img/' . $foto_lama);
            }
        } else {
            $foto = $foto_lama; // Gunakan foto lama jika tidak ada yang baru
        }

        // Handle Video File Upload
        if (!empty($_FILES['video_file']['name'])) {
            $video_file = 'video_' . time() . '_' . basename($_FILES['video_file']['name']);
            $target_video = '../video/' . $video_file;

            // Pindahkan file
            if (!move_uploaded_file($_FILES['video_file']['tmp_name'], $target_video)) {
                throw new Exception('Gagal mengunggah file video.');
            }

            // Hapus video lama jika ada
            if ($act === 'ubah' && !empty($video_lama) && file_exists('../video/' . $video_lama)) {
                unlink('../video/' . $video_lama);
            }
        }

        // Handle Sumber File Upload
        if (!empty($_FILES['sumber_file']['name'])) {
            $sumber_file = 'sumber_' . time() . '_' . basename($_FILES['sumber_file']['name']);
            $target_sumber = '../sumber/' . $sumber_file;

            // Pindahkan file
            if (!move_uploaded_file($_FILES['sumber_file']['tmp_name'], $target_sumber)) {
                throw new Exception('Gagal mengunggah file sumber.');
            }
        }

        // Tetapkan nilai video dan sumber
        $video = $video_file ?? $video_link ?? $video_lama;
        $sumber = !empty($sumber_link) ? $sumber_link : $sumber_file;

        if ($act === 'baru') {
            // Tambahkan data baru
            $sql = "INSERT INTO tbl_vidio (judul, tanggal, kategori, deskripsi, foto, vidio, sumber) VALUES (?, ?, ?, ?, ?, ?, ?)";
            $stmt = $conn->prepare($sql);
            $stmt->bind_param("sssssss", $judul, $tanggal, $kategori, $deskripsi, $foto, $video, $sumber);
            $stmt->execute();
            $stmt->close();

            $_SESSION['alert'] = ['title' => 'Sukses', 'message' => 'Video berhasil ditambahkan!', 'type' => 'success'];

        } elseif ($act === 'ubah') {
            // Ubah data yang ada
            $sql = "UPDATE tbl_vidio SET judul = ?, tanggal = ?, kategori = ?, deskripsi = ?, foto = ?, vidio = ?, sumber = ? WHERE id = ?";
            $stmt = $conn->prepare($sql);
            $stmt->bind_param("sssssssi", $judul, $tanggal, $kategori, $deskripsi, $foto, $video, $sumber, $id);
            $stmt->execute();
            $stmt->close();

            $_SESSION['alert'] = ['title' => 'Sukses', 'message' => 'Video berhasil diubah!', 'type' => 'success'];

        } elseif ($act === 'hapus') {
            // Hapus data
            $sql = "DELETE FROM tbl_vidio WHERE id = ?";
            $stmt = $conn->prepare($sql);
            $stmt->bind_param("i", $id);
            $stmt->execute();
            $stmt->close();

            // Hapus file terkait
            if (!empty($foto_lama) && file_exists('../img/' . $foto_lama)) {
                unlink('../img/' . $foto_lama);
            }

            if (!empty($video_lama) && file_exists('../video/' . $video_lama)) {
                unlink('../video/' . $video_lama);
            }

            if (!empty($sumber_file) && file_exists('../sumber/' . $sumber_file)) {
                unlink('../sumber/' . $sumber_file);
            }

            $_SESSION['alert'] = ['title' => 'Sukses', 'message' => 'Video berhasil dihapus!', 'type' => 'success'];
        }

    } catch (Exception $e) {
        $_SESSION['alert'] = ['title' => 'Error', 'message' => $e->getMessage(), 'type' => 'danger'];
    }

    // Redirect ke halaman video
    header("Location: ../admin/index.php?page=vidio");
    exit;
}
?>
