<?php

namespace Locacao\Model;

use Exception;
use \Locacao\DB\Sql;
use \Locacao\Generator;


class ProductEsp extends Generator{

    public static function listAll(){

        $sql = new Sql();

        return json_encode( $sql->select("SELECT * FROM produtos_esp ORDER BY codigoEsp"));
    }


    public function insert(){
        
        $sql = new Sql();


        /*echo "<br>codigoEsp ".$this->getcodigoEsp() .
        "<br>valorCompra ".$this->getvalorCompra() .
        "<br>status ".$this->getstatus() .
        "<br>dtFabricacao ".$this->getdtFabricacao() .
        "<br>numSerie ".$this->getnumSerie() .
        "<br>anotacoes ".$this->getanotacoes() .
        "<br>fornecedor ".$this->getfornecedor();*/

        if(($this->getcodigoEsp() != "") && ($this->getstatus() != "")){
            

            $results = $sql->select("CALL sp_produtos_esp_save(:idProduto_gen, :codigoEsp, :valorCompra, :status, :dtFabricacao, :numSerie, :anotacoes, :fornecedor)", array(
                ":idProduto_gen"=>$this->getidProduto_gen(),
                ":codigoEsp"=>$this->getcodigoEsp(),
                ":valorCompra"=>$this->getvalorCompra(),
                ":status"=>$this->getstatus(),
                ":dtFabricacao"=>$this->getdtFabricacao(),
                ":numSerie"=>$this->getnumSerie(),
                ":anotacoes"=>$this->getanotacoes(),
                ":fornecedor"=>$this->getfornecedor()
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

        $results = $sql->select("SELECT * FROM produtos_esp
        WHERE idProduto_esp = :idProduto_esp", array(
            ":idProduto_esp"=>$idproduct
        ));

        if(count($results) > 0){

            $this->setData($results[0]);
        }
    }

    public function loadProduct($idproduct){ //carrega produto Específico

        $sql = new Sql();
 
        /*
        SELECT a.idProduto_esp, a.codigoEsp, a.descricao, a.valorCompra, a.status,
        a.dtFabricacao, a.numSerie, a.anotacoes, b.idCategoria, b.codCategoria, c.idFornecedor, c.codFornecedor, c.nome 
        FROM produtos_esp a INNER JOIN prod_categorias b ON(a.idCategoria = b.idCategoria)
        INNER JOIN fornecedores c ON(a.idFornecedor = c.idFornecedor)
            WHERE a.idProduto = :idProduto */

        //dados das tabelas: produtos_esp, categorias e fornecedores
        $res1 = $sql->select("SELECT a.idProduto_esp, a.codigoEsp, a.valorCompra, a.status, a.dtFabricacao, a.numSerie, a.anotacoes,
            b.idCategoria, b.descricao,
            c.codCategoria,
            d.idFornecedor, d.codFornecedor
            FROM produtos_esp a
            INNER JOIN produtos_gen b ON(a.idProduto_gen = b.idProduto_gen)
            INNER JOIN prod_categorias c ON(b.idCategoria = c.idCategoria)
            INNER JOIN fornecedores d ON(a.idFornecedor = d.idFornecedor)
            WHERE a.idProduto_esp = :idProduto", array(
            ":idProduto"=>$idproduct
        ));

        if(count($res1) > 0){

        //dados das tabela tipos de produtos
        /*$res2 = $sql->select("SELECT b.id, b.descTipo, b.ordem_tipo, b.codTipo
        FROM produtos a RIGHT JOIN prod_tipos b ON(a.tipo1 = b.id OR a.tipo2 = b.id OR a.tipo3 = b.id OR a.tipo4 = b.id)
        WHERE idProduto = :idProduto
        ORDER BY b.ordem_tipo, b.codTipo", array(
            ":idProduto"=>$idproduct
        ));*/


        $res2 = $sql->select("SELECT * FROM prod_containers 
        WHERE idProduto = :idProduto", array(
            ":idProduto"=>$idproduct
        ));

        if(count($res2) > 0){
            $res2 = $res2[0];
        }

            return json_encode([
                $res1[0], //produto
                $res2 //container (se não for container, vai reotornar um array vazio)
            ]);
        }
    }

