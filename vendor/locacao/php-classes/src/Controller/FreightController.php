<?php

namespace Locacao\Controller;

use \Locacao\Generator;
use \Locacao\Model\User;
use \Locacao\Model\Freight;

class FreightController extends Generator
{
    //construtor
    public function __construct()
    {
    }

    public function save($update = false) //Add a new Freight or Update
    {
        User::verifyLogin();
        
        $error = $this->verifyFields($update); //verifica os campos do formulário
        $aux = json_decode($error);

        if ($aux->error) {
            return $error;
        }

        $freight = new Freight(); //Model

        $freight->setData($_POST); 

        if ($update) { //se for atualizar
            return $freight->update(); 

        } else { // se for cadastrar novo Frete
            return json_encode($freight->insert());
        }
    }

    
    public function verifyFields($update = false)
    {/*Verifica todos os campos ---------------------------*/

        $errors = array();

        //print_r($_POST);

        //CAMPOS OBRIGATÓRIOS: idLocacao, tipo_frete, status e data_hora

        if(!$update){
            if($_POST["idLocacao"] == "") {
                $errors["#idLocacao"] = "Id Locação é obrigatório";
            }
        }

        if ($_POST["tipo_frete"] == "") {
            $errors["#tipo_frete"] = "Tipo de frete é obrigatório!";
        }

        if($_POST["status"] == "") {
            $errors["#status"] = "Status é obrigatório";
        }
        
        if ($_POST["data_hora"] == "") {
            $errors["#data_hora"] = "Data e hora são obrigatórios!";
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

    public function ajax_list_freights($requestData)
    {
        $column_search = array("tipo_frete", "status", "data_hora"); //colunas pesquisáveis pelo datatables
        $column_order = array("tipo_frete", "status", "data_hora"); //ordem que vai aparecer

        //faz a pesquisa no banco de dados
        $freight = new Freight(); //model

        $datatable = $freight->get_datatable($requestData, $column_search, $column_order);

        $data = array();

        $count = $requestData['start'];
        foreach ($datatable['data'] as $freight) { //para cada registro retornado
            //print_r($freight);

            // Ler e criar o array de dados ---------------------
            $row = array();
          
            $count++;
                  
            $row = [
                "count"=>$count,
                "id"=>$freight['id'],      
                "idLocacao"=>$freight['idLocacao'],
                "tipo_frete"=>$freight['tipo_frete'],
                "status"=>$freight['status'],
                "data_hora"=>$freight['data_hora'],
                "observacao"=>$freight['observacao'],
            ];

            $data[] = $row;
        } 

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
        return Freight::total();
    }
}