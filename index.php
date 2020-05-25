<?php

require_once("vendor/autoload.php");

//nasmespace
use \Slim\Slim;
use \Locacao\Page;
use\Locacao\Model\Costumer;
use \Locacao\Model\User;

session_start();

//cria a aplicação Slim
$app = new Slim();


//configura o modo Debug para explicar cada erro 
$app->config('debug', true);

require_once("reposibleWorks.php");
require_once("users.php");
require_once("costumers.php"); //Clientes
require_once("products.php"); //produtos genéricos
require_once("products_esp.php"); //produtos específicos
require_once("prod-types.php");
require_once("suppliers.php"); //fornecedores
require_once("budgets.php"); //orçamentos
require_once("budgetItens.php"); //itens de orçamento (produtos adicionados)
require_once("contracts.php"); //contratos
require_once("contract_itens.php"); //itens de contrato
require_once("rents.php"); //alugueis

$app->get('/', function(){

    User::verifyLogin();

    $page = new Page();

    $page->setTpl("index");

});

/* rota para página de Clientes --------------*/
$app->get('/costumers', function(){
	
    User::verifyLogin();
	
	$page = new Page();
	$page->setTpl("clientes");

});


$app->get('/products', function(){

    User::verifyLogin();

    $page = new Page();

    $page->setTpl("produtos");

});


$app->get('/products/containers', function(){

    User::verifyLogin();

    $page = new Page();

    $page->setTpl("produtos-containers");

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

    
    echo User::login($_POST["login"], $_POST["password"]); //autentifica usuário

    //header("Location: /"); //vai para página inicial
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