    public static function getByCode($code){ //pega o produto que está disponível a partir do código

        $sql = new Sql();
        
        $query = "SELECT a.idProduto, a.codigoEsp, a.descricao, b.descCategoria, c.descTipo as tipo1 FROM produtos_esp a 
        INNER JOIN prod_categorias b  ON(a.idCategoria = b.idCategoria)
        INNER JOIN prod_tipos c ON(a.tipo1 = c.id)
        WHERE (codigoEsp = :codigoEsp AND status = 1)";

        $results = $sql->select($query, array(
            ":codigoEsp"=>$code
        ));

        return json_encode($results[0]);
    }

    public static function searchDesc($desc){ //search if name or desc already exists

        $sql = new Sql();

        $results = $sql->select("SELECT * FROM produtos_esp WHERE (descricao = :descricao)", array(
            ":descricao"=>$desc
        ));

        return $results;
    }

    public function get_datatable($requestData, $column_search, $column_order, $idProduct_gen=false){
        
        $query = "SELECT a.idProduto_esp, a.codigoEsp, a.status, b.descricao, c.descCategoria FROM produtos_esp a 
                INNER JOIN produtos_gen b  ON(a.idProduto_gen = b.idProduto_gen)
                INNER JOIN prod_categorias c  ON(b.idCategoria = c.idCategoria)";

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

        }else{
            $query .= " WHERE (a.idProduto_gen = $idProduct_gen)";
        }
        
        $res = $this->searchAll($query);
        $this->setTotalFiltered(count($res));

        //ordenar o resultado
        $query .= " ORDER BY a." . $column_order[$requestData['order'][0]['column']] . " " . $requestData['order'][0]['dir'] . 
        "  LIMIT " . $requestData['start'] . " ," . $requestData['length'] . "   "; 
        
        $products = new Product();

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

        $results = $sql->select("SELECT * FROM produtos_esp");

        return count($results);		
	}
    

    public function update(){
        
        $sql = new Sql();

        $results = $sql->select("CALL sp_produtos_espUpdate_save(:idProduto_esp, :idProduto_gen, :codigoEsp, :valorCompra, :status, :dtFabricacao,
                                 :numSerie, :anotacoes, :fornecedor)", array(
            ":idProduto_esp"=>$this->getidProduto_esp(),
            ":idProduto_gen"=>$this->getidProduto_gen(),
            ":codigoEsp"=>$this->getcodigoEsp(),
            ":valorCompra"=>$this->getvalorCompra(),
            ":status"=>$this->getstatus(),
            ":dtFabricacao"=>$this->getdtFabricacao(),
            ":numSerie"=>$this->getnumSerie(),
            ":anotacoes"=>$this->getanotacoes(),
            ":fornecedor"=>$this->getfornecedor()
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
            $sql->query("CALL sp_produtos_esp_delete(:idProduto)", array(
                ":idProduto"=>$this->getidProduto_esp()
            ));

            if($this->get($this->getidProduto_esp())){

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

        $results = $sql->select("SELECT MAX(numSerie) FROM produtos_esp a
            INNER JOIN produtos_gen b ON(a.idProduto_gen = b.idProduto_gen)
            WHERE (b.idCategoria = :idCategoria)", array(
            ":idCategoria"=>$idCategoria
        ));
        
        if(count($results)){
            
            $nextNumber = 1 + $results[0]['MAX(numSerie)'];
       
            if($nextNumber < 10){
                $nextNumber = "000". $nextNumber;
    
            }else if($nextNumber < 100){
                $nextNumber = "00". $nextNumber;
                
            }else if($nextNumber < 1000){
                $nextNumber = "0". $nextNumber;
            }

        }else{ //se não existe nenhum registro na tabela
            $nextNumber = '0001';
        }

       return $nextNumber; //retorna o próximo número de série da categoria

    }

    public function loadProductEspByIdProductGen($idProduto_gen) { /* carrega todos os produtos específicos que estão disponíveis (status=1), relacionados a um produto genérico */
        
        $query = "SELECT a.idProduto_esp, a.codigoEsp, a.status,
            b.idProduto_gen, b.descricao,
            c.descCategoria
            FROM produtos_esp a
            INNER JOIN produtos_gen b ON(a.idProduto_gen = b.idProduto_gen)
            INNER JOIN prod_categorias c ON(b.idCategoria = c.idCategoria)
            WHERE (a.idProduto_gen = $idProduto_gen AND a.status = 1)";

        return json_encode($this->searchAll($query));
    }
    
} //end class ProductEsp
