<?php

namespace Locacao\Model;

//use Exception;
use \Locacao\DB\Sql;
use \Locacao\Generator;


class Supplier extends Generator{

    public static function listAll(){

        $sql = new Sql();

        $results = $sql->select("SELECT * FROM fornecedores ORDER BY nome");
    
        return json_encode($results);
    }

    
    public function get($idFornecedor){

        $sql = new Sql();

        $results = $sql->select("SELECT * FROM fornecedores WHERE idFornecedor = :idFornecedor", array(
            ":idFornecedor"=>$idFornecedor
        ));

        $this->setData($results[0]);

    }
    
}

