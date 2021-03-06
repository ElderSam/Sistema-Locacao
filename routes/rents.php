<?php

use \Locacao\Page;
use \Locacao\Controller\RentController;
use \Locacao\Model\User;
use \Locacao\Model\Rent;

function getPageRent($id="") {
    User::verifyLogin();

    $page = new Page();
    $page->setTpl("locacoes", array(
        "idRentToModal"=>$id
    ));
}

/* rota para página de locações, passando o id para abrir o modal direto --------------*/
$app->get('/rents', function(){
    getPageRent();
});

$app->get('/rents/:id', function($id){
    getPageRent($id);
});

$app->get('/rents/json', function(){

    User::verifyLogin();
    echo Rent::listAll();
  
});

$app->get('/rents/json/chart', function() {
    $x = new RentController();
    echo $x->getDataToChart();
});

$app->post('/rents/list_datatables', function(){ //ajax list datatables

	User::verifyLogin();
	
	//Receber a requisão da pesquisa 
	$requestData = $_REQUEST;

	$rents = new RentController();
	echo $rents->ajax_list_rents($requestData);
	
});

/* rota para deletar aluguel --------------------------*/
$app->post("/rents/:idrent/delete", function($idrent){
    
    User::verifyLogin();

    $rent = new RentController();

    echo $rent->delete($idrent);

});

$app->get("/rents/json/:idrent", function($idrent){
	
    User::verifyLogin();

	$rent = new Rent();

	/*$rent->get((int)$idrent);
    echo json_encode($rent->getValues());*/
    
    echo $rent->loadRent((int)$idrent);

});

$app->get("/rents/addToContract/:codRent", function($codRent){
	
    User::verifyLogin();

	$rent = new Rent();

	echo $rent->getByCode($codRent);

});

/* rota para criar aluguel (salva no banco) -----------*/
$app->post("/rents/create", function(){

    
	$rent = new RentController();
	echo $rent->save();

});


/* rota para atualizar aluguel --------------------------*/
$app->post("/rents/:idrent", function($idrent){ //update
	
    User::verifyLogin();

	$rent = new RentController();

	$update = true;

	echo $rent->save($update);
	
});