<?php
include_once('Automation.php');

$voucher = new Automation('127.0.0.1', 'test', 'test');

$filename = $auto->GenerateFilename('prefix_voucherpackage', '.csv');

$localdir_filename = 'D:\\'.$filename;
$ftpdir_filename = '\\'.$filename; 

$auto->get_file($localdir_filename, $ftpdir_filename);
?>
