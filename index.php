<?php
require 'vendor/autoload.php'; // Autoload PhpSpreadsheet

use PhpOffice\PhpSpreadsheet\IOFactory;

function loadExcelData($filePath) {
    // Load spreadsheet
    $spreadsheet = IOFactory::load($filePath);
    $worksheet = $spreadsheet->getActiveSheet();
    $data = [];

    // Loop through each row and column to get the data
    foreach ($worksheet->getRowIterator() as $row) {
        $rowData = [];
        $cellIterator = $row->getCellIterator();
        $cellIterator->setIterateOnlyExistingCells(false);
        foreach ($cellIterator as $cell) {
            $rowData[] = $cell->getValue();
        }
        $data[] = $rowData;
    }
    return $data;
}

$data = loadExcelData('alinco-gunungintan.xlsx'); // Ganti dengan file Excel kamu

$brand = isset($_GET['brand']) ? strtolower($_GET['brand']) : '';

if ($brand) {
    // Filter data berdasarkan brand
    $filtered_data = array_filter($data, function($row) use ($brand) {
        return strpos(strtolower($row[1]), $brand) !== false; // Bandingkan kolom brand (index ke-1)
    });
} else {
    // Jika tidak ada filter, tampilkan semua data
    $filtered_data = $data;
}
?>

<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Data HandyTalky</title>
    <style>
        table {
            width: 100%;
            border-collapse: collapse;
        }
        table, th, td {
            border: 1px solid black;
            padding: 8px;
        }
        th {
            background-color: #f2f2f2;
        }
    </style>
</head>
<body>

<h2>Data HandyTalky Berdasarkan Brand</h2>

<!-- Form untuk pencarian brand -->
<form method="GET">
    <label for="brand">Cari Brand:</label>
    <input type="text" id="brand" name="brand" placeholder="Masukkan brand (contoh: Motorola)">
    <button type="submit">Cari</button>
</form>

<h3>Hasil:</h3>
<table>
    <thead>
        <tr>
            <th>Judul</th>
            <th>Brand</th>
            <th>Deskripsi</th>
        </tr>
    </thead>
    <tbody>
        <?php foreach ($filtered_data as $row): ?>
            <tr>
                <td><?php echo htmlspecialchars($row[0]); ?></td> <!-- Kolom Judul -->
                <td><?php echo htmlspecialchars($row[1]); ?></td> <!-- Kolom Brand -->
                <td><?php echo htmlspecialchars($row[2]); ?></td> <!-- Kolom Deskripsi -->
            </tr>
        <?php endforeach; ?>
    </tbody>
</table>

</body>
</html>
