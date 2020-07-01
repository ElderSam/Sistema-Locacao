<?php

namespace Locacao\Controller;

use \Locacao\Utils\myPDF;
use \Locacao\Generator;
use \Locacao\Model\User;
use \Locacao\Model\Budget;
use \Locacao\Model\Contract;
use \Locacao\Controller\ContractItemController;
use \Locacao\Utils\PDFs\BudgetPDF;

class BudgetController extends Generator
{
    //construtor
    public function __construct()
    {
    }

    public function save($update = false){ //Add a new Budget or Update
    
        
        User::verifyLogin();
        
        $error = $this->verifyFields($update); //verifica os campos do formulário
        $aux = json_decode($error);

        if ($aux->error) {
            return $error;
        }

        $budget = new Budget(); //Model

        if(!isset($_POST['idContrato'])){ //se for um orçamento

            $_POST['idContrato'] = $_POST['idOrcamento']; //muda para a classe model reconhecer
            
            if($_POST['status'] == 3){ //se o status for Aprovado
                date_default_timezone_set('America/Sao_Paulo');
                $_POST['dtAprovacao'] = date('y-m-d');
                
                //Aqui foi mudado o valor de código de Orçamento para código de Contrato
                $_POST['codigo'] = Contract::showsNextCode($_POST['dtEmissao']);
            }else{
                //pega apenas o número do orçamento (tira o /ano)
                $auxArr = explode('/', $_POST['codigo']);
                $_POST['codigo'] = $auxArr[0];
        
            }

        }

       
        $budget->setData($_POST);


        if ($update) { //se for atualizar
            
            return $budget->update();


        } else { // se for cadastrar novo Fornecedor
            
            $res = $budget->insert();        
           
            return $res;
            
        }
    }


