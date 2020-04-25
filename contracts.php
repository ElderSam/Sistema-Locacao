<?php

use \Locacao\Page;
use \Locacao\Controller\ContractController;
use \Locacao\Model\Contract;
use \Locacao\Model\User;

/* rota para página de contratos --------------*/

$app->get('/contracts', function(){

    User::verifyLogin();
	
	$page = new Page();
	$page->setTpl("contratos");
  
});

$app->get('/contracts/json', function(){

    User::verifyLogin();

    echo Contract::listAll();
  
});


/* rota que mostra o próximo código de contrato */
$app->post('/contracts/showsNextNumber', function(){

    User::verifyLogin();

    echo Contract::showsNextNumber();
  
});

$app->post('/contracts/list_datatables', function(){ //ajax list datatables

	User::verifyLogin();
	
	//Receber a requisão da pesquisa 
	$requestData = $_REQUEST;

	$contracts = new ContractController();
	echo $contracts->ajax_list_contracts($requestData);
	
});


/* rota para deletar contrato --------------------------*/
$app->post("/contracts/:idcontract/delete", function($idcontract){
    
    User::verifyLogin();

    $contract = new Contract();

	$contract->get((int)$idcontract); //carrega o contrato, para ter certeza que ainda existe no banco

	echo $contract->delete();

});

$app->get("/contracts/json/:idcontract", function($idcontract){
	
    User::verifyLogin();

	$contract = new Contract();

	$contract->get((int)$idcontract);

	echo json_encode($contract->getValues());

});


/* rota para atualizar contrato --------------------------*/
$app->post("/contracts/:idcontract", function($idcontract){ //update
	
    User::verifyLogin();

	$contract = new ContractController();

	$update = true;

	echo $contract->save($update);
	
});
