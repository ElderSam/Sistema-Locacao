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

        $ultimaFatura = $this->fatura->getultimaFatura($contrato['idContrato']); //BUSCA A ÚLTIMA FATURA DO CONTRATO
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

            if($dtUltimaFatura < $hoje) //se a última fatura foi antes de hoje
            {
                echo "<br>regraFatura: ".$contrato['regraFatura'];
                $diaVencFatura = $contrato['diaFatura'];

                if($contrato['regraFatura'] == 1) // se tiver dia fixo, verificar se é o dia de hoje (dia atual) 
                {        
                    //NÃO FAZ NADA

                }else if($contrato['regraFatura'] == 2) //se a regra para faturar for dia da semana
                {
                    $diaVencFatura = $this->getExactDate($contrato['semanaDoMes'], $diaVencFatura, $hoje);
                    $diaVencFatura = $diaVencFatura->format('Y-m-d');
                    echo "<br>diaVencFatura calculado: $diaVencFatura";
                }

            }


        } else {
            echo "<br>NÃO TEM MEDIÇÃO<br>";
            /* PARA A PRIMEIRA FATURA, PEGA O PRIMEIRO ALUGUEL, 
                A PARTIR DA DATA DE INICIO DESTE CONTA 30 DIAS PARA O VENCIMENTO */   
        }

    }

    function getExactDate($numSemana, $diaSemana, $hoje) // retorna a data Y-m-d a partir do número da semana dentro do mês e do dia dentro da semana
    {
        //PERCORRER O MÊS, ATÉ CHEGAR NA SEMANA DO CONTRATO
         //PERCORRER A SEMANA ATÉ CHEGAR NO DIA DA SEMANA -> ENCONTRAR A DATA EXATA

        $auxDate = new DateTime($hoje->format('Y-m') . '-01'); //primeiro dia do mês
        $auxDayInWeek = $auxDate->format('N') + 1;
        $auxNumWeek = 0;

        if($diaSemana >= $auxDayInWeek) { //se o dia da semana desejado for maior que o primeiro dia do mês
            $auxNumWeek += 1;
            echo "dia da semana do contrato é maior que o dia 01 do mês";
        }

        $auxNumWeek += 1;
        $auxDif = abs($auxDayInWeek - $diaSemana); //retorna o valor absoluto da diferença
        /*echo "<br><br>auxDayInWeek: $auxDayInWeek <br>
            diaSemana do Contrato: $diaSemana, diferença: $auxDif <br> numSemana do Contrato: $numSemana";*/

        $auxDate = $auxDate->add(new DateInterval('P0'.(7 - $auxDif).'D'));

        //echo "<br>date: ". $auxDate->format('Y-m-d') . " auxNumWeek: " .$auxNumWeek;

        //$auxDate = $auxDate->add(new DateInterval('P07D'));

        while($auxNumWeek < $numSemana)
        {
            $auxDate = $auxDate->add(new DateInterval('P07D'));
            $auxNumWeek += 1;
        }

        return $auxDate;
    }

}

?>