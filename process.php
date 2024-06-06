<?php
require 'vendor/autoload.php';

use PhpOffice\PhpSpreadsheet\IOFactory;
use PhpOffice\PhpSpreadsheet\Spreadsheet;
use PhpOffice\PhpSpreadsheet\Writer\Xlsx;

if ($_SERVER['REQUEST_METHOD'] == 'POST' && isset($_FILES['file'])) {
    $filePath = $_FILES['file']['tmp_name'];

    // Baca file Excel yang diunggah
    $spreadsheet = IOFactory::load($filePath);
    $sheet = $spreadsheet->getActiveSheet();

    $data = [];
    foreach ($sheet->getRowIterator() as $row) {
        $cellIterator = $row->getCellIterator();
        $cellIterator->setIterateOnlyExistingCells(false);

        $rowData = [];
        foreach ($cellIterator as $cell) {
            $rowData[] = $cell->getValue();
        }
        $data[] = $rowData;
    }

    // Ekstrak data untuk analisis ANOVA
    $header = array_shift($data); // Hapus header
    $treatments = array_column($data, 1);
    $scores = array_column($data, 2);

    // Lakukan analisis One-Way ANOVA
    $anovaResult = oneWayAnova($treatments, $scores);

    if ($anovaResult['significant']) {
        // Lakukan uji lanjut DUNCAN jika ada pengaruh nyata
        $duncanResult = duncanTest($treatments, $scores);

        // Simpan hasil pengujian ke file Excel baru
        $resultSpreadsheet = new Spreadsheet();
        $resultSheet = $resultSpreadsheet->getActiveSheet();
        $resultSheet->fromArray($duncanResult, NULL, 'A1');

        $writer = new Xlsx($resultSpreadsheet);
        $writer->save('hasil_pengujian.xlsx');

        echo "Analisis selesai. Hasil disimpan di 'hasil_pengujian.xlsx'.";
    } else {
        echo "Tidak ditemukan pengaruh nyata dalam analisis ANOVA.";
    }
}

function oneWayAnova($treatments, $scores) {
    // Implementasi analisis ANOVA di sini
    // Ini adalah contoh sederhana dan tidak lengkap
    // Anda perlu menggunakan pustaka statistik untuk hasil yang akurat
    $anovaResult = ['significant' => true]; // Ganti dengan logika ANOVA sebenarnya
    return $anovaResult;
}

function duncanTest($treatments, $scores) {
    // Implementasi uji lanjut DUNCAN di sini
    // Ini adalah contoh sederhana dan tidak lengkap
    // Anda perlu menggunakan pustaka statistik untuk hasil yang akurat
    $duncanResult = [
        ['Treatment', 'Group'],
        ['A', '1'],
        ['B', '2'],
        ['C', '1']
    ]; // Ganti dengan logika Duncan sebenarnya
    return $duncanResult;
}
?>
