<?php

namespace Locacao\Model;

use Exception;
use \Locacao\DB\Sql;
use \Locacao\Generator;
use stdClass;


class Invoice extends Generator { //classe de Fatura

    function getContracts() { // pega todos os contratos que possuem alugueis
        $arrContratos = array();

        $sql = new Sql();

        $query = "SELECT idContrato, codContrato, statusOrcamento, obra_idObra, dtFim as dtFimContrato,
            temMedicao, regraFatura, semanaDoMes, diaFatura
            FROM contratos
            WHERE statusOrcamento IN(2, 3, 4)";

        $contratos = $sql->select($query);
        //print_r($contratos);

        if(count($contratos) > 0) {

            foreach($contratos as $contrato) {
                //print_r($contrato);

                $query = "SELECT * FROM historicoalugueis
                    WHERE contrato_idContrato = :IDCONTRATO";

                $alugueis = $sql->select($query, array(
                    ":IDCONTRATO"=>$contrato['idContrato']
                ));

                $obj = new stdClass();
                $obj->contrato = $contrato;
                $obj->alugueis = $alugueis;

                $arrContratos[] = $obj;
            }
        }

        $arrContratos = json_encode($arrContratos);
        //print_r($arrContratos);
        return $arrContratos;
    }

}
