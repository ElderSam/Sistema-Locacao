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

require_once("./routes/reposibleWorks.php");
require_once("./routes/users.php");
require_once("./routes/costumers.php"); //Clientes
require_once("./routes/products.php"); //produtos genéricos
require_once("./routes/products_esp.php"); //produtos específicos
require_once("./routes/prod-types.php");
require_once("./routes/suppliers.php"); //fornecedores
require_once("./routes/budgets.php"); //orçamentos
require_once("./routes/budgetItens.php"); //itens de orçamento (produtos adicionados)
require_once("./routes/contracts.php"); //contratos
require_once("./routes/contract_itens.php"); //itens de contrato
require_once("./routes/rents.php"); //alugueis
require_once("./routes/freights.php"); //fretes (entregas e retiradas)
require_once("./routes/construction.php");
require_once("./routes/invoices.php"); //faturas
require_once("./routes/invoice_itens.php"); //faturas

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

$app->get("/accessDenied", function() {
    $page = new Page();
    $page->setTpl("accessDenied");
});

//executa
$app->run();

?>