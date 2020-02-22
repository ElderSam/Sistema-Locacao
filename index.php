<?php

require_once("vendor/autoload.php");

//nasmespace
use \Slim\Slim;
use \Locacao\Page;

//cria a aplicação Slim
$app = new Slim();

//configura o modo Debug para explicar cada erro 
$app->config('debug', true);

$app->get('/', function(){

    $page = new Page();

    $page->setTpl("index");

});

$app->get('/produtos', function(){

    $page = new Page();

    $page->setTpl("produtos");

});


$app->get('/clientes', function(){

    $page = new Page();

    $page->setTpl("clientes");
    $page->setTpl("modais");

});


$app->get('/obras', function(){

    echo "Essa página ainda não foi criada!";
});


//executa
$app->run();

?>