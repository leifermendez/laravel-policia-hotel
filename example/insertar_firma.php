<?php

include __DIR__ . "/../vendor/autoload.php";

use \leifermendez\police\PoliceHotel;

$pdf = __DIR__ . '/DUMMY.pdf';
$salida = __DIR__ . '/DUMMY_SIGNATURE.pdf';
$firma = __DIR__ . '/EJEMPLO_FIRMA.png';

$police = new PoliceHotel('USER_POLICE', 'PASS_POLICE');
$res = $police->signaturePDF($pdf, $salida, $firma);

var_dump($res);