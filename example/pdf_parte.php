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

$options = [
    'file_path' => 'file_name.pdf', //Ruta donde vas a guardar el PDF
    'id_user' => 'ID_USER', //<---Lo obtienes con la funciona register()
    'id_host' => 'ID_HOST', //<---Lo obtienes con la funciona register()
    'sexo' => 'M', // Solo una letra (1),
    'sexoStr' => 'MASCULINO', // Palabra 'MASCULINO',
    'nacionalidad' => 'A9109AAAAA', // Codigo de nacionalidad,
    'nacionalidadStr' => 'ESPAÑA', // Nombre de pais,
    'numIdentificacion' => 'TEP758880F', // Numero del passaporte o nie ,etc
    'tipoDocumento' => 'P', // Letra tipo de documento
    'tipoDocumentoStr' => 'PASAPORTE', // Palabra tipo de documento
    'fechaExpedicionDoc' => '03/03/1999', // Fecha expedicion del documento
    'nombre' => 'Roberto', // Fecha expedicion del documento
    'apellido1' => 'Ramirez', // Fecha expedicion del documento
    'apellido2' => 'Lopez', // Fecha expedicion del documento
    'fechaNacimiento' => '03/03/1999', // Fecha entrada al pais
    'fechaEntrada' => '03/03/2019', // Fecha entrada al pais
];

$police->pdf($options);