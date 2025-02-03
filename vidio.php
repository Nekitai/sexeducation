<?php
session_start();
require_once 'action/connect.php';
require_once 'action/fungsi.php';

// Fetch categories for filtering
$sqlCategories = "SELECT DISTINCT kategori FROM tbl_vidio";
$resultCategories = mysqli_query($conn, $sqlCategories);

// Initialize category filter
$kategoriFilter = isset($_GET['kategori']) ? $_GET['kategori'] : '';

if (isset($_SESSION['login']) && $_SESSION['login'] === true) {
    // Pengguna login: tampilkan semua artikel
    $sqlartikel = "SELECT * FROM tbl_vidio ORDER BY tanggal"; // Ambil semua artikel, atau sesuaikan batasan LIMIT
} else {
    // Pengguna belum login: hanya tampilkan 3 artikel pertama
    $sqlartikel = "SELECT * FROM tbl_vidio ORDER BY tanggal LIMIT 3";
}

// Initialize search filter
$searchFilter = isset($_GET['search']) ? trim($_GET['search']) : '';

$whereClause = [];
if (!empty($kategoriFilter)) {
    $whereClause[] = "kategori = '" . mysqli_real_escape_string($conn, $kategoriFilter) . "'";
}
if (!empty($searchFilter)) {
    $whereClause[] = "(judul LIKE '%" . mysqli_real_escape_string($conn, $searchFilter) . "%' OR deskripsi LIKE '%" . mysqli_real_escape_string($conn, $searchFilter) . "%')";
}

$whereSQL = '';
if (!empty($whereClause)) {
    $whereSQL = "WHERE " . implode(' AND ', $whereClause);
}

// Fetch filtered articles
$sqlartikel = "SELECT * FROM tbl_vidio $whereSQL ORDER BY tanggal" . (isset($_SESSION['login']) && $_SESSION['login'] === true ? "" : " LIMIT 3");
$resultvidio = mysqli_query($conn, $sqlartikel);


require_once 'action/connect.php';
require_once 'action/fungsi.php';
$title='Semua artikel';
$sqlprofile="SELECT * FROM tbl_user LIMIT 1";
$resultprofile=mysqli_query($conn,$sqlprofile);
$rowprofile=$resultprofile->fetch_assoc();
$sqlvidio="SELECT * FROM tbl_vidio ORDER BY tanggal LIMIT 3";
$resultvidio=mysqli_query($conn,$sqlartikel);
include 'template/head.php';
include 'template/headmenu.php';
require_once 'partial/modal/modal.php';
?>
<style>
    input[type="text"], .form-control {
        color: #333; 
    background-color: #f8f9fa; 
    border: 1px solid #ced4da; 
    border-radius: 5px; 
    padding: 10px; 
    width: 100%; 
}

</style>
<br>
<br>
<!-- ====== Recent Blog Posts Section ====== --> 
<section id="recent-posts" class="recent-posts sections-bg">
    <div class="container" data-aos="fade-up">
    <form method="GET" action="" class="mb-4">
    <div class="mb-3">
        <label for="search" class="form-label">Cari artikel:</label>
        <input type="text" name="search" id="search" class="form-control" placeholder="Cari judul atau konten..." value="<?= htmlspecialchars(isset($_GET['search']) ? $_GET['search'] : '', ENT_QUOTES, 'UTF-8') ?>">
    </div>
    <div class="mb-3">
        <label for="kategori" class="form-label">Filter berdasarkan kategori:</label>
        <select name="kategori" id="kategori" class="form-select">
            <option value="">Semua Kategori</option>
            <?php while ($rowCategory = mysqli_fetch_assoc($resultCategories)) { ?>
                <option value="<?= htmlspecialchars($rowCategory['kategori'], ENT_QUOTES, 'UTF-8') ?>" <?= (isset($_GET['kategori']) && $_GET['kategori'] === $rowCategory['kategori']) ? 'selected' : '' ?>>
                    <?= htmlspecialchars($rowCategory['kategori'], ENT_QUOTES, 'UTF-8') ?>
                </option>
            <?php } ?>
        </select>
    </div>
    <button type="submit" class="btn btn-primary">Tampilkan</button>
</form>

        <div class="section-header">
            <h2>Vidio</h2>
        </div>
        <div class="row gy-4">
            <?php
            if (mysqli_num_rows($resultvidio) > 0) {
                while ($rowvidio = mysqli_fetch_assoc($resultvidio)) {
                    ?>
                    <div class="col-xl-4 col-md-6">
                        <article>
                            <div class="post-img">
                                <img src="img/<?= $rowvidio['foto'] ?>" alt="" class="img-fluid">
                            </div>
                            <h2 class="title">
                                <a href="vidiopage.php?h=<?= $rowvidio['id'] ?>">
                            <?= strlen($rowvidio['judul']) > 50 ? substr($rowvidio['judul'], 0, 50) . '...' : $rowvidio['judul'] ?>
                                </a>
                            </h2>
                            <h5><?= $rowvidio['deskripsi'] ?></h5>
                            <div class="d-flex align-items-center">
                                <div class="post-meta">
                                    <p class="post-date">
                                        <time datetime="2022-01-01"><?= tanggal($rowvidio['tanggal']) ?></time>
                                    </p>
                                </div>
                            </div>
                        </article>
                    </div>
                    <?php
                }
            }            
            ?>
        </div>
    </div>
</section>
<?php
if(isset($_GET['a'])){
    if($_GET['c']=='danger'){
        $icon='fa-solid fa-triangle-exclamation';
    }else{
        $icon='fa-regular fa-face-smile';
    }
    ?>
    <div class="position-fixed top-50 start-50 translate-middle z-3">
        <div class="alert alert-<?=$_GET['c']?> alert-dismissible fade show" role="alert">
            <strong><i class="<?=$icon?> pe-3"></i></strong>
            <?=$_GET['a']?>
            <button type="button" class="btn-close" data-bs-dismiss="alert" aria-label="Close"></button>
        </div>
    </div>
    <?php
}
?>
<?php
include 'template/foot.php';
?>
<script src="assets/js/main.js"></script>
<script src="assets/vendor/bootstrap/js/bootstrap.bundle.min.js"></script>
