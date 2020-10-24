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
            if($paraFaturar) $faturasPendentes[] = $paraFaturar; 
        }

        return json_encode($faturasPendentes);

    }

    function getAlugueisParaFaturar($arrContrato) //para cada Contrato
    {
        $contrato = $arrContrato['contrato'];
        print_r($contrato);
        echo "<br><br>";

        $hoje = new DateTime();
        echo "HOJE: ". $hoje->format('Y-m-d');

        $arrUltimaFatura = $this->fatura->getultimaFatura($contrato['idContrato']); //BUSCA A ÚLTIMA FATURA DO CONTRATO
        echo "<br>". $arrUltimaFatura;
        $arrUltimaFatura = json_decode($arrUltimaFatura, true);

        $fatura = [];

        if(count($arrUltimaFatura['fatura']) > 0) //se esse contrato tem alguma fatura
        {
            $fatura = $arrUltimaFatura['fatura']['0'];
   
            //PEGA A DATA DE EMISSÃO DA ÚLTIMA FATURA
            $dtUltimaFatura = new Datetime($fatura['dtEmissao']);
            echo "<br><br>dtUltimaFatura: ".$dtUltimaFatura->format('Y-m-d');    
            
            //VERIFICA SE JÁ TEVE UMA FATURA NESSE MÊS
            if($dtUltimaFatura->format('Y-m') === $hoje->format('Y-m'))
            {
                echo "<br>JÁ TEVE FATURA NESTE MÊS: ".$hoje->format('Y-m');
                return false;
            }

        } else
        {
            echo "<br>CONTRATO SEM FATURA<br>"; 
            $dtUltimaFatura = false;    
        }

        if($contrato['temMedicao']) // se o contrato tiver regra para faturar
        {   
            //PEGAR LISTA QUANDO O CONTRATO TEM MEDIÇÃO
            $dtVenc = $this->getDataFaturaMedicao($contrato, $dtUltimaFatura, $hoje);

        } else
        {
            //PEGAR LISTA QUANDO O CONTRATO NÃO TEM MEDIÇÃO
            $dtVenc = $this->getDataFaturaNormal($arrContrato['alugueis'], $fatura);
        }

        $dtEmissao = $this->getDtNovaFatura($dtVenc, $hoje);

        return json_encode([
            "idContrato"=>$contrato['idContrato'],
            "dtEmissaoFatura"=>$dtEmissao->format('Y-m-d'),
            "dtVencFatura"=>$dtVenc->format('Y-m-d')
        ]);

        /* VERIFICAR O QUE ESTÁ ATRASADO PARA FATURAR E PEGAR A LISTA
        */     
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

    function getDataFaturaNormal($alugueis, $fatura=[])
    {
        echo "<br>NÃO TEM MEDIÇÃO<br>";

        echo "Alugueis: ";
        print_r($alugueis);

        if(count($fatura) == 0) //SE NÃO TEVE FATURA ANTERIOR
        {
            echo "<br>FAZER A PRIMEIRA FATURA<br>";

            /* PARA FAZER A PRIMEIRA FATURA, PEGA O PRIMEIRO ALUGUEL, */
            $dtInicioFatura = new DateTime($alugueis[0]['dtInicio']);

             /* A PARTIR DA DATA DE INICIO DO PRIMEIRO ALUGUEL, CONTA 01 MÊS PARA O VENCIMENTO */ 
            $dtVencFatura = new DateTime($dtInicioFatura->format('Y-m-d'));
            $dtVencFatura->add(new DateInterval('P01M')); //adiciona 1 mês
            echo "<br>inicio: " . $dtInicioFatura->format('Y-m-d') . ", fim: " . $dtVencFatura->format('Y-m-d');

            return $dtVencFatura;

        } else {

        }
    }

    function getDataFaturaMedicao($contrato, $dtUltimaFatura, $hoje)
    {

        if($dtUltimaFatura) //se teve alguma fatura
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

        }else
        {
            echo "<br>FAZER PRIMEIRA FATURA COM MEDIÇÃO";
        }

    }

    function getDtNovaFatura($dtVencFatura, $hoje) {
        $dia = $dtVencFatura->format('d');
        $mes = $hoje->format('m');
        $ano = $hoje->format('Y');
        $dtVenc = new DateTime("$ano-$mes-$dia");
        echo "<br>data vencimento: ". $dtVenc->format('Y-m-d');

        $dtNewFatura = $dtVenc->sub(new DateInterval('P15D')); //subtrai 15 dias da data de vencimento
        echo " -> data nova Fatura: ".$dtNewFatura->format('Y-m-d') . "\n";

        return $dtNewFatura;
    }

}

?>