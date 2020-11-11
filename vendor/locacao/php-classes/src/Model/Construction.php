<?php

namespace Locacao\Model;

use Exception;
use \Locacao\DB\Sql;
use \Locacao\Generator;
//use \Locacao\Mailer;

class Construction extends Generator{
 

     // Método para fazer a consulta no banco pelo ID   
    public function get($idObra){

        $sql = new Sql();

       //$results = $sql->select("SELECT * FROM resp_obras WHERE idResp = :idResp", array(
        $results = $sql->select("SELECT o.idObra, o.codObra, o.complemento, o.cidade, o.bairro, o.numero, o.uf, o.cep, o.endereco, o.dtCadastro,  o.id_fk_cliente, o.id_fk_respObra, c.nome FROM obras AS o INNER JOIN clientes AS c ON c.idCliente = o.id_fk_cliente INNER JOIN resp_obras AS r ON r.idResp = o.id_fk_respObra  WHERE o.idObra = :idObra", array(
            ":idObra"=>$idObra
        ));

        $this->setData($results[0]);
    }

    public function getReposible($idCliente){
        
        $sql = new Sql();

        $results = $sql->select("SELECT r.idResp, r.respObra FROM resp_obras r WHERE r.id_fk_cliente = :idCliente", array(
            ":idCliente"=>$idCliente
        ));

        $this->setData($results);

    }

// Método para listar todos os registros
    public static function listAll($idCliente = false){

        $sql = new Sql();
        
        $query = "SELECT * FROM obras";
        $arrValues = [];

        if($idCliente){
            $query .= " WHERE id_fk_cliente = $idCliente";
        }

        return json_encode( $sql->select($query));
    }


    public static function total($idCliente) { //retorna a quantidade todal de registros na tabela

        $sql = new Sql();

        $results = $sql->select("SELECT * FROM obras WHERE id_fk_cliente = :idCliente", array(
            ":idCliente"=>$idCliente
        ));

        return count($results);		
	}
    

    public function get_datatable($requestData, $column_search, $column_order, $idCliente){
        
           $sql = new Sql(); 
        
        $query = "SELECT o.idObra, o.codObra, r.respObra, o.cidade, o.endereco FROM obras AS o INNER JOIN resp_obras AS r ON r.idResp = o.id_fk_respObra";

        if (!empty($requestData['search']['value'])) { //verifica se eu digitei algo no campo de filtro

            $first = TRUE;

            foreach ($column_search as $field) {

                $search = strtoupper($requestData['search']['value']); //tranforma em maiúsculo

                //filtra no banco
                if ($first) {
                    $query .= " WHERE o.id_fk_cliente = $idCliente AND ($field LIKE '$search%'"; //primeiro caso
                    $first = FALSE;
                } else{
                    $query .= " OR $field LIKE '$search%'";
                }
            } //fim do foreach
            if (!$first) {
                $query .= ")"; //termina o WHERE e a query
            }

        }
        else{
            $query.= " WHERE (o.id_fk_cliente = $idCliente)";
        }
        

        $res = $this->searchAll($query);
        $this->setTotalFiltered(count($res));

        //ordenar o resultado
        $query .= " ORDER BY o." . $column_order[$requestData['order'][0]['column']] . " " . $requestData['order'][0]['dir'] . 
        "  LIMIT " . $requestData['start'] . " ," . $requestData['length'] . "   "; 
        //echo $query;

        $construction = new Construction();
        return array(
            'totalFiltered'=>$this->getTotalFiltered(),
            'data'=>$construction->searchAll($query)
        );
    }

    
    public function searchAll($query){ //pesquisa genérica (para todos os campos). Recebe uma query

        $sql = new Sql();

        $results = $sql->select($query);

        return $results;

    }

    public function insert($idCliente){
        //echo "idCliente: " . $idCliente;
        // $this->setid_fk_cliente($idCliente);
        // $this->setid_fk_respObra(2);

       // print_r($_POST);
        $sql = new Sql();

        if(($this->getcodObra() != "0" )){
            
            $results = $sql->select("CALL sp_obras_save(:codObra, :complemento, :cidade, :bairro, :numero, :uf, :cep, :endereco, :id_fk_cliente, :id_fk_respObra)", array(
                ":codObra"=>$this->getcodObra(),
                ":complemento"=>$this->getcomplemento(),
                ":cidade"=>$this->getcidade(),
                ":bairro"=>$this->getbairro(),
                ":numero"=>$this->getnumero(),
                ":uf"=>$this->getuf(),
                ":cep"=>$this->getcep(),
                ":endereco"=>$this->getendereco(),
                ":id_fk_cliente"=>$idCliente,
                ":id_fk_respObra"=>$this->getrespObra()
            ));

           // print_r($)
         
            if(count($results) > 0){

                $this->setData($results[0]); //carrega atributos desse objeto com o retorno da inserção no banco

                return json_encode($results[0]);

            }else{
                return json_encode([
                    "error"=>true,
                    "msg"=>"Erro ao inserir Obra!"
                    ]);
            }
       
        }else{

            return json_encode([
				'error' => true,
				"msg" => "Campos incompletos!"
			]);
        }
    }
    
    public static function searchCompany($name){ //search if name or user already exists

        $sql = new Sql();

        $results = $sql->select("SELECT * FROM obras WHERE (nome = :nome)", array(
            ":nome"=>$name
        ));

        return $results;
    }

    public function showsNextNumber($idCliente){
        $sql = new Sql();

        $results = $sql->select("SELECT MAX(codObra) FROM obras WHERE id_fk_cliente = :id_fk_cliente", array(
            ":id_fk_cliente"=>$idCliente
        ));

        $nextNumber = 1 + $results[0]['MAX(codObra)'];
       
        // if($nextNumber < 10){
        //     $nextNumber = "00". $nextNumber;

        // }else if($nextNumber < 100){
        //     $nextNumber = "0". $nextNumber;
            
        // }
        
        return $nextNumber; //retorna o próximo número de série da categoria

    }

    public function update($idCliente){

        $sql = new Sql();
        //print_r($_POST);

        $results = $sql->select("CALL sp_obrasUpdate_save(:idObra, :codObra, :complemento, :cidade, :bairro, :numero, :uf, :cep, :endereco, :id_fk_cliente, :id_fk_respObra)", array(
            ":idObra"=>$this->getIdObra(),
            ":codObra"=>$this->getcodObra(),
            ":complemento"=>$this->getcomplemento(),
            ":cidade"=>$this->getcidade(),
            ":bairro"=>$this->getbairro(),
            ":numero"=>$this->getnumero(),
            ":uf"=>$this->getuf(),
            ":cep"=>$this->getcep(),
            ":endereco"=>$this->getendereco(),
            ":id_fk_cliente"=>$this->getid_fk_cliente(),
            ":id_fk_respObra"=>$this->getrespObra()
        ));

        if(count($results) > 0){

            $this->setData($results[0]); //carrega atributos desse objeto com o retorno da atualização no banco

            return json_encode($results[0]);

        }else{
            return json_encode([
                "error"=>true,
                "msg"=>"Erro ao atualizar Obra!"
                ]);
        }

    }

    public function delete(){

        $sql = new Sql();

        try{
            $sql->query("CALL sp_obras_delete(:idObra)", array(
                ":idObra"=>$this->getidObra()
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


