<?php

/**
 * Smart Dealer RESTful Client API
 *
 * @package   Smart Dealership
 * @author    Patrick Otto <patrick@smartdealership.com.br>
 * @version   1.0
 * @access    public
 * @copyright Smart Dealer(c), 2015
 * @see       http://www.smartdealer.com.br
 *
 * @param  string $sdl the client instance name ou URL of werbservice ex: dealership or http://domain.com/rest/
 * @param  string $usr REST username (for WWW-authentication)
 * @param  string $pwd REST password
 * @param  array $opt the API client options (not required)
 */

namespace Smart;

class Api {

    private $sdl, $usr, $pwd, $error = array();
    var $settings = array(
        'handle' => 'curl',
        'timeout' => 10,
        'use_ssl' => false,
        'port' => 80,
        'debug' => false
    );
    var $methods = array(
        '/config/affiliates/' => array(
            'method' => 'get',
            'desc' => 'return branches listing (affiliates)'
        ),
        '/parts/' => array(
            'method' => 'get',
            'desc' => 'returns a parts list'
        ),
        '/parts/provider/' => array(
            'method' => 'get',
            'desc' => 'returns to manufacturer list (providers)'
        ),
        '/parts/order/' => array(
            'method' => 'post',
            'desc' => 'create or update parts orders',
        ),
        '/parts/notify/' => array(
            'method' => 'post',
            'desc' => 'create or update pending the parts inventory (alerts)',
        ),
        '/parts/order/:id' => array(
            'method' => 'delete',
            'desc' => 'delete part orders',
        ),
    );

    const WS_PATH = '.smartdealer.com.br/webservice/rest/';
    const WS_DF_TIMEOUT = 10;
    const WS_DF_PORT = 80;
    const WS_SIGNATURE = '7cac394e6e2864b8e2f98e7fe815ab6b';

    public function __construct($sdl, $usr, $pwd, Array $opt = array()) {

        $default = array(
            'options' => array('default' => $this->protocol() . $sdl . self::WS_PATH
            )
        );

        $this->sdl = trim(filter_var($sdl, FILTER_VALIDATE_URL, $default), ' /');
        $this->usr = filter_var($usr, FILTER_SANITIZE_STRING | FILTER_SANITIZE_SPECIAL_CHARS);
        $this->pwd = filter_var($pwd, FILTER_SANITIZE_STRING);

        $this->settings($opt);
    }

    public function get($rest, $arg = array()) {

        if (!in_array($rest, array_keys($this->methods)))
            $this->logError('The ' . $rest . ' method is invalid. Get $api->methods() to list available.');

        return ($this->getError()) ? array() : $this->call($rest, $arg);
    }

    public function post($rest, $arg = array()) {

        $a = '';

        // check server
        if (!$this->validWs()) {

            $this->logError('The URL of Rest Webservice is not valid or server not permitted this request!');

            return $this->output();
        }

        $time = (!empty($this->settings['timeout'])) ? (int) $this->settings['timeout'] : self::WS_DF_TIMEOUT;
        $port = (!empty($this->settings['port'])) ? (int) $this->settings['port'] : self::WS_DF_PORT;
        $auth = base64_encode($this->usr . ":" . $this->pwd);

        if (!empty($this->settings['handle'])) {
            switch ($this->settings['handle']) {
                case 'curl' :

                    // curl request 
                    $cr = curl_init($this->sdl . $rest);

                    curl_setopt($cr, CURLOPT_HTTPAUTH, CURLAUTH_BASIC);
                    curl_setopt($cr, CURLOPT_TIMEOUT, $time);
                    curl_setopt($cr, CURLOPT_USERPWD, $this->usr . ":" . $this->pwd);
                    curl_setopt($cr, CURLOPT_RETURNTRANSFER, true);
                    curl_setopt($cr, CURLOPT_HTTPAUTH, CURLAUTH_ANY);
                    curl_setopt($cr, CURLOPT_SSL_VERIFYPEER, !empty($this->settings['use_sll']));
                    curl_setopt($cr, CURLOPT_POST, true);
                    curl_setopt($cr, CURLOPT_POSTFIELDS, $arg);

                    // exec
                    $a = curl_exec($cr);

                    // close
                    curl_close($cr);

                    break;
                case 'socket' : // @testing: migrated all handlers
                    // build query
                    $arg = http_build_query($arg);

                    $header = "POST / HTTP/1.0\r\n\r\n";
                    $header.= "Accept: text/html\r\n";
                    $header.= "Authorization: Basic $auth\r\n\r\n";
                    $header.= "Content-Type: application/x-www-form-urlencoded\r\n\r\n";
                    $header.= "Content-Length: " . strlen($arg) . "\r\n\r\n";
                    $header.= $arg . "\r\n\r\n";

                    $host = preg_replace('/^\w+\:\/\//', '', $this->sdl . $rest);
                    $fp = fsockopen($host, $port, $errno, $errstr, $time);

                    $a = '';

                    if (!$fp)
                        $this->logError($errstr);
                    else {
                        fwrite($fp, $header);
                        while (!feof($fp))
                            echo fgets($fp, 128);
                        fclose($fp);
                    }

                    var_dump($a, trim($host, '/'));
                    die;

                    break;
            }
        } else {
            $this->logError('required \'handle\' param on settings');
        }

        return $this->output($a);
    }

