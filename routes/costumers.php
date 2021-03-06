<?php


use \Locacao\Page;
use \Locacao\Controller\CostumerController;
use \Locacao\Model\Costumer;
use \Locacao\Model\User;
use \Locacao\Model\Construction;


/* rota para chamar o método listAll*/
$app->get('/costumers/json', function(){

	User::verifyLogin();
    echo Costumer::listAll();
  
});

/* rota que mostra o próximo código de Cliente */
$app->post('/costumers/showsNextNumber', function(){

    User::verifyLogin();

    echo Costumer::showsNextNumber();
  
});

$app->post('/costumers/list_datatables', function(){ //Ira listar os Clientes

	User::verifyLogin();

	//Receber a requisão da pesquisa 
	$requestData = $_REQUEST;

	$costumer = new CostumerController();
	echo $costumer->ajax_list_costumers($requestData);
	
});



$app->get("/costumers/json/:idCliente", function($idCliente){
	
	User::verifyLogin();

	$cliente = new Costumer();

	$cliente->get((int)$idCliente);

	echo json_encode($cliente->getValues());

});

$app->get('/costumers/json/:idCliente/constructions', function($idCliente){ //filtra obras por cliente

    User::verifyLogin();

   echo Construction::listAll($idCliente);
  
});

/* rota para criar cliente (salva no banco) -----------*/
$app->post("/costumers/create", function(){

	$costumer = new CostumerController();
	echo $costumer->save();

});

// Rota para atualizar Cliente 
$app->post("/costumer/:idCostumer", function($idCostumer){ //update
	
    User::verifyLogin();

	$costumer = new CostumerController();

	$update = true;

	echo $costumer->save($update);
	
});

/* rota para deletar Cliente --------------------------*/
$app->post("/costumers/:idCliente/delete", function($idCliente){
    
    User::verifyLogin();

    $costumer = new Costumer();

	$costumer->get((int)$idCliente); //carrega o cliente, para ter certeza que ainda existe no banco

	echo $costumer->delete();

});




?>
