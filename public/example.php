<?php

// incluse API class
include '../src/smart/api.php';

// auth settings
$sdl = 'http://localhost/sdsweb/webservice/rest';
$usr = 'teste';
$pwd = '56e893aebac5c5e549997b9a61ccf3f76bfe2580';

# OR

$sdl = 'grupotoniello';
$usr = 'click';
$pwd = '58c927d49befcccfdbdc03a09a903b073f3cf092';

// instace SD Api
$api = new Smart\Api($sdl, $usr, $pwd, array('debug' => false, 'output_format' => 1));

// call Restful route
$response = $api->get('/connect/offers/');

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
