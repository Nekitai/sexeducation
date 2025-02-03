<?php
require '../vendor/autoload.php'; // Pastikan path sesuai dengan Composer
require_once '../action/connect.php';

use PhpOffice\PhpSpreadsheet\Spreadsheet;
use PhpOffice\PhpSpreadsheet\Writer\Xlsx;

// Query data komentar
$sql = "
    SELECT 'artikel' AS type, id, tanggal, user, komentar FROM tbl_komentarartikel
    UNION ALL
    SELECT 'video' AS type, id, tanggal, user, komentar FROM tbl_komentarvidio
    ORDER BY tanggal DESC";
$result = mysqli_query($conn, $sql);

// Membuat file spreadsheet baru
$spreadsheet = new Spreadsheet();
$sheet = $spreadsheet->getActiveSheet();

// Header kolom
$sheet->setCellValue('A1', '#');
$sheet->setCellValue('B1', 'Tanggal');
$sheet->setCellValue('C1', 'User');
$sheet->setCellValue('D1', 'Komentar');

// Isi data
$rowNum = 2; // Mulai dari baris ke-2
$no = 1;
while ($row = mysqli_fetch_assoc($result)) {
    $sheet->setCellValue('A' . $rowNum, $no++);
    $sheet->setCellValue('B' . $rowNum, $row['tanggal']);
    $sheet->setCellValue('C' . $rowNum, $row['user']);
    $sheet->setCellValue('D' . $rowNum, $row['komentar']);
    $rowNum++;
}

// Set header untuk download
header('Content-Type: application/vnd.openxmlformats-officedocument.spreadsheetml.sheet');
header('Content-Disposition: attachment; filename="data_komentar.xlsx"');

// Tulis file ke output
$writer = new Xlsx($spreadsheet);
$writer->save('php://output');
exit;
?>
