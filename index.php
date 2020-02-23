<?php

require_once("vendor/autoload.php");

//nasmespace
use \Slim\Slim;
use \Locacao\Page;

//cria a aplicação Slim
$app = new Slim();

//configura o modo Debug para explicar cada erro 
$app->config('debug', false);

$app->get('/', function(){

    //echo "Hello World!";
    $page = new Page();

    $page->setTpl("index");

});

//executa
$app->run();

?>