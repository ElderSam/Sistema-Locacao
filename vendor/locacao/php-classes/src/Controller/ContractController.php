<?php

namespace Locacao\Controller;

use \Locacao\Model\User;
use \Locacao\Model\Contract;
use \Locacao\Controller\BudgetController;
use Locacao\Utils\myPDF;
use Locacao\Utils\PDFs\ContractPDF;

class ContractController extends BudgetController
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

        //pega apenas o número do orçamento (tira o /ano)
        $auxArr = explode('/', $_POST['codigo']);
        $_POST['codigo'] = $auxArr[0];
        
        $contract->setData($_POST);

       
        if ($update) { //se for atualizar
           $auxcod = $contract->showsNextCode($_POST["dtEmissao"]);
           $contract->setcodigo($auxcod);
           
           return $contract->update(); //do método pai 'Budget'


        } else { // se for cadastrar novo Contrato
            
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

        if ($_POST["idCliente"] == "") {
            $errors["#idCliente"] = "Cliente é obrigatório!";
        }

        if ($_POST["obra_idObra"] == "") { //é obrigatório ter uma Obra (pois o Contrato vai estar relacionado diretamente à obra)
            $errors["#obra_idObra"] = "Obra é obrigatória!";
        }

        if ($_POST["dtEmissao"] == "") {
            $errors["#dtEmissao"] = "Data de Emissão é obrigatória!";
        }
  
        if ($_POST["dtInicio"] == "") {
            $errors["#dtInicio"] = "Data de Início é obrigatória!";
        }

        if ($_POST["solicitante"] == "") {
            $errors["#solicitante"] = "Nome do solicitante é obrigatório!";
        }
                
        if($_POST["email"] != "" && parent::validaEmail($_POST["email"]) == false){ //se o e-mail estiver correto
            $errors["#email"] = "E-mail Incorreto!";
        }

        if ($_POST["status"] == "") {
            $errors["#status"] = "Status é obrigatório!";
        }

        if(empty($_POST["temMedicao"])){
            $_POST["temMedicao"] = 0;
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
    public function ajax_list_contracts($requestData) //carrega tabela de contratos
    {

        $column_search = array("a.statusOrcamento", "a.codContrato", "a.dtEmissao", "b.codObra", "a.nomeEmpresa", "c.nome"); //colunas pesquisáveis pelo datatables
        $column_order = array("a.statusOrcamento", "a.codContrato", "a.dtEmissao"); //ordem que vai aparecer (o codigo primeiro)


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

    public function getPDF($id, $destiny){

        $contract = new Contract();

        $contrato = $contract->getValuesToContractPDF($id);

        $empresa = $contract->getValuesToCompanyPDF();
        
        $itens = new ContractItemController();

        $listItens = $itens->getValuesToContractPDF($id);

        $contractPDF = new ContractPDF($contrato, $listItens, $empresa);

        $res = $contractPDF->show();

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
}//end class ContractController
