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

/* rota para criar frete (salva no banco) -----------*/
$app->post("/rents/create", function(){
    User::verifyLogin();

	$freight = new FreightController();
	echo $freight->save();
});


/* rota para atualizar frete --------------------------*/
$app->post("/freights/:idfreight", function($idfreight){ //update
	
    User::verifyLogin();

	$freight = new FreightController();
	$update = true;
	echo $freight->save($update);	
});

/* rota para deletar frete --------------------------*/
$app->post("/freights/:idfreight/delete", function($idfreight){
    
    User::verifyLogin();

    $freight = new FreightController();
    echo $freight->delete($idfreight);
});

$app->post('/freights/list_datatables', function(){ //ajax list datatables

	User::verifyLogin();
	
	//Receber a requisão da pesquisa 
	$requestData = $_REQUEST;

	$freights = new FreightController();
	echo $freights->ajax_list_freights($requestData);
	
});