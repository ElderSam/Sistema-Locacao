<?php

use \Locacao\Page;
use \Locacao\Controller\ProductEspController;
use Locacao\Model\Category;
use \Locacao\Model\User;
use \Locacao\Model\ProductEsp;

/* ------------ ROTAS DE PRODUTOS ESPECÍFICOS --------------- */

/* rota para página de produtos --------------*/
$app->get('/products_esp', function(){

    User::verifyLogin();

    $page = new Page();
    $page->setTpl("produtos_esp");

});

/* rota para página de lista produtos específicos de um tipo já cadastrado --------------*/
$app->get('/products_esp/:idproduct', function($idproduct){

    User::verifyLogin();

    $page = new Page();
    $page->setTpl("produtos_esp", [
        "idProduct_gen"=>$idproduct
    ]);

});


$app->get('/products_esp/json', function(){

    User::verifyLogin();

    echo ProductEsp::listAll();
  
});


$app->post('/products_esp/list_datatables/:idProduct_gen', function($idProduct_gen){ //ajax list datatables

	User::verifyLogin();
	
	//Receber a requisão da pesquisa 
	$requestData = $_REQUEST;

	$products = new ProductEspController();
	echo $products->ajax_list_products($requestData, $idProduct_gen);
	
});

/* rota para deletar usuário --------------------------*/
$app->post("/products_esp/:idproduct/delete", function($idproduct){
    
    User::verifyLogin();

    $product = new ProductEspController();

    echo $product->delete($idproduct);

});

$app->get("/products_esp/json/:idproduct", function($idproduct){
	
    User::verifyLogin();

	$product = new ProductEsp();

	$product->get((int)$idproduct);

	echo $product->loadProduct((int)$idproduct);

});

$app->get("/products_esp/addToContract/:codeProd", function($codeProd){
	
    User::verifyLogin();

	$product = new ProductEsp();

	echo $product->getByCode($codeProd);

});

/* rota para criar usuário (salva no banco) -----------*/
$app->post("/products_esp/create", function(){

	$product = new ProductEspController();
	echo $product->save();

});


/* rota para atualizar usuário --------------------------*/
$app->post("/products_esp/:idproduct", function($idproduct){ //update
	
    User::verifyLogin();

	$product = new ProductEspController();

	$update = true;

	echo $product->save($update);
	
});


$app->get('/products_esp/categories/json', function(){

    User::verifyLogin();

    echo Category::listAll();
  
});


$app->get('/products_esp/types/json/:idCategory', function($idCategory){

    User::verifyLogin();

    echo Category::listTypes($idCategory);
  
});


$app->post('/products_esp/showsNextNumber/:idCategory', function($idCategory){

    User::verifyLogin();

    echo ProductEsp::showsNextNumber($idCategory);
  
});