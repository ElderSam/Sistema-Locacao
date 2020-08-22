<?php

namespace Locacao\Controller;

use \Locacao\Generator;
use \Locacao\Model\User;
use \Locacao\Model\ContractItem;

class ContractItemController extends Generator
{

    //construtor
    public function __construct()
    {
    }

    public function save($update = false) //Add a new ContractItem or Update
    {
        
        User::verifyLogin();
        
        $error = $this->verifyFields($update); //verifica os campos do formulário
        $aux = json_decode($error);

        if ($aux->error) {
            return $error;
        }

        $contractItem = new ContractItem(); //Model

        $contractItem->setData($_POST);

        if ($update) { //se for atualizar
            
            $upd =  $contractItem->update();      
            return $upd;       

        } else { // se for cadastrar novo Fornecedor
            
            $res = $contractItem->insert();
            //print_r($res);           
            return $res;                   
        }
    }


    public function verifyFields($update = false)
    {/*Verifica todos os campos ---------------------------*/

        $errors = array();

        //print_r($_POST);

        if ($_POST["idContrato"] == "") {
                $errors["#contrato"] = "Contrato é obrigatório!";
        }

        if ($_POST["idProduto_gen"] == "") {
            $errors["#codeProduct"] = "Produto é obrigatório!";
        }
        
        if ($_POST["vlAluguel"] == "") {
            $errors["#vlAluguel"] = "Valor do Aluguel é obrigatório!";
        }

        if ($_POST["quantidade"] == "") {
            $errors["#quantidade"] = "Quantidade é obrigatória!";
        }

        if ($_POST["periodoLocacao"] == "") {
            $errors["#periodoLocacao"] = "Período é obrigatório!";
        }


       /* if ($_POST["dtInicio"] == "") {
            $errors["#dtInicio"] = "Data início é obrigatória!";
        }
        
        if ($_POST["dtFinal"] == "") {
            $errors["#dtFinal"] = "Data final é obrigatória!";
        }*/

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

    public function ajax_list_contractItens($requestData, $idContrato)
    {

        $column_search = array("codFornecedor", "nome", "status", "telefone1", "cidade"); //colunas pesquisáveis pelo datatables
        $column_order = array("codFornecedor", "nome", "status", "telefone1", "cidade"); //ordem que vai aparecer (o codigo primeiro)

        //faz a pesquisa no banco de dados
        $contractItem = new ContractItem(); //model

        $datatable = $contractItem->get_datatable($requestData, $column_search, $column_order, $idContrato);

        $data = array();

        foreach ($datatable['data'] as $contractItem) { //para cada registro retornado

            /*if ($contractItem['status'] == 1) {
                $status = "Ativo";
            } else{
                $status = "Inativo";
            }*/

            

            // Ler e criar o array de dados ---------------------
            
            $row = [
                "idItem"=>$contractItem['idItem'],
                "idContrato"=>$contractItem['idContrato'],
                "idProduto_gen"=>$contractItem['idProduto_gen'],
                "descricao"=>$contractItem['descricao'],
                "descCategoria"=>$contractItem['descCategoria'],
                "vlAluguel"=>$contractItem['vlAluguel'],
                "quantidade"=>$contractItem['quantidade'],
                "custoEntrega"=>$contractItem['custoEntrega'],
                "custoRetirada"=>$contractItem['custoRetirada'], 
                "periodoLocacao"=>$contractItem['periodoLocacao'],
                "observacao"=>$contractItem['observacao'],             
            ];

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
        return ContractItem::total();
    }

    public function delete($idContractItem){
       
        $contractItem = new ContractItem();
        echo "id: " . $idContractItem;
        $contractItem->get((int)$idContractItem); //carrega o usuário, para ter certeza que ainda existe no banco
       
        return $contractItem->delete();      
    }

    public function getValuesToBudgetPDF($idOrcamento){

        $items = new ContractItem();
        
        $listItems = $items->getValuesToBudgetPDF($idOrcamento);
          
        for($i=0; $i<count($listItems); $i++){
            //print_r($item);
            
            $periodo = $listItems[$i]['periodoLocacao'];
            $arrayPeriodos = array("diário", "semanal", "quinzenal", "mensal");

            $listItems[$i]['periodoLocacao'] = $arrayPeriodos[$periodo -1]; //pega a string no array referente ao número 
            //echo "<br>periodoLocacao: $periodo => " . $listItems[$i]['periodoLocacao'];   
        }

        return json_encode($listItems);
    }

    public function getValuesToContractPDF($idContrato){

        $items = new ContractItem();
        
        $listItems = $items->getValuesToContractPDF($idContrato);
          
        for($i=0; $i<count($listItems); $i++){
            //print_r($item);
            
            $periodo = $listItems[$i]['periodoLocacao'];
            $arrayPeriodos = array("diário", "semanal", "quinzenal", "mensal");

            $listItems[$i]['periodoLocacao'] = $arrayPeriodos[$periodo -1]; //pega a string no array referente ao número 
            //echo "<br>periodoLocacao: $periodo => " . $listItems[$i]['periodoLocacao'];   
        }

        return json_encode($listItems);
    }

}//end class ContractItemController
