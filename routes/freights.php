<?php

use \Locacao\Page;
use \Locacao\Model\User;
use \Locacao\Controller\FreightController;
use \Locacao\Model\Freight;


function getPageFreight($idRent=false) {
    User::verifyLogin();

    $page = new Page();
    $page->setTpl("fretes", array(
        "idRentURL"=>$idRent
    ));
}

/* rota para página de fretes (entrega e retirada) --------------*/
$app->get('/freights', function(){
    getPageFreight();
});

$app->get('/freights/rent/:idRent', function($idRent) {
    getPageFreight($idRent);
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

//---------------------------------------------
function loadTable($idRent=false) {
    User::verifyLogin();
	
	//Receber a requisão da pesquisa 
	$requestData = $_REQUEST;

	$freights = new FreightController();
	echo $freights->ajax_list_freights($requestData, $idRent);
}

$app->post('/freights/list_datatables', function(){ //ajax list datatables
	loadTable();
});

$app->post('/freights/list_datatables/rent/:idRent', function($idRent){ //ajax list datatables
	loadTable($idRent);
});
//---------------------------------------------

/* rota para criar frete (salva no banco) -----------*/
$app->post("/freights/create", function(){
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