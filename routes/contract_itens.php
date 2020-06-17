<?php

use \Locacao\Page;
use \Locacao\Controller\ContractItemController;
use \Locacao\Model\User;
use \Locacao\Model\ContractItem;

/* rota para página de ... --------------*/
/*$app->get('/contract_itens', function(){

    User::verifyLogin();

    $page = new Page();
    $page->setTpl("...");

});*/

$app->get('/contract_itens/json', function(){

    User::verifyLogin();

    echo ContractItem::listAll();
  
});


$app->post('/contract_itens/list_datatables/:idContrato', function($idContrato){ //ajax list datatables


	User::verifyLogin();
	
	//Receber a requisão da pesquisa 
	$requestData = $_REQUEST;

	$contract_itens = new ContractItemController();
	echo $contract_itens->ajax_list_contractItens($requestData, $idContrato);
	
});

/* rota para deletar item de aluguel --------------------------*/
$app->post("/contract_itens/:idItem/delete", function($idItem){
    
    User::verifyLogin();

    $contractItem = new ContractItemController();

    echo $contractItem->delete($idItem);

});

$app->get("/contract_itens/json/:idItem", function($idItem){
	
    User::verifyLogin();

	$contractItem = new ContractItem();

	$contractItem->get((int)$idItem);

	echo $contractItem->loadContractItem((int)$idItem);

});
/*
$app->get("/contract_itens/addToContract/:codContractItem", function($codContractItem){
	
    User::verifyLogin();

	$contractItem = new ContractItem();

	echo $contractItem->getByCode($codContractItem);

});*/

/* rota para criar item de aluguel (salva no banco) -----------*/
$app->post("/contract_itens/create", function(){
    
	$contractItem = new ContractItemController();
	echo $contractItem->save();

});


/* rota para atualizar  --------------------------*/
$app->post("/contract_itens/:idItem", function($idItem){ //update
	
    User::verifyLogin();

	$contractItem = new ContractItemController();

	$update = true;

	echo $contractItem->save($update);
	
});

/*
$app->get('/contract_itens/categories/json', function(){

    User::verifyLogin();

    echo Category::listAll();
  
});


$app->get('/contract_itens/types/json/:idCategory', function($idCategory){

    User::verifyLogin();

    echo Category::listTypes($idCategory);
  
});


$app->post('/contract_itens/showsNextNumber/:idCategory', function($idCategory){

    User::verifyLogin();

    echo ContractItem::showsNextNumber($idCategory);
  
});*/