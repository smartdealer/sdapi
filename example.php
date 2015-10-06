<?php

include 'sdapi.class.php';

$sdl = 'http://localhost/sdsweb/webservice/rest';

$usr = 'teste';
$pwd = '56e893aebac5c5e549997b9a61ccf3f76bfe2580';

$api = new Smart\Api($sdl, $usr, $pwd);

$response = $api->get('/connect/pack/27');

$sts = $api->getError();

if ($sts)
    die($sts);

echo "<pre>";
var_dump($response);