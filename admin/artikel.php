<?php
@session_start();
if (!isset($_SESSION["login"]) && $_SESSION["login"] != true) {
    header("location: ../index.php");
}
$sql = "SELECT * FROM tbl_artikel ORDER BY tanggal";
$result = mysqli_query($conn, $sql);
?>
<style>
    body {
        font-family: 'Poppins', sans-serif;
    }

    table {
        position: relative;
        margin: 20px auto;
        width: 90%;
        background-color: #fff;
        border-radius: 5px;
        box-shadow: 0 2px 5px rgba(0, 0, 0, 0.1);
        padding: 20px;
        margin-bottom: 20px;
    }

    .table thead th {
        background-color: #007bff;
        color: #ffffff;
        padding: 12px 15px;
        text-align: center;
        border-bottom: 2px solid #ddd;
    }

    .table tbody td {
        padding: 12px 15px;
        border-bottom: 1px solid #ddd;
    }

    .table tbody tr:nth-child(even) {
        background-color: #f9f9f9;
    }

    .table tbody tr:hover {
        background-color: #f1f1f1;
        transition: background-color 0.3s;
    }

    .btn-primary,
    .btn-success,
    .btn-danger {
        padding: 8px 15px;
        font-size: 14px;
        border: none;
        border-radius: 5px;
        color: #fff;
        cursor: pointer;
        transition: 0.3s;
    }

    .btn-primary {
        background-color: #007bff;
        margin: 60px ;
        width: 20px;
    }

    .btn-primary:hover {
        background-color: #0056b3;
    }

    .btn-success {
        background-color: #28a745;
    }

    .btn-success:hover {
        background-color: #1e7e34;
    }

    .btn-danger {
        background-color: #dc3545;
    }

    .btn-danger:hover {
        background-color: #c82333;
    }

    .action-buttons {
        display: flex;
        gap: 10px;
    }

    .no-data {
        text-align: center;
        padding: 20px;
        color: #666;
    }
</style>

<a href="/admin/index.php?page=kelola-artikel&act=baru" class="btn btn-primary">Baru</a>
<table class="table">
    <thead>
        <tr>
            <th scope="col">#</th>
            <th scope="col">Tanggal</th>
            <th scope="col">Judul</th>
            <th scope="col">Deskripsi</th>
            <th scope="col">Kategori</th>
            <th scope="col">Artikel</th>
            <th scope="col">Aksi</th>
        </tr>
    </thead>
    <tbody>
        <?php
        if (mysqli_num_rows($result)) {
            $no = 1; // Mengubah nomor mulai dari 1
            while ($row = mysqli_fetch_assoc($result)) {
        ?>
                <tr>
                    <td><?= $no++ ?></td>
                    <td><?= tanggal($row['tanggal']) ?></td>
                    <td><?= $row['judul'] ?></td>
                    <td><?= $row['deskripsi'] ?></td>
                    <td><?= $row['kategori'] ?></td>
                    <td><?= mb_strimwidth($row['artikel'], 0, 200, "...") ?></td>
                    <td>
                        <div class="action-buttons">
                            <a href="/admin/index.php?page=kelola-artikel&act=ubah&id=<?= $row['id'] ?>" class="btn btn-success"><ion-icon name="create-outline"></ion-icon></a>
                            <a href="/admin/index.php?page=kelola-artikel&act=hapus&id=<?= $row['id'] ?>" class="btn btn-danger"><ion-icon name="trash-outline"></ion-icon></a>
                        </div>
                    </td>
                </tr>
        <?php
            }
        } else {
        ?>
            <tr>
                <td colspan="7" class="no-data">Belum ada data</td>
            </tr>
        <?php
        }
        ?>
    </tbody>
</table>
<script src="assets/js/main.js"></script>

<!-- ====== ionicons ======= -->
<script type="module" src="https://unpkg.com/ionicons@5.5.2/dist/ionicons/ionicons.esm.js"></script>
<script nomodule src="https://unpkg.com/ionicons@5.5.2/dist/ionicons/ionicons.js"></script>
<!-- Include SweetAlert2 -->
<!-- Include SweetAlert2 -->
<script src="https://cdn.jsdelivr.net/npm/sweetalert2@11"></script>