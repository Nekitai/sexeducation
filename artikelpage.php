<?php
session_start();
if (isset($_SESSION['login']) && $_SESSION['login'] === true) {
    $sqlartikel = "SELECT * FROM tbl_artikel ORDER BY tanggal";
} else {
    $sqlartikel = "SELECT * FROM tbl_artikel ORDER BY tanggal LIMIT 3";
}

require_once 'action/connect.php';
require_once 'action/fungsi.php';
$title = 'Video';
$sqlprofile = "SELECT * FROM tbl_user LIMIT 1";
$resultprofile = mysqli_query($conn, $sqlprofile);
$rowprofile = $resultprofile->fetch_assoc();

if (isset($_GET['h'])) {
    $stmt = $conn->prepare("SELECT * FROM tbl_artikel WHERE id = ?");
    $stmt->bind_param("i", $_GET['h']);
    $stmt->execute();
    $resultartikel = $stmt->get_result();
    $rowartikel = $resultartikel->fetch_assoc();
    $stmt->close();

    $stmt = $conn->prepare("SELECT * FROM tbl_artikel WHERE id != ? ORDER BY RAND() LIMIT 5");
    $stmt->bind_param("i", $_GET['h']);
    $stmt->execute();
    $resultrecent = $stmt->get_result();
    $stmt->close();
}
if (isset($_GET['h'])) {
    $stmt = $conn->prepare("SELECT * FROM tbl_artikel WHERE id = ?");
    $stmt->bind_param("i", $_GET['h']);
    $stmt->execute();
    $resultartikel = $stmt->get_result();
    $rowartikel = $resultartikel->fetch_assoc();
    $stmt->close();

    $stmt = $conn->prepare("SELECT * FROM tbl_artikel WHERE id != ? ORDER BY RAND() LIMIT 5");
    $stmt->bind_param("i", $_GET['h']);
    $stmt->execute();
    $resultrecent = $stmt->get_result();
    $stmt->close();
}

include 'template/head.php';
include 'template/headmenu.php';
include 'partial/modal/modal.php';
?>
<style>
    .comment-form input[type="text"],
.comment-form textarea {
    color: #333; /* Ganti dengan warna teks yang diinginkan */
    background-color: #f8f9fa; /* Warna latar belakang input/textarea */
    border: 1px solid #ced4da; /* Warna border input/textarea */
    border-radius: 5px; /* Membuat sudut input/textarea melengkung */
    padding: 10px; /* Memberikan padding pada input/textarea */
    width: 100%; /* Memastikan input/textarea mengisi lebar kontainer */
}

/* Jika Anda ingin memberikan warna teks khusus pada komentar yang sudah ditulis */
.comment-item p {
    color: #333; /* Ganti dengan warna teks yang diinginkan */
}

</style>
<br>
<br>
<br>>
<section id="blog" class="blog">
    <div class="container" data-aos="fade-up">
        <div class="row g-5">
            <div class="col-lg-8">
                <article class="blog-details">
                    <div class="post-img">
                        <img src="img/<?= htmlspecialchars($rowartikel['foto'] ?? '', ENT_QUOTES, 'UTF-8') ?>" alt="<?= htmlspecialchars($rowartikel['judul'] ?? '', ENT_QUOTES, 'UTF-8') ?>" class="img-fluid">
                    </div>
                    <h2 class="title"><?= htmlspecialchars($rowartikel['judul'] ?? '', ENT_QUOTES, 'UTF-8') ?></h2>
                    <h5 class="title"><?= htmlspecialchars($rowartikel['deskripsi'] ?? '', ENT_QUOTES, 'UTF-8') ?></h5>
                    <div class="meta-top">
                        <ul class="list-inline">
                            <li class="list-inline-item"><i class="bi bi-clock"></i> <?= tanggal($rowartikel['tanggal'] ?? '') ?></li>
                            <li class="list-inline-item"><i class="bi bi-tag"></i> <?= htmlspecialchars($rowartikel['kategori'] ?? '', ENT_QUOTES, 'UTF-8') ?></li>
                        </ul>
                    </div>
                    <div class="content">
                        <?= $rowartikel['artikel'] ?? '' ?>
                    </div>
                
    <!-- Tombol untuk mengakses atau mengunduh sumber -->
    <?php if (!empty($rowartikel['sumber'])): ?>
        <div class="mt-4">
            <a href="artikel.php" class="btn btn-secondary">
                <i class="bi bi-arrow-left"></i> Kembali
            </a>
            <?php if (filter_var($rowartikel['sumber'], FILTER_VALIDATE_URL)): ?>
                <!-- Jika sumber adalah tautan -->
                <a href="<?= htmlspecialchars($rowartikel['sumber'], ENT_QUOTES, 'UTF-8') ?>" target="_blank" class="btn btn-primary">
                    <i class="bi bi-link-45deg"></i> Kunjungi Sumber
                </a>
            <?php else: ?>
                <!-- Jika sumber adalah file -->
                <a href="sumber/<?= htmlspecialchars($rowartikel['sumber'], ENT_QUOTES, 'UTF-8') ?>" download class="btn btn-primary">
                    <i class="bi bi-download"></i> Unduh Sumber
                </a>
            <?php endif; ?>
        </div>
    <?php endif; ?>
