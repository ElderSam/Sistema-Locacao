<?php

namespace Locacao\Model;

use Exception;
use \Locacao\DB\Sql;
use \Locacao\Generator;
use stdClass;


class Invoice extends Generator { //classe de Fatura

    public function insert($dataNewFatura)
    {
        echo "<br><br>INSERT: ";
        $dataNewFatura = json_decode($dataNewFatura, true);
        //print_r($dataNewFatura);
        $fatura = $dataNewFatura['fatura'];
        print_r($fatura);
        $this->setData($fatura);
       // print_r($dataNewFatura->fatura->idContrato);

        $sql = new Sql();
        $newFatura = $sql->select("CALL sp_faturas_save(
            idContrato,
            numFatura,
            dtEmissao,
            enviarPorEmail,
            emailEnvio,
            dtEnvio,
            adicional,
            valorTotal,
            observacoes    
            )", array(

            //':idFatura'=>$this->getidFatura(),
            ':idContrato'=>$this->getidContrato(),
            ':numFatura'=>$this->getnumFatura(),
            ':dtEmissao'=>$this->getdtEmissao(),
            ':enviarPorEmail'=>$this->getenviarPorEmail(),
            ':emailEnvio'=>$this->getemailEnvio(),
            ':dtEnvio'=>$this->getdtEnvio(),
            ':adicional'=>$this->getadicional(),
            ':valorTotal'=>$this->getvalorTotal(),
            ':observacoes'=>$this->getobservacoes()
        ));

        if(count($newFatura) > 0)
        {
            $this->setData($newFatura[0]); //carrega atributos desse objeto com o retorno da inserção no banco
            return json_encode($newFatura[0]);

        }else{
            return json_encode([
                "error"=>true,
                "msg"=>"Erro ao inserir Fatura!"
            ]);
        }
    }

    function getContracts($idContrato=false) { // pega todos os contratos que possuem alugueis
        $arrContratos = array();

        $sql = new Sql();

        $query = "SELECT idContrato, codContrato, statusOrcamento as status, obra_idObra, dtFim as dtFimContrato,
            temMedicao, regraFatura, semanaDoMes, diaFatura
            FROM contratos
            WHERE statusOrcamento IN(4)"; //somente contratos vigentes

        if($idContrato)
        {
            $query .= " AND idContrato = $idContrato";
        }

        $contratos = $sql->select($query);
        //print_r($contratos);

        if(count($contratos) > 0) {

            foreach($contratos as $contrato) {
                //print_r($contrato);
                $alugueis = $this->getRentsByContract($contrato['idContrato']);

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

    function getRentsByContract($idContrato)
    {
        $sql = new Sql();

        $query = "SELECT a.*, c.periodoLocacao FROM `historicoalugueis` a
        INNER JOIN `produtos_esp` b ON(b.idProduto_esp = a.produto_idProduto)
        INNER JOIN `contrato_itens` c ON(c.idProduto_gen = b.idProduto_gen)
        WHERE (a.contrato_idContrato = :IDCONTRATO AND a.status NOT IN(0))
        GROUP BY a.idHistoricoAluguel
        ORDER BY dtInicio ASC";

        return $sql->select($query, array(
            ":IDCONTRATO"=>$idContrato
        ));
    }

    function getultimaFatura($idContrato) {
        $sql = new Sql();
        $query = "SELECT DISTINCT * FROM `faturas`
            WHERE idContrato = :IDCONTRATO
            ORDER BY dtEmissao DESC
            LIMIT 1";

        $fatura = [];
        $fatura = $sql->select($query, array(
            ":IDCONTRATO"=>$idContrato
        ));

        $itensFatura = [];

        if(count($fatura) > 0)
        {              
            $this->setidFatura($fatura[0]['idFatura']);

            $query = "SELECT DISTINCT * FROM `fatura_itens`
                WHERE idFatura = :IDFATURA
                ORDER BY dtFim DESC
                LIMIT 1";

            $itensFatura = $sql->select($query, array(
                ":IDFATURA"=>$this->getidFatura()
            ));

        }else {
            //echo "CONTRATO SEM FATURA AINDA";
           
        }

        return json_encode([
            'fatura'=>$fatura,
            'itens_fatura'=>$itensFatura
        ]);
    }

}
