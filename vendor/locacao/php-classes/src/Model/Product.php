<?php

namespace Locacao\Model;

use Exception;
use \Locacao\DB\Sql;
use \Locacao\Generator;


class Product extends Generator{

    public static function listAll(){

        $sql = new Sql();

        return json_encode( $sql->select("SELECT * FROM produtos ORDER BY codigo"));
    }


    public function insert(){
        
        $sql = new Sql();

        if(($this->getcodigo() != "") && ($this->getdescricao() != "") && ($this->getstatus() != "")){
           
            $results = $sql->select("CALL sp_produtos_save(:codigo, :descricao, :valorCompra, :status, :dtFabricacao, :tipo1, :tipo2, :tipo3, :tipo4, :numSerie, :anotacoes, :fornecedor, :categoria)", array(
                ":codigo"=>$this->getcodigo(),
                ":descricao"=>$this->getdescricao(),
                ":valorCompra"=>$this->getvalorCompra(),
                ":status"=>$this->getstatus(),
                ":dtFabricacao"=>$this->getdtFabricacao(),
                ":tipo1"=>$this->gettipo1(),
                ":tipo2"=>$this->gettipo2(),
                ":tipo3"=>$this->gettipo3(),
                ":tipo4"=>$this->gettipo4(),
                ":numSerie"=>$this->getnumSerie(),
                ":anotacoes"=>$this->getanotacoes(),
                ":fornecedor"=>$this->getfornecedor(),
                ":categoria"=>$this->getcategoria()
            ));

            if(count($results) > 0){

                $this->setData($results[0]); //carrega atributos desse objeto com o retorno da inserção no banco

                return json_encode($results[0]);

            }else{
                return json_encode([
                    "error"=>true,
                    "msg"=>"Erro ao inserir Produto!"
                    ]);
            }
       
        }else{

            return json_encode([
				'error' => true,
				"msg" => "Campos incompletos!"
			]);
        }
    }

    public function get($idproduct){

        $sql = new Sql();

        $results = $sql->select("SELECT * FROM produtos
        WHERE idProduto = :idProduto", array(
            ":idProduto"=>$idproduct
        ));

