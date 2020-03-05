<?php

use \Locacao\Page;
use \Locacao\Controller\ContainerController;
use \Locacao\Model\User;
use \Locacao\Model\Containers;

/* rota para página de produtos/containers --------------*/
$app->get('/products/containers', function(){

    User::verifyLogin();

    $page = new Page();

    $page->setTpl("produtos-containers");

});


$app->get('/products/containers/json', function(){

    User::verifyLogin();

    echo Containers::listAll();
  
});

$app->post('/products/containers/list_datatables', function(){ //ajax list datatables

	User::verifyLogin();
	
	//Receber a requisão da pesquisa 
	$requestData = $_REQUEST;

	$containers = new ContainerController();
	echo $containers->ajax_list_containers($requestData);
	
});

/* rota para deletar usuário --------------------------*/
$app->post("/products/:idcontainer/delete", function($idcontainer){
    
    User::verifyLogin();

    $container = new Container();

	$container->get((int)$idcontainer); //carrega o usuário, para ter certeza que ainda existe no banco

	echo $container->delete();

});

$app->get("/products/containers/json/:idcontainer", function($idcontainer){
	
    User::verifyLogin();

	$container = new Container();

	$container->get((int)$idcontainer);

	echo json_encode($container->getValues());

});

/* rota para criar usuário (salva no banco) -----------*/
$app->post("/products/containers/create", function(){

	$container = new ContainerController();
	echo $container->save();

});


/* rota para atualizar usuário --------------------------*/
$app->post("/products/containers/:idcontainer", function($idcontainer){ //update
	
    User::verifyLogin();

	$container = new ContainerController();

	$update = true;

	echo $container->save($update);
	
});

