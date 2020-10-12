<?php

namespace Locacao\Model;

use Exception;
use \Locacao\DB\Sql;
use \Locacao\Generator;

class Freight extends Generator {

    public static function searchAll($query) {
        $sql = new Sql();
        return $sql->select($query);
    }
    
    public static function listAll()
    {
        $sql = new Sql();

        $results = $sql->select("SELECT * FROM fretes ORDER BY idLocacao");
    
        return json_encode($results);
    }

    public static function total() 
    {
        $sql = new Sql();

        $results = $sql->select("SELECT * FROM fretes");

        return count($results);
    }

    public function verifyTable() {
        $sql = new Sql();

        return $sql->select("SELECT * FROM fretes WHERE tipo_frete = :TIPO_FRETE", array(
            ":TIPO_FRETE"=>$this->gettipo_frete(),
        ));
    }

    public function insert()
    {
        $sql = new Sql();

        if(($this->gettipo_frete() != "") && ($this->getstatus() != ""))
        {
            if(count($this->verifyTable()) == 0) { //se já não existe um frete cadastrado com o mesmo status
                $results = $sql->select("CALL sp_fretes_save(:idLocacao, :tipo_frete, :status, :data_hora, :observacao)", array(
                    ":idLocacao"=>$this->getidLocacao(),
                    ":tipo_frete"=>$this->gettipo_frete(),
                    ":status"=>$this->getstatus(),
                    ":data_hora"=>$this->getdata_hora(),
                    ":observacao"=>$this->getobservacao(),
                ));
    
                if(count($results) > 0){
    
                    $this->setData($results[0]); //carrega atributos desse objeto com o retorno da inserção no banco
    
                    return json_encode($results[0]);
    
                }else{
                    return json_encode([
                        "error"=>true,
                        "msg"=>"Erro ao inserir Frete!"
                    ]);
                }

            }else {
                return json_encode([
                    "error"=>true,
                    "msg"=>"Já existe um frete desse Tipo!"
                ]);
            }
            
        }else{

            return json_encode([
				'error' => true,
				"msg" => "Campos incompletos!"
			]);
        }
    }

    public function update()
    {
        $sql = new Sql();

        $results = $sql->select("CALL sp_fretesUpdate_save(:id, :tipo_frete, :status, :data_hora, :observacao)", array(
            ":id"=>$this->getid(),
            //":idLocacao"=>$this->getidLocacao(),
            ":tipo_frete"=>$this->gettipo_frete(),
            ":status"=>$this->getstatus(),
            ":data_hora"=>$this->getdata_hora(),
            ":observacao"=>$this->getobservacao(),
        ));

        if(count($results) > 0){

            $this->setData($results[0]); //carrega atributos do objeto

            return json_encode($results[0]);

        }else{
            return json_encode([
                "error"=>true,
                "msg"=>"Erro ao atualizar Frete!"
                ]);
        }

    }

    public function get($id){

        $sql = new Sql();

        $results = $sql->select("SELECT * FROM fretes WHERE id = :id", array(
            ":id"=>$id
        ));

        if(count($results) > 0){
            $this->setData($results[0]);
        }
    }

    public function get_datatable($requestData, $column_search, $column_order, $idRent)
    {
        $query = "SELECT * FROM fretes";

        if (!empty($requestData['search']['value'])) //verifica se eu digitei algo no campo de filtro
        { 
            $first = TRUE;

            foreach ($column_search as $field)
            {     
                $search = strtoupper($requestData['search']['value']); //tranforma em maiúsculo

                //filtra no banco
                if ($first) 
                {
                    $query .= " WHERE ($field LIKE '%$search%'"; //primeiro caso
                    $first = FALSE;

                } else {
                    
                    if(($field == "tipo_frete") || ($field == "status")) //--------------
                    {
                        $aux = strtoupper($search); //deixa a string em maiúsculo
                        $aux = substr($aux, 0, 5); //pega os 5 primeiros caracteres
    
                        if($field == "tipo_frete")
                        {
                            if($aux == "ENTRE") //entrega
                            {
                                $value = 0;

                            }else if($aux == "RETIR") //retirada
                            {
                                $value = 1;
                            }

                        }else { //field == status
                            if($aux == "PENDE") //pendente
                            {
                                $value = 0;
             
                            }else if($aux == "CONCL") //concluído
                            {
                                $value = 1;
                            }
                        } 

                        if(isset($value)){
                            $query .= " OR $field = $value";
                        }

                    } else if($field == "data_hora") //----------------------------
                    {
                        if(strlen($search) >= 10){ //precisa digitar a data completa no campo pesquisar, ex: 20/09/2020
              
                            //trata a data (dia/mes/ano -> ano-mes-dia)
                            $aux = str_replace("/", "-", $search);
                            $data = date('Y-m-d', strtotime($aux));

                            $data .= substr($search, 10, strlen($search) ); //pega o resto da string (as horas)
                            //echo "$field: $data";

                            $query .= " OR $field = '$data'";
                        }
                    } //----------------------------
 
                } //fim do primeiro else

            } //fim do foreach

            if($idRent) {
                $query .= " AND idLocacao = $idRent";
            }

            if (!$first) {
                $query .= ")"; //termina o WHERE e a query
            }

        }else {        
            if($idRent) {
                $query .= " WHERE (idLocacao = $idRent)";
            }
        }

        //print_r($query);

        $res = $this->searchAll($query);
        $this->setTotalFiltered(count($res));

        //ordenar o resultado
        $query .= " ORDER BY " . $column_order[$requestData['order'][0]['column']] . " " . $requestData['order'][0]['dir'] . 
        "  LIMIT " . $requestData['start'] . " ," . $requestData['length'] . "   "; 
        
        $freights = new Freight();
        //echo $query;
        return array(
            'totalFiltered'=>$this->getTotalFiltered(),
            'data'=>$freights->searchAll($query)
        );

    } // fim do método get_datatable()

    function loadFreight($id) {
        $sql = new Sql();

        $query = "SELECT * FROM fretes
            WHERE id = :id";
        
        $freight = $sql->select($query, array(
            ":id"=>$id
        ));

        return json_encode($freight[0]);
    }
    
    
    public function delete($id){
      
        $sql = new Sql();
        
        try{
            $sql->query("CALL sp_fretes_delete(:id)", array(
                ":id"=>$id
            ));

            if($this->get($id)){

                return json_encode([
                    "error"=>true,
                    "msg"=>"Erro ao excluir Frete"
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