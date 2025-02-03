  <footer id="footer" class="footer">
    <div class="container footer-top">
      <div class="row gy-4">
        <div class="col-lg-4 col-md-6 footer-about">
          <a href="index.html" class="logo d-flex align-items-center">
            <span class="sitename">iLanding</span>
          </a>
          <div class="footer-contact pt-3">
            <p>UNITAMA</p>
            <p>VFCX+7GM, Jl. Perintis Kemerdekaan, Tamalanrea, Kec. Tamalanrea, Kota Makassar, Sulawesi Selatan 90245</p>
            <p class="mt-3"><strong>Phone:</strong> <span>+6285 2566 01498</span></p>
            <p><strong>Email:</strong> <span>fajar22@mhs.unitama.ac.id</span></p>
          </div>
          <div class="social-links d-flex mt-4">
            <a href="https://x.com/aapriiyadii"><i class="bi bi-twitter-x"></i></a>
            <a href="https://www.instagram.com/yparajaf/"><i class="bi bi-instagram"></i></a>
          </div>
        </div>

        <div class="col-lg-2 col-md-3 footer-links">
          <h4>Useful Links</h4>
          <ul>
            <li><a href="#">Home</a></li>
            <li><a href="artikel.php">Artikel</a></li>
            <li><a href="vidio.php">Education</a></li>
          </ul>
        </div>

      </div>
    </div>

    <div class="container copyright text-center mt-4">
      <p>Thank for</p>
      <p>© <span>Copyright</span> <strong class="px-1 sitename">iLanding</strong> <span>All Rights Reserved</span></p>
      <div class="credits">
        <!-- All the links in the footer should remain intact. -->
        <!-- You can delete the links only if you've purchased the pro version. -->
        <!-- Licensing information: https://bootstrapmade.com/license/ -->
        <!-- Purchase the pro version with working PHP/AJAX contact form: [buy-url] -->
        Designed by <a href="https://bootstrapmade.com/">BootstrapMade</a>
      </div>
    </div>

  </footer>

  <!-- Scroll Top -->
  <a href="#" id="scroll-top" class="scroll-top d-flex align-items-center justify-content-center"><i class="bi bi-arrow-up-short"></i></a>

  <!-- Vendor JS Files -->
  <script src="assets/vendor/bootstrap/js/bootstrap.bundle.min.js"></script>
  <script src="assets/vendor/php-email-form/validate.js"></script>
  <script src="assets/vendor/aos/aos.js"></script>
  <script src="assets/vendor/glightbox/js/glightbox.min.js"></script>
  <script src="assets/vendor/swiper/swiper-bundle.min.js"></script>
  <script src="assets/vendor/purecounter/purecounter_vanilla.js"></script>

  <!-- Main JS File -->
  <script src="assets/js/main.js"></script>
  <!-- Tambahkan SweetAlert2 CDN -->
<script src="https://cdn.jsdelivr.net/npm/sweetalert2@11"></script>

<script>
    const timeoutDuration = 15 * 60 * 1000; // 15 menit dalam milidetik
    const warningDuration = 1 * 60 * 1000; // Peringatan 1 menit sebelum logout

    let timeoutWarning = setTimeout(() => {
        Swal.fire({
            icon: 'warning',
            title: 'Peringatan',
            text: 'Sesi Anda akan habis dalam 1 menit. Silakan lakukan aktivitas untuk memperbarui sesi.',
            timer: warningDuration, // Perlihatkan selama 1 menit
            timerProgressBar: true,
        });
    }, timeoutDuration - warningDuration);

    let timeoutLogout = setTimeout(() => {
        Swal.fire({
            icon: 'error',
            title: 'Sesi Habis',
            text: 'Sesi Anda telah habis. Anda akan dialihkan ke halaman login.',
            confirmButtonText: 'OK',
        }).then(() => {
            window.location.href = '../action/logout.php';
        });
    }, timeoutDuration);

    // Reset timer saat ada aktivitas (klik atau gerakan mouse)
    document.addEventListener('mousemove', resetTimer);
    document.addEventListener('click', resetTimer);

    function resetTimer() {
        clearTimeout(timeoutWarning);
        clearTimeout(timeoutLogout);

        timeoutWarning = setTimeout(() => {
            Swal.fire({
                icon: 'warning',
                title: 'Peringatan',
                text: 'Sesi Anda akan habis dalam 1 menit. Silakan lakukan aktivitas untuk memperbarui sesi.',
                timer: warningDuration, // Perlihatkan selama 1 menit
                timerProgressBar: true,
            });
        }, timeoutDuration - warningDuration);

        timeoutLogout = setTimeout(() => {
            Swal.fire({
                icon: 'error',
                title: 'Sesi Habis',
                text: 'Sesi Anda telah habis. Anda akan dialihkan ke halaman login.',
                confirmButtonText: 'OK',
            }).then(() => {
                window.location.href = '../action/logout.php';
            });
        }, timeoutDuration);
    }
</script>

<?php
if (isset($_GET['message']) && $_GET['message'] == 'session_expired') {
    echo "<script>
        Swal.fire({
            icon: 'error',
            title: 'Sesi Habis',
            text: 'Sesi Anda telah habis. Silakan login kembali.',
            confirmButtonText: 'OK'
        });
    </script>";
}
?>
</body>

</html>