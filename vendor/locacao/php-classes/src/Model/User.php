<?php

namespace Locacao\Model;

use \Locacao\DB\Sql;
use \Locacao\Model;
use \Locacao\Mailer;


class User extends Model{

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

        return $sql->select("SELECT * FROM usuarios ORDER BY nomeUsuario");
    }

    public function save(){

        $sql = new Sql();

        $results = $sql->select("CALL sp_usuarios_save(:nomeCompleto, :funcao, :nomeusuario, :senha, :email, :administrador, :foto", array(
            ":nomeCompleto"=>$this->getnomeCompleto(),
            ":funcao"=>$this->getfuncao(),
            ":nomeusuario"=>$this->getnomeusuario(),
            ":senha"=>$this->getsenha(),
            ":email"=>$this->getemail(),
            ":administrador"=>$this->getadministrador(),
            ":foto"=>$this->getfoto()
        ));

        $this->setData($results[0]); //carrega atributos desse objeto com o retorno da inserção no banco
    }

    public function get($iduser){

        $sql = new Sql();

        $results = $sql->select("SELECT * FROM usuarios WHERE idUsuario = :idUsuario", array(
            ":idUsuario"=>$iduser
        ));

        $this->setData($results[0]);
    }

    public function update(){

        $sql = new Sql();

        $results = $sql->select("CALL sp_usuariosUpdate_save(:idUsuario, :nomeCompleto, :funcao, :nomeusuario, :senha, :email, :administrador, :foto", array(
            ":idUsuario"=>$this->getidUsuario(),
            ":nomeCompleto"=>$this->getnomeCompleto(),
            ":funcao"=>$this->getfuncao(),
            ":nomeusuario"=>$this->getnomeusuario(),
            ":senha"=>$this->getsenha(),
            ":email"=>$this->getemail(),
            ":administrador"=>$this->getadministrador(),
            ":foto"=>$this->getfoto()
        ));

        $this->setData($resulst[0]);
    }

    public function delete(){

        $sql = new Sql();

        $sql->query("CALL sp_usuarios_delete(:idUsuario)", array(
            ":idUsuario"=>$this->getidUsuario()
        ));
    }

    



}
