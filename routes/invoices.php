<?php

use \Locacao\Page;
use \Locacao\Model\User;
use \Locacao\Controller\InvoiceController;
use \Locacao\Model\Invoice;


/* rota para página de faturas --------------*/
$app->get('/invoices', function(){

    User::verifyLogin();
	
	$page = new Page();
	$page->setTpl("faturas");
});

/* rota para página de fatura_salvar --------------*/
$app->get('/invoices/new', function(){

    User::verifyLogin();
	
	$page = new Page();
	$page->setTpl("fatura_salvar", [
		"idFatura"=>'0'
	]);
  
});

$app->post('/invoices/list_datatables', function(){ //ajax list datatables

	User::verifyLogin();
	
	//Receber a requisão da pesquisa 
	$requestData = $_REQUEST;

	$invoices = new InvoiceController();
	echo $invoices->ajax_list_contracts($requestData);
	
});


// /* rota para página de faturas --------------*/
// $app->get('/invoices', function(){
//     //User::verifyLogin();
//     // echo "rota faturas";
//     $faturaController = new InvoiceController();
//     echo $faturaController->getFaturasParaFazer();
// });

