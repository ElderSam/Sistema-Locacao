<?php

namespace Locacao\Model;

use Exception;
use \Locacao\DB\Sql;
use \Locacao\Generator;

class InvoiceItem extends Generator{
    
    public static function listAll(){

        $sql = new Sql();
        $results = $sql->select("SELECT * FROM fatura_itens ORDER BY idItemFatura");
        return json_encode($results);
    }

    public function insert(){
        
        $sql = new Sql();

        if(($this->getidAluguel() != "") && ($this->getidFatura() != "")){

            $results = $sql->select("CALL sp_fatura_itens_save(:idFatura, :idAluguel, :periodoLocacao, :vlAluguelCobrado, :custoEntrega, :custoRetirada, :dtInicio, :dtFim)", array(
                ":idFatura"=>$this->getidFatura(),
                ":idAluguel"=>$this->getidAluguel(),
                ":periodoLocacao"=>$this->getperiodoLocacao(),
                ":vlAluguelCobrado"=>$this->getvlAluguelCobrado(),
                ":custoEntrega"=>$this->getfrete()['custoEntrega'],
                ":custoRetirada"=>$this->getfrete()['custoRetirada'],
                ":dtInicio"=>$this->getdtInicio(),
                ":dtFim"=>$this->getdtFim()
            ));

            if(count($results) > 0){

                $this->setData($results[0]); //carrega atributos desse objeto com o retorno da inserção no banco
                return json_encode($results[0]);

            }else{
                return json_encode([
                    "error"=>true,
                    "msg"=>"Erro ao inserir item de Fatura!"
                    ]);
            }

        }else{

            return json_encode([
				'error' => true,
				"msg" => "Campos incompletos!"
			]);
        }
    }
    
    public function get($idItem){

        $sql = new Sql();

        $results = $sql->select("SELECT a.*, b.codigoGen FROM fatura_itens a
            INNER JOIN produtos_gen b ON(a.idProduto_gen = b.idProduto_gen)
            WHERE a.idItem = :idItem", array(
            ":idItem"=>$idItem
        ));

        if(count($results) > 0){
            $this->setData($results[0]);

        }
    }

    public function update(){
        
        $sql = new Sql();

        $results = $sql->select("CALL sp_fatura_itensUpdate_save(:idItem, :idFatura, :idAluguel, :periodoLocacao, :vlAluguelCobrado, :custoEntrega, :custoRetirada, :dtInicio, :dtFim)", array(
                ":idItem"=>$this->getidItem(),
                ":idFatura"=>$this->getidFatura(),
                ":idAluguel"=>$this->getidAluguel(),
                ":periodoLocacao"=>$this->getperiodoLocacao(),
                ":vlAluguelCobrado"=>$this->getvlAluguelCobrado(),
                ":custoEntrega"=>$this->getfrete()['custoEntrega'],
                ":custoRetirada"=>$this->getfrete()['custoRetirada'],
                ":dtInicio"=>$this->getdtInicio(),
                ":dtFim"=>$this->getdtFim()
        ));

        if(count($results) > 0){

            $this->setData($results[0]); //carrega atributos desse objeto com o retorno da atualização no banco
            return json_encode($results[0]);

        }else{
            return json_encode([
                "error"=>true,
                "msg"=>"Erro ao atualizar Aluguel!"
                ]);
        }
    }

    public function delete(){
      
        $sql = new Sql();

        try{

            $sql->query("CALL sp_fatura_itens_delete(:idItem)", array(
                ":idItem"=>$this->getidItem()
            ));

            if($this->get($this->getidItem())){

                return json_encode([
                    "error"=>true,
                    "msg"=>"Erro ao excluir Item"
                ]);

            }else{

                return json_encode([
                    "error"=>false,
                ]);
            }

        }catch(Exception $e){

            return json_encode([
                "error"=>true,
                "msg"=>$e->getMessage()
            ]);

        }
    }

}