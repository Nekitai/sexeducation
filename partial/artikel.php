<?php 
$sqlartikel="SELECT * FROM tbl_artikel ORDER BY tanggal LIMIT 3";
$resultartikel=mysqli_query($conn, $sqlartikel);
?>
<!-- ======= Recent Blog  Posts Section ======= -->
<section id="recent-posts" class="recent-posts sections-bg">
    <div class="container" data-aos="fade-up">
        <div class="section-header">
            <h2>Artikel</h2>
        </div>
        <div class="row gy-4">
            <?php
            if (mysqli_num_rows($resultartikel) > 0) {
                while ($rowartikel = mysqli_fetch_assoc($resultartikel)) {
                    ?>
                    <div class="col-xl-4 col-md-6">
                        <article>
                            <div class="post-img">
                                <img src="img/<?= $rowartikel['foto'] ?>" alt="" class="img-fluid">
                            </div>
                            <h3 class="title">
                                <a href="artikelpage.php?h=<?= $rowartikel['id'] ?>">
                                    <?= strlen($rowartikel['judul']) > 50 ? substr($rowartikel['judul'], 0, 50) . '...' : $rowartikel['judul'] ?>
                                </a>
                            </h3>
                            <p><?= $rowartikel['deskripsi'] ?></p>
                            <div class="d-flex align-items-center">
                                <div class="post-meta">
                                    <p class="post-date">
                                        <time datetime="2022-01-01"><?= tanggal($rowartikel['tanggal']) ?></time>
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
<div class="text-center mt-4">
    <a href="artikel.php" class="btn btn-primary">More</a>
</div>
</div>

</section><!-- /About Section --><!-- End Recent Blog Posts Section --> 
