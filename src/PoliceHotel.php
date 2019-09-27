<?php

namespace leifermendez\police;

use anlutro\cURL\cURL;
use leifermendez\police\CurlP;

class PoliceHotel
{
    private $endpoint, $curl;

    public function __construct()
    {
        $this->endpoint = 'https://webpol.policia.es/e-hotel';
        $this->curl = new CurlP;
    }

    public function csr()
    {

        $response = $this->curl->to($this->endpoint)
            ->withResponseHeaders()
            ->setCookieJar("demo_cook.txt")
            ->returnResponseObject()
            ->get();

        $response = $response->content;
        $response = str_replace(["\r\n","\n"," "],"",$response);

        preg_match('/<metaname="_csrf"content="(.{36})"\/>/',
            $response, $matches, PREG_OFFSET_CAPTURE);
        if(count($matches)){
            $matches = array_reverse($matches);
        }
        $matches = array_shift($matches);
        $csr = $matches[0];

       return $csr;

    }

    public function login($username=null, $password = null)
    {
//H28391AAA5Y
        //Alterhome2018
        $csr = $this->csr();
        $response = $this->curl->to($this->endpoint.'/execute_login')
            //->withHeaders($headers)
            ->setCookieFile("demo_cook.txt")
            ->withData( array(
                'username' => $username,
                'password' => $password,
                '_csrf' => $csr
            ) )
            ->post();

        var_dump($response);
    }

}