<?php

namespace Locacao\Model;

use Exception;
use \Locacao\DB\Sql;
use \Locacao\Generator;


class Container extends Generator{

    public static function listAll(){

        $sql = new Sql();

        return json_encode( $sql->select("SELECT * FROM prod_containers ORDER BY codigo"));
    }


    public function insert($idProduto){

        $this->setidProduto($idProduto);
    
        $sql = new Sql();

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

            //print_r($results);
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

    public function get($idProduto){

        $sql = new Sql();

        $results = $sql->select("SELECT * FROM prod_containers WHERE idProduto = :idProduto", array(
            ":idProduto"=>$idProduto
        ));

        if(count($results) > 0){
            $this->setData($results[0]);
        }
        
    }

    public function searchAll($query){ //pesquisa genérica (para todos os campos). Recebe uma query

        $sql = new Sql();

        $results = $sql->select($query);

        return $results;

    }

    public static function total() { //retorna a quantidade todal de registros na tabela

        $sql = new Sql();

        $results = $sql->select("SELECT * FROM prod_containers");

        return count($results);		
	}
    

    public function update(){
        $sql = new Sql();

        $results = $sql->select("CALL sp_prod_containersUpdate_save(:idProduto, :tipoPorta, :janelasLat, :janelasCirc, 
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

        if(count($results) > 0){

            $this->setData($results[0]); //carrega atributos desse objeto com o retorno da atualização no banco

            return json_encode($results[0]);

        }else{
            return json_encode([
                "error"=>true,
                "msg"=>"Erro ao atualizar Container!"
                ]);
        }

    }

    public function delete(){

        $sql = new Sql();

        try{
            $sql->query("CALL sp_prod_containers_delete(:idProduto)", array(
                ":idProduto"=>$this->getidProduto()
            ));

            if($this->get($this->getidProduto())){

                return json_encode([
                    "error"=>true,
                    "msg"=>'Erro ao excluir produto Container'
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
