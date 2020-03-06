<?php

namespace Locacao\Model;

use Exception;
use \Locacao\DB\Sql;
use \Locacao\Generator;
//use \Locacao\Mailer;

class Client extends Generator{
 


    public static function verifyLogin(){

        if(
            !isset($_SESSION[User::SESSION])
            ||
            !$_SESSION[User::SESSION]
            ||
            !(int)$_SESSION[User::SESSION]["idUsuario"] > 0 //se é um usuário. obs: se for vazia, transforma em 0
        ){

            header("Location: /login");
            exit;
            
        }
    }


// Método para listar todos os registros
    public static function listAll(){

        $sql = new Sql();

        return json_encode( $sql->select("SELECT * FROM clientes ORDER BY nome"));
    }

// Método para inserir novos registros
    public function insert(){
        
        $sql = new Sql();

        if(($this->getNome() != "") && ($this->getEndereco() != "") && ($this->getTipoCliente() != "")){
           
            $results = $sql->select("CALL sp_usuarios_save(:idCliente, :nome, :status, :telefone1, :telefone2, :email1, :email2, :endereco, :tipoEndereco,
                                     :complemento, :cidade, :bairro, :numero, :uf, :cep, :cpf, :rg, :cnpj, :ie, tipoCliente)", array(
                ":idCliente"=>$this->getidCliente(),
                ":nome"=>$this->getNome(),
                ":status"=>$this->getStatus(),
                ":telefone1"=>$this->getTelefone1(),
                ":telefone2"=>$this->gettelefone2(),
                ":email1"=>$this->getEmail1(),
                ":email2"=>$this->getEmail2(),
                ":endereco"=>$this->getEndereco(),
                ":tipoEndereco"=>$this->getTipoEndereco(),
                ":complemento"=>$this->getComplemento(),
                ":cidade"=>$this->getCidade(),
                ":bairro"=>$this->getBairro(),
                ":numero"=>$this->getNumero(),
                ":uf"=>$this->getUf(),
                ":cep"=>$this->getCep(),
                ":rg"=>$this->getRg(),
                ":cnpj"=>$this->getCnpj(),
                ":tipoCliente"=>$this->getTipoCliente()                        
            ));


            if(count($results) > 0){

                $this->setData($results[0]); //carrega atributos desse objeto com o retorno da inserção no banco

                return json_encode($results[0]);

            }else{
                return json_encode([
                    "error"=>true,
                    "msg"=>"Erro ao inserir Usuário!"
                    ]);
            }
       
        }else{

            return json_encode([
				'error' => true,
				"msg" => "Campos incompletos!"
			]);
        }
    }

// Método para fazer a consulta no banco pelo ID   
    public function get($idCliente){

        $sql = new Sql();

        $results = $sql->select("SELECT * FROM clientes WHERE idCliente = :idCliente", array(
            ":idCliente"=>$idCliente
        ));

        $this->setData($results[0]);
    }


    public static function searchName($name){ //search if name or user already exists

        $sql = new Sql();

        $results = $sql->select("SELECT * FROM usuarios WHERE (nomeCompleto = :nomeCompleto)", array(
            ":nomeCompleto"=>$name
        ));

        return $results;
    }

    public static function searchUser($user){ //search if name or user already exists

        $sql = new Sql();

        $results = $sql->select("SELECT * FROM usuarios WHERE (nomeUsuario = :nomeUsuario)", array(
            ":nomeUsuario"=>$user
        ));

        return $results;
    }

    public function get_datatable($requestData, $column_search, $column_order){
        
        $query = "SELECT * FROM clientes";

        if (!empty($requestData['search']['value'])) { //verifica se eu digitei algo no campo de filtro

            $first = TRUE;

            foreach ($column_search as $field) {

                $search = strtoupper($requestData['search']['value']); //tranforma em maiúsculo

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
        
        $users = new User();
        return array(
            'totalFiltered'=>$this->getTotalFiltered(),
            'data'=>$users->searchAll($query)
        );
    }

    public function searchAll($query){ //pesquisa genérica (para todos os campos). Recebe uma query

        $sql = new Sql();

        $results = $sql->select($query);

        return $results;

    }

    public static function total() { //retorna a quantidade todal de registros na tabela

        $sql = new Sql();

        $results = $sql->select("SELECT * FROM usuarios");

        return count($results);		
	}
    

    public function update(){

        $sql = new Sql();

        $results = $sql->select("CALL sp_usuariosUpdate_save(:idUsuario, :nomeCompleto, :funcao, :nomeUsuario, :email, :administrador, :foto)", array(
            ":idUsuario"=>$this->getidUsuario(),
            ":nomeCompleto"=>$this->getnomeCompleto(),
            ":funcao"=>$this->getfuncao(),
            ":nomeUsuario"=>$this->getnomeUsuario(),
            //":senha"=>$this->getsenha(),
            ":email"=>$this->getemail(),
            ":administrador"=>$this->getadministrador(),
            ":foto"=>$this->getfoto()
        ));

        if(count($results) > 0){

            $this->setData($results[0]); //carrega atributos desse objeto com o retorno da atualização no banco

            return json_encode($results[0]);

        }else{
            return json_encode([
                "error"=>true,
                "msg"=>"Erro ao atualizar Usuário!"
                ]);
        }

    }

    public function delete(){

        $sql = new Sql();

        try{
            $sql->query("CALL sp_usuarios_delete(:idUsuario)", array(
                ":idUsuario"=>$this->getidUsuario()
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
