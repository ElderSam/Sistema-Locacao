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

        foreach($arrContracts as $arrContrato)  //para cada contrato
        {
            $paraFaturar = false;
            if(count($arrContrato['alugueis']) > 0) //se existir aluguel para faturar
            {
                $paraFaturar = $this->getAlugueisParaFaturar($arrContrato);
            }else
            {
                echo "CONTRATO SEM ALUGUEL";
            }
            
            echo "----------------------------------------<br><br>";
            if($paraFaturar) $faturasPendentes[] = $paraFaturar; //se retornou, coloca na lista
        }

        return json_encode($faturasPendentes);

    }

    function getAlugueisParaFaturar($arrContrato) //para cada Contrato
    {
        $contrato = $arrContrato['contrato'];
        print_r($contrato);
        echo "<br><br> <b>idContrato</b>=". $contrato['idContrato']."<br>";

        $hoje = new DateTime();
        echo "HOJE: ". $hoje->format('Y-m-d');

        $arrUltimaFatura = $this->fatura->getultimaFatura($contrato['idContrato']); //BUSCA A ÚLTIMA FATURA DO CONTRATO
        echo "<br>ÚLTIMA FATURA: ". $arrUltimaFatura;
        $arrUltimaFatura = json_decode($arrUltimaFatura, true);

        $dtUltimaFatura = $this->calculaDtUltimaFatura($arrUltimaFatura);

        if($dtUltimaFatura) //se teve fatura anterior
        {
            //VERIFICA SE JÁ TEVE UMA FATURA NESSE MÊS
            if($dtUltimaFatura->format('Y-m') === $hoje->format('Y-m'))
            {
                echo "<br>JÁ TEVE FATURA NESTE MÊS: ".$hoje->format('Y-m');
                return false;
            }
        }

        $dtInicio = $this->calculaDtInicio($dtUltimaFatura, $hoje, $arrContrato['alugueis']);

        $dtVenc = $this->calculaDtVenc($dtInicio, $hoje, $contrato);

        echo "<br>inicio: " . $dtInicio->format('Y-m-d') . ", dtVenc: " . $dtVenc->format('Y-m-d');
        
        if(!$dtVenc) return false;

        $this->calculaDtFim($dtVenc);
        //CALCULA DTFIM (1 DIA ANTES DE DTVENC)

        $dtEmissao = $this->calculaDtEmissao($dtVenc);

        return json_encode([
            "idContrato"=>$contrato['idContrato'],
            "dtEmissaoFatura"=>$dtEmissao->format('Y-m-d'),
            "dtInicioFatura"=>$dtInicio->format('Y-m-d'),
            "dtVencFatura"=>$dtVenc->format('Y-m-d')
        ]);

        /* VERIFICAR O QUE ESTÁ ATRASADO PARA FATURAR E PEGAR A LISTA
        */     
    }

    function calculaDtUltimaFatura($arrUltimaFatura)
    {
        if(count($arrUltimaFatura['fatura']) > 0) //se esse contrato tem alguma fatura
        {
            $fatura = $arrUltimaFatura['fatura'][0];

            //PEGA A DATA DE EMISSÃO DA ÚLTIMA FATURA
            return new Datetime($fatura['dtEmissao']);  

        } else
        {
            echo "<br>CONTRATO SEM FATURA<br>"; 
            return false;    
        }
    }

    function calculaDtInicio($dtUltimaFatura, $hoje, $alugueis)
    {
        //CALCULA DTINICIO
            //ALUGUEL COM FATURA
            //ALUGUEL SEM FATURA

        if(!$dtUltimaFatura) { //se não teve fatura anterior
            echo "<br>NÃO TEM FATURA ANTERIOR
                <br>FAZER A PRIMEIRA FATURA<br>";

            echo "Alugueis: ";
            print_r($alugueis);

            /* PARA FAZER A PRIMEIRA FATURA, PEGA O PRIMEIRO ALUGUEL, */
            $dtInicioFatura = new DateTime($alugueis[0]['dtInicio']);

        }else
        {
            echo "<br>TEM FATURA ANTERIOR<br>
                <br>dtUltimaFatura: ".$dtUltimaFatura->format('Y-m-d');  

            $dtInicioFatura = new DateTime($dtUltimaFatura->format('Y-m-d'));
            $dtInicioFatura->add(new DateInterval('P01D')); //adiciona 1 dia
        }

        return $dtInicioFatura;
    }

    function calculaDtVenc($dtInicioFatura, $hoje, $contrato)
    {
        echo "medição=".$contrato['temMedicao'];

        if($contrato['temMedicao']) // CONTRATO COM MEDIÇÃO: se o contrato tiver regra para faturar
        {   
            $dtVenc = $this->getDtVencFaturaMedicao($contrato, $hoje);

        } else //CONTRATO SEM MEDIÇÃO
        {
            $dtInicio = $dtInicioFatura->format('Y-m-d');
            $dtVenc = $this->getDtVencFaturaNormal($dtInicio);
        }

        return $dtVenc;
    }

    function calculaDtFim()
    {

    }

    function getDtVencFaturaMedicao($contrato, $hoje)
    {
        //PEGAR LISTA QUANDO O CONTRATO TEM MEDIÇÃO
        echo "<br>TEM MEDIÇÃO<br>";

        echo "<br>regraFatura: ".$contrato['regraFatura'];
        $dtVenc = $contrato['diaFatura'];
    
        if($contrato['regraFatura'] == 1) // se tiver dia fixo, verificar se é o dia de hoje (dia atual) 
        {
            $txtDate = $dtVenc . $hoje->format("m-d");
            $dtVenc = new DateTime($txtDate);
            
        }else if($contrato['regraFatura'] == 2) //se a regra para faturar for dia da semana
        {
            $dtVenc = $this->getExactDate($contrato['semanaDoMes'], $dtVenc, $hoje);    
        }

        echo "<br>dtVenc: ". $dtVenc->format('Y-m-d');              
        return $dtVenc;
    }

    function getDtVencFaturaNormal($dtInicio)
    {
        //PEGAR LISTA QUANDO O CONTRATO NÃO TEM MEDIÇÃO
        //$dtVenc = $this->getDataFaturaNormal($arrContrato['alugueis'], $fatura);

        echo "<br>NÃO TEM MEDIÇÃO<br>";

        /* A PARTIR DA DATA DE INICIO DO PRIMEIRO ALUGUEL, CONTA 01 MÊS PARA O VENCIMENTO */ 
        $dtVenc = new DateTime($dtInicio);
        $dtVenc->add(new DateInterval('P01M')); //adiciona 1 mês
        return $dtVenc;
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

    function calculaDtEmissao($dtVenc) { // data de emissão da nova fatura

        //DTEMISSAO (10 DIAS ANTES DE DTVENC)
        $dtNewFatura = $dtVenc->sub(new DateInterval('P10D')); //subtrai 10 dias da data de vencimento
        echo " -> data nova Fatura: ".$dtNewFatura->format('Y-m-d') . "<br>";

        return $dtNewFatura;
    }

    function getNumWeekdayInMonth($date) // retorna o número da semana dentro do mês (que tem aquele dia da semana)
    {
        $weekdayInMonth = 1;
      
        $dia = $date->format('d');

        while(($dia - 7) >= 1)
        {  //15 - 7 -> 8 - 7 = 1 
            $dia -= 7;
            $weekdayInMonth++;
        }

        return $weekdayInMonth;
    }

}

?>