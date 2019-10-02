<?php
include_once('Automation.php');

// inisiasi automasi (lihat #1 cara penggunaan di file Automation.php)
$voucher = new Automation('127.0.0.1', 'test', 'test');

// Generate filename (lihat #2 pada cara penggunaan di file Automation.php)
$filename = $auto->GenerateFilename('prefix_voucherpackage', '.csv');

// set local direktori dan ftp direktori beserta filename nya
$localdir_filename = 'D:\\'.$filename; // output : D:\SAMPLE_TEST_FILE.txt

$ftpdir_filename = '\\'.$filename; 
// output : \folder\di\ftp\server\SAMPLE_TEST_FILE.txt

// ambil file (lihat #3 pada cara penggunaan di file Automation.php)
$auto->get_file($localdir_filename, $ftpdir_filename);

?>
