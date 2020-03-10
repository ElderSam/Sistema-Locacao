<?php

use \Locacao\Page;
use \Locacao\Model\User;
use \Locacao\Model\Supplier;

/* rota para página de produtos --------------*/
/*$app->get('/suppliers', function(){

    User::verifyLogin();

    $page = new Page();
    $page->setTpl("produtos");

});*/

$app->get('/suppliers/json', function(){

    User::verifyLogin();

    echo Supplier::listAll();
  
});

