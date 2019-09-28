<?php

namespace leifermendez\police;

use leifermendez\police\PoliceHotel;

class PoliceService {
    /**
     */
    public function to($crendential)
    {
        $builder = new PoliceHotel(
            $crendential['user'],
            $crendential['pass']
        );
        
        return $builder;
    }
}
