<?php
@session_start();
require_once '../action/connect.php';

if (!isset($_SESSION["login"]) || $_SESSION["login"] != true) {
    header("Location: ../index.php");
    exit;
}

if (isset($_GET['id']) && ($_GET['act'] == 'ubah' || $_GET['act'] == 'hapus')) {
    $sql = "SELECT * FROM tbl_vidio WHERE id = ?";
    $stmt = $conn->prepare($sql);
    $stmt->bind_param("i", $_GET['id']);
    $stmt->execute();
    $result = $stmt->get_result();
    $row = $result->fetch_assoc();
    $stmt->close();
} else {
    $row = null;
}
?>
<script src="https://cdn.jsdelivr.net/npm/sweetalert2@11"></script>

    <style>
    div.card {
        position: relative;
        margin: 20px auto;
        width: 90%;
        background-color: #fff;
        border-radius: 5px;
        box-shadow: 0 5px 10px rgba(0, 0, 0, 0.1);
        padding: 20px;
        margin-bottom: 20px;
        display: grid;
        gap: 40px;
    }
    

    .article-form .form-control {
        border: 1px solid #ced4da;
        border-radius: 8px;
        padding: 10px;
        font-size: 14px;
        width: 100%;
        margin: 10px 0;
        cursor: pointer;
    }
    .article-form .form-control:hover {
        border-color:rgb(42, 49, 51);
        background-color: #80bdff;
    }
    .article-form .form-control:focus {
        border-color: #80bdff;
        box-shadow: 0 0 5px rgba(0, 123, 255, 0.25);
    }

    .article-form .btn-primary {
        background-color: #007bff;
        border: none;
        padding: 10px 20px;
        font-size: 16px;
        border-radius: 8px;
        transition: 0.3s;
    }

    .article-form .btn-primary:hover {
        background-color: #0056b3;
    }

    .article-form .btn-warning {
        background-color: #ffc107;
        border: none;
        padding: 10px 20px;
        font-size: 16px;
        border-radius: 8px;
        transition: 0.3s;
        text-decoration: none;
    }

    .article-form .btn-warning:hover {
        background-color: #e0a800;
    }

    .article-form .btn-danger {
        background-color: #dc3545;
        border: none;
        padding: 10px 20px;
        font-size: 16px;
        border-radius: 8px;
        transition: 0.3s;
    }

    .article-form .btn-danger:hover {
        background-color: #c82333;
    }

    /* Styling for TinyMCE */
    .tox-edit-area {
        border: 1px solid #ced4da;
        border-radius: 8px;
        padding: 10px;
        font-size: 14px;
        min-height: 300px;
    }

    .tox-toolbar {
        background: #f1f1f1;
        border: 1px solid #ced4da;
        border-radius: 8px 8px 0 0;
    }
    .blockquote {
        text-align: center;
        font-size: 20px;
        font-weight: bold;
    }
</style>
<div class="card">
<figure class="text-center">
    <blockquote class="blockquote">
        <p>
            <span data-feather="minus"></span>
            <?= ucwords($_GET['act'] == "baru" ? "Video" :"") ?>
            <?= ucwords($_GET['act'] == "ubah" ? "Ubah: {$row['judul']}" : "") ?>
            <?= ucwords($_GET['act'] == "hapus" ? "Hapus: {$row['judul']}" : "") ?>
            <span data-feather="minus"></span>
        </p>
    </blockquote>
</figure>

<form action="../action/kelola-vidio.php" method="POST" enctype="multipart/form-data" class="article-form">
    <input type="hidden" name="act" value="<?= $_GET['act'] ?>">
    <input type="hidden" name="id" value="<?= $row['id'] ?? '' ?>">
    <input type="hidden" name="foto_lama" value="<?= $row['foto'] ?? '' ?>">

    <!-- Judul -->
    <div class="mb-3 row">
        <label for="judul" class="col-sm-5 col-form-label">Judul</label>
        <div class="col-sm-10">
            <input type="text" class="form-control" id="judul" name="judul" value="<?= @$row['judul'] ?>" <?= $_GET['act'] == 'hapus' ? 'readonly' : '' ?>>
        </div>
    </div><br>

    <!-- Tanggal -->
    <div class="mb-3 row">
        <label for="tanggal" class="col-sm-2 col-form-label">Tanggal</label>
        <div class="col-sm-10">
            <input type="date" class="form-control" id="tanggal" name="tanggal" value="<?= @$row['tanggal'] ?>" <?= $_GET['act'] == 'hapus' ? 'readonly' : '' ?>>
        </div>
    </div><br>

    <!-- Foto -->
<div class="mb-3 row">
    <label for="foto" class="col-sm-2 col-form-label">Foto</label>
    <div class="col-sm-10">
        <input type="file" class="form-control" id="foto" name="foto" accept="image/*" <?= $_GET['act'] == 'hapus' ? 'disabled' : '' ?>>
    </div>
