<?php

include __DIR__ . "/../vendor/autoload.php";

use \leifermendez\police\PoliceHotel;

$pdf = __DIR__ . '/DUMMY.pdf';
$salida = __DIR__ . '/DUMMY_SIGNATURE.pdf';
$firma = __DIR__ . '/resources/FIRMA_1.png';

$police = new PoliceHotel('USER_POLICE', 'PASS_POLICE');
$res = $police->signaturePDF($pdf, $salida, $firma,null,'MADRID');

var_dump($res);