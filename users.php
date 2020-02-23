<?php

use \Locacao\Page;
use \Locacao\Model\User;

/* rota para página de usuários --------------*/
$app->get("/users", function(){

    User::verifyLogin();
    
    $users = User::listAll();
    
    $page = new Page();

    $page->setTpl("usuarios", array(
        "users"=>$users
    ));

    //$page->setTpl("usuarios-create");

});

/* rota para página de criar usuário -------------------*/
/*$app->get("/users/create", function(){

    User::verifyLogin();

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
$app->get("/users/:iduser", function($iduser){

    User::verifyLogin();

	$user = new User();

	$user->get((int)$iduser);

	$page = new Page();

	//mostra o conteúdo de users-update.html
	$page->setTpl("users-update", array( 
		"user"=>$user->getValues()
	)); 

});

/* rota para criar usuário (salva no banco) -----------*/
$app->post("/users/create", function(){
    
    User::verifyLogin();
    
	$user = new User();

	$_POST["administrador"] = (isset($_POST["administrador"])) ? 1 : 0;

	//criptografa a senha
	$_POST['password'] = password_hash($_POST["password"], PASSWORD_DEFAULT, [
		"cost"=>12
	]);

	$user->setData($_POST);

	$user->save();

	header("Location: /admin/users");
	exit;
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