    public function verifyFields($update = false)
    {/*Verifica todos os campos ---------------------------*/
       //print_r($_POST);

        $errors = array();

        if ($_POST["codigo"] == "") {
            $errors["#codigo"] = "Código é obrigatório!";
        }

        /*if ($_POST["idCliente"] == "") {
            $errors["#idCliente"] = "Cliente é obrigatório!";
        }*/

        if (($_POST["idCliente"] != "") && ($_POST["obra_idObra"] == "")) { //se escolher um cliente, então é obrigatório ter uma Obra (pois o Orçamento/Contrato vai estar relacionado diretamente à obra)
            $errors["#obra_idObra"] = "Obra é obrigatória!";
        }

        if ($_POST["dtEmissao"] == "") {
            $errors["#dtEmissao"] = "Data de Emissão é obrigatória!";
        }

        if ($_POST["solicitante"] == "") {
            $errors["#solicitante"] = "Nome do solicitante é obrigatório!";
        }
                
        if($_POST["email"] != "" && $this->validaEmail($_POST["email"]) == false){ //se o e-mail estiver correto
            $errors["#email"] = "E-mail Incorreto!";
        }

        if ($_POST["status"] == "") {
            $errors["#status"] = "Status é obrigatório!";
        }

        if(($update) && isset($_POST["idContrato"])){ //se for atualizar um Contrato
            
            if ($_POST["dtInicio"] == "") {
                $errors["#dtInicio"] = "data obrigatória!";

            }

            if ($_POST["prazoDuracao"] == "") {
                $errors["#prazoDuracao"] = "Prazo é obrigatório!";
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

    public function validaEmail($email)
    {
        //verifica se e-mail esta no formato correto de escrita
        if (!preg_match('/^([a-zA-Z0-9.-_])*([@])([a-z0-9]).([a-z]{2,3})/', $email)) {
            return false;
        } else {
            //Valida o dominio
            $dominio = explode('@', $email);

            if (!checkdnsrr($dominio[1], 'A')) { //OBS: se não estiver com Internet, a função vai retornar false, pois ela usa a conexão para verificar se o domínio existe
                return false;
            } else {
                return true;
            } // Retorno true para indicar que o e-mail é valido
        }
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
    public function ajax_list_budgets($requestData) //carrega tabela de orçamentos
    {

        $column_search = array("statusOrcamento", "codContrato", "dtEmissao"); //colunas pesquisáveis pelo datatables
        $column_order = array("statusOrcamento", "codContrato", "dtEmissao"); //ordem que vai aparecer (o codigo primeiro)

        //faz a pesquisa no banco de dados
        $budget = new Budget(); //model

        $datatable = $budget->get_datatable_budgets($requestData, $column_search, $column_order);

        $data = array();

        //print_r($datatable);

        foreach ($datatable['data'] as $budget) { //para cada registro retornado

            $statusOrcamento = '';

            if ($budget['statusOrcamento'] == 0) {
                $statusOrcamento = "Pendente";

            } else if ($budget['statusOrcamento'] == 1){
                $statusOrcamento = "Arquivado";
            }

            if($budget['codObra'] != NULL){
                $obraCliente = $budget['codObra'] . " - " . $budget['nome'];
           
            }else{
                $obraCliente = $budget['nomeEmpresa'];
            }  

            // Ler e criar o array de dados ---------------------
            $row = array();

            $row = [
                "idContrato"=>$budget['idContrato'],
                "statusOrcamento"=>$statusOrcamento,
                "codContrato"=>$budget['codContrato'],
                "dtEmissao"=>date('d/m/Y', strtotime($budget['dtEmissao'])),
                "obraCliente"=>$obraCliente
            ];

            /*$row[] = "<strong style='color: $color'>$statusOrcamento</strong>";
            $row[] = $budget['codContrato'];
            $row[] = date('d/m/Y', strtotime($budget['dtEmissao']));
            $row[] = $obraCliente;
            $row[] = "<button type='button' title='ver detalhes' class='btn btn-warning btnEdit'
                onclick='loadBudget($id);'>
                    <i class='fas fa-bars sm'></i>
                </button>
                <button type='button' title='excluir' onclick='deleteBudget($id);'
                    class='btn btn-danger btnDelete'>
                    <i class='fas fa-trash'></i>
                </button>";*/

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

        return Budget::total();
    }

    public function delete($idBudget){
       
        $budget = new Budget();
        //echo "id: " . $idBudget;
        $budget->get((int)$idBudget); //carrega o usuário, para ter certeza que ainda existe no banco
       
        return $budget->delete();       
    }

    public function getPDF($id, $destiny)
    {   
        $budget = new Budget();
    
        $orcamento = $budget->getValuesToBudgetPDF($id);
        //echo $orcamento;
        $items = new ContractItemController();

        $listItems = $items->getValuesToBudgetPDF($id);     
        //echo $listItems;

        $budgetPDF = new BudgetPDF($orcamento, $listItems);
    
        $res = $budgetPDF->show();
        //echo $content;

        $pdf = new myPDF();
        $file_name = str_replace('/', '-', $res[0]); //substitui / por - (porque quando baixa o PDF, não reconhece a barra no nome do arquivo)
        
        $pdf->createPDF($file_name.".pdf", $res[1]); //$res[1]  é o conteúdo do PDF

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

    public function verifyFieldsEmail(){  //valida os campos
        $errors = array();

        $email = $_POST['toAdress'];
        if($email == ""){ //se o e-mail estiver correto){
            $errors["#toAdress"] = "E-mail é obrigatório!";

        }else if($this->validaEmail($email) == false){
            $errors["#toAdress"] = "E-mail inválido!";
        }

        if($_POST['subject'] == ""){
            $errors["#subject"] = "Assunto é obrigatório!";
        }

        if($_POST['html'] == ""){
            $errors["#html"] = "Mensagem é obrigatória!";
        }

        if (count($errors) > 0) { //se tiver algum erro de input (campo) do formulário de email

            return json_encode([
                'error' => true,
                'error_list' => $errors,
                'msg'=>'campos incompletos/inválidos!'
            ]);
        } else { //se ainda não tem erro

            return json_encode([
                'error' => false
            ]);
        }
    }

}//end class BudgetController
