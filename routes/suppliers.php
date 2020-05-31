<?php


//require_once('./Controllers/UsersController.php');


use \Locacao\Page;
use \Locacao\Controller\SupplierController;
use \Locacao\Model\Supplier;
use \Locacao\Model\User;

/* rota para página de fornecedores --------------*/

$app->get('/suppliers', function(){

    User::verifyLogin();
	
    //$suppliers = User::listAll();
	//$suppliers = json_decode($suppliers, false);
	
	$page = new Page();
	$page->setTpl("fornecedores");

    /*$page->setTpl("fornecedores", array(
        "suppliers"=>$suppliers
    ));*/
  
});



$app->get('/suppliers/json', function(){

    User::verifyLogin();

    echo Supplier::listAll();
  
});


/* rota que mostra o próximo código de fornecedor */
$app->post('/suppliers/showsNextNumber', function(){

    User::verifyLogin();

    echo Supplier::showsNextNumber();
  
});

$app->post('/suppliers/list_datatables', function(){ //ajax list datatables

	User::verifyLogin();
	
	//Receber a requisão da pesquisa 
	$requestData = $_REQUEST;

	$suppliers = new SupplierController();
	echo $suppliers->ajax_list_suppliers($requestData);
	
});


/* rota para deletar fornecedor --------------------------*/
$app->post("/suppliers/:idsupplier/delete", function($idsupplier){
    
    User::verifyLogin();

    $supplier = new Supplier();

	$supplier->get((int)$idsupplier); //carrega o fornecedor, para ter certeza que ainda existe no banco

	echo $supplier->delete();

});

$app->get("/suppliers/json/:idsupplier", function($idsupplier){
	
    User::verifyLogin();

	$supplier = new Supplier();

	$supplier->get((int)$idsupplier);

	echo json_encode($supplier->getValues());

});

/* rota para criar fornecedor (salva no banco) -----------*/
$app->post("/suppliers/create", function(){

	$supplier = new SupplierController();
	echo $supplier->save();

});


/* rota para atualizar fornecedor --------------------------*/
$app->post("/suppliers/:idsupplier", function($idsupplier){ //update
	
    User::verifyLogin();

	$supplier = new SupplierController();

	$update = true;

	echo $supplier->save($update);
	
});

