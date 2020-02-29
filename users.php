<?php


//require_once('./Controllers/UsersController.php');


use \Locacao\Page;
use \Locacao\Controller\UserController;
use \Locacao\Model\User;

/* rota para página de usuários --------------*/

$app->get('/users', function(){

    User::verifyLogin();
	
    $users = User::listAll();
	//print_r($users);
    $page = new Page();

	$users = json_decode($users, false);
	
    $page->setTpl("usuarios", array(
        "users"=>$users
    ));
  
});

$app->get('/users/json', function(){

    User::verifyLogin();

    echo User::listAll();
  
});

$app->post('/users/list_datatables', function(){ //ajax list datatables

	User::verifyLogin();
	
	
	//Receber a requisão da pesquisa 
	$requestData = $_REQUEST;

	$users = new UserController();
	echo $users->ajax_list_users($requestData);
	
});



/* rota para página de criar usuário -------------------*/
/*$app->get("/users/create", function(){

    User::verifyLogin();

	echo "get users/create";
    $page = new Page();

    $page->setTpl("usuarios-create");

});*/

/* rota para deletar usuário --------------------------*/
$app->get("/users/:iduser/delete", function($iduser){
    
    User::verifyLogin();

    $user = new User();

	$user->get((int)$iduser); //carrega o usuário, para ter certeza que ainda existe no banco

	$user->delete();

	header("Location: /users");
	exit;
});


/* rota para página atualizar usuário -------------------- */
/*$app->get("/users/:iduser", function($iduser){

    User::verifyLogin();

	$user = new User();

	$user->get((int)$iduser);

	$user = json_decode($user, false);

	$page = new Page();

	//mostra o conteúdo de users-update.html
	$page->setTpl("users-update", array( 
		"user"=>$user->getValues()
	)); 

});*/

$app->get("/users/json/:iduser", function($iduser){
	
    User::verifyLogin();

	$user = new User();

	$user->get((int)$iduser);

	echo json_encode($user->getValues());

});

/* rota para criar usuário (salva no banco) -----------*/
$app->post("/users/create", function(){

	$user = new UserController();
	echo $user->save();

});


/* rota para atualizar usuário --------------------------*/
$app->post("/users/:iduser", function($iduser){
    
    User::verifyLogin();

    $user = new User();

	$_POST["administrador"] = (isset($_POST["administrador"])) ? 1 : 0;
	
	$user->get((int)$iduser);

	$user->setData($_POST);

	$user->update();

	header("Location: /users");
	exit;
	
});

