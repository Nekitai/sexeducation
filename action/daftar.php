<?php
$color="danger";
if($_SERVER["REQUEST_METHOD"]=="POST"){
    require_once'../action/connect.php';
    $username=$_POST['user'];
    $password=$_POST['password'];
    $password_hash=password_hash($password,PASSWORD_DEFAULT);
    $repassword=$_POST['repassword'];
    $sql="SELECT * FROM tbl_user WHERE user='$username'";
    $result=mysqli_query($conn, $sql);
    if(mysqli_num_rows($result)==0){
        if(empty($username)||empty($password)){
            $alert="Pastikan username atau password terisi";
        }elseif ($password!==$repassword) {
            $alert="Password dan Konfirmasi tidak sama";
        }else{
            $sql="INSERT INTO tbl_user (user,password) VALUES ('$username','$password_hash')";
            if ($conn->query($sql)===TRUE){
                $alert="Berhasil mendaftar, silahkan login";
                $color="success";
            }else{
                $alert="Terjadi kesalahan saat menyimpan data";
            }
        }
    }else{
        $alert="Pengguna telah terdaftar, silahkan login";
    }
}else{
    $alert="pastikan anda mengisi formulir pendaftaran";
}
header("location:../index.php?a=$alert&c=$color");