</article>

                <!-- Komentar -->
                <div class="comments mt-5">
                    <h4 class="comments-title">Komentar</h4>
                    <?php
                    $stmt = $conn->prepare("SELECT * FROM tbl_komentarartikel WHERE artikel_id = ? ORDER BY tanggal DESC");
                    $stmt->bind_param("i", $_GET['h']);
                    $stmt->execute();
                    $resultkomentar = $stmt->get_result();

                    if ($resultkomentar->num_rows > 0): ?>
                        <ul class="comment-list">
                            <?php while ($rowkomentar = $resultkomentar->fetch_assoc()): ?>
                                <li class="comment-item">
                                    <div class="comment-meta">
                                        <strong><?= htmlspecialchars($rowkomentar['user'] ?? '', ENT_QUOTES, 'UTF-8') ?></strong>
                                        <time datetime="<?= $rowkomentar['tanggal'] ?? '' ?>"> - <?= tanggal($rowkomentar['tanggal'] ?? '') ?></time>
                                    </div>
                                    <p><?= htmlspecialchars($rowkomentar['komentar'] ?? '', ENT_QUOTES, 'UTF-8') ?></p>
                                </li>
                            <?php endwhile; ?>
                        </ul>
                    <?php else: ?>
                        <p>Berikan Tanggapan Anda atau Saran dan Kritiknya</p>
                    <?php endif; ?>
                    <?php $stmt->close(); ?>

                    <!-- Form Tambah Komentar -->
                    <div class="comment-form mt-4">
                        <h5>Tambahkan Komentar</h5>
                        <form action="action/komentar-artikel.php" method="POST">
                            <input type="hidden" name="artikel_id" value="<?= htmlspecialchars($_GET['h'], ENT_QUOTES, 'UTF-8') ?>">
                            <div class="mb-3">
                                <label for="nama" class="form-label">Nama</label>
                                <input type="text" 
                                       class="form-control" 
                                       id="nama" 
                                       name="nama" 
                                       value="<?= htmlspecialchars($_SESSION['username'] ?? '', ENT_QUOTES, 'UTF-8') ?>" 
                                       <?= isset($_SESSION['username']) ? 'readonly' : 'required' ?>>
                            </div>
                            <div class="mb-3">
                                <label for="komentar" class="form-label">Komentar</label>
                                <textarea class="form-control" id="komentar" name="komentar" rows="6" style="resize: vertical; width: 100%" required></textarea>
                            </div>
                            <button type="submit" class="btn btn-primary">Kirim</button>
                        </form>
                    </div>
                </div>
            </div>
            <!-- Sidebar -->
            <div class="col-lg-4">
                <div class="sidebar">
                    <h3 class="sidebar-title">Artikel Lainnya</h3>
                    <div class="recent-posts">
                        <?php while ($rowrecent = $resultrecent->fetch_assoc()) { ?>
                            <div class="post-item">
                                <img src="img/<?= htmlspecialchars($rowrecent['foto'] ?? '', ENT_QUOTES, 'UTF-8') ?>" alt="<?= htmlspecialchars($rowrecent['judul'] ?? '', ENT_QUOTES, 'UTF-8') ?>" class="img-thumbnail">
                                <div>
                                    <h4><a href="artikelpage.php?h=<?= $rowrecent['id'] ?>"><?= htmlspecialchars($rowrecent['judul'] ?? '', ENT_QUOTES, 'UTF-8') ?></a></h4>
                                    <time datetime="<?= $rowrecent['tanggal'] ?? '' ?>"><?= tanggal($rowrecent['tanggal'] ?? '') ?></time>
                                </div>
                            </div>
                        <?php } ?>
                    </div>
                </div>
            </div>
        </div>
    </div>
</section>
<script src="assets/js/main.js"></script>
<script src="assets/vendor/bootstrap/js/bootstrap.bundle.min.js"></script>
<?php
if(isset($_GET['a'])){
    $icon = ($_GET['c'] == 'danger') ? 'fa-solid fa-triangle-exclamation' : 'fa-regular fa-face-smile';
    ?>
    <div class="position-fixed top-50 start-50 translate-middle z-3">
        <div class="alert alert-<?= $_GET['c'] ?> alert-dismissible fade show" role="alert">
            <strong><i class="<?= $icon ?> pe-3"></i></strong>
            <?= $_GET['a'] ?>
            <button type="button" class="btn-close" data-bs-dismiss="alert" aria-label="Close"></button>
        </div>
    </div>
    <?php
}
include 'template/foot.php';
?>
<?php
require_once 'session_timeout.php'; // Tempatkan logika timeout di file terpisah untuk efisiensi
?>
