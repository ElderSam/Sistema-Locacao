<?php

namespace Locacao\Model;

use Exception;
use \Locacao\DB\Sql;
use \Locacao\Generator;


class ProdType extends Generator{

    public static function listAll(){

        $sql = new Sql();

        return json_encode( $sql->select("SELECT * FROM prod_tipos ORDER BY idCategoria"));
    }


    public function insert(){

        $sql = new Sql();

        if(($this->getdescTipo() != "") && ($this->getcodTipo() != "") && ($this->getordem_tipo() != "")){
           
            $results = $sql->select("CALL sp_prod_tipos_save(:descTipo, :idCategoria, :ordem_tipo, :codTipo)", array(
                ":descTipo"=>$this->getdescTipo(),
                ":idCategoria"=>$this->getidCategoria(),
                ":ordem_tipo"=>$this->getordem_tipo(),
                ":codTipo"=>$this->getcodTipo()               
            ));


            if(count($results) > 0){

                $this->setData($results[0]); //carrega atributos desse objeto com o retorno da inserção no banco

                return json_encode($results[0]);

            }else{
                return json_encode([
                    "error"=>true,
                    "msg"=>"Erro ao inserir Tipo de produto!"
                    ]);
            }
       
        }else{

            return json_encode([
				'error' => true,
				"msg" => "Campos incompletos!"
			]);
        }
    }

    public function get($iduser){

        $sql = new Sql();

        $results = $sql->select("SELECT * FROM prod_tipos WHERE id = :id", array(
            ":id"=>$iduser
        ));

        $this->setData($results[0]);
    }

    public static function searchDesc($desc){ //search if desc already exists

        $sql = new Sql();

        $results = $sql->select("SELECT * FROM prod_tipos WHERE (descTipo = :descTipo)", array(
            ":descTipo"=>$desc
        ));

        return $results;
    }


    public function get_datatable($requestData, $column_search, $column_order){
        
        $query = "SELECT * FROM prod_tipos a INNER JOIN prod_categorias b ON (a.idCategoria = b.idCategoria)";

        if (!empty($requestData['search']['value'])) { //verifica se eu digitei algo no campo de filtro

            $first = TRUE;

            foreach ($column_search as $field) {

                $search = strtoupper($requestData['search']['value']); //tranforma em maiúsculo


               /* if ($field == "administrador") {

                    if ($search == "SIM") {
                        $search = 1;
                    } else if (($search == "NãO") || ($search == "NÃO") || ($search == "NAO")) {
                        $search = 0;
                    }
                }*/

                //filtra no banco
                if ($first) {
                    $query .= " WHERE ($field LIKE '$search%'"; //primeiro caso
                    $first = FALSE;
                } else {
                    $query .= " OR $field LIKE '$search%'";
                }
            } //fim do foreach
            if (!$first) {
                $query .= ")"; //termina o WHERE e a query
            }

        }
        
        $res = $this->searchAll($query);
        $this->setTotalFiltered(count($res));
        
        //ordenar o resultado
        $query .= " ORDER BY codCategoria, ordem_tipo, codTipo " . $requestData['order'][0]['dir'] . 
        "  LIMIT " . $requestData['start'] . " ," . $requestData['length'] . "   "; 
        
        $prodTypes = new ProdType();
        return array(
            'totalFiltered'=>$this->getTotalFiltered(),
            'data'=>$prodTypes->searchAll($query)
        );
    }

    public function searchAll($query){ //pesquisa genérica (para todos os campos). Recebe uma query

        $sql = new Sql();

        $results = $sql->select($query);

        return $results;

    }

    public static function total() { //retorna a quantidade todal de registros na tabela

        $sql = new Sql();

        $results = $sql->select("SELECT * FROM prod_tipos");

        return count($results);		
	}
    

    public function update(){

        $sql = new Sql();

        $results = $sql->select("CALL sp_prod_tiposUpdate_save(:id, :descTipo, :idCategoria, :ordem_tipo, :codTipo)", array(
            ":id"=>$this->getid(),
            ":descTipo"=>$this->getdescTipo(),
            ":idCategoria"=>$this->getidCategoria(),
            ":ordem_tipo"=>$this->getordem_tipo(),
            ":codTipo"=>$this->getcodTipo()
         
        ));

        if(count($results) > 0){

            $this->setData($results[0]); //carrega atributos desse objeto com o retorno da atualização no banco

            return json_encode($results[0]);

        }else{
            return json_encode([
                "error"=>true,
                "msg"=>"Erro ao atualizar Tipo de produto!"
                ]);
        }

    }

    public function delete(){

        $sql = new Sql();

        try{
            $sql->query("CALL sp_prod_tipos_delete(:id)", array(
                ":id"=>$this->getid()
            ));

            echo json_encode([
                "error"=>false,
            ]);

        }catch(Exception $e){

            echo json_encode([
                "error"=>true,
                "msg"=>$e->getMessage()
            ]);

        }      
    }

    public static function showsNextNumber($idCategoria, $ordem_tipo){

        $sql = new Sql();

        $results = $sql->select("SELECT MAX(codTipo) FROM prod_tipos WHERE (idCategoria = :idCategoria AND ordem_tipo = :ordem_tipo)", array(
            ":idCategoria"=>$idCategoria,
            ":ordem_tipo"=>$ordem_tipo
        ));

        $nextNumber = 1 + $results[0]['MAX(codTipo)'];
       
        if($nextNumber < 10){
            $nextNumber = "0". $nextNumber;

        }else{
            $nextNumber = $nextNumber;
            
        }
        
        return $nextNumber; //retorna o próximo número de série da categoria

    }
}
