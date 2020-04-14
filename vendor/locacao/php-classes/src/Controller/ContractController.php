<?php

namespace Locacao\Controller;

use \Locacao\Generator;
use \Locacao\Model\User;
use \Locacao\Model\Contract;

class ContractController extends Generator
{

    //construtor
    public function __construct()
    {
    }

    public function save($update = false) //Add a new Contract or Update
    {
        
        User::verifyLogin();
        
        $error = $this->verifyFields($update); //verifica os campos do formulário
        $aux = json_decode($error);

        if ($aux->error) {
            return $error;
        }

        $contract = new Contract(); //Model

        if(!isset($_POST['idContrato'])){ //se for um orçamento

            $_POST['idContrato'] = $_POST['idOrcamento']; //muda para a classe model reconhecer
            
            if($_POST['status'] == 1){ //se o status for Aprovado
                date_default_timezone_set('America/Sao_Paulo');
                $_POST['dtAprovacao'] = date('y-m-d');
            }

        }

        $contract->setData($_POST);


        if ($update) { //se for atualizar
            
            return $contract->update();


        } else { // se for cadastrar novo Fornecedor
             
            $res = $contract->insert();        
           
            return $res;
            
        }
    }


    public function verifyFields($update = false)
    {/*Verifica todos os campos ---------------------------*/
       // print_r($_POST);

        $errors = array();

        if ($_POST["codigo"] == "") {
            $errors["#codigo"] = "Código é obrigatório!";
        }

        if ($_POST["dtEmissao"] == "") {
            $errors["#dtEmissao"] = "Data de Emissão é obrigatória!";
        }
                
        if ($_POST["status"] == "") {
            $errors["#status"] = "Status é obrigatório!";
        }
        
        if ($_POST["custoEntrega"] == "") {
            $errors["#custoEntrega"] = "Custo Entrega é obrigatório!";
        }


        if ($_POST["custoRetirada"] == "") {
            $errors["#custoRetirada"] = "Custo Retiratda é obrigatório!";
        }

        
        if ($_POST["valorAluguel"] == "") {
            $errors["#valorAluguel"] = "Valor Aluguel é obrigatório!";
        } 

        if(($update) && isset($_POST["idContrato"])){ //se for atualizar um Contrato
            
            if ($_POST["dtInicio"] == "") {
                $errors["#dtInicio"] = "data obrigatória!";

            }

            if ($_POST["dtFim"] == "") {
                $errors["#dtFim"] = "data obrigatória!";
            }
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
    }/* --- fim verificaErros() ---------------------------*/

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
    public function ajax_list_budgets($requestData) //carrega tabela de orçamentos
    {

        $column_search = array("statusOrcamento", "codContrato", "dtEmissao", "Obra", "valorAluguel"); //colunas pesquisáveis pelo datatables
        $column_order = array("statusOrcamento", "codContrato", "dtEmissao", "Obra", "valorAluguel"); //ordem que vai aparecer (o codigo primeiro)

        //faz a pesquisa no banco de dados
        $contract = new Contract(); //model

        $datatable = $contract->get_datatable_budgets($requestData, $column_search, $column_order);

        $data = array();

        foreach ($datatable['data'] as $contract) { //para cada registro retornado

            $statusOrcamento = '';
            $color = '';

            if ($contract['statusOrcamento'] == 0) {
                $statusOrcamento = "Arquivado";
                $color = 'grey';

            } else if ($contract['statusOrcamento'] == 1){
                $statusOrcamento = "Pendente";
                $color = 'orange';


            }

            $obraCliente = $contract['codObra'] . " - " . $contract['nome'];

            $id = $contract['idContrato'];

            // Ler e criar o array de dados ---------------------
            $row = array();

            $row[] = "<strong style='color: $color'>$statusOrcamento</strong>";
            $row[] = $contract['codContrato'];
            $row[] = date('d/m/Y', strtotime($contract['dtEmissao']));
            $row[] = $obraCliente;
            $row[] = 'R$ '.$contract['valorAluguel'];
            $row[] = "<button type='button' title='ver detalhes' class='btn btn-warning btnEdit'
                onclick='loadBudget($id);'>
                    <i class='fas fa-bars sm'></i>
                </button>
                <button type='button' title='excluir' onclick='deleteBudget($id);'
                    class='btn btn-danger btnDelete'>
                    <i class='fas fa-trash'></i>
                </button>";

            $data[] = $row;
        } //

        //Cria o array de informações a serem retornadas para o Javascript
        $json = array(
            "draw" => intval($requestData['draw']), //para cada requisição é enviado um número como parâmetro
            "recordsTotal" => $this->records_total(),  //Quantidade de registros que há no banco de dados
            "recordsFiltered" => $datatable['totalFiltered'], //Total de registros quando houver pesquisa
            "data" => $data,  //Array de dados completo dos dados retornados da tabela 
        );

        return json_encode($json); //enviar dados como formato json

    }

    
    // lista todos os Orçamentos
    public function ajax_list_contracts($requestData) //carrega tabela de contratos
    {

        $column_search = array("statusOrcamento", "codContrato", "dtEmissao", "Obra", "valorAluguel"); //colunas pesquisáveis pelo datatables
        $column_order = array("statusOrcamento", "codContrato", "dtEmissao", "Obra", "valorAluguel"); //ordem que vai aparecer (o codigo primeiro)

        //faz a pesquisa no banco de dados
        $contract = new Contract(); //model

        $datatable = $contract->get_datatable_contracts($requestData, $column_search, $column_order);

        $data = array();

        foreach ($datatable['data'] as $contract) { //para cada registro retornado
            
            $statusOrcamento = '';
            $color = '';
            
            if ($contract['statusOrcamento'] == 2){
                $statusOrcamento = "Vencido";
                $color = 'red';

            }else if ($contract['statusOrcamento'] == 3){
                $statusOrcamento = "Aprovado";
                $color = 'orange';

            }else if ($contract['statusOrcamento'] == 4){
                $statusOrcamento = "Em vigência";
                $color = 'green';

            }else if ($contract['statusOrcamento'] == 5){
                $statusOrcamento = "Encerrado";
                $color = 'grey';

            }

            $obraCliente = $contract['codObra'] . " - " . $contract['nome'];

            $id = $contract['idContrato'];

            // Ler e criar o array de dados ---------------------
            $row = array();

            $row[] = "<strong style='color: $color'>$statusOrcamento</strong>";
            $row[] = $contract['codContrato'];
            $row[] = date('d/m/Y', strtotime($contract['dtEmissao']));
            $row[] = $obraCliente;
            $row[] = 'R$ '.$contract['valorAluguel'];
            $row[] = "<button type='button' title='ver detalhes' class='btn btn-warning btnEdit'
                onclick='loadContract($id);'>
                    <i class='fas fa-bars sm'></i>
                </button>";

            $data[] = $row;
        } //

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



    public function delete($idcontract){
       
        $contract = new Contract();
        //echo "id: " . $idcontract;
        $contract->get((int)$idcontract); //carrega o usuário, para ter certeza que ainda existe no banco
       
        return $contract->delete();
        
        
    }
}//end class ContractController
