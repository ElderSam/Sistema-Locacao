<?php

namespace Locacao\Model;

use Exception;
use \Locacao\DB\Sql;
use \Locacao\Generator;


class Rent extends Generator{

    public static function listAll(){

        $sql = new Sql();

        $results = $sql->select("SELECT * FROM historicoalugueis ORDER BY nome");
    
        return json_encode($results);
    }

    public function createNewCode(){
        
        $ano = strtotime($this->getdtInicio());
        $ano = date("Y", $ano);

        $sql = new Sql();

        $query = "SELECT MAX(codigo) FROM historicoalugueis 
            WHERE (YEAR(dtInicio) = :ano)";

        $ultimo = $sql->select($query, array(
            ":ano"=>$ano
        ));

        $nextCode = $ultimo[0]['MAX(codigo)'] + 1;

        return $nextCode;

    }

    public function insert(){
    
        $this->setcodigo($this->createNewCode());
        
        $sql = new Sql();
        
        //print_r($_POST);

        if(($this->getproduto_idProduto() != "") && ($this->getcontrato_idContrato() != "") && ($this->getstatus() != "")){

            $results = $sql->select("CALL sp_historicoalugueis_save(:codigo, :contrato_idContrato, :produto_idProduto, :status, :vlAluguel, :dtInicio, :dtFinal, :custoEntrega, :custoRetirada, :observacao)", array(
                ":codigo"=>$this->getcodigo(),
                ":contrato_idContrato"=>$this->getcontrato_idContrato(),
                ":produto_idProduto"=>$this->getproduto_idProduto(), //produto específico
                ":status"=>$this->getstatus(),
                ":vlAluguel"=>$this->getvlAluguel(),
                ":dtInicio"=>$this->getdtInicio(),
                ":dtFinal"=>$this->getdtFinal(),
                ":custoEntrega"=>$this->getcustoEntrega(),
                ":custoRetirada"=>$this->getcustoRetirada(),
                ":observacao"=>$this->getobservacao()
            ));

            if(count($results) > 0){

                $this->setData($results[0]); //carrega atributos desse objeto com o retorno da inserção no banco

                return json_encode($results[0]);

            }else{
                return json_encode([
                    "error"=>true,
                    "msg"=>"Erro ao inserir Aluguel!"
                    ]);
            }
       
        }else{

            return json_encode([
				'error' => true,
				"msg" => "Campos incompletos!"
			]);
        }
    }

    
    public function get($idFornecedor){

        $sql = new Sql();

        $results = $sql->select("SELECT * FROM historicoalugueis WHERE idFornecedor = :idFornecedor", array(
            ":idFornecedor"=>$idFornecedor
        ));

        if(count($results) > 0){
            $this->setData($results[0]);

        }
    }

    public function get_datatable($requestData, $column_search, $column_order){
        
        $query = "SELECT a.*, b.idProduto_esp, b.idProduto_gen, b.codigoEsp FROM historicoalugueis a
            INNER JOIN produtos_esp b ON(a.produto_idProduto = b.idProduto_esp)";

        if (!empty($requestData['search']['value'])) { //verifica se eu digitei algo no campo de filtro

            $first = TRUE;

            foreach ($column_search as $field) {     
               
                $search = strtoupper($requestData['search']['value']); //tranforma em maiúsculo

                //filtra no banco
                if ($first) {
                    $query .= " WHERE ($field LIKE '%$search%'"; //primeiro caso
                    $first = FALSE;
                } else {
                    $query .= " OR $field LIKE '%$search%'";
                }
            } //fim do foreach
            if (!$first) {
                $query .= ")"; //termina o WHERE e a query
            }

        }
        
        $res = $this->searchAll($query);
        $this->setTotalFiltered(count($res));

        //ordenar o resultado
        $query .= " ORDER BY " . $column_order[$requestData['order'][0]['column']] . " " . $requestData['order'][0]['dir'] . 
        "  LIMIT " . $requestData['start'] . " ," . $requestData['length'] . "   "; 
        
        $rents = new Rent();
        //echo $query;
        return array(
            'totalFiltered'=>$this->getTotalFiltered(),
            'data'=>$rents->searchAll($query)
        );
    }

    public function searchAll($query){ //pesquisa genérica (para todos os campos). Recebe uma query

        $sql = new Sql();

        $results = $sql->select($query);

        return $results;

    }

    public static function total() { //retorna a quantidade todal de registros na tabela

        $sql = new Sql();

        $results = $sql->select("SELECT * FROM historicoalugueis");

        return count($results);		
	}
    

    public function update(){
        
        $sql = new Sql();

        $results = $sql->select("CALL sp_historicoalugueisUpdate_save(:idHistoricoAluguel, :contrato_idContrato, :produto_idProduto, :status, :vlAluguel, :dtInicio, :dtFinal, :custoEntrega, :custoRetirada, :observacao)", array(
            ":contrato_idContrato"=>$this->getcontrato_idContrato(),
            ":produto_idProduto"=>$this->getproduto_idProduto(),
            ":status"=>$this->getstatus(),
            ":vlAluguel"=>$this->getvlAluguel(),
            ":dtInicio"=>$this->getdtInicio(),
            ":dtFinal"=>$this->getdtFinal(),
            ":custoEntrega"=>$this->getcustoEntrega(),
            ":custoRetirada"=>$this->getcustoRetirada(),
            ":observacao"=>$this->getobservacao()
        ));

        if(count($results) > 0){

            $this->setData($results[0]); //carrega atributos desse objeto com o retorno da atualização no banco

            return json_encode($results[0]);

        }else{
            return json_encode([
                "error"=>true,
                "msg"=>"Erro ao atualizar Aluguel!"
                ]);
        }

    }

    public function delete(){
      
        $sql = new Sql();

        try{

            $sql->query("CALL sp_historicoalugueis_delete(:idFornecedor)", array(
                ":idFornecedor"=>$this->getidFornecedor()
            ));

            if($this->get($this->getidFornecedor())){

                return json_encode([
                    "error"=>true,
                    "msg"=>"Erro ao excluir Aluguel"
                ]);

            }else{

                return json_encode([
                    "error"=>false,
                ]);
            }

        }catch(Exception $e){

            return json_encode([
                "error"=>true,
                "msg"=>$e->getMessage()
            ]);

        }
       
    }
    
}

