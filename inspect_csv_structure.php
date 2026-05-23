<?php

$filePath = __DIR__ . '/storage/app/public/imports/caixa_import_20260522_002811.csv';
$handle = fopen($filePath, 'r');

$lineCount = 0;
while (($row = fgetcsv($handle, 0, ';')) !== false && $lineCount < 40) {
    $lineCount++;
    $row = array_map(
        fn($cell) => $cell !== null ? mb_convert_encoding($cell, 'UTF-8', 'ISO-8859-1') : '',
        $row
    );
    // Find the non-empty columns
    $nonEmpty = array_filter($row, fn($c) => trim($c) !== '');
    if (count($nonEmpty) > 0) {
        echo "Line $lineCount (size " . count($row) . "): " . implode(" | ", array_slice($row, 0, 8)) . "\n";
    } else {
        echo "Line $lineCount: EMPTY\n";
    }
}
fclose($handle);
