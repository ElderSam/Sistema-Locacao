<?php

use \Locacao\Page;
use \Locacao\Model\User;
use \Locacao\Controller\FreightController;
use \Locacao\Model\Freight;


/* rota para página de fretes (entrega e retirada) --------------*/
$app->get('/freights', function(){

    User::verifyLogin();

    $page = new Page();
    $page->setTpl("fretes");
});

$app->get('/freights/json', function() {

    User::verifyLogin();

    echo Freight::listAll();
});

$app->get('/freights/json/:id', function($id) {

    User::verifyLogin();
    $freight = new Freight();
    echo $freight->loadFreight((int)$id);
});

$app->post('/freights/list_datatables', function(){ //ajax list datatables

	User::verifyLogin();
	
	//Receber a requisão da pesquisa 
	$requestData = $_REQUEST;

	$freights = new FreightController();
	echo $freights->ajax_list_freights($requestData);
	
});
