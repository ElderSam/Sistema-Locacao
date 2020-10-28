<?php

namespace Locacao\Controller;

use \Locacao\Generator;
//use \Locacao\Model\InvoiceItem;
use stdClass;
use DateTime;
//use DateInterval;

class InvoiceItemController extends Generator
{
    function getItemFatura($fatura, $aluguel) {
       
        /*
            OBS: SE O ALUGUEL COMEÇAR NO MESMO DIA QUE A FATURA,
                ENTÃO COBRA APENAS O FRETE
        */
    
        //echo "<br><br>-------------- ITEM DE FATURA: -----------------";

        /* PREENCHER ITENS DA FATURA */
        if($fatura['dtFim'] < $aluguel['dtInicio']) //se a fatura encerra antes de iniciar o aluguel
        {
            //echo "<br>ALUGUEL COMEÇA DEPOIS DO FIM DA FATURA";
            return false;
        }

        $dtInicio = $this->getDtInicio($fatura, $aluguel);
        $dtFim = $this->getDtFim($fatura, $aluguel);

        $valorCobrado = $this->calculaValorCobrado($dtInicio, $dtFim, $aluguel);
        
        if($valorCobrado === false) return false;
             
        $valorFrete = $this->calculaValorFrete($fatura, $aluguel);

        return [
            'idAluguel'=>$aluguel['idHistoricoAluguel'],
            'periodoLocacao'=>$aluguel['periodoLocacao'],
            'vlAluguelCobrado'=>$valorCobrado,
            'frete'=>$valorFrete,
            //MOSTRAR QUAL O TIPO DE FRETE?? (ENTREGA OU RETIRADA)
            'dtInicio'=>$dtInicio,
            'dtFim'=>$dtFim
        ];
    }
        
    function calculaValorCobrado($dtInicio, $dtFim, $aluguel)
    {
        //echo "<br><br>calculaValorCobrado: <br>";
        //print_r($aluguel);
        $qtdDias = $this->getQtdDias($dtInicio, $dtFim);

        if($qtdDias === false) return false;

        if($qtdDias == 0)
        {
            $valorTotal = 0;

        }else{
            $periodoLocacao = $aluguel['periodoLocacao'];
            $vlAluguel = $aluguel['vlAluguel'];
            
            //echo "<br> CALCULO DO VALOR COBRADO";
            /* CÁLCULO DO VALOR COBRADO */
            if($periodoLocacao == 1) //diario
            {   $valorTotal = ($qtdDias * $vlAluguel);
            
            } else if($periodoLocacao == 2) //'semanal'
            {   $valorTotal = ($qtdDias/7 * $vlAluguel);
            
            }else if($periodoLocacao == 3) //'quinzenal'
            {   $valorTotal = ($qtdDias/15 * $vlAluguel);
            
            }else if($periodoLocacao == 4) //'mensal'
            {   $valorTotal = ($qtdDias/30 * $vlAluguel);  //ou pegar a diferença de dias
            }
        }
      
        //echo " VALOR COBRADO: $valorTotal";
        return $valorTotal; 
    }

    function calculaValorFrete($fatura, $aluguel)
    {
        //echo "<br>calculaValorFrete: ";

        $custoEntrega = $this->getCustoEntrega($fatura, $aluguel);


        if($custoEntrega == 0)
        {
            $custoRetirada = $this->getCustoRetirada($fatura, $aluguel);   
        }else{
            $custoRetirada = 0;
        }
       
        return [
            'custoEntrega'=>$custoEntrega,
            'custoRetirada'=>$custoRetirada
        ];
    }

    function getCustoEntrega($fatura, $aluguel)
    {
        if($aluguel['dtInicio'] < $fatura['dtInicio']) //se o aluguel começou antes dessa fatura
        {
            return 0;

        }else{
            //echo " frete ENTREGA";
            return $aluguel['custoEntrega'];
        }
    }

    function getCustoRetirada($fatura, $aluguel)
    {
        if($aluguel['dtFinal'] < $fatura['dtFim']) //se o aluguel encerra antes da data final da fatura
        {
            //echo " frete RETIRADA";
            return $aluguel['custoRetirada'];

        }else{
            return 0;
        }
    }

    function getQtdDias($dtInicio, $dtFim) //calcula a quantidade de dias dentro do período cobrado na fatura
    {
        /* calcula a quantidade de dias de $dtInicio até $dtFim */
        //echo "<br>$dtInicio até $dtFim";
        $dtInicio = new DateTime($dtInicio);
        $dtFim = new DateTime($dtFim);
        $interval = $dtInicio->diff($dtFim);
        //echo " -> quantidade de dias: " .$interval->format('%R%a days');

        if($interval->format('%a') < 0)
        {
            //echo ' NEGATIVO';
            return false;
        }

        return $interval->format('%a'); //quantidade de dias
    }

    function getDtInicio($fatura, $aluguel)
    {
        if($aluguel['dtInicio'] < $fatura['dtInicio']) //se o aluguel começou antes dessa fatura
        {
            $dtInicio = $fatura['dtInicio'];

        }else{
            $dtInicio = $aluguel['dtInicio'];
        }

        return $dtInicio;
    }

    function getDtFim($fatura, $aluguel)
    {
        if($aluguel['dtFinal'] < $fatura['dtFim']) //se o aluguel encerra antes da data final da fatura
        {
            $dtFim = $aluguel['dtFinal'];

        }else{
            $dtFim = $fatura['dtFim'];
        }

        return $dtFim;
    }

}