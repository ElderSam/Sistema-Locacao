<?php

use \Locacao\Page;
use \Locacao\Model\User;
use \Locacao\Controller\InvoiceController;
use \Locacao\Model\Invoice;

/* FATURA */
function getPageInvoice($idInvoice=false) {
    User::verifyLogin();

    $page = new Page();
    $page->setTpl("faturas", array(
        "idInvoiceURL"=>$idInvoice
    ));
}

/* rota para página de faturas --------------*/
$app->get('/invoices', function(){
    getPageInvoice();
});

/* rota para pegar lista de faturas pendentes (para fazer) */
$app->get('/invoices/json/pending', function(){
    //User::verifyLogin();
    //echo "rota faturas<br>";
    $faturaController = new InvoiceController();
    echo $faturaController->getFaturasParaFazer();
});

