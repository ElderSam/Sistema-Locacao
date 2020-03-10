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

    public static function listTypes($codCategory){

        $sql = new Sql();

        $results = $sql->select("SELECT * FROM prod_tipos a 
        INNER JOIN prod_categorias b ON(a.idCategoria = b.idCategoria)
        WHERE b.codCategoria = :CODCATEGORY ORDER BY a.ordem_tipo, a.codigo",array(
            ':CODCATEGORY'=> $codCategory
        ));
    
        return json_encode($results);
    }

    public function get($idCategoria, $codCategoria = false){

        $sql = new Sql();

        if(!$idCategoria){
            if($codCategoria){

                $results = $sql->select("SELECT * FROM prod_categorias WHERE idCategoria = :codCategoria", array(
                    ":codCategoria"=>$codCategoria
                ));

                return $results[0]['idCategoria'];
                 
            }

        }else{
            $results = $sql->select("SELECT * FROM prod_categorias WHERE idCategoria = :idCategoria", array(
                ":idCategoria"=>$idCategoria
            ));

            $this->setData($results[0]);
        }


    }



    
}

