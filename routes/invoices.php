<?php

use \Locacao\Page;
use \Locacao\Model\User;
use \Locacao\Controller\InvoiceController;
use \Locacao\Model\Invoice;

/* FATURA
    getFaturasParaFazer()

 */
/* rota para página de faturas --------------*/
$app->get('/invoices', function(){
    //User::verifyLogin();
    echo "rota faturas";
    $faturaController = new InvoiceController();
    echo $faturaController->getFaturasParaFazer();
});

