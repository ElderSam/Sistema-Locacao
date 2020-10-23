<?php

namespace Locacao\Controller;

use \Locacao\Generator;
use \Locacao\Model\Invoice;
use stdClass;
use DateTime;
use DateInterval;

//https://www.ramosdainformatica.com.br/programacao/php/como-manipular-data-hora-com-php/

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

    function getAlugueisParaFaturar($arrContrato) //para cada Contrato
    {
        $contrato = $arrContrato['contrato'];
        print_r($contrato);
        echo "<br><br>";

        $hoje = new DateTime();
        echo "HOJE: ". $hoje->format('Y-m-d');

        //$ultimaFatura = $this->fatura->getultimaFatura(); //BUSCA A ÚLTIMA FATURA DO CONTRATO
        //DEVE VERIFICAR SE JÁ NÃO TEVE UMA FATURA NESSE MÊS
        //PEGA A DATA DE EMISSÃO DA FATURA
        //$dtUltimaFatura = $ultimaFatura['fatura']['dtEmissao'];
        $dtUltimaFatura = '2020-09-03';

        $dtUltimaFatura = new DateTime($dtUltimaFatura);

        /*
        PEGAR LISTA QUANDO O CONTRATO NÃO TEM MEDIÇÃO;

        PEGAR LISTA QUANDO O CONTRATO TEM MEDIÇÃO

        VERIFICAR O QUE ESTÁ ATRASADO PARA FATURAR E PEGAR A LISTA
        */ 

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