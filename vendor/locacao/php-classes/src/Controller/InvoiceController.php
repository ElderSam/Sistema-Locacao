<?php

namespace Locacao\Controller;

use \Locacao\Generator;
use \Locacao\Model\Invoice;

class InvoiceController extends Generator //controller de Fatura
{
    function __construct()
    {
        date_default_timezone_set("America/Sao_Paulo");

        $this->fatura = new Invoice(); //model
    }

    function getFaturasParaFazer() /* Retorna alugueis que devem ser faturados no dia OU que já deveriam ter sido faturados */
    {
        $arrContracts = $this->fatura->getContracts();  // PEGA TODOS OS CONTRATOS QUE POSSUEM ALUGUEIS 
        //print_r($arrContracts);
        $arrContracts = json_decode($arrContracts, true);
    }

}

?>