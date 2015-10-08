<?php

// incluse API class
include 'source/sdapi.class.php';

// auth settings
$sdl = 'http://localhost/sdsweb/webservice/rest';
$usr = 'teste';
$pwd = '56e893aebac5c5e549997b9a61ccf3f76bfe2580';

# OR

$sdl = 'prima';
$usr = 'primafiat';
$pwd = '80f2be7cc42dc32000730a35079226da65e3142f';

// instace SD Api
$api = new Smart\Api($sdl, $usr, $pwd, array('debug' => false, 'output_format' => 1));

// call Restful route
$response = $api->get('/connect/packs/');

// collect erros
$sts = $api->getError();

// if error, show and stop
if ($sts) {
    var_dump($sts);
    die;
}

// debug response
echo "<pre>";
var_dump($response);
