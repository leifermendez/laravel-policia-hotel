<?php

namespace leifermendez\police;

use DateTime;

use setasign\Fpdi\Fpdi;

class PoliceHotel
{
    private $endpoint, $cookie, $user, $pass, $_csrf, $headers, $fpdi;

    protected $pkgoptions = array(
        'countries' => array(),
        'user' => array(),
        'pdf' => array(),
    );

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

            return $this;

        } catch (\Exception $e) {
            return $e->getMessage();
        }
    }

    private function curl($url = null, $method = 'GET', $data = array(), $headers = array())
    {
        try {

            $ch = curl_init();
            curl_setopt($ch, CURLOPT_RETURNTRANSFER, TRUE);
            curl_setopt($ch, CURLOPT_HTTPHEADER, $headers);
            curl_setopt($ch, CURLOPT_SSL_VERIFYHOST, false);
            curl_setopt($ch, CURLOPT_SSL_VERIFYPEER, false);
            curl_setopt($ch, CURLOPT_HEADER, 1);
            curl_setopt($ch, CURLOPT_URL, $url);
            if ($method === 'POST') curl_setopt($ch, CURLOPT_POST, TRUE);
            if ($method === 'POST') curl_setopt($ch, CURLOPT_POSTFIELDS, http_build_query($data));
            ob_start();

            $response = curl_exec($ch);
            $raw_response = $response;

            /** cookies ** */

            preg_match_all('/^Set-Cookie:\s*([^;]*)/mi', $response, $matches);
            $cookies = array();
            foreach ($matches[1] as $item) {
                parse_str($item, $cookie);
                $cookies = array_merge($cookies, $cookie);
            }
            $parse_cookies = array();

            $cookies = array_reverse($cookies);
            foreach ($cookies as $key_cookie => $value_cookie) {
                $parse_cookies[] = $key_cookie . '=' . $value_cookie;
            }
            $parse_cookies = implode('; ', $parse_cookies);

            $raw_response = str_replace(["\r\n", "\n", " "], "", $raw_response);

            ob_end_clean();
            curl_close($ch);

            preg_match('/<metaname="_csrf"content="(.{36})"\/>/',
                $raw_response, $matches, PREG_OFFSET_CAPTURE);
            if (count($matches)) {
                $matches = array_reverse($matches);
            }
            $matches = array_shift($matches);
            $csr = $matches[0];

            return array(
                'content' => $raw_response,
                'cookies' => $parse_cookies,
                '_csrf' => $csr
            );

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

    private function getID()
    {
        try {
            $headers = array_merge(
                $this->headers,
                [
                    'Cookie: ' . $this->cookie,
                    'X-CSRF-TOKEN: ' . $this->_csrf,
                    'X-Requested-With: XMLHttpRequest'
                ]
            );

            $response = $this->curl(
                $this->endpoint . '/hospederia/manual/vista/grabadorManual',
                'GET',
                [],
                $headers
            );

            $pattern = '/id="idHospederia"type="hidden"value="([^"]+).*>/i';

            $pattern_csrf = '/name="_csrf"value="([^"]+).*>/i';

            preg_match($pattern, $response['content'], $matches);
            preg_match($pattern_csrf, $response['content'], $matches_csrf);

            return array('id' => $matches[1], '_csrf' => $matches_csrf[1]);

        } catch (\Exception $e) {
            return $e->getMessage();
        }
    }

    private function downloadPdf($path = null)
    {
        try {
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

            if (!$path) {
                throw new \Exception('error.path.save');
            }

            $downloadPath = $path;
            $file = fopen($downloadPath, "w+");
            fputs($file, $response);
            fclose($file);

            $this->pkgoptions['pdf'] = $downloadPath;
            return $this->pkgoptions['pdf'];

        } catch (\Exception $e) {
            return $e->getMessage();
        }
    }

    private function login()
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

            $curl_response = $this->curl(
                $this->endpoint . '/execute_login',
                'POST',
                $data,
                $headers
            );

            if (strpos($curl_response['content'], '/e-hotel/inicio') !== false) {
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

    private function getIDuser()
    {
        try {

            $headers = array_merge(
                $this->headers,
                [
                    'Cookie: ' . $this->cookie,
                    'X-CSRF-TOKEN: ' . $this->_csrf,
                    'X-Requested-With: XMLHttpRequest'
                ]
            );


            $response = $this->curl(
                $this->endpoint . '/hospederia/manual/vista/parteViajero',
                'GET',
                [],
                $headers
            );

            preg_match_all('/name="idHuesped\"(.*)value=\"(.*?)\"/i', $response['content'], $id);
            $id = array_reverse($id);
            $id = $id[0][0];

            return $id;

        } catch (\Exception $e) {
            return $e->getMessage();
        }
    }

    /** FUNCIONES PUBLICAS* */

    public function getCountries()
    {
        try {
            $this->login();

            $headers = array_merge(
                $this->headers,
                [
                    'Cookie: ' . $this->cookie,
                    'X-CSRF-TOKEN: ' . $this->_csrf,
                    'X-Requested-With: XMLHttpRequest'
                ]
            );

            $response = $this->curl(
                $this->endpoint . '/hospederia/manual/vista/grabadorManual',
                'GET',
                [],
                $headers
            );

            $pattern = '/<selectid="nacionalidad"(.*?)<\/select>/i';
            $pattern_options = '@<optionvalue=\"(.*)\">(.*)</option>@';

            preg_match($pattern, $response['content'], $matches);
            $raw_countries = $matches[1];

            preg_match($pattern_options, $raw_countries, $matches_options);

            $countries = explode('optionvalue=', $matches_options[1]);
            $new_countries = array();


            foreach ($countries as $country) {
                preg_match_all('/[A-Za-z0-9]+/i', $country, $tmp);
                if ($tmp && count($tmp) && (count($tmp[0]) > 1)) {
                    $new_countries[] = [
                        'id' => $tmp[0][0],
                        'name' => $tmp[0][1]
                    ];
                }
            }

            $this->pkgoptions['countries'] = $new_countries;
            return $this->pkgoptions['countries'];


        } catch (\Exception $e) {
            return $e->getMessage();
        }
    }

    public function register($data_extra)
    {
        try {
            $this->login();

            if (!$data_extra['nacionalidad']) {
                throw new \Exception('error.nacionalidad.null');
            }

            $response = $this->getID();

            $data = [
                'jsonHiddenComunes' => '',
                'idHospederia' => $response['id'],
                'nombre' => '',
                'apellido1' => '',
                'apellido2' => '',
                'nacionalidad' => '',
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
                '_csrf' => $response['_csrf']
            ];

            $data = array_merge($data, $data_extra);

            $headers = array_merge(
                $this->headers,
                [
                    'Cookie: ' . $this->cookie
                ]
            );

            $response = $this->curl(
                $this->endpoint . '/hospederia/manual/insertar/huesped',
                'POST',
                $data,
                $headers
            );

            if (strpos($response['content'], '/vista/parteViajero') !== false) {
                $this->_csrf = $data['_csrf'];
                $id_user = $this->getIDuser();
                $return = array('id_user' => $id_user, 'id_host' => $data['idHospederia']);

                $this->pkgoptions['user'] = $return;
                return $this->pkgoptions['user'];


            } else {
                throw new \Exception('error.vista.parte.vaiajero');
            }

        } catch (\Exception $e) {
            return $e->getMessage();
        }
    }

    public function pdf($options)
    {

        try {

            $this->login();

            $headers = array_merge(
                $this->headers,
                [
                    'Cookie: ' . $this->cookie,
                    'X-CSRF-TOKEN: ' . $this->_csrf,
                    'X-Requested-With: XMLHttpRequest'
                ]
            );


            $bod_source = $options['fechaNacimiento'];
            $bod = new DateTime($bod_source);

            $issue_date = $options['fechaExpedicionDoc'];
            $enter_date = $options['fechaEntrada'];

            $date_issue = new DateTime($issue_date);
            $date_enter = new DateTime($enter_date);


            $host = [
                "idHuesped" => $options['id_user'],
                "idHospederia" => $options['id_host'],
                "idAgrupacion" => 0,
                "codigoMetadata" => 0,
                "sexo" => $options['sexo'],
                "nacionalidad" => $options['nacionalidad'],
                "nacionalidadStr" => $options['nacionalidadStr'],
                "persona" => [
                    "nombre" => $options['nombre'],
                    "apellido1" => $options['apellido1'],
                    "apellido2" => $options['apellido2'],
                    "fechaNacimiento" => $bod->format('d/m/Y'), //03/03/1999
                    "anoNacimiento" => $bod->format('Y'),
                    "sexo" => $options['sexo'],
                    "datoMigrado" => false,
                    "documento" => [
                        "numIdentificacion" => $options['numIdentificacion'],
                        "tipoDocumento" => $options['tipoDocumento'],
                        "tipoDocumentoStr" => $options['tipoDocumentoStr'],
                        "datoMigrado" => false,
                        "controlado" => false
                    ],
                    "esArrendatario" => false,
                    "esConductor" => false,
                    "nacionalidad" => $options['nacionalidad'],
                    "ano" => $bod->format('Y')
                ],
                "fechaEntrada" => $date_enter->format('d/m/Y'),
                "fechaExpedicionDoc" => $date_issue->format('d/m/Y'),
                "sexoStr" => $options['sexoStr'],
                "fechaNacimVchar" => $bod->format('Ymd'), //19990303
                "dia" => $bod->format('d'),
                "mes" => $bod->format('m'),
                "ano" => $bod->format('Y')
            ];

            $data = [
                'idHuesped' => $options['id_user'],
                'huespedJson' => json_encode($host)
            ];


            $curl_response = $this->curl(
                $this->endpoint . '/hospederia/generarParteHuesped',
                'POST',
                $data,
                $headers
            );

            $response = $curl_response['content'];

            if (strpos($response, '/e-hotel/previsualizacionPdf') !== false) {

                if ($options['file_path']) $this->downloadPdf($options['file_path']);
            } else {
                throw new \Exception('error.pdf.link');
            }

            return array(
                'file' => $options['file_path']
            );

        } catch (\Exception $e) {
            return $e->getMessage();
        }
    }

    public function signaturePDF($file, $output, $signature = null)
    {
        try {

            $pdf = new Fpdi();
            $pdf->AddPage();
            $pdf->setSourceFile($file);
            $template = $pdf->importPage(1);
            $size = $pdf->getTemplateSize($template);
            $pdf->useTemplate($template, null, null, $size['w'], $size['h'], true);
            $pdf->Image($signature, 77, 123, 50, 30);
            $pdf->Output($output, "F");
            return $output;
        } catch (\Exception $e) {
            return $e->getMessage();
        }
    }

}
