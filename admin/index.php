<?php
    session_start();
    if (!isset($_SESSION['login']) || $_SESSION['username'] !== 'admin') {
        header("Location: ../index.php");
        exit();
    }
    if(!isset($_GET['page'])){
        $title='artikel';
        $titleheader=$title;
    }else{
        $title=$_GET['page'];
        $titleheader=implode(" ",explode("-",$title));
    }
    require_once '../action/connect.php';
    require_once '../action/fungsi.php';

// Flash message
$message = null;
if (isset($_SESSION['flash_message'])) {
    $message = $_SESSION['flash_message'];
    unset($_SESSION['flash_message']); 
}
?>
<?php
    if(isset($_GET['a'])){
        if($_GET['c']=='danger'){
            $icon='fa-solid fa-triangle-exclamation';
        }else{
            $icon='fa-regular fa-face-smile';
        }
?>

    <?php
    }
    require_once '../action/session_timeout.php';
    ?>
<!DOCTYPE html>
<html lang="en">

<head>
    <meta charset="UTF-8">
    <meta http-equiv="X-UA-Compatible" content="IE=edge">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Admin</title>
    <!-- ======= Styles ====== -->
    <link rel="stylesheet" href="assets/css/style.css">
    <script src="https://cdn.tiny.cloud/1/6v3y3ol4se6cugn7wpdspezvaxfn8aape2wnbmsedfmis41z/tinymce/7/tinymce.min.js" referrerpolicy="origin"></script>
    <script>
        tinymce.init({
            selector: '#artikel',
            height: 500,
            plugins: 'image link lists table code',
            toolbar: 'undo redo | styleselect | bold italic | alignleft aligncenter alignright | bullist numlist outdent indent | link image',
        });

        function pratinjauArtikel() {
            const judul = document.getElementById('judul').value;
            const artikel = tinymce.get('artikel').getContent();
            const previewWindow = window.open('', '_blank');
            previewWindow.document.write(`
                <html>
                    <head><title>${judul}</title></head>
                    <body>
                        <h1>${judul}</h1>
                        ${artikel}
                    </body>
                </html>
            `);
            previewWindow.document.close();
        }

        function previewImage(event) {
            const reader = new FileReader();
            reader.onload = function () {
                const img = document.getElementById('foto-preview');
                img.src = reader.result;
                img.style.display = 'block';
            };
            reader.readAsDataURL(event.target.files[0]);
        }
    </script>
    <script src="https://cdn.jsdelivr.net/npm/sweetalert2@11"></script>
</head>
    <body>
    <!-- =============== Navigation ================ -->
    <div class="container">
        <div class="navigation">
            <ul>
                <li>
                    <a class="navbar-brand"  href="#">
                        <span class="icon">
                            <ion-icon name="logo-apple"></ion-icon>
                        </span>
                        <span class="title"><?=$_SESSION["username"]?></span>
                    </a>
                </li>

                <li>
                    <a class="nav-link <?=@$_GET['page']=='artikel'|| @$_GET['page']=='artikel'?'active':''?>" href="/admin/index.php?page=artikel">
                        <span class="icon">
                            <ion-icon name="newspaper-outline"></ion-icon>
                        </span>
                        <span class="title">Artikel</span>
                    </a>
                </li>

                <li>
                    <a class="nav-link <?=@$_GET['page']=='vidio'|| @$_GET['page']=='vidio'?'active':''?>" href="/admin/index.php?page=vidio">
                        <span class="icon">
                            <ion-icon name="videocam-outline"></ion-icon>
                        </span>
                        <span class="title">Video</span>
                    </a>
                </li>

                <li>
                <a class="nav-link <?=@$_GET['page']=='koementar'||@$_GET['page']=='kelola-tenaga-kependidikan'?'active':''?>" href="/admin/index.php?page=komentar">
                        <span class="icon">
                            <ion-icon name="chatbubble-outline"></ion-icon>
                        </span>
                        <span class="title">Komentar</span>
                    </a>
                </li>
                <li>
                    <a href="../action/logout.php">
                        <span class="icon">
                            <ion-icon name="log-out-outline"></ion-icon>
                        </span>
                        <span class="title">Sign Out</span>
                    </a>
                </li>
            </ul>
        </div>

        <!-- ========================= Main ==================== -->
        <div class="main">
            <div class="topbar">
                <div class="toggle">
                    <ion-icon name="menu-outline"></ion-icon>
                </div>

                
                <div class="user">
                    <img src="assets/imgs/customer01.jpg" alt="">
                </div>
            </div>

            
            <main class="col-md-9 ms-sm-auto col-lg-10 px-md-4">
                <div class="d-flex justify-content-between flex-md-nowrap align-items-center pt-3 pb-2 mb-3 border-bottom">
                    
                </div>
                <?php
                    include (strtolower($title).".php");
                ?>
            </main>

    </div>
   
    <script src="dist/js/bootstrap.bundle.min.js"></script>
    <script src="icons/feather.min.js"></script>
    <script src="dashboard.js"></script>
    <script>
        feather.replace();
    </script>
    <!-- =========== Scripts =========  -->
    <script src="assets/js/main.js"></script>

    <!-- ====== ionicons ======= -->
    <script type="module" src="https://unpkg.com/ionicons@5.5.2/dist/ionicons/ionicons.esm.js"></script>
    <script nomodule src="https://unpkg.com/ionicons@5.5.2/dist/ionicons/ionicons.js"></script>
    <!-- Include SweetAlert2 -->
<!-- Include SweetAlert2 -->
<script src="https://cdn.jsdelivr.net/npm/sweetalert2@11"></script>

<?php if (isset($_SESSION['alert'])): ?>
<script>
    // Show SweetAlert if alert message exists
    Swal.fire({
        title: "<?= ($_SESSION['alert']['color'] == 'success') ? 'Berhasil' : ($_SESSION['alert']['color'] == 'danger' ? 'Gagal' : 'Peringatan') ?>",
        text: "<?= $_SESSION['alert']['message'] ?>",
        icon: "<?= $_SESSION['alert']['color'] ?>",
        showConfirmButton: false,
        timer : 2000
    }).then((result) => {
        if (result.isConfirmed) {
            // Clear the session alert after showing the message
            <?php unset($_SESSION['alert']); ?>
        }
    });
</script>
<?php endif; ?>

</body>

</html>