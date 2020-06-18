<?php

namespace Locacao\Controller;

use \Locacao\Model\Contract;
use \Locacao\Controller\BudgetController;

class ContractController extends BudgetController
{
    //construtor
    public function __construct()
    {
    }

    /*-------------------------------- DataTables -------------------------------------------------------------------*/

    /* CAMPOS VIA POST (Para trabalhar como DataTables)

		$_POST['search']['value'] = Campo para busca
		$_POST['order'] = [['0, 'asc']]
			$_POST['order'][0]['column'] = index da coluna
			$_POST['order'][0]['dir'] = tipo de ordenação (asc, desc)
		$_POST['length'] = Quantos campos mostrar
		$_POST['length'] = Qual posição começar
    */
    
    // lista todos os Orçamentos
    public function ajax_list_contracts($requestData) //carrega tabela de contratos
    {

        $column_search = array("statusOrcamento", "codContrato", "dtEmissao"); //colunas pesquisáveis pelo datatables
        $column_order = array("statusOrcamento", "codContrato", "dtEmissao"); //ordem que vai aparecer (o codigo primeiro)

        //faz a pesquisa no banco de dados
        $contract = new Contract(); //model

        $datatable = $contract->get_datatable_contracts($requestData, $column_search, $column_order);

        $data = array();

        //print_r($datatable);

        foreach ($datatable['data'] as $contract) { //para cada registro retornado
            
            $statusOrcamento = '';
            
            if ($contract['statusOrcamento'] == 2){
                $statusOrcamento = "Vencido";

            }else if ($contract['statusOrcamento'] == 3){
                $statusOrcamento = "Aprovado";

            }else if ($contract['statusOrcamento'] == 4){
                $statusOrcamento = "Em vigência";

            }else if ($contract['statusOrcamento'] == 5){
                $statusOrcamento = "Encerrado";

            }
            
            if($contract['codObra'] != NULL){
                $obraCliente = $contract['codObra'] . " - " . $contract['nome'];
           
            }else{
                $obraCliente = "";
            }  

            // Ler e criar o array de dados ---------------------
            $row = array();

            $row = [
                "idContrato"=>$contract['idContrato'],
                "statusOrcamento"=>$statusOrcamento,
                "codContrato"=>$contract['codContrato'],
                "dtEmissao"=>date('d/m/Y', strtotime($contract['dtEmissao'])),
                "obraCliente"=>$obraCliente
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
        return Contract::total();
    }

}//end class ContractController
