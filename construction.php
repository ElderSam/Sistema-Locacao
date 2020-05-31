<?php


use \Locacao\Page;
use \Locacao\Controller\ConstructionController;
use Locacao\Model\Construction;
//use \Locacao\Model\ReposibleWorks;
use \Locacao\Model\User;


/* rota para chamar o método listAll*/
$app->get('/construction/:idCliente', function(){
		
    User::verifyLogin();
	$page = new Page();
	$page->setTpl("obras");

});


$app->post('/construction/list_datatables/:idCliente', function($idCliente){ //Ira listar as obras

	User::verifyLogin();

	//Receber a requisão da pesquisa 
	$requestData = $_REQUEST;

	$construction = new ConstructionController();
	echo $construction->ajax_list_construction($requestData, $idCliente);
	
});



$app->get('/construction/json/:idObra', function($idObra){
	
	User::verifyLogin();

	$const = new Construction();

	$const->get((int)$idObra);

	echo json_encode($const->getValues());

});


$app->get('/loadReposibleWorks/:idCostumer', function($idCostumer){

	User::verifyLogin();

	$const = new  Construction();

	$const->getReposible((int)$idCostumer);

	echo json_encode($const->getValues());
});

$app->get('/loadCodObra/:idCostumer', function($idCostumer){

	User::verifyLogin();

	$const = new  Construction();

	echo $const->showsNextNumber((int)$idCostumer);	 
});


// rota para criar responsável (salva no banco) -----------
$app->post("/construction/create/:idCliente", function($idCliente){
	
	$construction = new ConstructionController();
	echo $construction->save(false, $idCliente);

});



// Rota para atualizar Cliente 
$app->post("/construction/:idObra", function(){ //update
	
    User::verifyLogin();

	$construction = new ConstructionController();

	$update = true;

	echo $construction->save($update);
	
});


// rota para deletar Cliente --------------------------
$app->post("/construction/:idObra/delete", function($idObra){
    
    User::verifyLogin();

    $construction = new Construction();

	$construction->get((int)$idObra); //carrega o cliente, para ter certeza que ainda existe no banco

	echo $construction->delete();

});




?>
