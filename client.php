<?php


use \Locacao\Page;
use \Locacao\Controller\ClientController;
use \Locacao\Model\Client;

/* rota para página de Clientes --------------*/

/* rota para .... --------------*/
$app->get('/costumers/json', function(){

    echo Client::listAll();
  
});

$app->get('/costumers', function(){


    $page = new Page();

    $page->setTpl("costumers");

});



$app->post('/costumers/list_datatables', function(){ //ajax list datatables


	//Receber a requisão da pesquisa 
	$requestData = $_REQUEST;

	$client = new ClientController();
	echo $client->ajax_list_clients($requestData);
	
});



$app->get("/costumers/json/:idCliente", function($idCliente){
	

	$cliente = new Client();

	$cliente->get((int)$idCliente);

	echo json_encode($cliente->getValues());

});


?>
