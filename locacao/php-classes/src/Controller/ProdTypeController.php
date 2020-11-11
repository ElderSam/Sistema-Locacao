<?php

namespace Locacao\Controller;

use \Locacao\Generator;
use \Locacao\Model\ProdType;
use \Locacao\Model\User;

class ProdTypeController extends Generator
{

    //construtor
    public function __construct()
    {
    }


    public function save($update = false) //Add a new ProdType or Update
    {

        User::verifyLogin();

        $error = $this->verifyFields($update); //verifica os campos do formulário
        $aux = json_decode($error);

        if ($aux->error) {
            return $error;
        }

        $prodType = new ProdType(); //Model

        $prodType->setData($_POST);

        if ($update) { //se for atualizar
            return $prodType->update();

        } else { // se for cadastrar novo usuário
            return $prodType->insert();
        }
    }


    public function verifyFields($update = false)
    {/*Verifica todos os campos ---------------------------*/

        $errors = array();

        $errors = array();

        if ($_POST["descTipo"] == "") {
            $errors["#descTipo"] = "Descrição é obrigatória!";
        }
        if ($_POST["idCategoria"] == "") {
            $errors["#idCategoria"] = "Categoria é obrigatória!";
        }
        if ($_POST["codTipo"] == "") {
            $errors["#codTipo"] = "Código é obrigatório!";
        }
        if (($_POST["ordem_tipo"] == "") && (!$update)) {
            $errors["#ordem_tipo"] = "ordem do tipo é obrigatória!";
        }


        $exists = ProdType::searchDesc($_POST["descTipo"]);
        if (count($exists) > 0) { //se existe nome completo igual já registrado

            if ($update) {
                foreach ($exists as $prodType) {

                    if (($_POST['descTipo'] == $prodType['descTipo']) && ($_POST['id'] != $prodType['id'])) {
                        $errors["#descTipo"] = "Já existe um tipo com essa Descrição";
                        break;
                    }
                }
            } else {
                $errors["#descTipo"] = "Já existe um tipo com essa Descrição";
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

    public function ajax_list_prodTypes($requestData)
    {

        $column_search = array("descCategoria", "ordem_tipo", "codTipo", "descTipo"); //colunas pesquisáveis pelo datatables
        $column_order = array("descCategoria", "ordem_tipo", "codTipo", "descTipo"); //ordem que vai aparecer (o nome primeiro)

        //faz a pesquisa no banco de dados
        $prodTypes = new ProdType(); //model

        $datatable = $prodTypes->get_datatable($requestData, $column_search, $column_order);

        $data = array();

        foreach ($datatable['data'] as $prodType) { //para cada registro retornado

          /*  if ($prodType['administrador'] == 0) {
                $isAdm = "Não";
            } else {
                $isAdm = "Sim";
            }
*/
            $id = $prodType['id'];

            // Ler e criar o array de dados ---------------------
            $row = array();

            $row[] = $prodType['descCategoria'];
            $row[] = $prodType['ordem_tipo'];       
            $row[] = $prodType['codTipo'];
            $row[] = $prodType['descTipo'];
            
            //$row[] = $isAdm;
            $row[] = "<button type='button' title='ver detalhes' class='btn btn-warning btnEdit'
                onclick='loadProdType($id);'>
                    <i class='fas fa-bars sm'></i>
                </button>
                <button type='button' title='excluir' onclick='deleteProdType($id);'
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

        return ProdType::total();
    }
}//end class ProdTypeController
