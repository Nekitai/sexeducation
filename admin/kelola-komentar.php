<?php
@session_start();
require_once '../action/connect.php';

if (!isset($_SESSION["login"]) || $_SESSION["login"] != true) {
    header("Location: ../index.php");
    exit;
}

if (isset($_GET['id']) && ($_GET['act'] == 'ubah' || $_GET['act'] == 'hapus')) {
    $sql = "
        SELECT 'artikel' AS type, id, user, komentar, tanggal 
        FROM tbl_komentarartikel 
        WHERE id = ? 
        UNION 
        SELECT 'vidoo' AS type, id, user, komentar, tanggal 
        FROM tbl_komentarvidio 
        WHERE id = ?
    ";
    $stmt = $conn->prepare($sql);
    $stmt->bind_param("ii", $_GET['id'], $_GET['id']);
    $stmt->execute();
    $result = $stmt->get_result();
    $row = $result->fetch_assoc();
    $stmt->close();

    if (!$row) {
        $row = ['id' => '', 'user' => '', 'komentar' => '', 'tanggal' => '', 'type' => ''];
    }

    $formAction = $row['type'] === 'artikel' ? 'kelola-komentarartikel.php' : 'kelola-komentarvidio.php';
} else {
    $row = ['id' => '', 'user' => '', 'komentar' => '', 'tanggal' => '', 'type' => ''];
    $formAction = 'kelola-komentarartikel.php';
}

?>
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
            <?= ucwords($_GET['act'] == "baru" ? "Artikel Baru" :"") ?>
            <?= ucwords($_GET['act'] == "ubah" ? "Ubah" : "") ?>
            <?= ucwords($_GET['act'] == "hapus" ? "Hapus" : "") ?>
            <span data-feather="minus"></span>
        </p>
    </blockquote>
</figure>

<form action="../action/<?= $formAction ?>" method="POST" enctype="multipart/form-data" class="article-form">
    <input type="hidden" name="act" value="<?= $_GET['act'] ?>">
    <input type="hidden" name="id" value="<?= $row['id'] ?? '' ?>">

    <!-- Judul -->
    <div class="mb-3 row">
        <label for="judul" class="col-sm-5 col-form-label">User</label>
        <div class="col-sm-10">
            <input type="text" class="form-control" id="judul" name="judul" value="<?= @$row['user'] ?>" <?= $_GET['act'] == 'hapus' ? 'readonly' : '' ?>>
        </div>
    </div><br>

    <!-- Tanggal -->
    <div class="mb-3 row">
        <label for="tanggal" class="col-sm-2 col-form-label">Tanggal</label>
        <div class="col-sm-10">
            <input type="date" class="form-control" id="tanggal" name="tanggal" value="<?= @$row['tanggal'] ?>" <?= $_GET['act'] == 'hapus' ? 'readonly' : '' ?>>
        </div>
    </div><br>
    <!-- Komentar -->
    <div class="mb-3 row">
        <label for="judul" class="col-sm-5 col-form-label">User</label>
        <div class="col-sm-10">
            <input type="text" class="form-control" id="judul" name="judul" value="<?= @$row['komentar'] ?>" <?= $_GET['act'] == 'hapus' ? 'readonly' : '' ?>>
        </div>
    </div><br>

    <!-- Tombol -->
    <div class="offset-sm-2">
    <a href="/admin/index.php?page=komentar" class="btn btn-warning me-2">Kembali</a>
    <?php if ($_GET['act'] != 'hapus'): ?>
        <button type="submit" class="btn btn-primary"><?= ucwords($_GET['act']) ?></button>
    <?php else: ?>
        <button type="submit" class="btn btn-danger">Hapus</button>
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
<script src="https://cdn.jsdelivr.net/npm/sweetalert2@11"></script>

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


