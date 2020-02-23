<?php

require_once("vendor/autoload.php");

//nasmespace
use \Slim\Slim;
use \Locacao\Page;
use \Locacao\Model\User;

session_start();

//cria a aplicação Slim
$app = new Slim();

//require_once("users.php");

//configura o modo Debug para explicar cada erro 
$app->config('debug', false);

$app->get('/', function(){

    User::verifyLogin();

    $page = new Page();

    $page->setTpl("index");

});

$app->get('/produtos', function(){

    User::verifyLogin();

    $page = new Page();

    $page->setTpl("produtos");

});


$app->get('/clientes', function(){

    User::verifyLogin();

    $page = new Page();

    $page->setTpl("clientes");
    $page->setTpl("modais");

});


$app->get('/obras', function(){

    User::verifyLogin();

    echo "Essa página ainda não foi criada!";
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