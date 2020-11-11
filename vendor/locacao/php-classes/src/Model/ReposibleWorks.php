<?php

namespace Locacao\Model;

use Exception;
use \Locacao\DB\Sql;
use \Locacao\Generator;
//use \Locacao\Mailer;

class ReposibleWorks extends Generator{
 

     // Método para fazer a consulta no banco pelo ID   
    public function get($idReposible){

        $sql = new Sql();

       //$results = $sql->select("SELECT * FROM resp_obras WHERE idResp = :idResp", array(
        $results = $sql->select("SELECT r.idResp, r.codigo, r.respObra, r.telefone1, r.telefone2, r.telefone3, r.email1, r.email2, r.anotacoes, r.dtCadastro, r.id_fk_cliente, c.nome FROM resp_obras r INNER JOIN clientes c on r.id_fk_cliente = c.idCliente WHERE r.idResp = :idResp", array(
            ":idResp"=>$idReposible
        ));

        $this->setData($results[0]);
    }


// Método para listar todos os registros
    public static function listAll(){

        $sql = new Sql();

        return json_encode( $sql->select("SELECT * FROM resp_Obras"));
    }


    public static function total($idCliente) { //retorna a quantidade todal de registros na tabela

        $sql = new Sql();

        $results = $sql->select("SELECT * FROM resp_Obras WHERE id_fk_cliente = :idCliente", array(
            ":idCliente"=>$idCliente
        ));

        return count($results);		
	}
    

    public function get_datatable($requestData, $column_search, $column_order, $idCliente){
        
           $sql = new Sql(); 
        
        $query = "SELECT * FROM resp_Obras";

        if (!empty($requestData['search']['value'])) { //verifica se eu digitei algo no campo de filtro

            $first = TRUE;

            foreach ($column_search as $field) {

                $search = strtoupper($requestData['search']['value']); //tranforma em maiúsculo

                //filtra no banco
                if ($first) {
                    $query .= " WHERE id_fk_cliente = $idCliente AND ($field LIKE '$search%'"; //primeiro caso
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
            $query.= " WHERE (id_fk_cliente = $idCliente)";
        }
        
        $res = $this->searchAll($query);
        $this->setTotalFiltered(count($res));

        //ordenar o resultado
        $query .= " ORDER BY " . $column_order[$requestData['order'][0]['column']] . " " . $requestData['order'][0]['dir'] . 
        "  LIMIT " . $requestData['start'] . " ," . $requestData['length'] . "   "; 
        
        
        $reposible = new ReposibleWorks();
        return array(
            'totalFiltered'=>$this->getTotalFiltered(),
            'data'=>$reposible->searchAll($query)
        );
    }

    
    public function searchAll($query){ //pesquisa genérica (para todos os campos). Recebe uma query

        $sql = new Sql();

        $results = $sql->select($query);

        return $results;

    }

    public function insert($idCliente){
     
        $sql = new Sql();

        if(($this->getrespObra() != "")){
           
            $results = $sql->select("CALL sp_responsaveis_save(:codigo, :respObra, :telefone1, :telefone2, :telefone3, :email1, :email2, :anotacoes, :id_fk_cliente)", array(
                ":codigo"=>$this->getcodigo(),
                ":respObra"=>$this->getrespObra(),
                ":telefone1"=>$this->gettelefone1(),
                ":telefone2"=>$this->gettelefone2(),
                ":telefone3"=>$this->gettelefone3(),
                ":email1"=>$this->getemail1(),
                ":email2"=>$this->getemail2(),
                ":anotacoes"=>$this->getanotacoes(),
                ":id_fk_cliente" => $idCliente
            ));


            if(count($results) > 0){

                $this->setData($results[0]); //carrega atributos desse objeto com o retorno da inserção no banco

                return json_encode($results[0]);

            }else{
                return json_encode([
                    "error"=>true,
                    "msg"=>"Erro ao inserir Responsável!"
                    ]);
            }
       
        }else{

            return json_encode([
				'error' => true,
				"msg" => "Campos incompletos!"
			]);
        }
    }
    
    public static function showsNextNumber($idCliente){

        $sql = new Sql();

        $results = $sql->select("SELECT MAX(codigo) FROM resp_obras WHERE id_fk_cliente = :idCliente", array(
            ":idCliente"=>$idCliente
        ));
        $nextNumber = 1 + $results[0]['MAX(codigo)'];
       
        if($nextNumber < 10){
            $nextNumber = "00". $nextNumber;

        }else if($nextNumber < 100){
            $nextNumber = "0". $nextNumber;
            
        }

        return $nextNumber; //retorna o próximo número de série da categoria

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
        //print_r($_POST);

        $results = $sql->select("CALL sp_responsaveisUpdate_save(:idResp, :codigo, :respObra, :telefone1, :telefone2, :telefone3, :email1, :email2, :anotacoes, :id_fk_cliente)", array(
            ":idResp"=>$this->getid(),
            ":codigo"=>$this->getcodigo(),
            ":respObra"=>$this->getrespObra(),
            ":telefone1"=>$this->gettelefone1(),
            ":telefone2"=>$this->gettelefone2(),
            ":telefone3"=>$this->gettelefone3(),
            ":email1"=>$this->getemail1(),
            ":email2"=>$this->getemail2(),
            ":anotacoes"=>$this->getanotacoes(),
            ":id_fk_cliente"=>$this->getid_fk_cliente()
        ));

        if(count($results) > 0){

            $this->setData($results[0]); //carrega atributos desse objeto com o retorno da atualização no banco

            return json_encode($results[0]);

        }else{
            return json_encode([
                "error"=>true,
                "msg"=>"Erro ao atualizar Responsável!"
                ]);
        }

    }

    public function delete(){

        $sql = new Sql();
            
      $resultsObra = $sql->select("SELECT idObra FROM obras WHERE id_fk_respObra = :idResp", array(

        ":idResp"=>$this->getidResp()
      ));  

        if(count($resultsObra) > 0){

            echo json_encode([
                "error"=>true,
                "msg"=>"Você não pode excluir, pois este Responsável  está sendo usado em Obras"
            ]);

        }
        else{
            try{
                $sql->query("CALL sp_responsaveis_delete(:idResp)", array(
                    ":idResp"=>$this->getidResp()
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


