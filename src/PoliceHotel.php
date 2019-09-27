<?php

namespace leifermendez\police;

class PoliceHotel
{
    private $endpoint, $cookie, $user, $pass, $_csrf, $headers;

    public function __construct($user, $pass)
    {
        try {
            $this->user = $user;
            $this->pass = $pass;
            $this->endpoint = 'https://webpol.policia.es/e-hotel';
            $this->headers = [
                'User-Agent: PostmanRuntime/7.16.3',
            ];

            if (!$user or !$pass) {
                throw new \Exception('error.login.users');
            }

        } catch (\Exception $e) {
            return $e->getMessage();
        }
    }

    private function home()
    {

        try {
            $headers = $this->headers;
            $headers = array_merge(
                $headers,
                [
                    'Cookie: ' . $this->cookie,
                    'Content-Type: application/x-www-form-urlencoded',
                ]
            );

            $parse_cookies = array();
            $ch = curl_init();
            curl_setopt($ch, CURLOPT_RETURNTRANSFER, TRUE);
            curl_setopt($ch, CURLOPT_HTTPHEADER, $headers);
            curl_setopt($ch, CURLOPT_SSL_VERIFYHOST, false);
            curl_setopt($ch, CURLOPT_SSL_VERIFYPEER, false);
            curl_setopt($ch, CURLOPT_HEADER, 1);
            curl_setopt($ch, CURLOPT_URL, $this->endpoint . '/inicio');
            ob_start();
            $response = curl_exec($ch);
            $response = str_replace(["\r\n", "\n", " "], "", $response);


            preg_match('/<metaname="_csrf"content="(.{36})"\/>/',
                $response, $matches, PREG_OFFSET_CAPTURE);
            if (count($matches)) {
                $matches = array_reverse($matches);
            }
            $matches = array_shift($matches);
            $csr = $matches[0];
            $this->_csrf = $csr;

            return array('_csrf' => $csr, 'cookie' => $this->cookie);

        } catch (\Exception $e) {
            return $e->getMessage();
        }
    }

    private function csr()
    {

        $parse_cookies = array();
        $ch = curl_init();
        curl_setopt($ch, CURLOPT_RETURNTRANSFER, TRUE);
        curl_setopt($ch, CURLOPT_USERAGENT, 'PostmanRuntime/7.16.3');
        curl_setopt($ch, CURLOPT_SSL_VERIFYHOST, false);
        curl_setopt($ch, CURLOPT_SSL_VERIFYPEER, false);
        curl_setopt($ch, CURLOPT_HEADER, 1);
        curl_setopt($ch, CURLOPT_URL, $this->endpoint . '/login');
        ob_start();
        $response = curl_exec($ch);

        preg_match_all('/^Set-Cookie:\s*([^;]*)/mi', $response, $matches);
        $cookies = array();
        foreach ($matches[1] as $item) {
            parse_str($item, $cookie);
            $cookies = array_merge($cookies, $cookie);
        }

        $cookies = array_reverse($cookies);
        foreach ($cookies as $key_cookie => $value_cookie) {
            $parse_cookies[] = $key_cookie . '=' . $value_cookie;
        }
        $parse_cookies = implode('; ', $parse_cookies);
        ob_end_clean();
        curl_close($ch);

        $response = str_replace(["\r\n", "\n", " "], "", $response);

        preg_match('/<metaname="_csrf"content="(.{36})"\/>/',
            $response, $matches, PREG_OFFSET_CAPTURE);
        if (count($matches)) {
            $matches = array_reverse($matches);
        }
        $matches = array_shift($matches);
        $csr = $matches[0];
        $this->_csrf = $csr;
        $this->cookie = $parse_cookies;
        return array('_csrf' => $csr, 'cookie' => $parse_cookies);

    }

    private function downloadPdf($path = null)
    {
        try{
            $headers = array_merge(
                $this->headers,
                [
                    'Cookie: ' . $this->cookie
                ]
            );
            $ch = curl_init();
            curl_setopt($ch, CURLOPT_RETURNTRANSFER, TRUE);
            curl_setopt($ch, CURLOPT_HTTPHEADER, $headers);
            curl_setopt($ch, CURLOPT_SSL_VERIFYHOST, false);
            curl_setopt($ch, CURLOPT_SSL_VERIFYPEER, false);
            curl_setopt($ch, CURLOPT_HEADER, 1);
            curl_setopt($ch, CURLOPT_URL, $this->endpoint . '/hospederia/generarPDFparteHuesped');
            ob_start();

            $response = curl_exec($ch);

            if(!$path){
                throw new \Exception('error.path.save');
            }

            $downloadPath = $path;
            $file = fopen($downloadPath, "w+");
            fputs($file, $response);
            fclose($file);

        }catch (\Exception $e){
            return $e->getMessage();
        }
    }

