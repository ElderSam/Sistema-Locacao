<?php


use \Locacao\Page;
use \Locacao\Controller\ReposibleWorksController;
use \Locacao\Model\ReposibleWorks;
use \Locacao\Model\User;


/* rota para chamar o método listAll*/
$app->get('/reposibleWorks/json/:idCliente', function(){
		
    User::verifyLogin();
	$page = new Page();
	$page->setTpl("respObras");

});


$app->post('/reposibleWorks/list_datatables/:idCliente', function($idCliente){ //Ira listar os chefes de obra

	User::verifyLogin();

	//Receber a requisão da pesquisa 
	$requestData = $_REQUEST;

	$reposilble = new ReposibleWorksController();
	echo $reposilble->ajax_list_costumers($requestData, $idCliente);
	
});



$app->get('/reposibleWorks2/json/:idResp', function($idResp){
	
	User::verifyLogin();

	$resp = new ReposibleWorks();

	$resp->get((int)$idResp);

	echo json_encode($resp->getValues());

});

/* rota para criar responsável (salva no banco) -----------*/
$app->post("/reposibleWorks/create/:idCliente", function($idCliente){

	$reposible = new reposibleWorksController();
	echo $reposible->save(false, $idCliente);

});

// Rota para atualizar Cliente 
$app->post("/reposibleWorks/:idReposibleWorks", function(){ //update
	
    User::verifyLogin();

	$reposible = new reposibleWorksController();

	$update = true;

	echo $reposible->save($update);
	
});

/* rota para deletar Cliente --------------------------*/
$app->post("/reposibleWorks/:idResp/delete", function($idResp){
    
    User::verifyLogin();

    $reposible = new reposibleWorks();

	$reposible->get((int)$idResp); //carrega o cliente, para ter certeza que ainda existe no banco

	echo $reposible->delete();

});




?>
