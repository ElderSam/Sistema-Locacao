<?php

namespace Locacao\Model;

use Exception;
use \Locacao\DB\Sql;
use \Locacao\Generator;


class ContractItem extends Generator{

    public static function listAll(){

        $sql = new Sql();

        $results = $sql->select("SELECT * FROM contrato_itens ORDER BY nome");
    
        return json_encode($results);
    }

    public function insert(){
        
        $sql = new Sql();
        
        //print_r($_POST);

        if(($this->getidProduto_gen() != "") && ($this->getidContrato() != "")){
           
            $results = $sql->select("CALL sp_contrato_itens_save(:idContrato, :idProduto_gen, :vlAluguel, :quantidade, :custoEntrega, :custoRetirada, :periodoLocacao, :observacao)", array(
                ":idContrato"=>$this->getidContrato(),
                ":idProduto_gen"=>$this->getidProduto_gen(),
                ":vlAluguel"=>$this->getvlAluguel(),
                ":quantidade"=>$this->getquantidade(),
                ":custoEntrega"=>$this->getcustoEntrega(),
                ":custoRetirada"=>$this->getcustoRetirada(),
                ":periodoLocacao"=>$this->getperiodoLocacao(),
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

        $results = $sql->select("SELECT * FROM contrato_itens WHERE idFornecedor = :idFornecedor", array(
            ":idFornecedor"=>$idFornecedor
        ));

        if(count($results) > 0){
            $this->setData($results[0]);

        }
    }

 
    public static function searchName($nome){ //search if name or desc already exists

        $sql = new Sql();

        $results = $sql->select("SELECT * FROM contrato_itens WHERE (nome = :nome)", array(
            ":nome"=>$nome
        ));

        return $results;
    }

    public function get_datatable($requestData, $column_search, $column_order, $idContrato){
        
        $query = "SELECT a.*, b.descricao, c.descCategoria FROM `contrato_itens` a
            INNER JOIN produtos_gen b ON (a.idProduto_gen = b.idProduto_gen)
            INNER JOIN prod_categorias c ON (b.idCategoria = c.idCategoria)";

        if (!empty($requestData['search']['value'])) { //verifica se eu digitei algo no campo de filtro

            $first = TRUE;

            foreach ($column_search as $field) {
                
               
                $search = strtoupper($requestData['search']['value']); //tranforma em maiúsculo

                //filtra no banco
                if ($first) {
                    $query .= " WHERE (a.idContrato = $idContrato AND $field LIKE '%$search%'"; //primeiro caso
                    $first = FALSE;
                } else {
                    $query .= " OR $field LIKE '%$search%'";
                }
            } //fim do foreach
            if (!$first) {
                $query .= ")"; //termina o WHERE e a query
            }

        }else{ //se não pesquisou nada
            $query .= "WHERE (a.idContrato = $idContrato)";
        }

        // WHERE a.idContrato = $idContrato
        //echo "query: " . $query;
        
        $res = $this->searchAll($query);
        $this->setTotalFiltered(count($res));

        //ordenar o resultado
        $query .= " ORDER BY a.idItem " . $requestData['order'][0]['dir'] . 
        "  LIMIT " . $requestData['start'] . " ," . $requestData['length'] . "   "; 
        
        //echo "<br>query: " . $query;

        $contractItens = new ContractItem();
        //echo $query;
        return array(
            'totalFiltered'=>$this->getTotalFiltered(),
            'data'=>$contractItens->searchAll($query)
        );
    }

    public function searchAll($query){ //pesquisa genérica (para todos os campos). Recebe uma query

        $sql = new Sql();

        $results = $sql->select($query);

        return $results;

    }

    public static function total() { //retorna a quantidade todal de registros na tabela

        $sql = new Sql();

        $results = $sql->select("SELECT * FROM contrato_itens");

        return count($results);		
	}
    

    public function update(){
        
        $sql = new Sql();

        $results = $sql->select("CALL sp_contrato_itensUpdate_save(:idHistoricoAluguel, :idContrato, :idProduto_gen, :vlAluguel, :quantidade, :custoEntrega, :custoRetirada, :periodoLocacao, :observacao)", array(
            ":idContrato"=>$this->getidContrato(),
            ":idProduto_gen"=>$this->getidProduto_gen(),
            ":vlAluguel"=>$this->getvlAluguel(),
            ":quantidade"=>$this->getquantidade(),
            //":periodoAluguel"=>$this->getperiodoAluguel(),
            ":dtInicio"=>$this->getdtInicio(),
            ":dtFinal"=>$this->getdtFinal(),
            ":custoEntrega"=>$this->getcustoEntrega(),
            ":custoRetirada"=>$this->getcustoRetirada(),
            ":periodoLocacao"=>$this->getperiodoLocacao(),
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

            $sql->query("CALL sp_contrato_itens_delete(:idFornecedor)", array(
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

    public static function productAlreadyAdded($idContract, $codeProd){ //verifica se o código passado já existe na lista de itens (produtos) do orçamento/contrato
        
        $sql = new Sql();

        $results = $sql->select("SELECT * FROM contrato_itens a
            INNER JOIN produtos_gen b ON(a.idProduto_gen = b.idProduto_gen) 
            WHERE (a.idContrato = :idContract AND b.codigoGen = :codeProd)", array(
                ':idContract'=>$idContract,
                ':codeProd'=>$codeProd
            ));

        if(count($results) > 0){
            
            return json_encode([
                "error"=>true,
                "msg"=>"O produto com este código já foi adicionado à lista"
            ]);

        }else{
            return json_encode([
                "error"=>false
            ]);
        }
    
        return json_encode($results);
    }
    
}

