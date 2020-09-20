<?php

namespace Locacao\Model;

use Exception;
use \Locacao\DB\Sql;
use \Locacao\Generator;

class Freight extends Generator {

    
    public static function listAll(){

        $sql = new Sql();

        $results = $sql->select("SELECT * FROM fretes ORDER BY idLocacao");
    
        return json_encode($results);
    }
}