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

    $sql = new Locacao\DB\Sql();

    $results = $sql->select("SELECT * FROM usuario");

    echo json_encode($results);
    //echo "Hello World!";
    /*$page = new Page();

    $page->setTpl("index");*/

});


$app->get('/clientes', function(){

    echo "help";
    //$page = new Page();

    //$page->setTpl("clientes");

});


//executa
$app->run();

?>