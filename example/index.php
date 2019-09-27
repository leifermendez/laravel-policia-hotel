<?php

include __DIR__ ."/../vendor/autoload.php";
use \leifermendez\police\PoliceHotel;

$police = new PoliceHotel('H28391AAA5H','Alterhome2018');
//$police->login('H28391AAA5H','Alterhome2018');
$police->pdf();