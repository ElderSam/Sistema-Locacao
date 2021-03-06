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

$app->get('/contracts/:id', function($id){

	User::verifyLogin();

	$contract = new Contract();
	$contract->get((int)$id);
	$array = $contract->getValues();
	//print_r($array);
	$contract->setData($array);
	
	$page = new Page();
	$page->setTpl("contrato_salvar", [
		"idContrato"=>$id,
		"codigo"=>$contract->getcodContrato(),
		"idCliente"=>$contract->getidCliente(),
		"obra_idObra"=>$contract->getobra_idObra(),
		"status"=>$contract->getstatusOrcamento(),
		"solicitante"=>$contract->getsolicitante(),
		"telefone"=>$contract->gettelefone(),
		"email"=>$contract->getemail(),
		"dtEmissao"=>$contract->getdtEmissao(),
		"dtAprovacao"=>$contract->getdtAprovacao(),
		"dtInicio"=>$contract->getdtInicio(),
		"dtFim"=>$contract->getdtFim(),

		"temMedicao"=>$contract->gettemMedicao(),
		"regraFatura"=>$contract->getregraFatura(),
		"semanaDoMes"=>$contract->getsemanaDoMes(),
		"diaFatura"=>$contract->getdiaFatura(),
		
		"notas"=>$contract->getnotas(),	
    ]);

});

/* rota que mostra o próximo código de contrato */
$app->get('/contracts/showsNextCode/:dtEmissao', function($dtEmissao){

	User::verifyLogin();
	

    echo Contract::showsNextCode($dtEmissao); 
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


$app->get("/contracts/:id/pdf/show", function($id){ //o destino pode ser a visualização do PDF (/show) ou enviar por e-mail (/sendEmail)
	
	User::verifyLogin();

	$contractController = new ContractController();
	echo $contractController->getPDF($id, 'show');

});


$app->get("/contracts/json/costumer/:idCliente", function($idCliente){
	
    User::verifyLogin();

	$contract = new Contract();

	echo $contract->getContractsCostumer((int)$idCliente);

});


$app->get("/company/json/", function(){
	
    User::verifyLogin();

	$contract = new Contract();

	echo $contract->getValuesToCompanyPDF();

});