</div><br>

    <!-- Deskripsi -->
    <div class="mb-3 row">
        <label for="deskripsi" class="col-sm-2 col-form-label">Deskripsi</label>
        <div class="col-sm-10">
            <textarea class="form-control" id="deskripsi" name="deskripsi" rows="5" <?= $_GET['act'] == 'hapus' ? 'readonly' : '' ?>><?= @$row['deskripsi'] ?></textarea>
        </div>
    </div><br>

    <!-- Kategori -->
    <div class="mb-3 row">
        <label for="kategori" class="col-sm-2 col-form-label">Kategori</label>
        <div class="col-sm-10">
            <select class="form-control" id="kategori" name="kategori" <?= $_GET['act'] == 'hapus' ? 'disabled' : '' ?>>
                <option value="">-- Pilih Kategori --</option>
                <option value="anak" <?= @$row['kategori'] == 'anak' ? 'selected' : '' ?>>Anak-anak</option>
                <option value="remaja" <?= @$row['kategori'] == 'remaja' ? 'selected' : '' ?>>Remaja</option>
                <option value="orangtua" <?= @$row['kategori'] == 'orangtua' ? 'selected' : '' ?>>Orang Tua</option>
            </select>
        </div>
    </div><br>

    <!-- Sumber -->
    <div class="mb-3 row">
        <label for="sumber" class="col-sm-2 col-form-label">Sumber</label>
        <div class="col-sm-10">
            <!-- URL sumber -->
            <input type="text" class="form-control mb-2" id="sumber" name="sumber_link" value="<?= filter_var($row['sumber'] ?? '', FILTER_VALIDATE_URL) ? $row['sumber'] : '' ?>" placeholder="Masukkan URL sumber" <?= $_GET['act'] == 'hapus' ? 'readonly' : '' ?>>
            
            <!-- Input File -->
            <input type="file" class="form-control" id="sumberFileInput" name="sumber_file" <?= $_GET['act'] == 'hapus' ? 'disabled' : '' ?>>
        </div>
    </div><br>

    <!-- Video -->
    <div class="mb-3 row">
        <label for="video" class="col-sm-2 col-form-label">Video</label>
    <div class="col-sm-10">
        <!-- Input Link Video -->
        <input type="text" class="form-control mb-2" id="video" name="video_link" value="<?= filter_var($row['vidio'] ?? '', FILTER_VALIDATE_URL) ? $row['vidio'] : '' ?>" placeholder="Masukkan URL video (YouTube/Vimeo)" <?= $_GET['act'] == 'hapus' ? 'readonly' : '' ?>>
        
        <!-- Input File Video -->
        <input type="file" class="form-control" id="videoFileInput" name="video_file" accept="video/*" <?= $_GET['act'] == 'hapus' ? 'disabled' : '' ?>>
        </div>
    </div><br><br>


    <!-- Tombol -->
    <div class="offset-sm-2">
    <a href="/admin/index.php?page=vidio" class="btn btn-warning me-2">Kembali</a>
    <?php if ($_GET['act'] != 'hapus'): ?>
        <button type="submit" class="btn btn-primary"><?= ucwords($_GET['act']) ?></button>
    <?php else: ?>
        <ion-icon name="create-outline"></ion-icon><button type="submit" class="btn btn-danger">Hapus</button>
    <?php endif; ?>
</div>

</form>
</div>


<!-- Tambahkan script untuk TinyMCE -->
<script src="https://cdn.tiny.cloud/1/6v3y3ol4se6cugn7wpdspezvaxfn8aape2wnbmsedfmis41z/tinymce/7/tinymce.min.js" referrerpolicy="origin"></script>
<script>
    tinymce.init({
        selector: '#artikel',  // Memilih elemen textarea dengan id 'artikel'
        height: 700,  // Tinggi editor
        plugins: 'image link lists table code',  // Plugin yang digunakan
        toolbar: 'undo redo | styleselect | bold italic | alignleft aligncenter alignright | bullist numlist outdent indent | link image',  // Toolbar editor
    });
</script>
<!-- Include SweetAlert2 -->

<?php if (isset($_SESSION['alert'])): ?>
<script>
    // Show SweetAlert if alert message exists
    Swal.fire({
        title: "<?= $_SESSION['alert']['title'] ?>",
        text: "<?= $_SESSION['alert']['message'] ?>",
        icon: "<?= $_SESSION['alert']['type'] ?>",
        confirmButtonText: 'OK'
    });
</script>
<?php 
    // Unset the session alert to avoid repeated showing
    unset($_SESSION['alert']);
endif; 
?>
<script>
    // Tambahkan konfirmasi sebelum menghapus
    document.querySelector('button.btn-danger').addEventListener('click', function(event) {
        event.preventDefault(); // Hentikan submit form sementara
        Swal.fire({
            title: 'Apakah Anda yakin?',
            text: "Data yang dihapus tidak dapat dikembalikan!",
            icon: 'warning',
            showCancelButton: true,
            confirmButtonColor: '#d33',
            cancelButtonColor: '#3085d6',
            confirmButtonText: 'Ya, Hapus!',
            cancelButtonText: 'Batal'
        }).then((result) => {
            if (result.isConfirmed) {
                event.target.closest('form').submit(); // Submit form jika dikonfirmasi
            }
        });
    });
</script>


