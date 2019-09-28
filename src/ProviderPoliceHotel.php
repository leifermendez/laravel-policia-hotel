<?php

namespace leifermendez\police;

use Illuminate\Support\ServiceProvider;

class PoliceProvider extends ServiceProvider {

    public function register()
    {
        $this->app->singleton('PoliceHotel', function () {
            return new PoliceService();
        }
        );
    }
    /**
     * @return array
     */
    public function provides()
    {
        return array('PoliceHotel');
    }
}