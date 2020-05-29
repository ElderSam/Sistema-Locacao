<?php

//use \Locacao\Page;
use \Locacao\Controller\ContractItemController;
use \Locacao\Model\User;
use Locacao\Model\ContractItem;
use \Locacao\Model\Product;

$app->get("/budgetItens/addItemToContract/:idContract/:codeProd", function($idContract, $codeProd){
	
    User::verifyLogin();
	
	$teste = ContractItem::productAlreadyAdded($idContract, $codeProd);

	$jaAdicionado = json_decode($teste);

	if($jaAdicionado->error){

		echo $teste;

	}else{
	
		$product = new Product();

		echo $product->getByCode($codeProd);
	}
});

$app->get("/budgetItens/searchItemInContract/:idContract/:codeProd", function($idContract, $codeProd){
	
    User::verifyLogin();

    $product = new Product();
    echo $product->getByCode($codeProd);

});


$app->get('/budgetItens/json/:idItem', function($idItem){

	User::verifyLogin();

    $contract = new ContractItem();

	$contract->get((int)$idItem);

	echo json_encode($contract->getValues());
});

/* rota para deletar item --------------------------*/
$app->post("/budgetItens/:idItem/delete", function($idItem){
    
    User::verifyLogin();

    $item = new ContractItem();
	$item->get((int)$idItem); //carrega o item, para ter certeza que ainda existe no banco

	echo $item->delete();

});


/* rota para atualizar item --------------------------*/
$app->post("/budgetItens/create", function(){ //update
    
    User::verifyLogin();

	$item = new ContractItemController();

	$update = true;

	echo $item->save();
	
});


/* rota para atualizar item --------------------------*/
$app->post("/budgetItens/:idbudget", function($idbudget){ //update
    
    User::verifyLogin();

	$item = new ContractItemController();

	$update = true;

	echo $item->save($update);
	
});
