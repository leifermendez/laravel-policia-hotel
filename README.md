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

    //En construcción

   $police->pdf($options);

```

El registro de viajeros en el sistema Webpol  o E-Hotel de la Policía se ha convertido ya en una actividad rutinaria para propietarios y gestores de alojamientos turísticos. La Ley Orgánica de Protección de la Seguridad Ciudadana del 30 de marzo de 2015 regula esta actividad y exige una serie de requisitos indispensables para cumplir con la legalidad.

 [Portafolio] https://leifermendez.github.io