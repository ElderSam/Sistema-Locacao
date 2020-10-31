<?php

use \Locacao\Page;
use \Locacao\Model\User;
use \Locacao\Controller\InvoiceController;
use \Locacao\Model\Invoice;

/* FATURA */
function getPage($fileName, $array=false) {
    User::verifyLogin();

    $page = new Page();

    if($array) {
        $page->setTpl($fileName, $array);

    }else{
        $page->setTpl($fileName);
    }
}

/* rota para página de faturas --------------*/
$app->get('/invoices', function(){
    getPage('faturas');
});

/* rota para pegar lista de faturas pendentes (para fazer) */
$app->get('/invoices/json/pending', function(){
    //User::verifyLogin();
    //echo "rota faturas<br>";
    $faturaController = new InvoiceController();
    echo $faturaController->getFaturasParaFazer();
});

/* entrar na página que tem o formulário para salvar fatura */
$app->get('/invoices/contract/:idContract/createForm', function($idContract){   

    getPage('fatura_salvar', array(
        'idContrato'=>$idContract
    ));
});

$app->get('/invoices/contract/:idContract/create', function($idContract){

    //echo "gerar fatura<br> idContract: $idContract <br>";
    $faturaController = new InvoiceController();
    echo $faturaController->getDataToFormFatura($idContract);
});
