<?php

namespace Locacao\Model;

use Exception;
use \Locacao\DB\Sql;
use \Locacao\Generator;


class Container extends Generator{

    public static function listAll(){

        $sql = new Sql();

        return json_encode( $sql->select("SELECT * FROM produtos ORDER BY codigo"));
    }


    public function insert($idProduto){

        $this->setidProduto($idProduto);
    
        $sql = new Sql();

        echo "forrado: " . $this->getforrado('1') ."eletr: " . 
        $this->geteletrificado('1') ."chuv: " . 
        $this->getchuveiro('1');

        /*echo ":idProduto " . $this->getidProduto().
  
        ":tipoPorta " . $this->gettipoPorta().
        ":janelasLat " . $this->getjanelasLat().
        ":janelasCirc " . $this->getjanelasCirc().
        ":forrado " . $this->getforrado().
        ":eletrificado " . $this->geteletrificado().
        ":tomadas " . $this->gettomadas().
        ":lampadas " . $this->getlampadas().
        ":entradasAC " . $this->getentradasAC().
        ":sanitarios " . $this->getsanitarios().
        ":chuveiro " . $this->getchuveiro();*/

        if(($this->gettipoPorta() != "")){
           
            $results = $sql->select("CALL sp_prod_containers_save(:idProduto, :tipoPorta, :janelasLat, :janelasCirc, 
            :forrado, :eletrificado, :tomadas, :lampadas, :entradasAC, :sanitarios, :chuveiro)", array(
                ":idProduto"=>$this->getidProduto(),
                ":tipoPorta"=>$this->gettipoPorta(),
                ":janelasLat"=>$this->getjanelasLat(),
                ":janelasCirc"=>$this->getjanelasCirc(),
                ":forrado"=>$this->getforrado(),
                ":eletrificado"=>$this->geteletrificado(),
                ":tomadas"=>$this->gettomadas(),
                ":lampadas"=>$this->getlampadas(),
                ":entradasAC"=>$this->getentradasAC(),
                ":sanitarios"=>$this->getsanitarios(),
                ":chuveiro"=>$this->getchuveiro()
            ));

            print_r($results);
            if(count($results) > 0){

                $this->setData($results[0]); //carrega atributos desse objeto com o retorno da inserção no banco

                return json_encode($results[0]);

            }else{
                return json_encode([
                    "error"=>true,
                    "msg"=>"Erro ao inserir Container!"
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

        $results = $sql->select("SELECT * FROM produtos WHERE idProduto = :idProduto", array(
            ":idProduto"=>$idproduct
        ));

        $this->setData($results[0]);
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
        
        $query = "SELECT * FROM produtos";

        if (!empty($requestData['search']['value'])) { //verifica se eu digitei algo no campo de filtro

            $first = TRUE;

            foreach ($column_search as $field) {

                $search = strtoupper($requestData['search']['value']); //tranforma em maiúsculo


                if ($field == "status") {

                    if (($search == "DISPONIVEL") || ($search == "DISPONÍVEL")) {
                        $search = 1;
                    } else if (($search == "ALUGADO")) {
                        $search = 0;
                    } else if (($search == "MANUTENCAO") || ($search == "MANUTENÇÃO")) {
                        $search = 2;
                    }
                }

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
        $query .= " ORDER BY " . $column_order[$requestData['order'][0]['column']] . " " . $requestData['order'][0]['dir'] . 
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

        $results = $sql->select("SELECT * FROM produtos");

        return count($results);		
	}
    

    public function update(){

        $sql = new Sql();

        $results = $sql->select("CALL sp_containersUpdate_save(:idProduto, :codigo, :descricao, :valorCompra, :status, :dtFabricacao, :tipo, :anotacoes, :idFornecedor, :idCategoria)", array(
            ":idProduto"=>$this->getidProduto(),
            ":codigo"=>$this->getcodigo(),
            ":descricao"=>$this->getdescricao(),
            ":valorCompra"=>$this->getvalorCompra(),
            ":status"=>$this->getstatus(),
            ":dtFabricacao"=>$this->getdtFabricacao(),
            ":tipo"=>$this->gettipo(),
            ":anotacoes"=>$this->getanotacoes(),
            ":idFornecedor"=>$this->getidFornecedor(),
            ":idCategoria"=>$this->getidCategoria()
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
            $sql->query("CALL sp_containers_delete(:idProduto)", array(
                ":idProduto"=>$this->getidProduto()
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
}
