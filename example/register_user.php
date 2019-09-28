<?php

include __DIR__ ."/../vendor/autoload.php";
use \leifermendez\police\PoliceHotel;

$police = new PoliceHotel('USER_POLICE','PASS_POLICE');

/**
 * P = PASAPORTE
 * I = CARTA DE IDENTIDAD EXTRANJERA
 * I = CARTA DE IDENTIDAD EXTRANJERA
 * N = NIE O TARJETA ESPAÑOLA DE EXTRANJEROS
 * X = PERMISO DE RESIDENCIA DE ESTADO MIEMBRO DE LA UE
 */
$data_user = [
    'nombre' => 'LUIS', // Solo caracteres valiso letras
    'apellido1' => 'RAMIREZ', // Solo caracteres valiso letras
    'apellido2' => 'LOPEZ', // Solo caracteres valiso letras
    'nacionalidad' => 'A9430AAAAA',
    'nacionalidadStr' => 'VIETNAM',
    'tipoDocumento' => 'P',
    'tipoDocumentoStr' => 'PASAPORTE',
    'numIdentificacion' => 'QDQ015771J',
    'fechaExpedicionDoc' => '27/09/2019',
    'dia' => '03', //<---- Bod dia nacimiento example 03 (2)
    'mes' => '03', //<---- Bod mes nacimiento example 03 (2)
    'ano' => '1999', //<---- Bod dia nacimiento example 1999 (4)
    'fechaNacimiento' => '03/03/1999',
    'sexo' => 'M',
    'sexoStr' => 'MASCULINO',
    'fechaEntrada' => '27/09/2019',
];

$police->register($data_user);