<?php

use \Locacao\Page;
use \Locacao\Model\User;

/* rota para página de locações/alugueis --------------*/
$app->get('/rents', function(){

    User::verifyLogin();

    $page = new Page();
    $page->setTpl("fretes");

});