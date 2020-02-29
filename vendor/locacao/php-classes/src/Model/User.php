<?php

namespace Locacao\Model;

use \Locacao\DB\Sql;
use \Locacao\Generator;
use \Locacao\Mailer;


class User extends Generator{

    const SESSION = "User";
    const SECRET = "Loc_Secret"; //here your key (secret)
    const SECRET_IV = "Loc_Secret_IV"; //here your key 2 (secret)


    public static function login($login, $password){

        $sql = new Sql();
        
        $results = $sql->select("SELECT * FROM usuarios WHERE nomeUsuario = :LOGIN", array(
            ":LOGIN"=>$login 
        ));

        if(count($results) === 0) //se não encontrou o login
        {
            throw new \Exception("Usuário inexistente ou senha inválida.");
        }

        $data = $results[0];

        if(password_verify($password, $data["senha"]) === true){ //se a senha digitada é equivalente ao Hash do banco
            
            $user = new User();

            $user->setData($data);

            $_SESSION[User::SESSION] = $user->getValues();
            
            return $user;
            
        }else {
            throw new \Exception("Usuário inexistente ou senha inválida.");
        }

    }

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

    public static function logout(){
        
        $_SESSION[User::SESSION] = NULL;
    }

    public static function listAll(){

        $sql = new Sql();

        return json_encode( $sql->select("SELECT  idUsuario, nomeCompleto, funcao, nomeUsuario, email, administrador, foto, dtCadastro FROM usuarios ORDER BY nomeUsuario"));
    }


    public function save(){
        
        $sql = new Sql();

        if(($this->getnomeCompleto() != "") && ($this->getfuncao() != "") && ($this->getemail() != "")){
           
            $results = $sql->select("CALL sp_usuarios_save(:nomeCompleto, :funcao, :nomeUsuario, :senha, :email, :administrador, :foto)", array(
                ":nomeCompleto"=>$this->getnomeCompleto(),
                ":funcao"=>$this->getfuncao(),
                ":nomeUsuario"=>$this->getnomeUsuario(),
                ":senha"=>$this->getsenha(),
                ":email"=>$this->getemail(),
                ":administrador"=>$this->getadministrador(),
                ":foto"=>$this->getfoto()
            ));


            if(count($results) > 0){
                $this->setidUsuario($results[0]['idUsuario']); //esse id vai ser usado para colocar o nome da foto

                return json_encode($results[0]);

            }else{
                return json_encode([
                    "error"=>true,
                    "msg"=>"Erro ao inserir Usuário!"
                    ]);
            }

            $this->setData($results[0]); //carrega atributos desse objeto com o retorno da inserção no banco
        
        }else{

            return json_encode([
				'error' => true,
				"msg" => "Campos incompletos!"
			]);
        }
    }

    public function get($iduser){

        $sql = new Sql();

        $results = $sql->select("SELECT idUsuario, nomeCompleto, funcao, nomeUsuario, email, administrador, foto, dtCadastro FROM usuarios WHERE idUsuario = :idUsuario", array(
            ":idUsuario"=>$iduser
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
        
        $query = "SELECT idUsuario, nomeCompleto, funcao, nomeUsuario, email, administrador, foto, dtCadastro FROM usuarios";

        if (!empty($requestData['search']['value'])) {

            $first = TRUE;

            foreach ($column_search as $field) {

                $search = strtoupper($requestData['search']['value']);

                if ($field == "administrador") {

                    if ($search == "SIM") {
                        $search = 1;
                    } else if (($search == "NãO") || ($search == "NÃO") || ($search == "NAO")) {
                        $search = 0;
                    }
                }

                if ($first) {
                    $query .= " WHERE ($field LIKE '$search%'"; //primeiro caso
                    $first = FALSE;
                } else {
                    $query .= " OR $field LIKE '$search%'";
                }
            }
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

    public function searchAll($query){

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

        $results = $sql->select("CALL sp_usuariosUpdate_save(:idUsuario, :nomeCompleto, :funcao, :nomeusuario, :senha, :email, :administrador, :foto", array(
            ":idUsuario"=>$this->getidUsuario(),
            ":nomeCompleto"=>$this->getnomeCompleto(),
            ":funcao"=>$this->getfuncao(),
            ":nomeUsuario"=>$this->getnomeUsuario(),
            ":senha"=>$this->getsenha(),
            ":email"=>$this->getemail(),
            ":administrador"=>$this->getadministrador(),
            ":foto"=>$this->getfoto()
        ));

        $this->setData($results[0]);
    }

    public function delete(){

        $sql = new Sql();

        $sql->query("CALL sp_usuarios_delete(:idUsuario)", array(
            ":idUsuario"=>$this->getidUsuario()
        ));
    }



}
