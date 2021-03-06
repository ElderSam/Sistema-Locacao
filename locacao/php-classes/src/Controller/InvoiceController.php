<?php

namespace Locacao\Controller;

use \Locacao\Model\User;
use \Locacao\Generator;
use \Locacao\Model\Invoice;
use \Locacao\Controller\InvoiceItemController;
use \Locacao\Model\InvoiceItem;
use \Locacao\Model\Contract;

use Locacao\Utils\myPDF;
use Locacao\Utils\PDFs\InvoicePDF;

use Exception;
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

    public function getInvoice($id) {

        $itemController = new InvoiceItem();

        return json_encode([
            'fatura'=>$this->fatura->loadInvoice($id),
            'fatura_itens'=>$itemController->getInvoiceItens($id)
        ]);
    }

    public function save($update = false) //salva (insere/atualiza) uma Fatura
    {
        User::verifyLogin();

        $error = $this->verifyFields($update); //verifica os campos do formulário   
        $aux = json_decode($error);

        if ($aux->error){
            return $error;
        }

        $this->fatura->setData($_POST);

        if ($update) { //se for atualizar   
            return  $this->fatura->update();

        } else { // se for cadastrar nova Fatura
            $dtEmissao = $this->fatura->getdtEmissao();
            $this->fatura->setnumFatura($this->generateNumFatura($dtEmissao));
            
            $res = $this->fatura->insert();

            $auxInsert = json_decode($res);

            if (isset($auxInsert->error) && ($auxInsert->error)){
                return $error;
            }

            $itemController = new InvoiceItemController();

            $arrFatura_itens = $this->fatura->getarrFatura_itens();
            $arrFatura_itens = json_decode($arrFatura_itens, true);

            //print_r($arrFatura_itens);
            $invoiceItens = [];

            foreach($arrFatura_itens as $item) {
                $item['idFatura'] = $this->fatura->getidFatura();
                $invoiceItens[] = $itemController->save($item);
            }

            return json_encode([
                'fatura'=>$res,
                'fatura_itens'=>$invoiceItens
            ]);
            
        }
    }

    public function verifyFields($update = false)
    {/*Verifica todos os campos ---------------------------*/

        //print_r($_POST);
        $errors = array();

        //CAMPOS OBRIGATÓRIOS:

        if(!$update){

            if (!isset($_POST['idContrato']) || ($_POST['idContrato'] == '')) {
                    $errors['#idContrato'] = 'Contrato é obrigatório!';
            }

            /* idCobranca */
        }else {

            if($_POST['idFatura'] == '') {
                $errors['#idFatura'] = 'idFatura é obrigatória!';
            }

            if($_POST['numFatura'] == '') {
                $errors['#numFatura'] = 'número da Fatura é obrigatória!';
            }
        }

        if($_POST['enviarPorEmail'] == 1) {
            if($_POST['emailEnvio'] == '') {
                $errors['#emailEnvio'] = 'Email é obrigatório!';
            }
        }

        $camposObrigatorios = [
            'idContrato',
            'dtEmissao',
            'dtInicio',
            'dtFim',
            'dtEnvio',
            //'valorTotal',

            'formaPagamento', // (1-boleto, 2-DOC, 3-transferência, 4-dinheiro, 5-cheque, 6-outros)
            'dtVencimento',
            'statusPagamento', // (0-pendente, 1-parcial, 2-pago, 3-cancelado, 4-perdido)
        ];

        foreach($camposObrigatorios as $campo) { //verifica todos os itens do array se estão vazios
            if($_POST[$campo] == '') {
                $errors['#'.$campo] = 'campo obrigatório!';
            }
        }

        if(($_POST['formaPagamento'] == '') || ($_POST['formaPagamento'] < 1) || ($_POST['formaPagamento'] > 6)) {
            $errors['#formaPagamento'] = 'valor inválido!';
        }

        if(($_POST['statusPagamento'] < 0) || ($_POST['statusPagamento'] > 4)) {
            $errors['#statusPagamento'] = 'valor inválido!';
        }

        if (count($errors) > 0) { //se tiver algum erro de input (campo) do formulário
            return json_encode([
                'error' => true,
                'error_list' => $errors
            ]);

        } else { //se ainda não tem erro

            return json_encode([
                'error' => false
            ]);
        }
    }/* --- fim verifyFields() ---------------------------*/

    public function generateNumFatura($dtEmissao) {
        $ano = substr($dtEmissao, 0, 4);
        $num = ((int)$this->fatura->getMaxNumFatura($dtEmissao)) + 1; //soma +1 ao último número de fatura no ano
        return $num . "-" . $ano;
    }

    public function ajax_list_invoices($requestData)
    {
        $column_search = array("numFatura", "statusPagamento", "dtEmissao", "dtVencimento", "valorTotal", "nomeCliente"); //colunas pesquisáveis pelo datatables
        $column_order = array("statusPagamento", "dtEmissao", "dtVencimento", "numFatura", "valorTotal", "nomeCliente"); //ordem que vai aparecer (o codigo primeiro)

        //faz a pesquisa no banco de dados
        $invoice = new Invoice(); //model
        $datatable = $invoice->get_datatable_invoices($requestData, $column_search, $column_order);
        //print_r($datatable);

        $data = array();
        
        foreach ($datatable['data'] as $invoice) { //para cada registro retornado
            //print_r($invoice);

            // Ler e criar o array de dados ---------------------
            $row = array();

            $row = [
                "idFatura"=>$invoice['idFatura'],
                "numFatura"=>$invoice['numFatura'],
                "statusPagamento"=>$invoice['statusPagamento'],
                "dtEmissao"=>$invoice['dtEmissao'],
                "dtVencimento"=>$invoice['dtVencimento'],
                "valorTotal"=>$invoice['valorTotal'],
                "nomeCliente"=>$invoice['nomeCliente'],
            ];

            //print_r($row);
            $data[] = $row;
        }

        //Cria o array de informações a serem retornadas para o Javascript
        $json = array(
            "draw" => intval($requestData['draw']), //para cada requisição é enviado um número como parâmetro
            "recordsTotal" => $this->records_total(),  //Quantidade de registros que há no banco de dados
            "recordsFiltered" => $datatable['totalFiltered'], //Total de registros quando houver pesquisa
            "data" => $data,  //Array de dados completo dos dados retornados da tabela 
        );

        return json_encode($json); //enviar dados como formato json
    }

    public function records_total()
    {
        return Invoice::total();
    }

    public function delete($idInvoice){
       
        $invoice = new Invoice();
        //echo "id: " . $idInvoice;
        $invoice->get((int)$idInvoice); //carrega o registro, para ter certeza que ainda existe no banco
       
        return $invoice->delete();      
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
                $hoje = new DateTime();
                //echo "HOJE: ". $hoje->format('Y-m-d') . "<br>";
        
                $paraFaturar = $this->getAlugueisParaFaturar($arrContrato, $hoje);
            }else
            {
                //echo "CONTRATO SEM ALUGUEL INICIADO";
            }
            
            //echo "----------------------------------------<br><br>";
            if($paraFaturar) $faturasPendentes[] = $paraFaturar; //se retornou, coloca na lista
        }

        return json_encode($faturasPendentes);

    }

    function getAlugueisParaFaturar($arrContrato, $hoje) //para cada Contrato
    {
        $contrato = $arrContrato['contrato'];
        //print_r($contrato);
        //echo "<br><br> <b>idContrato</b>=". $contrato['idContrato']."<br>";

        $arrUltimaFatura = $this->fatura->getultimaFatura($contrato['idContrato']); //BUSCA A ÚLTIMA FATURA DO CONTRATO
        //echo "<br>ÚLTIMA FATURA: ". $arrUltimaFatura;
        $arrUltimaFatura = json_decode($arrUltimaFatura, true);

        $dtFimUltimaFatura = $this->getDtFimUltimaFatura($arrUltimaFatura);

        if($dtFimUltimaFatura) //se teve fatura anterior
        {
            //VERIFICA SE JÁ TEVE UMA FATURA NESSE MÊS
            if($dtFimUltimaFatura->format('Y-m') === $hoje->format('Y-m'))
            {
                //echo "<br>JÁ TEVE FATURA NESTE MÊS: ".$hoje->format('Y-m');
                return false;
            }
        }

        $dtInicio = $this->calculaDtInicio($dtFimUltimaFatura, $arrContrato['alugueis']);

        $dtVenc = $this->calculaDtVenc($dtInicio, $hoje, $contrato);

        //echo "<br>inicio: " . $dtInicio->format('Y-m-d') . ", dtVenc: " . $dtVenc->format('Y-m-d');
        
        if(!$dtVenc) return false;

        $dtVenc = $dtVenc->format('Y-m-d');
        $dtFim = $this->calculaDtFim($dtVenc);

        $dtEmissao = $this->calculaDtEmissao($dtVenc);

        return [
            "idContrato"=>$contrato['idContrato'],
            "codContrato"=>$contrato['codContrato'],
            "dtEmissao"=>$dtEmissao->format('Y-m-d'),
            "dtInicio"=>$dtInicio->format('Y-m-d'),
            "dtFim"=>$dtFim->format('Y-m-d'),
            "dtVencimento"=>$dtVenc
        ];

        /* VERIFICAR O QUE ESTÁ ATRASADO PARA FATURAR E PEGAR A LISTA
        */     
    }

    function getDtFimUltimaFatura($arrUltimaFatura)
    {
        if(count($arrUltimaFatura['fatura']) > 0) //se esse contrato tem alguma fatura
        {
            
            //PEGA O ITEM COM A MAIOR DATA FIM DO DA ÚLTIMA FATURA
            if(count($arrUltimaFatura['fatura_itens']) > 0)
            {
                //print_r($arrUltimaFatura);

                try{
                    
                    $Itemfatura = $arrUltimaFatura['fatura_itens'][0]; //pega o item com a maior dtFim
                    return new Datetime($Itemfatura['dtFim']);  

                }catch(Exception $err) {
                    //echo "ERRO: Fatura não possui itens";
                    echo json_encode([
                        "error"=>true,
                        "msg"=>"Erro! Fatura sem itens. $err"
                    ]);
                }
    
            }

        } else
        {
            //echo "<br>CONTRATO SEM FATURA<br>"; 
            return false;    
        }
    }

    function calculaDtInicio($dtFimUltimaFatura, $alugueis)
    {
        //CALCULA DTINICIO
            //ALUGUEL COM FATURA
            //ALUGUEL SEM FATURA

        if(!$dtFimUltimaFatura) { //se não teve fatura anterior
            /*echo "<br>NÃO TEM FATURA ANTERIOR
                <br>FAZER A PRIMEIRA FATURA<br>";

            echo "Alugueis: ";
            print_r($alugueis);*/

            /* PARA FAZER A PRIMEIRA FATURA, PEGA O PRIMEIRO ALUGUEL, */
            $dtInicioFatura = new DateTime($alugueis[0]['dtInicio']);

        }else
        {
            /*echo "<br>TEM FATURA ANTERIOR<br>
                <br>dtFimUltimaFatura: ".$dtFimUltimaFatura->format('Y-m-d'); */

            $dtInicioFatura = new DateTime($dtFimUltimaFatura->format('Y-m-d'));
            $dtInicioFatura->add(new DateInterval('P01D')); //adiciona 1 dia
        }

        return $dtInicioFatura;
    }

    function calculaDtVenc($dtInicioFatura, $hoje, $contrato)
    {
        //echo "medição=".$contrato['temMedicao'];

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

    function calculaDtFim($dtVenc)
    {
        $dtFim = new DateTime($dtVenc);
        //CALCULA DTFIM (1 DIA ANTES DE DTVENC)
        $dtFim->sub(new DateInterval('P01D'));
        return $dtFim;
    }

    function getDtVencFaturaMedicao($contrato, $hoje)
    {
        //PEGAR LISTA QUANDO O CONTRATO TEM MEDIÇÃO
        /*echo "<br>TEM MEDIÇÃO<br>";

        echo "<br>regraFatura: ".$contrato['regraFatura'];*/
        $dtVenc = $contrato['diaFatura'];
    
        if($contrato['regraFatura'] == 1) // se tiver dia fixo, verificar se é o dia de hoje (dia atual) 
        {
            $txtDate = $dtVenc . $hoje->format("m-d");
            $dtVenc = new DateTime($txtDate);
            
        }else if($contrato['regraFatura'] == 2) //se a regra para faturar for dia da semana
        {
            $dtVenc = $this->getExactDate($contrato['semanaDoMes'], $dtVenc, $hoje);    
        }
              
        return $dtVenc;
    }

    function getDtVencFaturaNormal($dtInicio)
    {
        //PEGAR LISTA QUANDO O CONTRATO NÃO TEM MEDIÇÃO
        //$dtVenc = $this->getDataFaturaNormal($arrContrato['alugueis'], $fatura);

        //echo "<br>NÃO TEM MEDIÇÃO<br>";

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
            //echo "dia da semana do contrato é maior que o dia 01 do mês";
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
        $dtNewFatura = new DateTime($dtVenc);
        $dtNewFatura = $dtNewFatura->sub(new DateInterval('P10D')); //subtrai 10 dias da data de vencimento
        //echo " -> data nova Fatura: ".$dtNewFatura->format('Y-m-d') . "<br>";

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

    function getDataToFormFatura($idContract)
    {
        $this->ItemFatura = new InvoiceItemController();

        $arrContrato = $this->fatura->getContracts($idContract);  // PEGA TODOS OS CONTRATOS QUE POSSUEM ALUGUEIS 
        //print_r($arrContrato);
        $arrContrato = json_decode($arrContrato, true);
        $arrContrato = $arrContrato[0];

        if(count($arrContrato['alugueis']) > 0) //se existir aluguel para faturar
        {
            $hoje = new DateTime();

            $paraFaturar = $this->getAlugueisParaFaturar($arrContrato, $hoje);
            //echo "<br> FATURA: ";
            //print_r($paraFaturar);

            /*echo "<br>ALUGUEIS: ";
            print_r($arrContrato['alugueis']);*/

            //ENTÃO ENTRA NA LISTA PARA FATURAR

            //echo "<br><br>FAZER FATURA";  
            $dataNewFatura = [];

            foreach($arrContrato['alugueis'] as $aluguel) //para cada aluguel
            {
                $itemFatura = $this->ItemFatura->getItemFatura($paraFaturar, $aluguel);

                /*if($itemFatura['error'])
                {
                    echo "<br><br>";

                    return json_encode([
                        'error'=>true,
                        'msg'=>'ocorreu algum erro'
                    ]);
                }*/

                if($itemFatura != false) {
                    $dataNewFatura[] = $itemFatura;
                }
                
            }

            return json_encode([
                'fatura'=>$paraFaturar,
                'fatura_itens'=>$dataNewFatura
            ]);

        }else
        {
            return json_encode([
                'error'=>true,
                'msg'=>"CONTRATO SEM ALUGUEL INICIADO",
            ]);
        }
    }

    public function getPDF($id, $destiny){

        $invoice = new Invoice();

        $fatura = $invoice->getValuesToInvoicePDF($id);

        $contract = new Contract();
        $empresa = $contract->getValuesToCompanyPDF();
        //$empresa = $invoice->getValuesToCompanyPDF();
        
        $itens = new InvoiceItemController();

        $listItens = $itens->getValuesToInvoicePDF($id);

        $invoicePDF = new InvoicePDF($fatura, $listItens, $empresa);

        $res = $invoicePDF->show();

        $pdf = new myPDF();

        $file_name = str_replace('/', '-', $res[0]);

        $pdf->createPDF($file_name. ".pdf", $res[1]);
       
        if($destiny == "sendEmail"){
            //print_r($_POST);

            //valida os campos
            $error = $this->verifyFieldsEmail(); //verifica os campos do formulário de email
            $aux = json_decode($error);
    
            if ($aux->error) {
                return $error;
            }
            
            //se estiver tudo ok, então envia o e-mail
            return $pdf->sendEmail($_POST['toAdress'], $_POST['toName'], $_POST['subject'], $_POST['html']);

        }else{
            $pdf->display();
        }
        
    }

}

?>