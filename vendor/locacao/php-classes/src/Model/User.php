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



}
