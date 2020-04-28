<?php

namespace Locacao\Model;

use Exception;
use \Locacao\DB\Sql;
use \Locacao\Generator;
//use \Locacao\Mailer;

class Costumer extends Generator{
 

// Método para fazer a consulta no banco pelo ID   
    public function get($idCliente){

        $sql = new Sql();

        $results = $sql->select("SELECT * FROM clientes WHERE idCliente = :idCliente", array(
            ":idCliente"=>$idCliente
        ));

        $this->setData($results[0]);
    }

// Método para listar todos os registros
    public static function listAll(){

        $sql = new Sql();

        return json_encode( $sql->select("SELECT * FROM clientes ORDER BY nome"));
    }


    public static function total() { //retorna a quantidade todal de registros na tabela

        $sql = new Sql();

        $results = $sql->select("SELECT * FROM clientes");

        return count($results);		
	}
    

    public function get_datatable($requestData, $column_search, $column_order){
        
        $query = "SELECT * FROM clientes";

        if (!empty($requestData['search']['value'])) { //verifica se eu digitei algo no campo de filtro

            $first = TRUE;

            foreach ($column_search as $field) {

                $search = strtoupper($requestData['search']['value']); //tranforma em maiúsculo

                $val = strtoupper($search);

                if ($field == "status") {

                    if ($search == $val) {
                        $search = 0;
                    } else if (($search == $val)) {
                        $search = 1;
                    }
                }


                if ($field == "tipoCliente") {
                    
                    if (($val == "FISICA") || ($val == "FíSICA") || ($val == "FÍSICA")) {
                        $val = "F";
                    } else if (($val == "JURIDICA") || ($val == "JURíDICA") || ($val == "JURÍDICA")) {
                        $search = "J";
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
        
        $costumers = new Costumer();
        return array(
            'totalFiltered'=>$this->getTotalFiltered(),
            'data'=>$costumers->searchAll($query)
        );
    }

    
    public function searchAll($query){ //pesquisa genérica (para todos os campos). Recebe uma query

        $sql = new Sql();

        $results = $sql->select($query);

        return $results;

    }

    public function insert(){
        
        $sql = new Sql();

        if(($this->getnome() != "") && ($this->getstatus() != "") && ($this->gettipoCliente () != "")){
           
            $results = $sql->select("CALL sp_clientes_save(:codigo, :nome, :status, :telefone1, :telefone2, :email1, :email2, :endereco, :complemento, :cidade, :bairro, :numero, :uf, :cep, :cpf, :rg, :cnpj, :ie, :tipoCliente)", array(
                ":codigo"=>$this->getcodigo(),
                ":nome"=>$this->getnome(),
                ":status"=>$this->getstatus(),
                ":telefone1"=>$this->gettelefone1(),
                ":telefone2"=>$this->gettelefone2(),
                ":email1"=>$this->getemail1(),
                ":email2"=>$this->getemail2(),
                ":endereco"=>$this->getendereco(),
                ":complemento"=>$this->getcomplemento(),
                ":cidade"=>$this->getcidade(),
                ":bairro"=>$this->getbairro(),
                ":numero"=>$this->getnumero(),
                ":uf"=>$this->getuf(),
                ":cep"=>$this->getcep(),
                ":cpf"=>$this->getcpf(),
                ":rg"=>$this->getrg(),
                ":cnpj"=>$this->getcnpj(),
                ":ie"=>$this->getie(),
                ":tipoCliente"=>$this->gettipoCliente(),
            ));


            if(count($results) > 0){

                $this->setData($results[0]); //carrega atributos desse objeto com o retorno da inserção no banco

                return json_encode($results[0]);

            }else{
                return json_encode([
                    "error"=>true,
                    "msg"=>"Erro ao inserir Cliente!"
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

        $results = $sql->select("SELECT * FROM clientes WHERE (nome = :nome)", array(
            ":nome"=>$name
        ));

        return $results;
    }

    public function update(){


        $sql = new Sql();

        $results = $sql->select("CALL sp_clientesUpdate_save(:idCliente, :codigo, :nome, :status, :telefone1, :telefone2, :email1, :email2, :endereco, :complemento, :cidade, :bairro, :numero, :uf, :cep, :cpf, :rg, :cnpj, :ie, :tipoCliente)", array(
            ":idCliente"=>$this->getidCliente(),
            ":codigo"=>$this->getcodigo(),
            ":nome"=>$this->getnome(),
            ":status"=>$this->getstatus(),
            ":telefone1"=>$this->gettelefone1(),
            ":telefone2"=>$this->gettelefone2(),
            ":email1"=>$this->getemail1(),
            ":email2"=>$this->getemail2(),
            ":endereco"=>$this->getendereco(),
            ":complemento"=>$this->getcomplemento(),
            ":cidade"=>$this->getcidade(),
            ":bairro"=>$this->getbairro(),
            ":numero"=>$this->getnumero(),
            ":uf"=>$this->getuf(),
            ":cep"=>$this->getcep(),
            ":cpf"=>$this->getcpf(),
            ":rg"=>$this->getrg(),
            ":cnpj"=>$this->getcnpj(),
            ":ie"=>$this->getie(),
            ":tipoCliente"=>$this->gettipoCliente(),
        ));

        if(count($results) > 0){

            $this->setData($results[0]); //carrega atributos desse objeto com o retorno da atualização no banco

            return json_encode($results[0]);

        }else{
            return json_encode([
                "error"=>true,
                "msg"=>"Erro ao atualizar Cliente!"
                ]);
        }

    }

    public static function showsNextNumber(){

        $sql = new Sql();

        $results = $sql->select("SELECT MAX(codigo) FROM clientes");
        $nextNumber = 1 + $results[0]['MAX(codigo)'];
       
        if($nextNumber < 10){
            $nextNumber = "00". $nextNumber;

        }else if($nextNumber < 100){
            $nextNumber = "0". $nextNumber;
            
        }
        
        return $nextNumber; //retorna o próximo número de série da categoria

    }

    public function delete(){

        $sql = new Sql();

      $results = $sql->select("SELECT idResp FROM resp_obras WHERE id_fk_cliente = :idCliente ", array(
            ":idCliente"=>$this->getidCliente()
        ));        

        if(count($results) > 0){
            
            echo json_encode([
                "error"=>true,
                "msg"=>"Você não pode excluir, pois este cliente tem Responsáveis de Obra"
            ]);
        }else{
            
        try{
            $sql->query("CALL sp_clientes_delete(:idCliente)", array(
                ":idCliente"=>$this->getidCliente()
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

}
