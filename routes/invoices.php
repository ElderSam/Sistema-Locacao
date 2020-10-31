<?php

use \Locacao\Page;
use \Locacao\Model\User;
use \Locacao\Controller\InvoiceController;
use \Locacao\Model\Invoice;

/* FATURA */
function getPageInvoice($array=false/*$idInvoice=false*/) {
    User::verifyLogin();

    $page = new Page();
    if($array)
    {
        $page->setTpl("faturas", $array);
            
        /*$page->setTpl("faturas", array(
            "idInvoiceURL"=>$idInvoice
        ));*/

    }else{
        $page->setTpl("faturas", $array);
    } 
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

$app->get('/invoices/contract/:idContract/createForm', function($idContract){   
 
    $array = [
        'idContract'=>$idContract
    ];

    getPageInvoice($array);
});

$app->get('/invoices/contract/:idContract/create', function($idContract){

    //echo "gerar fatura<br> idContract: $idContract <br>";
    $faturaController = new InvoiceController();
    echo $faturaController->getDataToFormFatura($idContract);
});
