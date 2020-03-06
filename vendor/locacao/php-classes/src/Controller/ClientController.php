<?php

namespace Locacao\Controller;

use \Locacao\Generator;
use \Locacao\Model\Client;

class ClientController extends Generator
{

    //construtor
    public function __construct()
    {
    }


  
    public function ajax_list_clients($requestData)
    {

        $column_search = array("nome", "status", "cpf", "cnpj", "tipoCliente"); //colunas pesquisáveis pelo datatables
        $column_order = array("nome", "status", "cpf", "cnpj", "tipoCliente"); //ordem que vai aparecer (o nome primeiro)

        //faz a pesquisa no banco de dados
        $clients = new Client(); //model

        $datatable = $clients->get_datatable($requestData, $column_search, $column_order);

        $data = array();

        foreach ($datatable['data'] as $clients) { //para cada registro retornado

            if ($clients['status'] == 0) {
                $isAdm = "Inativo";
            } else {
                $isAdm = "Ativo";
            }

            $id = $clients['idCliente'];

            // Ler e criar o array de dados ---------------------
            $row = array();

            $row[] = $clients['nome'];
            $row[] = $isAdm;

            if($clients['cpf'] === NULL){
                $clients['cpf'] = " ";
            }else{
                $row[] = $clients['cpf'];
            }

            if($clients['cnpj'] === NULL){
                $clients['cnpj'] = " ";
            }else{
                $row[] = $clients['cnpj'];
            }

            $row[] = $clients['tipoCliente'];
           
            $row[] = "<button type='button' title='ver detalhes' class='btn btn-warning btnEdit'
                onclick='loadCostumer($id);'>
                    <i class='fas fa-bars sm'></i>
                </button>
                <button type='button' title='excluir' onclick='deleteUser($id);'
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

        return CLient::total();
    }
}//end class UserController
