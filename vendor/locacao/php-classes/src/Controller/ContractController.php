<?php

namespace Locacao\Controller;

use \Locacao\Utils\myPDF;
use \Locacao\Generator;
use \Locacao\Model\User;
use \Locacao\Model\Contract;
use \Locacao\Controller\ContractItemController;
use \Locacao\Utils\PDFs\BudgetPDF;

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

        //pega apenas o número do orçamento (tira o /ano)
        $auxArr = explode('/', $_POST['codigo']);
        $_POST['codigo'] = $auxArr[0];
        
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

        $column_search = array("statusOrcamento", "codContrato", "dtEmissao", "Obra"); //colunas pesquisáveis pelo datatables
        $column_order = array("statusOrcamento", "codContrato", "dtEmissao", "Obra"); //ordem que vai aparecer (o codigo primeiro)

        //faz a pesquisa no banco de dados
        $contract = new Contract(); //model

        $datatable = $contract->get_datatable_budgets($requestData, $column_search, $column_order);

        $data = array();

        //print_r($datatable);

        foreach ($datatable['data'] as $contract) { //para cada registro retornado

            $statusOrcamento = '';

            if ($contract['statusOrcamento'] == 0) {
                $statusOrcamento = "Arquivado";

            } else if ($contract['statusOrcamento'] == 1){
                $statusOrcamento = "Pendente";
            }

            if($contract['codObra'] != NULL){
                $obraCliente = $contract['codObra'] . " - " . $contract['nome'];
           
            }else{
                $obraCliente = $contract['nomeEmpresa'];
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

            /*$row[] = "<strong style='color: $color'>$statusOrcamento</strong>";
            $row[] = $contract['codContrato'];
            $row[] = date('d/m/Y', strtotime($contract['dtEmissao']));
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

    
    // lista todos os Orçamentos
    public function ajax_list_contracts($requestData) //carrega tabela de contratos
    {

        $column_search = array("statusOrcamento", "codContrato", "dtEmissao", "Obra"); //colunas pesquisáveis pelo datatables
        $column_order = array("statusOrcamento", "codContrato", "dtEmissao", "Obra"); //ordem que vai aparecer (o codigo primeiro)

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

            /*$row[] = "<strong style='color: $color'>$statusOrcamento</strong>";
            $row[] = $contract['codContrato'];
            $row[] = date('d/m/Y', strtotime($contract['dtEmissao']));
            $row[] = $obraCliente;
            $row[] = "<button type='button' title='ver detalhes' class='btn btn-warning btnEdit'
                onclick='loadContract($id);'>
                    <i class='fas fa-bars sm'></i>
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

        return Contract::total();
    }



    public function delete($idcontract){
       
        $contract = new Contract();
        //echo "id: " . $idcontract;
        $contract->get((int)$idcontract); //carrega o usuário, para ter certeza que ainda existe no banco
       
        return $contract->delete();       
    }

    public function getPDF($id, $destiny)
    {   
        $contract = new Contract();
    
        $orcamento = $contract->getValuesToBudgetPDF($id);
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
            return $pdf->sendEmail($_POST['username'], $_POST['password'], $_POST['name_from'], $_POST['toAdress'], $_POST['toName'], $_POST['subject'], $_POST['html']);

        }else{
            $pdf->display();
        }
        
    }

    public function verifyFieldsEmail(){  //valida os campos
        $errors = array();

        $emails = array([$_POST['username'], 'username'], [$_POST['toAdress'], 'toAdress']);
            
        for($i=0; $i<count($emails); $i++){
            
            $field = $emails[$i][0];
            $txt = $emails[$i][1];

            if($field == ""){ //se o e-mail estiver correto){
                $errors["#$txt"] = "E-mail é obrigatório!";

            }else if($this->validaEmail($field) == false){
                $errors["#$txt"] = "E-mail inválido!";
            }   
        }

        if($_POST['password'] == ""){
            $errors["#password"] = "Senha é obrigatória!";
        }

        if($_POST['name_from'] == ""){
            $errors["#name_from"] = "Destinatário é obrigatório!";
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

}//end class ContractController
