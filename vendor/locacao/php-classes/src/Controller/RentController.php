<?php

namespace Locacao\Controller;

use \Locacao\Generator;
use \Locacao\Model\User;
use \Locacao\Model\Rent;

class RentController extends Generator
{

    //construtor
    public function __construct()
    {
    }



    public function save($update = false) //Add a new Rent or Update
    {
        
        User::verifyLogin();
        
        $error = $this->verifyFields($update); //verifica os campos do formulário
        $aux = json_decode($error);

        if ($aux->error) {
            return $error;
        }

        $rent = new Rent(); //Model

        $rent->setData($_POST);

        if ($update) { //se for atualizar
            
            $upd =  $rent->update();

            
            return $upd;
            

        } else { // se for cadastrar novo Fornecedor
            $res = $rent->insert();
            //print_r($res);
            
            return $res;
                   
        }
    }


    public function verifyFields($update = false)
    {/*Verifica todos os campos ---------------------------*/

        $errors = array();

        //print_r($_POST);

        if ($_POST["contrato_idContrato"] == "") {
                $errors["#contrato"] = "Contrato é obrigatório!";
        }

        /*if ($_POST["produto_idProduto"] == "") { //produto específico
            $errors["#codeProduct"] = "Produto é obrigatório!";
        }*/
        
        if ($_POST["vlAluguel"] == "") {
            $errors["#vlAluguel"] = "Valor do Aluguel é obrigatório!";
        }

        if ($_POST["dtInicio"] == "") {
            $errors["#dtInicio"] = "Data início é obrigatória!";
        }
        
        /* if ($_POST["dtFinal"] == "") {
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

    public function ajax_list_rents($requestData)
    {

        $column_search = array("codFornecedor", "nome", "status", "telefone1", "cidade"); //colunas pesquisáveis pelo datatables
        $column_order = array("codFornecedor", "nome", "status", "telefone1", "cidade"); //ordem que vai aparecer (o codigo primeiro)

        //faz a pesquisa no banco de dados
        $rent = new Rent(); //model

        $datatable = $rent->get_datatable($requestData, $column_search, $column_order);

        $data = array();

        foreach ($datatable['data'] as $rent) { //para cada registro retornado

            if ($rent['status'] == 1) {
                $status = "Ativo";
            } else{
                $status = "Inativo";
            }

            $id = $rent['idFornecedor'];

            // Ler e criar o array de dados ---------------------
            $row = array();

            $row[] = $rent['codFornecedor'];
            $row[] = $rent['nome'];
            $row[] = $status;
            $row[] = $rent['telefone1'];
            $row[] = $rent['cidade'];
            $row[] = "<button type='button' title='ver detalhes' class='btn btn-warning btnEdit'
                onclick='loadRent($id);'>
                    <i class='fas fa-bars sm'></i>
                </button>
                <button type='button' title='excluir' onclick='deleteRent($id);'
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

    public function records_total()
    {

        return Rent::total();
    }



    public function delete($idrent){
       
        $rent = new Rent();
        echo "id: " . $idrent;
        $rent->get((int)$idrent); //carrega o usuário, para ter certeza que ainda existe no banco
       
        return $rent->delete();
        
        
    }
}//end class RentController