    private function login($username = null, $password = null)
    {

        try {

            $data_csr = $this->csr();

            $data = [
                'username' => $this->user,
                'password' => $this->pass,
                '_csrf' => $data_csr['_csrf']
            ];

            $headers = $this->headers;
            $headers = array_merge(
                $headers,
                [
                    'Cookie: ' . $this->cookie,
                    'Content-Type: application/x-www-form-urlencoded',
                ]
            );

            $login = curl_init();
            curl_setopt($login, CURLOPT_RETURNTRANSFER, TRUE);
            curl_setopt($login, CURLOPT_URL, $this->endpoint . '/execute_login');
            curl_setopt($login, CURLOPT_HTTPHEADER, $headers);
            curl_setopt($login, CURLOPT_SSL_VERIFYHOST, false);
            curl_setopt($login, CURLOPT_SSL_VERIFYPEER, false);
            curl_setopt($login, CURLOPT_POST, TRUE);
            curl_setopt($login, CURLOPT_POSTFIELDS, http_build_query($data));
            ob_start();
            $response = curl_exec($login);
            ob_end_clean();
            curl_close($login);

            $response = str_replace(["\r\n", "\n", " "], "", $response);

            if (strpos($response, '/e-hotel/inicio') !== false) {
                $this->cookie = $data_csr['cookie'];
                $response_home = $this->home();

                return $response_home;
            } else {
                throw new \Exception('error.login.police');
            }

        } catch (\Exception $e) {
            return $e->getMessage();
        }

    }

    /** FUNCIONES PUBLICAS* */

    public function register($data_extra = [])
    {
        try {

            $data = [
                'jsonHiddenComunes' => '',
                'idHospederia' => '',
                'nombre' => '',
                'apellido1' => '',
                'apellido2' => '',
                'nacionalidad' => 'A9109AAAAA',
                'nacionalidadStr' => '',
                'tipoDocumento' => '',
                'tipoDocumentoStr' => '',
                'numIdentificacion' => '',
                'fechaExpedicionDoc' => '27/09/2019',
                'dia' => '',
                'mes' => '',
                'ano' => '',
                'fechaNacimiento' => '27/09/2019',
                'sexo' => 'M',
                'sexoStr' => 'MASCULINO',
                'fechaEntrada' => '27/09/2019',
                '_csrf' => ''
            ];

            $data = array_merge($data, $data_extra);

            $ch = curl_init();
            curl_setopt($ch, CURLOPT_RETURNTRANSFER, TRUE);
            curl_setopt($ch, CURLOPT_URL, $this->endpoint . '/hospederia/manual/insertar/huesped');
            curl_setopt($ch, CURLOPT_HTTPHEADER, $this->headers);
            curl_setopt($ch, CURLOPT_SSL_VERIFYHOST, false);
            curl_setopt($ch, CURLOPT_SSL_VERIFYPEER, false);
            curl_setopt($ch, CURLOPT_POST, TRUE);
            curl_setopt($ch, CURLOPT_POSTFIELDS, http_build_query($data));
            ob_start();
            $response = curl_exec($ch);
            ob_end_clean();
            curl_close($ch);

            return $response;

        } catch (\Exception $e) {
            return $e->getMessage();
        }
    }

    public function pdf($file_path)
    {

        try {

            $this->login($this->user, $this->pass);

            $headers = array_merge(
                $this->headers,
                [
                    'Cookie: ' . $this->cookie,
                    'X-CSRF-TOKEN: ' . $this->_csrf,
                    'X-Requested-With: XMLHttpRequest'
                ]
            );

            $host = [
                "idHuesped" => 0,
                "idHospederia" => 33615,
                "idAgrupacion" => 0,
                "codigoMetadata" => 0,
                "sexo" => "M",
                "nacionalidad" => "A9109AAAAA",
                "nacionalidadStr" => "ESPAÑA",
                "persona" => [
                    "nombre" => "ROBERTO",
                    "apellido1" => "RAMIREZ",
                    "apellido2" => "LOPEZ",
                    "fechaNacimiento" => "03/03/1999",
                    "anoNacimiento" => "1999",
                    "sexo" => "M",
                    "datoMigrado" => false,
                    "documento" => [
                        "numIdentificacion" => "TEP758880F",
                        "tipoDocumento" => "P",
                        "tipoDocumentoStr" => "PASAPORTE",
                        "datoMigrado" => false,
                        "controlado" => false
                    ],
                    "esArrendatario" => false,
                    "esConductor" => false,
                    "nacionalidad" => "A9109AAAAA",
                    "ano" => "1999"
                ],
                "fechaEntrada" => "27/09/2019",
                "fechaExpedicionDoc" => "27/09/2019",
                "sexoStr" => "MASCULINO",
                "fechaNacimVchar" => "19990303",
                "dia" => "03",
                "mes" => "03",
                "ano" => "1999"
            ];

            $data = [
                'idHuesped' => 228613412,
                'huespedJson' => json_encode($host)
            ];


            $ch = curl_init();
            curl_setopt($ch, CURLOPT_RETURNTRANSFER, TRUE);
            curl_setopt($ch, CURLOPT_URL, $this->endpoint . '/hospederia/generarParteHuesped');
            curl_setopt($ch, CURLOPT_HTTPHEADER, $headers);
            curl_setopt($ch, CURLOPT_SSL_VERIFYHOST, false);
            curl_setopt($ch, CURLOPT_SSL_VERIFYPEER, false);
            curl_setopt($ch, CURLOPT_POST, TRUE);
            curl_setopt($ch, CURLOPT_POSTFIELDS, http_build_query($data));
            ob_start();
            $response = curl_exec($ch);
            ob_end_clean();
            curl_close($ch);

            $response = str_replace(["\r\n", "\n", " "], "", $response);

            if (strpos($response, '/e-hotel/previsualizacionPdf') !== false) {
                if($file_path) $this->downloadPdf($file_path);
            } else {
                throw new \Exception('error.pdf.link');
            }

            return $response;

        } catch (\Exception $e) {
            return $e->getMessage();
        }
    }

}