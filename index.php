<?php
//$folderPath = 'F:\Music\Full';

require 'getID3-master/getid3/getid3.php';// Путь к библиотеке getID3
require 'vendor/autoload.php';

use PhpOffice\PhpSpreadsheet\Spreadsheet;
use PhpOffice\PhpSpreadsheet\Writer\Xlsx;

$folderPath = 'F:\Music\New folder'; // Путь к папке с музыкальными файлами

$getID3 = new getID3; // Создание объекта getID3

$musicTitles = array();// Создание массива для хранения названий треков

// Проходимся по всем файлам в папке
$files = scandir($folderPath);
foreach ($files as $file) {
    $filePath = $folderPath . '/' . $file;
    if (is_file($filePath) && pathinfo($filePath, PATHINFO_EXTENSION) == 'mp3') {
        $fileInfo = $getID3->analyze($filePath); // Анализ MP3 файл
        if (isset($fileInfo['tags']['id3v2']['title'][0])) {

            $musicTitles[] = ucfirst(strtolower($fileInfo['tags']['id3v2']['title'][0])) ; // Получаем название трека и добавляем в массив
        }
    }
}

// Создание Excel таблицы с полученными названиями треков
$excelFile = 'названия_треков.xlsx';
$excelData = array(array('Название трека'));
foreach ($musicTitles as $title) {
    $excelData[] = array($title);
}

// Создание нового объекта для работы с Excel
$spreadsheet = new Spreadsheet();
$sheet = $spreadsheet->getActiveSheet();

// Заполнение таблицы данными
$sheet->fromArray($excelData, null, 'A1');

// Сохранение таблицы в файл
$writer = new Xlsx($spreadsheet);
$writer->save($excelFile);

echo "Названия треков успешно сохранены в файл: $excelFile";
?>