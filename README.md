# Laravel parte policia Webpol 

### Instalar


```
composer require leifermendez/laravel-policia-hotel
```

### Uso


| Metodo                |  Retorno    | Descripcion                                                       |
|-----------------------|-------------------|-------------------------------------------------------------------|
| getCountries()         |  array       | Obtener los paises disponibles para el registro de usuario                |
| register($data)       |  id            | Registrar un huesped en la policia                     |
| pdf($options)       |  file            | Obtener PDF de alta de parte                                  |


### Inicio

```php

    use \leifermendez\police\PoliceHotel;
    
    $user = 'USUARIO_POLICA';
    $pass = 'CLAVE_POLICIA'

    $police = new PoliceHotel($user,$pass);

```

### Tipos de documentación

| TIPO                    | PALABRA                                                       |
|-------------------------|-------------------------------------------------------------------|
| P            | PASAPORTE               
| I      |  CARTA DE IDENTIDAD EXTRANJERA                              
| N        |  NIE O TARJETA ESPAÑOLA DE EXTRANJEROS                                           
| X| PERMISO DE RESIDENCIA DE ESTADO MIEMBRO DE LA UE|



### Lista de países

```php

  use \leifermendez\police\PoliceHotel;
    
    $user = 'USUARIO_POLICA';
    $pass = 'CLAVE_POLICIA'

    $police = new PoliceHotel($user,$pass);

    $police->getCountries();    
```


### Registrar Huesped

```php
  use \leifermendez\police\PoliceHotel;
    
    $user = 'USUARIO_POLICA';
    $pass = 'CLAVE_POLICIA'

    $police = new PoliceHotel($user,$pass);

$data_user = [
    'nombre' => 'LUIS', // Solo caracteres valiso letras
    'apellido1' => 'RAMIREZ', // Solo caracteres valiso letras
    'apellido2' => 'LOPEZ', // Solo caracteres valiso letras
    'nacionalidad' => 'A9430AAAAA',
    'nacionalidadStr' => 'VIETNAM',
    'tipoDocumento' => 'P',//<----Tipo de documentacion
    'tipoDocumentoStr' => 'PASAPORTE' //<----Tipo de documentacion palabra,
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

```

### PDF

```php
  use \leifermendez\police\PoliceHotel;
    
    $user = 'USUARIO_POLICA';
    $pass = 'CLAVE_POLICIA'

    $police = new PoliceHotel($user,$pass);

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

```

El registro de viajeros en el sistema Webpol  o E-Hotel de la Policía se ha convertido ya en una actividad rutinaria para propietarios y gestores de alojamientos turísticos. La Ley Orgánica de Protección de la Seguridad Ciudadana del 30 de marzo de 2015 regula esta actividad y exige una serie de requisitos indispensables para cumplir con la legalidad.

 [Ver portafolio](https://leifermendez.github.io)