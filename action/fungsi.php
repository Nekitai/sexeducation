<?php
function tanggal($tgl){
    $hari=date_format(date_create($tgl),"d");
    $bulan=date_format(date_create($tgl),"m");
    $tahun=date_format(date_create($tgl),"y");
    if($bulan=='01'):return $hari.'Januari'.$tahun;endif;
    if($bulan=='02'):return $hari.'Februari'.$tahun;endif;
    if($bulan=='03'):return $hari.'Maret'.$tahun;endif;
    if($bulan=='04'):return $hari.'April'.$tahun;endif;
    if($bulan=='05'):return $hari.'Mei'.$tahun;endif;
    if($bulan=='06'):return $hari.'Juni'.$tahun;endif;
    if($bulan=='07'):return $hari.'Juli'.$tahun;endif;
    if($bulan=='08'):return $hari.'Agustus'.$tahun;endif;
    if($bulan=='09'):return $hari.'September'.$tahun;endif;
    if($bulan=='10'):return $hari.'Oktober'.$tahun;endif;
    if($bulan=='11'):return $hari.'November'.$tahun;endif;
    if($bulan=='12'):return $hari.'Desember'.$tahun;endif;
}
?>