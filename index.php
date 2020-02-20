<?php

require('vendor/autoload.php');

//nasmespace
use \Slim\Slim;
use \Locacao\Page;

//configura o modo Debug para explicar cada erro 
$app->config('debug', true);


$app = new Slim();

$app->get('/', function(){

});

//executa
$app->run();

?>