<?php

namespace Locacao\Controller;

use \Locacao\Generator;
use \Locacao\Model\Invoice;
use DateTime;

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

        $faturasPendentes = [];

        foreach($arrContracts as $arrContrato)
        { //para cada contrato
            $faturasPendentes[] = $this->getAlugueisParaFaturar($arrContrato);
        }

    }

    function getAlugueisParaFaturar($arrContrato) { //para cada Contrato
        $contrato = $arrContrato['contrato'];
        print_r($contrato);
        echo "<br><br>";

        if($contrato['temMedicao']) // se o contrato tiver regra para faturar
        {


        } else {
            echo "<br>NÃO TEM MEDIÇÃO<br>";
            /* PARA A PRIMEIRA FATURA, PEGA O PRIMEIRO ALUGUEL, 
                A PARTIR DA DATA DE INICIO DESTE CONTA 30 DIAS PARA O VENCIMENTO */   
        }

    }

}

?>