        if(count($results) > 0){

            $this->setData($results[0]);
        }
    }

    public function loadProduct($idproduct){

        $sql = new Sql();
 
        //dados das tabelas: produtos, categorias e fornecedores
        $res1 = $sql->select("SELECT a.idProduto, a.codigo, a.descricao, a.valorCompra, a.status,
        a.dtFabricacao, a.numSerie, a.anotacoes, b.idCategoria, b.codCategoria, c.idFornecedor, c.codFornecedor, c.nome 
        FROM produtos a INNER JOIN prod_categorias b ON(a.idCategoria = b.idCategoria)
        INNER JOIN fornecedores c ON(a.idFornecedor = c.idFornecedor)
            WHERE a.idProduto = :idProduto", array(
            ":idProduto"=>$idproduct
        ));


        if(count($res1) > 0){

        //dados das tabela tipos de produtos
        $res2 = $sql->select("SELECT b.id, b.descTipo, b.ordem_tipo, b.codTipo
        FROM produtos a RIGHT JOIN prod_tipos b ON(a.tipo1 = b.id OR a.tipo2 = b.id OR a.tipo3 = b.id OR a.tipo4 = b.id)
        WHERE idProduto = :idProduto
        ORDER BY b.ordem_tipo, b.codTipo", array(
            ":idProduto"=>$idproduct
        ));


        $res3 = $sql->select("SELECT * FROM prod_containers 
        WHERE idProduto = :idProduto", array(
            ":idProduto"=>$idproduct
        ));


            return json_encode([
                $res1[0], //produto
                $res2, //tipos
                $res3 //container (se não for container, vai reotornar um array vazio)
            ]);
        }
    }

    public static function searchCode($code){ //search if name already exists

        $sql = new Sql();

        $results = $sql->select("SELECT * FROM produtos WHERE (codigo = :codigo)", array(
            ":codigo"=>$code
        ));

        return $results;
    }

    public static function searchDesc($desc){ //search if name or desc already exists

        $sql = new Sql();

        $results = $sql->select("SELECT * FROM produtos WHERE (descricao = :descricao)", array(
            ":descricao"=>$desc
        ));

        return $results;
    }

    public function get_datatable($requestData, $column_search, $column_order){
        
        $query = "SELECT a.idProduto, a.codigo, a.descricao, a.status, b.descCategoria, c.descTipo as tipo1 FROM produtos a 
                INNER JOIN prod_categorias b  ON(a.idCategoria = b.idCategoria)
                INNER JOIN prod_tipos c ON(a.tipo1 = c.id)";

        if (!empty($requestData['search']['value'])) { //verifica se eu digitei algo no campo de filtro

            $first = TRUE;

            foreach ($column_search as $field) {
                
               
                $search = strtoupper($requestData['search']['value']); //tranforma em maiúsculo


                if ($field == "status") {
                    $search = substr($search, 0, 4);  // retorna os 4 primeiros caracteres

                    if (($search == "DISP")) {
                        $search = 1;
                    } else if ($search == "ALUG") {
                        $search = 0;
                    } else if ($search == "MANU") {
                        $search = 2;
                    }

                    //echo "status: ".$search;
                }

                if ($field == "tipo1") {
                  
                    if (($search == "3M")) {
                        $search = 1;
                    } else if ($search == "4M") {
                        $search = 2;
                    } else if ($search == "6M") {
                        $search = 3;
                    } else if ($search == "12M") {
                        $search = 4;
                    }

                    //echo "tipo1: ".$search;
                }

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
        
        $products = new Product();
        //echo $query;
        return array(
            'totalFiltered'=>$this->getTotalFiltered(),
            'data'=>$products->searchAll($query)
        );
    }

    public function searchAll($query){ //pesquisa genérica (para todos os campos). Recebe uma query

        $sql = new Sql();

        $results = $sql->select($query);

        return $results;

    }

    public static function total() { //retorna a quantidade todal de registros na tabela

        $sql = new Sql();

        $results = $sql->select("SELECT * FROM produtos");

        return count($results);		
	}
    

    public function update(){
        
        $sql = new Sql();

        $results = $sql->select("CALL sp_produtosUpdate_save(:idProduto, :codigo, :descricao, :valorCompra, :status, :dtFabricacao,
                                :tipo1, :tipo2, :tipo3, :tipo4, :numSerie, :anotacoes, :fornecedor, :categoria)", array(
            ":idProduto"=>$this->getidProduto(),
            ":codigo"=>$this->getcodigo(),
            ":descricao"=>$this->getdescricao(),
            ":valorCompra"=>$this->getvalorCompra(),
            ":status"=>$this->getstatus(),
            ":dtFabricacao"=>$this->getdtFabricacao(),
            ":tipo1"=>$this->gettipo1(),
            ":tipo2"=>$this->gettipo2(),
            ":tipo3"=>$this->gettipo3(),
            ":tipo4"=>$this->gettipo4(),
            ":numSerie"=>$this->getnumSerie(),
            ":anotacoes"=>$this->getanotacoes(),
            ":fornecedor"=>$this->getfornecedor(),
            ":categoria"=>$this->getcategoria()
        ));

        if(count($results) > 0){

            $this->setData($results[0]); //carrega atributos desse objeto com o retorno da atualização no banco

            return json_encode($results[0]);

        }else{
            return json_encode([
                "error"=>true,
                "msg"=>"Erro ao atualizar Produto!"
                ]);
        }

    }

    public function delete(){

       
        $sql = new Sql();

        try{
            $sql->query("CALL sp_produtos_delete(:idProduto)", array(
                ":idProduto"=>$this->getidProduto()
            ));

            if($this->get($this->getidProduto())){

                return json_encode([
                    "error"=>true,
                    "msg"=>'Erro ao excluir produto'
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

    public static function showsNextNumber($idCategoria){

        $sql = new Sql();

        $results = $sql->select("SELECT MAX(numSerie) FROM produtos WHERE idCategoria = :idCategoria", array(
            ":idCategoria"=>$idCategoria
        ));
        $nextNumber = 1 + $results[0]['MAX(numSerie)'];
       
        if($nextNumber < 10){
            $nextNumber = "000". $nextNumber;

        }else if($nextNumber < 100){
            $nextNumber = "00". $nextNumber;
            
        }else if($nextNumber < 1000){
            $nextNumber = "0". $nextNumber;
        }
        
        return $nextNumber; //retorna o próximo número de série da categoria

    }
    
}
