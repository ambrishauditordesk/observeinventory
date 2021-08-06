<?php

use PhpOffice\PhpSpreadsheet\Spreadsheet;
use PhpOffice\PhpSpreadsheet\Writer\Xlsx;

include "../dbconnection.php";
session_start();

$cid = $_SESSION['client_id'];
$wid = $_SESSION['workspace_id'];

ini_set('memory_limit', '512M');

require "../vendor/autoload.php";

if($_SERVER['REQUEST_METHOD'] === 'POST')
{
$dataAr = json_decode($_POST['myjson']);
$filename = json_decode($_POST['filename']);
$con->query("DELETE FROM checkBeforeEdit WHERE fileName = '$filename'");
$spreadsheet = new Spreadsheet();
$sheet = $spreadsheet->getActiveSheet();
$startCell = 'A';
foreach($dataAr[0] as $arr) {
    $sheet->getColumnDimension($startCell++)->setAutoSize(true);
}
    
$sheet->fromArray($dataAr);
$writer = new Xlsx($spreadsheet);
$writer->setPreCalculateFormulas(false);
$writer->save($filename);
}
?>