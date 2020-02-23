<?php

require_once("vendor/autoload.php");

//nasmespace
use \Slim\Slim;
use \Locacao\Page;
use \Locacao\Model\User;

session_start();

//cria a aplicação Slim
$app = new Slim();



//configura o modo Debug para explicar cada erro 
$app->config('debug', false);

require_once("users.php");

$app->get('/', function(){

    User::verifyLogin();

    $page = new Page();

    $page->setTpl("index");

});

$app->get('/products', function(){

    User::verifyLogin();

    $page = new Page();

    $page->setTpl("produtos");

});


$app->get('/costumers', function(){

    User::verifyLogin();

    $page = new Page();

    $page->setTpl("clientes");
  

});


$app->get('/users', function(){

    User::verifyLogin();

    $page = new Page();

    $page->setTpl("usuarios");
  

});

$app->get('/index', function(){

    User::verifyLogin();

    $page = new Page();

    $page->setTpl("index");
  

});


$app->get('/construction', function(){

    User::verifyLogin();

    $page = new Page();

    $page->setTpl("obras");
  

});

/* rota página login ---------------------------------- */
$app->get('/login', function(){
    
    $page = new Page([
        "header"=>false,
        "footer"=>false
    ]);

    $page->setTpl("login");


});

/* rota validar login ---------------------------------- */
$app->post('/login', function(){

    
    User::login($_POST["login"], $_POST["password"]); //autentifica usuário

    header("Location: /"); //vai para página inicial
    exit;
});

$app->get("/logout", function(){

    User::logout();

	header("Location: /login");
	exit;

});

//executa
$app->run();

?>