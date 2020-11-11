<?php

namespace Locacao\Controller;

use \Locacao\Generator;
use Locacao\Model\Construction;
use \Locacao\Model\User;

class ConstructionController extends Generator
{

    //construtor
    public function __construct()
    {
    }

  
    public function ajax_list_construction($requestData, $idCliente)
    {

        $column_search = array("codObra", "respObra", "cidade", "endereco"); //colunas pesquisáveis pelo datatables
        $column_order = array("codObra", "respObra", "cidade", "endereco"); //ordem que vai aparecer (o nome primeiro)

        //faz a pesquisa no banco de dados
        $construction = new Construction(); //model

        $datatable = $construction->get_datatable($requestData, $column_search, $column_order, $idCliente);

        $data = array();

        foreach ($datatable['data'] as $construction) { //para cada registro retornado

            $id = $construction['idObra'];

            // Ler e criar o array de dados ---------------------
            $row = array();

            $row[] = $construction['codObra'];
            $row[] = $construction['respObra'];
            $row[] = $construction['cidade'];
            $row[] = $construction['endereco'];
           
           
            $row[] = "<button type='button' title='ver detalhes' class='btn btn-warning btnEdit'
                        onclick='loadConstruction($id);'>
                            <i class='fas fa-bars sm'></i>
                      </button>

                     <button type='button' title='excluir' onclick='deleteReposible($id);'
                        class='btn btn-danger btnDelete'>
                        <i class='fas fa-trash'></i>
                     </button>";

            $data[] = $row;
            
        } //

        //Cria o array de informações a serem retornadas para o Javascript
        $json = array(
            "draw" => intval($requestData['draw']), //para cada requisição é enviado um número como parâmetro
            "recordsTotal" => $this->records_total($idCliente),  //Quantidade de registros que há no banco de dados
            "recordsFiltered" => $datatable['totalFiltered'], //Total de registros quando houver pesquisa
            "data" => $data,  //Array de dados completo dos dados retornados da tabela 
        );

        return json_encode($json); //enviar dados como formato json

    }

    public function records_total($idCliente)
    {

        return Construction::total($idCliente);
    }

    public function save($update = false, $idCliente = 0) // Adicionar novo cliente ou editar
    {


        // $error = $this->verifyFields($update); //verifica os campos do formulário
        // $aux = json_decode($error);

        // if ($aux->error) {
        //     return $error;
        // }
       
        $construction = new Construction(); //Model

        $construction->setData($_POST);

        if ($update) { //se for atualizar
            return $construction->update($idCliente);

        } else { // se for cadastrar novo usuário
            return $construction->insert($idCliente);
        }
    }


    // public function verifyFields($update = false)
    // {/*Verifica todos os campos ---------------------------*/

    //     $errors = array();

    //     // if($_POST["email1"] != ""){
    //     //     if($this->validaEmail($_POST["email1"]) == false){ //se o e-mail estiver correto
    //     //         $errors["#email1"] = "E-mail Incorreto!";
    //     //     }
    //     // }    
        
    //     // if($_POST["email2"] != ""){
    //     //     if($this->validaEmail($_POST["email2"]) == false){ //se o e-mail estiver correto
    //     //         $errors["#email2"] = "E-mail Incorreto!";
    //     //     }
    //     // }
        

    //     $exists = 0;

    //     $exists = Construction::searchCompany($_POST["respObra"]);
    //     if (count($exists) > 0) { //se existe cliente igual já registrado

    //         if ($update) {
    //             foreach ($exists as $reposible) {

    //                 //Ver se o nome que foi retornado é igual ao que está sendo enviado, descosiderando o registro que o mesmo ID
    //                 if (($_POST['respObra'] == $reposible['respObra']) && ($_POST['idResp'] != $reposible['idResp'])) {
    //                     $errors["#respObra"] = "Esse Responsável já existe";
    //                     break;
    //                 }
    //             }
    //         } else {
    //             $errors["#respObra"] = "Esse Responsávels já existe";
    //         }

    //     }


    //     if (count($errors) > 0) { //se tiver algum erro de input (campo) do formulário

    //         return json_encode([
    //             'error' => true,
    //             'error_list' => $errors
    //         ]);
    //     } else { //se ainda não tem erro

    //         return json_encode([
    //             'error' => false
    //         ]);

    //         /*if($this->getfoto() == ""){
    //             $json["error_list"]["#desImagePath"] = "Não foi possível fazer Upload da imagem!";               
    //         }*/
    //     }
    // }/* --- fim verificaErros() ---------------------------*/

    // public function validaEmail($email)
    // {
    //     //verifica se e-mail esta no formato correto de escrita
    //     if (!preg_match('/^([a-zA-Z0-9.-_])*([@])([a-z0-9]).([a-z]{2,3})/', $email)) {
    //         return false;
    //     } else {
    //         //Valida o dominio
    //         $dominio = explode('@', $email);
    //         if (!checkdnsrr($dominio[1], 'A')) {
    //             return false;
    //         } else {
    //             return true;
    //         } // Retorno true para indicar que o e-mail é valido
    //     }
    // }





}//end class UserController
