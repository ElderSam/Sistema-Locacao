<?php

namespace Locacao\Model;

//use Exception;
use \Locacao\DB\Sql;
use \Locacao\Generator;


class Category extends Generator{

    public static function listAll(){

        $sql = new Sql();

        return json_encode( $sql->select("SELECT * FROM prod_categorias"));
    }

    public static function listTypes($idCategory){
        
        $sql = new Sql();

        $results = $sql->select("SELECT * FROM prod_tipos a 
        INNER JOIN prod_categorias b ON(a.idCategoria = b.idCategoria)
        WHERE b.idCategoria = :IDCATEGORY ORDER BY a.ordem_tipo, a.codTipo",array(
            ':IDCATEGORY'=> $idCategory
        ));
    
        return json_encode($results);
    }


    public function get($idCategoria){

        $sql = new Sql();

        $results = $sql->select("SELECT * FROM prod_categorias WHERE idCategoria = :idCategoria", array(
            ":idCategoria"=>$idCategoria
        ));

            $this->setData($results[0]);
    }
    
}

