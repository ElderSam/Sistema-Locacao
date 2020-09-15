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

        } else { // se for cadastrar novo Aluguel/Locação

            $res = [];

            foreach($this->getarrProductsEsp() as $item) {
                //echo " id: $item->id";

                $rent->setproduto_idProduto($item->id); //set atribute to Model

                $insert = ($rent->insert());
                $res[] = $insert;

                $aux = json_decode($insert);

                if (isset($aux->error) && $aux->error) {
                    //print_r($aux);
                    break;
                }
            } 
                
            return json_encode($res);
                   
        }
    }


    public function verifyFields($update = false)
    {/*Verifica todos os campos ---------------------------*/

        $errors = array();

        //print_r($_POST);

        //CAMPOS OBRIGATÓRIOS: cliente, contrato_idContrato, itens, status, dtInicio, vlAluguel, custoEntrega, custoRetirada, quantidade, arrSelectedProductsEsp

        if(!$update){
            if($_POST["cliente"] == "") {
                $errors["#cliente"] = "Cliente é obrigatório";
            }
    
            if (!isset($_POST["contrato_idContrato"]) || ($_POST["contrato_idContrato"] == "")) {
                    $errors["#contrato_idContrato"] = "Contrato é obrigatório!";
            }
    
            if($_POST["itens"] == "") {
                $errors["#itens"] = "Itens é obrigatório";
            }
        }

        if($_POST["status"] == "") {
            $errors["#status"] = "Status é obrigatório";
        }

        if ($_POST["dtInicio"] == "") {
            $errors["#dtInicio"] = "Data início é obrigatória!";
        }
        
        if ($_POST["vlAluguel"] == "") {
            $errors["#vlAluguel"] = "Valor do Aluguel é obrigatório!";
        }

        if($_POST["custoEntrega"] == "") {
            $errors["#custoEntrega"] = "Valor de Entrega é obrigatório";
        }
        
        if($_POST["custoRetirada"] == "") {
            $errors["#custoRetirada"] = "Valor de Retirada é obrigatório";
        }

        if(!$update){
            $this->setarrProductsEsp(json_decode($_POST["arrSelectedProductsEsp"])); //JSON -> array()
        
            $count = count($this->getarrProductsEsp());
    
            if($_POST["quantidade"] == "") {
                $errors["#quantidade"] = "Quantidade é obrigatória";
    
            }else if ($count != $_POST["quantidade"]) { //produto específico
                           
                // $quant = $_POST["quantidade"];       
                // echo "quantidadde:  $quant, count: $count";
                $errors["#list1"] = "Precisa selecionar a quantidade de produtos escolhida!";
            }  
    
        }/*else {
            print_r($_POST);

            //verificar se existe apenas um produto selecionado (checkbox)
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

    $column_search = array("codigo"/*, "produto"*/, "status", "dtInicio", "dtFinal"/*, "cliente", "contrato"*/ ); //colunas pesquisáveis pelo datatables
    $column_order = array("codigo"/*, "produto"*/, "status", "dtInicio", "dtFinal"/*, "cliente", "contrato"*/ ); //ordem que vai aparecer (o codigo primeiro)

        //faz a pesquisa no banco de dados
        $rent = new Rent(); //model

        $datatable = $rent->get_datatable($requestData, $column_search, $column_order);

        $data = array();

        foreach ($datatable['data'] as $rent) { //para cada registro retornado
            //print_r($rent);

            // Ler e criar o array de dados ---------------------
            $row = array();
                        
            $row = [
                "id"=>$rent['idHistoricoAluguel'],
                "codigo"=>$rent['codigo'],
                
                //produto
                "idProduto_gen"=>$rent['idProduto_gen'],
                "codigoEsp"=>$rent['codigoEsp'],

                "status"=>$rent['status'],
                "dtInicio"=>$rent['dtInicio'],
                "dtFinal"=>$rent['dtFinal'],
                /*"status"=>$rent['status'],
                "status"=>$rent['status'],*/
                /*cliente
                contrato*/               
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

        return Rent::total();
    }



    public function delete($idrent){
       
        $rent = new Rent();
        //echo "id: " . $idrent;
        $rent->get((int)$idrent); //carrega o usuário, para ter certeza que ainda existe no banco
       
        return $rent->delete();
        
        
    }
}//end class RentController
