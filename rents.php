<?php

use \Locacao\Page;
use \Locacao\Controller\RentController;
use \Locacao\Model\User;
use \Locacao\Model\Rent;

/* rota para página de alugueis --------------*/
$app->get('/rents', function(){

    User::verifyLogin();

    $page = new Page();
    $page->setTpl("alugueis");

});

$app->get('/rents/json', function(){

    User::verifyLogin();

    echo Rent::listAll();
  
});


$app->post('/rents/list_datatables', function(){ //ajax list datatables

	User::verifyLogin();
	
	//Receber a requisão da pesquisa 
	$requestData = $_REQUEST;

	$rents = new RentController();
	echo $rents->ajax_list_rents($requestData);
	
});

/* rota para deletar usuário --------------------------*/
$app->post("/rents/:idrent/delete", function($idrent){
    
    User::verifyLogin();

    $rent = new RentController();

    echo $rent->delete($idrent);

});

$app->get("/rents/json/:idrent", function($idrent){
	
    User::verifyLogin();

	$rent = new Rent();

	$rent->get((int)$idrent);

	echo $rent->loadRent((int)$idrent);

});

$app->get("/rents/addToContract/:codRent", function($codRent){
	
    User::verifyLogin();

	$rent = new Rent();

	echo $rent->getByCode($codRent);

});

/* rota para criar usuário (salva no banco) -----------*/
$app->post("/rents/create", function(){

    
	$rent = new RentController();
	echo $rent->save();

});


/* rota para atualizar usuário --------------------------*/
$app->post("/rents/:idrent", function($idrent){ //update
	
    User::verifyLogin();

	$rent = new RentController();

	$update = true;

	echo $rent->save($update);
	
});

/*
$app->get('/rents/categories/json', function(){

    User::verifyLogin();

    echo Category::listAll();
  
});


$app->get('/rents/types/json/:idCategory', function($idCategory){

    User::verifyLogin();

    echo Category::listTypes($idCategory);
  
});


$app->post('/rents/showsNextNumber/:idCategory', function($idCategory){

    User::verifyLogin();

    echo Rent::showsNextNumber($idCategory);
  
});*/