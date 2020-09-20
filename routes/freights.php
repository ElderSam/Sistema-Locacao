<?php

use \Locacao\Page;
use \Locacao\Model\User;
use \Locacao\Model\Freight;


/* rota para página de fretes (entrega e retirada) --------------*/
$app->get('/freights', function(){

    User::verifyLogin();

    $page = new Page();
    $page->setTpl("fretes");
});