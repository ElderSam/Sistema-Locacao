<?php

namespace Locacao\Model;

use Exception;
use \Locacao\DB\Sql;
use \Locacao\Generator;
use \Locacao\Model\Rent;

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

        return $sql->select("SELECT * FROM fretes 
            WHERE (idLocacao = :IDLOCACAO AND tipo_frete = :TIPO_FRETE)", array(
            ":IDLOCACAO"=>$this->getidLocacao(),
            ":TIPO_FRETE"=>$this->gettipo_frete()
        ));
    }

    public function insert()
    {
        $sql = new Sql();

        $rent = new Rent();
        $item = $rent->loadRent($this->getidLocacao());
        $item = json_decode($item);

        $auxOBS = "Cod. Prod.: $item->codigoEsp,  \n<b>Produto: $item->descricao</b>";
        $auxObs = $auxOBS . ",  \n" . $this->getobservacao();
        $this->setobservacao($auxObs);

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
        
        $oldRent = new Rent();
        $oldRent->get($this->getidLocacao());


        $results = $sql->select("CALL sp_fretesUpdate_save(:id, :tipo_frete, :status, :data_hora, :observacao)", array(
            ":id"=>$this->getid(),
            //":idLocacao"=>$this->getidLocacao(),
            ":tipo_frete"=>$this->gettipo_frete(),
            ":status"=>$this->getstatus(),
            ":data_hora"=>$this->getdata_hora(),
            ":observacao"=>$this->getobservacao(),
        ));

        if(count($results) > 0){

                $rent = new Rent();
                $rent->get($this->getidLocacao());

                if(($oldRent->getstatus() != 3) && ($rent->getstatus() == 3)) { //se foi modificado o status do aluguel para 3 (ENCERRADO), então devolve o produto (torna disponível)
                    $query = "UPDATE produtos_esp
                    SET status = 1 /* status 1 = disponível */
                    WHERE (idProduto_esp = :idProduto_esp)";

                    $sql->query($query, array(
                        ":idProduto_esp"=>$rent->getproduto_idProduto()
                    ));
                }

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
        $searchedFields = 1;

        if (!empty($requestData['search']['value'])) //verifica se eu digitei algo no campo de filtro
        { 
            $query .= " WHERE ";
            $searchedFields = 0;
            $first = true;

            foreach ($column_search as $field)
            {     
                $search = strtoupper($requestData['search']['value']); //tranforma em maiúsculo

                if(($field == "tipo_frete") || ($field == "status")) //--------------
                {
                    $aux = substr($search, 0, 5); //pega os 5 primeiros caracteres
                    //echo "<br> $aux";
                    $value = -1;

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

                        if(($value >= 0) && !$first) $query .= " OR";
                    }

                    if($value >= 0){
                        $query .= " $field = $value";

                        $searchedFields++;
                        $first = false;
                    }

                } else if($field == "data_hora") //----------------------------
                {
                    if(strlen($search) >= 10){ //precisa digitar a data completa no campo pesquisar, ex: 20/09/2020
            
                        //trata a data (dia/mes/ano -> ano-mes-dia)
                        $aux = str_replace("/", "-", $search);
                        $data = date('Y-m-d', strtotime($aux));

                        //echo "$field: $data";
                        if(!$first) $query .= " OR";
                        
                        $query .= " DATE_FORMAT($field, '%Y-%m-%d') = '$data'";

                        $searchedFields++;
                    }
                } //----------------------------

            } //fim do foreach

            if($idRent) {
                $query .= " AND (idLocacao = $idRent)";
            }

        }else {        
            if($idRent) {
                $query .= " WHERE (idLocacao = $idRent)";
            }
        }        

        if($searchedFields == 0) {
            $res = array();
            $query .= " 1";
            //echo "<br> res: 0";

        }else {
            $res = $this->searchAll($query);
            //print_r($res);
        }

        //echo $query;
        $this->setTotalFiltered(count($res));

        //ordenar o resultado
        $query .= " ORDER BY status, data_hora " . $requestData['order'][0]['dir'] . 
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