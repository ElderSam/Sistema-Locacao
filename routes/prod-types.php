<?php

use \Locacao\Page;
use \Locacao\Controller\ProdTypeController;
use \Locacao\Model\ProdType;
use \Locacao\Model\User;

/* rota para página de usuários --------------*/

$app->get('/prod-types', function(){

    User::verifyLogin();
	
	$page = new Page();
	$page->setTpl("prod-types");
  
});


$app->get('/prod-types/json', function(){

    User::verifyLogin();

    echo User::listAll();
  
});

$app->post('/prod-types/list_datatables', function(){ //ajax list datatables

	User::verifyLogin();
	
	//Receber a requisão da pesquisa 
	$requestData = $_REQUEST;

	$prodTypes = new ProdTypeController();
	echo $prodTypes->ajax_list_prodTypes($requestData);
	
});

/* rota para deletar usuário --------------------------*/
$app->post("/prod-types/:iduser/delete", function($iduser){
    
    User::verifyLogin();

    $type = new ProdType();

	$type->get((int)$iduser); //carrega o usuário, para ter certeza que ainda existe no banco

	echo $type->delete();

});


$app->get("/prod-types/json/:iduser", function($iduser){
	
    User::verifyLogin();

	$type = new ProdType();

	$type->get((int)$iduser);

	echo json_encode($type->getValues());

});

/* rota para criar usuário (salva no banco) -----------*/
$app->post("/prod-types/create", function(){

	$type = new ProdTypeController();
	echo $type->save();

});

/* rota para atualizar usuário --------------------------*/
$app->post("/prod-types/:iduser", function($iduser){ //update
	
    User::verifyLogin();

	$type = new ProdTypeController();

	$update = true;

	echo $type->save($update);
	
});


/* rota para retornar o próximo código --------------------------*/
$app->post("/prod-types/showsNextNumber/:idCategoria/:ordem_tipo", function($idCategoria, $ordem_tipo){ //update
	
    User::verifyLogin();

	echo ProdType::showsNextNumber($idCategoria, $ordem_tipo);
	
});

