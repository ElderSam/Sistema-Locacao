<?php
/* Rotas de itens de fatura */

use \Locacao\Page;
use \Locacao\Controller\InvoiceItemController;
use \Locacao\Model\User;
use \Locacao\Model\InvoiceItem;

$app->get('/invoice_itens/json', function(){

    User::verifyLogin();

    echo InvoiceItem::listAll();
});

$app->post('/invoice_itens/list_datatables/:idInvoice', function($idInvoice){ //ajax list datatables

	User::verifyLogin();

	$requestData = $_REQUEST; //Receber a requisão da pesquisa 
	$invoice_itens = new InvoiceItemController();
	echo $invoice_itens->ajax_list_invoiceItens($requestData, $idInvoice);
});

/* rota para deletar item de fatura --------------------------*/
$app->post("/invoice_itens/:idItem/delete", function($idItem){
    
    User::verifyLogin();

    $invoiceItem = new InvoiceItemController();
    echo $invoiceItem->delete($idItem);
});

/* rota para mostrar dados de um item de fatura pelo id */
$app->get("/invoice_itens/json/:idItem", function($idItem){
	
    User::verifyLogin();

	$invoiceItem = new InvoiceItem();
	//$invoiceItem->get((int)$idItem);
	echo $invoiceItem->getInvoiceItens((int)$idItem);
});

/* rota para mostrar dados de uma fatura pelo id */
$app->get("/invoice_itens/json/invoice/:idInvoice", function($idInvoice){
	
    User::verifyLogin();

    $invoiceItem = new InvoiceItemController();
	echo $invoiceItem->getInvoiceItens((int)$idInvoice);
});

/* rota para criar item de fatura (salva no banco) -----------*/
/*$app->post("/invoice_itens/create", function(){
    
	$invoiceItem = new InvoiceItemController();
	echo $invoiceItem->save();
});*/

/* rota para atualizar  --------------------------*/
$app->post("/invoice_itens/:idItem", function($idItem){ //update
	
    User::verifyLogin();

	$invoiceItem = new InvoiceItemController();
	$update = true;
	$item = $_POST;
	echo $invoiceItem->save($item, $update);
});