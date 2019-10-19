# Laravel parte policia Webpol 

Libreria WebPol Hotel para Laravel 4 or 5 framework - desarrollado por [Leifer](https://leifermendez.github.io).

 > __¡IMPORTANTE!__,  Ten en cuenta que para poder hacer uso de la librería deberás contar con las credenciales del sistema webpol las cuales únicamente puedes obtener de forma presencial en una comisaria de policia.
 > https://webpol.policia.es/e-hotel/

 > __NO OFICIAL__, La siguiente librería no tiene ningún respaldo oficial, es desarrollada con el propósito de ayudar a la comunidad que desarrolla sistemas relacionados con el mundo hotelero y turístico en España. [Ver màs](#parte_polcial)

![PDF_GIF](https://github.com/leifermendez/laravel-policia-hotel/blob/master/example/documentation/vista_previa.gif?raw=true)
![PDF](https://github.com/leifermendez/laravel-policia-hotel/blob/master/example/documentation/vista_previa.png?raw=true)

### Funciones

 > 1. [Registro de huésped](#registrar_huesped)
 > 2. [Obtener PDF](#obtener_pdf)
 > 2. [Lista países disponibles](#lista_paises)

 > Nuevo:
 > 1. [Firmar PDF](#firmar_pdf)


### Instalar


```
composer require leifermendez/laravel-policia-hotel
```

### Uso


| Metodo                |  Retorno    | Descripcion                                                       |
|-----------------------|-------------------|-------------------------------------------------------------------|
| getCountries()         |  array       | Obtener los paises disponibles para el registro de usuario                |
| register($data)       |  id            | Registrar un huesped en la policia                     |
| signaturePDF($pdf, $salida, $firma,null,'MADRID')       |  array            | Firmar PDF                   |
| pdf($options)       |  file            | Obtener PDF de alta de parte                                  |


### Laravel 5.* Integración

Add the service provider to your `config/app.php` file:

```php

    'providers'     => array(

        //...
        leifermendez\police\PoliceProvider::class,

    ),

```

Add the facade to your `config/app.php` file:

```php

    'aliases'       => array(

        //...
        'PoliceHotel'  => leifermendez\police\PoliceHotelFacade::class,

    ),

```

### Tipos de documentación

| TIPO                    | PALABRA                                                       |
|-------------------------|-------------------------------------------------------------------|
| P            | PASAPORTE               
| I      |  CARTA DE IDENTIDAD EXTRANJERA                              
| N        |  NIE O TARJETA ESPAÑOLA DE EXTRANJEROS                                           
| X| PERMISO DE RESIDENCIA DE ESTADO MIEMBRO DE LA UE|



###<a name="lista_paises"></a> Lista de países


```php

    use leifermendez\police\PoliceHotelFacade;

    $credentials = array(
        'user' => 'USER_POLICE',
        'pass' => 'PASS_POLICE'
    ); 
   
    
    $response = PoliceHotelFacade::to($credentials)
                    ->getCountries();


```


### <a name="registrar_huesped"></a>  Registrar Huesped

```php

    use leifermendez\police\PoliceHotelFacade;

    $credentials = array(
        'user' => 'USER_POLICE',
        'pass' => 'PASS_POLICE'
    ); 
   

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
    
    $response = PoliceHotelFacade::to($credentials)
                    ->register($data_user);


```

### <a name="obtener_pdf"></a> Obtener PDF

```php
    use leifermendez\police\PoliceHotelFacade;

    $credentials = array(
        'user' => 'USER_POLICE',
        'pass' => 'PASS_POLICE'
    ); 

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
    
    $response = PoliceHotelFacade::to($credentials)
                        ->pdf($options);

```

### <a name="firmar_pdf"></a> Firmar PDF

![Firma](https://github.com/leifermendez/laravel-policia-hotel/blob/master/example/documentation/firma.png?raw=true)

```php
    use leifermendez\police\PoliceHotelFacade;

    $credentials = array(
        'user' => 'USER_POLICE',
        'pass' => 'PASS_POLICE'
    ); 
    
    $pdf = __DIR__ . '/DUMMY.pdf'; // Ruta del PDF obtenido Ver paso (Obtener PDF)
    $salida = __DIR__ . '/DUMMY_SIGNATURE.pdf'; //Ruta donde quieres guardar el nuevo PDF firmado
    $firma = __DIR__ . '/resources/FIRMA_1.png'; // Firma en PNG (Archivo de imagen PNG)

    $response = PoliceHotelFacade::to($credentials)
                        ->signaturePDF($pdf, $salida, $firma,null,'MADRID');

```

El registro de viajeros en el sistema Webpol  o E-Hotel de la Policía se ha convertido ya en una actividad rutinaria para propietarios y gestores de alojamientos turísticos. La Ley Orgánica de Protección de la Seguridad Ciudadana del 30 de marzo de 2015 regula esta actividad y exige una serie de requisitos indispensables para cumplir con la legalidad.


### <a name="parte_polcial"></a> ¿Que es un parte policial?
El propietario o gestor deberá recoger los datos de identificación de todos los viajeros mayores de 16 años que se alojen en una vivienda turística, ajustándose al modelo de cada cuerpo de seguridad, o que recoja por lo menos el modelo del Ministerio de Interior.
[Ver más](http://www.interior.gob.es/web/servicios-al-ciudadano/seguridad/libro-registro-de-viajeros/comunicacion-de-datos-a-las-dependencias-policial)

[Ver portafolio](https://leifermendez.github.io)