    public function delete($id) {

        $a = '';

        // check server
        if (!$this->validWs()) {

            $this->logError('The URL of Rest Webservice is not valid or server not permitted this request!');

            return $this->output();
        }

        $time = (!empty($this->settings['timeout'])) ? (int) $this->settings['timeout'] : self::WS_DF_TIMEOUT;
        $port = (!empty($this->settings['port'])) ? (int) $this->settings['port'] : self::WS_DF_PORT;
        $auth = base64_encode($this->usr . ":" . $this->pwd);

        if (!empty($this->settings['handle'])) {
            switch ($this->settings['handle']) {
                case 'curl' :

                    // curl request 
                    $cr = curl_init($this->sdl . $id);

                    curl_setopt($cr, CURLOPT_HTTPAUTH, CURLAUTH_BASIC);
                    curl_setopt($cr, CURLOPT_TIMEOUT, $time);
                    curl_setopt($cr, CURLOPT_USERPWD, $this->usr . ":" . $this->pwd);
                    curl_setopt($cr, CURLOPT_RETURNTRANSFER, true);
                    curl_setopt($cr, CURLOPT_HTTPAUTH, CURLAUTH_ANY);
                    curl_setopt($cr, CURLOPT_SSL_VERIFYPEER, !empty($this->settings['use_sll']));
                    curl_setopt($cr, CURLOPT_CUSTOMREQUEST, 'DELETE');

                    // exec
                    $a = curl_exec($cr);

                    // close
                    curl_close($cr);

                    break;
            }
        } else {
            $this->logError('required \'handle\' param on settings');
        }

        return $this->output($a);
    }

    private function settings($opt) {
        $this->settings = array_merge($this->settings, array_intersect_key($opt, $this->settings));
    }

    public function methods() {
        return array_filter($this->methods);
    }

    // @MIGRATE
    public function call($rest, $arg) {

        // check server
        if (!$this->validWs()) {

            $this->logError('The URL of Rest Webservice is not valid or server not permitted this request!');

            return $this->output();
        }

        $time = (!empty($this->settings['timeout'])) ? (int) $this->settings['timeout'] : self::WS_DF_TIMEOUT;
        $port = (!empty($this->settings['port'])) ? (int) $this->settings['port'] : self::WS_DF_PORT;
        $auth = base64_encode($this->usr . ":" . $this->pwd);

        if (!empty($this->settings['handle'])) {
            switch ($this->settings['handle']) {
                case 'curl' :

                    // curl request 
                    $cr = curl_init($this->sdl . $rest);

                    curl_setopt($cr, CURLOPT_HTTPAUTH, CURLAUTH_BASIC);
                    curl_setopt($cr, CURLOPT_TIMEOUT, $time);
                    curl_setopt($cr, CURLOPT_USERPWD, $this->usr . ":" . $this->pwd);
                    curl_setopt($cr, CURLOPT_RETURNTRANSFER, true);
                    curl_setopt($cr, CURLOPT_HTTPAUTH, CURLAUTH_ANY);
                    curl_setopt($cr, CURLOPT_SSL_VERIFYPEER, !empty($this->settings['use_sll']));

                    // exec
                    $a = curl_exec($cr);

                    // close
                    curl_close($cr);

                    break;
                case 'socket' :

                    $header = "GET / HTTP/1.0\r\n\r\n";
                    $header.= "Accept: text/html\r\n";
                    $header.= "Authorization: Basic $auth\r\n\r\n";

                    $host = preg_replace('/^\w+\:\/\//', '', $this->sdl . $rest);

                    $fp = fsockopen($host, $port, $errno, $errstr, $time);

                    if (!$fp)
                        $this->logError($errstr);
                    else {
                        fputs($fp, $header);
                        while (!feof($fp))
                            echo fgets($fp, 128);
                    }
                    fclose($fp);

                    break;
                case 'stream' :

                    // stream settings
                    $opts = array(
                        'http' => array(
                            'method' => "GET",
                            'header' => "Accept-language: en\r\nContent-type: application/json\r\nAuthorization: Basic $auth",
                        )
                    );

                    // create stream
                    $context = stream_context_create($opts);

                    set_time_limit();

                    // get URL
                    $a = file_get_contents($this->sdl . $rest, false, $context);

                    break;
                default:
                    $this->logError('invalid \'handle\' (use curl, socket, stream)');
            }
        } else {
            $this->logError('required \'handle\' param on settings');
        }

        return $this->output($a);
    }

    private function protocol() {
        return 'http' . ((!empty($this->settings['use_sll'])) ? 's' : '') . '://';
    }

    private function logError($str) {
        $this->error[] = (string) $str;
    }

    public function getError() {
        return $this->error;
    }

    private function output($a = array()) {
        return ($a && ($b = json_decode($a)) && json_last_error() == JSON_ERROR_NONE) ? $b : array();
    }

    private function validWs() {

        // pingback
        ob_start();
        $a = @get_headers($this->sdl);
        $b = ob_get_contents();        
        ob_end_clean();
        
        $sign = (is_array($a)) ? (array) explode(':', current((array) preg_grep('/Server-Signature/i', $a))) : array();

        // send status
        return key_exists(0, $sign) && !strstr($a[0], '404') && trim(end($sign)) === self::WS_SIGNATURE;
    }

}