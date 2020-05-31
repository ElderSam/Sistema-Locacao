<?php

use \Locacao\Page;
use \Locacao\Controller\ContractController;
use \Locacao\Model\Contract;
use \Locacao\Model\User;


/* rota para página de orcamentos --------------*/

$app->get('/budgets', function(){

    User::verifyLogin();
	
	$page = new Page();
	$page->setTpl("orcamentos");
  
});

$app->get('/budgets/new', function(){

    User::verifyLogin();
	
	$page = new Page();
	$page->setTpl("orcamento_salvar", [
		"idOrcamento"=>'0'
	]);
  
});


$app->get('/budgets/json', function(){

    User::verifyLogin();

    echo Contract::listAll();
  
});


$app->get("/budgets/:id/pdf/show", function($id){

	User::verifyLogin();
	
	echo ContractController::getPDF($id);

});


$app->get('/budgets/:id', function($id){

	User::verifyLogin();

	$page = new Page();
	$page->setTpl("orcamento_salvar", [
        "idOrcamento"=>$id
    ]); 
  

});

/* rota que mostra o próximo código de contrato */
$app->post('/budgets/showsNextNumber', function(){

    User::verifyLogin();

    echo Contract::showsNextNumber();
  
});

$app->post('/budgets/list_datatables', function(){ //ajax list datatables

	User::verifyLogin();
	
	//Receber a requisão da pesquisa 
	$requestData = $_REQUEST;

	$budgets = new ContractController();
	echo $budgets->ajax_list_budgets($requestData);
	
});


/* rota para deletar contrato --------------------------*/
$app->post("/budgets/:idbudget/delete", function($idbudget){
    
    User::verifyLogin();

    $contract = new Contract();

	$contract->get((int)$idbudget); //carrega o contrato, para ter certeza que ainda existe no banco

	echo $contract->delete();

});

$app->get("/budgets/json/:idbudget", function($idbudget){
	
    User::verifyLogin();

	$contract = new Contract();

	$contract->get((int)$idbudget);

	echo json_encode($contract->getValues());

});

/* rota para criar contrato (salva no banco) -----------*/
$app->post("/budgets/create", function(){
	
	$contract = new ContractController();
	echo $contract->save();

});


/* rota para atualizar contrato --------------------------*/
$app->post("/budgets/:idbudget", function($idbudget){ //update
	
    User::verifyLogin();

	$contract = new ContractController();

	$update = true;

	echo $contract->save($update);
	
});