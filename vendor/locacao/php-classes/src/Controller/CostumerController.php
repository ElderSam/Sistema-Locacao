<?php

namespace Locacao\Controller;

use \Locacao\Generator;
use \Locacao\Model\Costumer;
use \Locacao\Model\User;

class CostumerController extends Generator
{

    //construtor
    public function __construct()
    {
    }


  
    public function ajax_list_costumers($requestData)
    {

        $column_search = array("nome", "status", "cpf", "cnpj", "tipoCliente"); //colunas pesquisáveis pelo datatables
        $column_order = array("nome", "status", "cpf", "cnpj", "tipoCliente"); //ordem que vai aparecer (o nome primeiro)

        //faz a pesquisa no banco de dados
        $costumers = new Costumer(); //model

        $datatable = $costumers->get_datatable($requestData, $column_search, $column_order);

        $data = array();

        foreach ($datatable['data'] as $costumer) { //para cada registro retornado

            if ($costumer['status'] == 0) {
                $status = "Inativo";
            } else {
                $status = "Ativo";
            }

            if($costumer['cpf'] == ""){
                $documento = $costumer["cnpj"];
            }else{
                $documento = $costumer["cpf"];
            }

            if ($costumer['tipoCliente'] == "F") {
                $tipoCliente = "Física";
            } else {
                $tipoCliente = "Jurídica";
            }

            $id = $costumer['idCliente'];

            // Ler e criar o array de dados ---------------------
            $row = array();

            $row[] = $costumer['nome'];
            $row[] = $status;
            $row[] = $documento;
            $row[] = $tipoCliente;
           
            $row[] = "<a class='btn btn-primary' title='responsáveis de obras' href='/reposibleWorks/json/$id' role='button'><i class='fas fa-hard-hat'></i></a>
                      <a class='btn btn-success' title='obras do cliente' href='/construction/$id' role='button'><i class='fas fa-hammer'></i></a>  
                      <button type='button' title='ver detalhes' class='btn btn-warning btnEdit'
                      onclick='loadCostumer($id);'>
                        <i class='fas fa-bars sm'></i>
                      </button>

                    <button type='button' title='excluir' onclick='deleteCostumer($id);'
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

        return Costumer::total();
    }

    public function save($update = false) // Adicionar novo cliente ou editar
    {


        $error = $this->verifyFields($update); //verifica os campos do formulário
        $aux = json_decode($error);

        if ($aux->error) {
            return $error;
        }

        $costumer = new Costumer(); //Model

        $costumer->setData($_POST);

        if ($update) { //se for atualizar
            return $costumer->update();

        } else { // se for cadastrar novo usuário
            return $costumer->insert();
        }
    }


    public function verifyFields($update = false)
    {/*Verifica todos os campos ---------------------------*/

        if ($_POST["nome"] == "") {
            $errors["#nome"] = "Nome do cliente é obrigatório";
        }

        $errors = array();
        if($_POST["email1"] != ""){
            if($this->validaEmail($_POST["email1"]) == false){ //se o e-mail estiver correto
                $errors["#email1"] = "E-mail Incorreto!";
            }
        }    
        
        if($_POST["email2"] != ""){
            if($this->validaEmail($_POST["email2"]) == false){ //se o e-mail estiver correto
                $errors["#email2"] = "E-mail Incorreto!";
            }
        }
        
        if ($_POST["tipoCliente"] == "") {
            $errors["#tipoCliente"] = " Tipo de Cliente é obrigatório!";
        }

        $exists = 0;

        $exists = Costumer::searchCompany($_POST["nome"]);
        if (count($exists) > 0) { //se existe cliente igual já registrado

            if ($update) {
             
                foreach ($exists as $company) {

                    //Ver se o nome que foi retornado é igual ao que está sendo enviado, descosiderando o registro que o mesmo ID
                    if (($_POST['nome'] == $company['nome']) && ($_POST['idCliente'] != $company['idCliente'])) {
                        $errors["#nome"] = "Esse cliente já existe";
                        break;
                    }
                }
            } else {
                $errors["#nome"] = "Esse cliente já existe";
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

            /*if($this->getfoto() == ""){
                $json["error_list"]["#desImagePath"] = "Não foi possível fazer Upload da imagem!";               
            }*/
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
            if (!checkdnsrr($dominio[1], 'A')) {
                return false;
            } else {
                return true;
            } // Retorno true para indicar que o e-mail é valido
        }
    }





}//end class UserController
