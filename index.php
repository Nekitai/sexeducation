<?php
session_start();
if (isset($_SESSION['login']) && $_SESSION['username'] === 'admin') {
  header("Location: admin/index.php");
  exit();
}
// Jika belum login, tampilkan halaman utama
require_once 'action/connect.php';
require_once 'action/fungsi.php';

$title = 'Home';

$sqlprofile = "SELECT * FROM tbl_user LIMIT 1";
$resultprofile = mysqli_query($conn, $sqlprofile);
$rowprofile = $resultprofile->fetch_assoc();

include 'template/head.php';
include 'template/headmenu.php';
include 'template/hero.php'; 
include 'template/stat.php';
include 'partial/artikel.php';
include 'partial/vidio.php';
include 'partial/modal/modal.php';
?>
<?php
if(isset($_GET['a'])){
    if($_GET['c']=='danger'){
        $icon='fa-solid fa-triangle-exclamation';
    }else{
        $icon= 'fa-regular fa-face-smile';
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
include 'action/session_timeout.php';
require_once 'action/session_timeout.php';
?>



<?php
if(isset($_GET['a'])){
    if($_GET['c']=='danger'){
        $icon='fa-solid fa-triangle-exclamation';
    } else {
        $icon= 'fa-regular fa-face-smile';
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
</body>
</html>
