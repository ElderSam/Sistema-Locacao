<?php

namespace Locacao\Model;

use Exception;
use \Locacao\DB\Sql;
use \Locacao\Generator;
use stdClass;


class Invoice extends Generator { //classe de Cobrança de Fatura

    public function insert()
    {
        $sql = new Sql();
        $newFatura = $sql->select("CALL sp_faturas_save(
            idFatura,
            formaPagamento,
            dtVencimento,
            especCobranca,       
            dtCobranca,
            statusPagamento,     
            numNF,
            numBoletoInt,
            numBoletoBanco,
            valorPago,
            dtPagamento,
            dtVerificacao
            
            )", array(
            //':idCobranca'=>$this->getidCobranca(),
            ':idFatura'=>$this->getidFatura(),
            ':formaPagamento'=>$this->getformaPagamento(),
            ':dtVencimento'=>$this->getdtVencimento(),
            ':especCobranca'=>$this->getespecCobranca(),
            ':dtCobranca'=>$this->getdtCobranca(),
            ':statusPagamento'=>$this->getstatusPagamento(),   
            ':numNF'=>$this->getnumNF(),
            ':numBoletoInt'=>$this->getnumBoletoInt(),
            ':numBoletoBanco'=>$this->getnumBoletoBanco(),
            ':valorPago'=>$this->getvalorPago(),
            ':dtPagamento'=>$this->getdtPagamento(),
            ':dtVerificacao'=>$this->getdtVerificacao()
        ));

        if(count($newFatura) > 0)
        {
            $this->setData($newFatura[0]); //carrega atributos desse objeto com o retorno da inserção no banco
            return json_encode($newFatura[0]);

        }else{
            return json_encode([
                "error"=>true,
                "msg"=>"Erro ao inserir cobrança de Fatura!"
            ]);
        }
    }

}
