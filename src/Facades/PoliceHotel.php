<?php

namespace leifermendez\police;

use Illuminate\Support\Facades\Facade;

class PoliceHotelFacade extends Facade {
    /**
     * @return string
     */
    protected static function getFacadeAccessor()
    {
        return 'PoliceHotel';
    }
}