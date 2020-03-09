<?php

use \Locacao\Page;
use \Locacao\Controller\ProductController;
use \Locacao\Model\User;
use \Locacao\Model\Product;

/* rota para página de produtos --------------*/
$app->get('/products', function(){

    User::verifyLogin();

    $page = new Page();
    $page->setTpl("produtos");

});

$app->get('/products/json', function(){

    User::verifyLogin();

    echo Product::listAll();
  
});


$app->post('/products/list_datatables', function(){ //ajax list datatables

	User::verifyLogin();
	
	//Receber a requisão da pesquisa 
	$requestData = $_REQUEST;

	$products = new ProductController();
	echo $products->ajax_list_products($requestData);
	
});

/* rota para deletar usuário --------------------------*/
$app->post("/products/:idproduct/delete", function($idproduct){
    
    User::verifyLogin();

    $product = new Product();

	$product->get((int)$idproduct); //carrega o usuário, para ter certeza que ainda existe no banco

	echo $product->delete();

});

$app->get("/products/json/:idproduct", function($idproduct){
	
    User::verifyLogin();

	$product = new Product();

	$product->get((int)$idproduct);

	echo json_encode($product->getValues());

});

/* rota para criar usuário (salva no banco) -----------*/
$app->post("/products/create", function(){

	$product = new ProductController();
	echo $product->save();

});


/* rota para atualizar usuário --------------------------*/
$app->post("/products/:idproduct", function($idproduct){ //update
	
    User::verifyLogin();

	$product = new ProductController();

	$update = true;

	echo $product->save($update);
	
});

