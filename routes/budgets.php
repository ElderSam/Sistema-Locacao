<?php

use \Locacao\Page;
use \Locacao\Controller\BudgetController;
use Locacao\Controller\ContractController;
use \Locacao\Model\Budget;
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

    echo Budget::listAll();
  
});


$app->get("/budgets/:id/pdf/show", function($id){ //o destino pode ser a visualização do PDF (/show) ou enviar por e-mail (/sendEmail)
	
	User::verifyLogin();

	$BudgetController = new BudgetController();
	echo $BudgetController->getPDF($id, 'show');

});


$app->post("/budgets/:id/pdf/sendEmail", function($id){ //o destino pode ser a visualização do PDF (/show) ou enviar por e-mail (/sendEmail)
	
	User::verifyLogin();

	$BudgetController = new BudgetController();
	echo $BudgetController->getPDF($id, 'sendEmail');

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

    echo Budget::showsNextNumber();
  
});

$app->post('/budgets/list_datatables', function(){ //ajax list datatables

	User::verifyLogin();
	
	//Receber a requisão da pesquisa 
	$requestData = $_REQUEST;

	$budgets = new BudgetController();
	echo $budgets->ajax_list_budgets($requestData);
	
});


/* rota para deletar contrato --------------------------*/
$app->post("/budgets/:idbudget/delete", function($idbudget){
    
    User::verifyLogin();

    $budget = new Budget();

	$budget->get((int)$idbudget); //carrega o contrato, para ter certeza que ainda existe no banco

	echo $budget->delete();

});

$app->get("/budgets/json/:idbudget", function($idbudget){
	
    User::verifyLogin();

	$budget = new Budget();

	$budget->get((int)$idbudget);

	echo json_encode($budget->getValues());

});

/* rota para criar contrato (salva no banco) -----------*/
$app->post("/budgets/create", function(){
	
	$budget = new BudgetController();
	echo $budget->save();

});


/* rota para atualizar contrato --------------------------*/
$app->post("/budgets/:idbudget", function($idbudget){ //update
	
	
    User::verifyLogin();


	$budget = new BudgetController();
	
	$update = true;

	echo $budget->save($update);
	
});