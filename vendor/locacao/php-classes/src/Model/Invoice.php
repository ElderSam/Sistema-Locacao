<?php

namespace Locacao\Model;

use Exception;
use \Locacao\DB\Sql;
use \Locacao\Generator;
use stdClass;


class Invoice extends Generator { //classe de Fatura

    public function getMaxNumFatura() {
        //consulta no banco, pega o maior número de fatura no ano (numFatura-ano da dtEmissao)
        $sql = new Sql();
        $query = "SELECT MAX(numFatura) from faturas";
        $res = $sql->select($query);

        echo substr($res['0'], -4, 5);
    }
    public function insert()
    {
        $sql = new Sql();
        $newFatura = $sql->select("CALL sp_faturas_save(
            :idContrato,
            :numFatura,
            :dtEmissao,
            :dtInicio,
            :dtFim,
            :enviarPorEmail,
            :emailEnvio,
            :dtEnvio,
            :adicional,
            :valorTotal,
            :observacoes,

            :formaPagamento,
            :dtVencimento,
            :especCobranca,       
            :dtCobranca,
            :statusPagamento,     
            :numNF,
            :numBoletoInt,
            :numBoletoBanco,
            :valorPago,
            :dtPagamento,
            :dtVerificacao
            )", array(

            //':idFatura'=>$this->getidFatura(),
            ':idContrato'=>$this->getidContrato(),
            ':numFatura'=>$this->getnumFatura(),
            ':dtEmissao'=>$this->getdtEmissao(),
            ':dtInicio'=>$this->getdtInicio(),
            ':dtFim'=>$this->getdtFim(),
            ':enviarPorEmail'=>$this->getenviarPorEmail(),
            ':emailEnvio'=>$this->getemailEnvio(),
            ':dtEnvio'=>$this->getdtEnvio(),
            ':adicional'=>$this->getadicional(),
            ':valorTotal'=>$this->getvalorTotal(),
            ':observacoes'=>$this->getobservacoes(),

            //':idFatura'=>$this->getidFatura(),
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
                "msg"=>"Erro ao inserir Fatura!"
            ]);
        }
    }

    public function update()
    {      
        $sql = new Sql();
        $update = $sql->select("CALL sp_faturasUpdate_save(
            idFatura,
            idContrato,
            numFatura,
            dtEmissao,
            dtInicio,
            dtFim,
            enviarPorEmail,
            emailEnvio,
            dtEnvio,
            adicional,
            valorTotal,
            observacoes,
            
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

            ':idFatura'=>$this->getidFatura(),
            ':idContrato'=>$this->getidContrato(),
            ':numFatura'=>$this->getnumFatura(),
            ':dtEmissao'=>$this->getdtEmissao(),
            ':dtInicio'=>$this->getdtInicio(),
            ':dtFim'=>$this->getdtFim(),
            ':enviarPorEmail'=>$this->getenviarPorEmail(),
            ':emailEnvio'=>$this->getemailEnvio(),
            ':dtEnvio'=>$this->getdtEnvio(),
            ':adicional'=>$this->getadicional(),
            ':valorTotal'=>$this->getvalorTotal(),
            ':observacoes'=>$this->getobservacoes(),

            //':idCobranca'=>$this->getidCobranca(),
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

        if(count($update) > 0)
        {
            $this->setData($update[0]); //carrega atributos desse objeto com o retorno da atualização no banco
            return json_encode($update[0]);

        }else{
            return json_encode([
                "error"=>true,
                "msg"=>"Erro ao atualizar Fatura!"
            ]);
        }
    }

    public static function searchAll($query) {
        $sql = new Sql();
        return $sql->select($query);
    }

    public function delete(){     
        $sql = new Sql();
        
        try{
            $sql->query("CALL sp_faturas_delete(:idFatura)", array(
                ":idFatura"=>$this->getidFatura()
            ));

            if($this->get($this->getidFatura())){

                return json_encode([
                    "error"=>true,
                    "msg"=>"Erro ao excluir Fatura"
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
    
    public static function listAll()
    {
        $sql = new Sql();
        $results = $sql->select("SELECT * FROM faturas ORDER BY idFatura");   
        return json_encode($results);
    }

    public static function total() 
    {
        $sql = new Sql();
        $results = $sql->select("SELECT * FROM faturas");
        return count($results);
    }

    public function get($id){
        $sql = new Sql();

        $results = $sql->select("SELECT * FROM faturas WHERE idFatura = :id", array(
            ":id"=>$id
        ));

        if(count($results) > 0){
            $this->setData($results[0]);
        }
    }

    public function get_datatable_invoices($requestData, $column_search, $idRent)
    {
        $query = "SELECT a.idFatura, a.numFatura, a.dtEmissao, a.valorTotal,
            b.dtVencimento, b.statusPagamento,
            e.nome as nomeCliente
            FROM faturas a
            INNER JOIN fatura_cobrancas b ON(b.idFatura = a.idFatura)
            INNER JOIN contratos c ON(c.idContrato = a.idContrato)
            INNER JOIN obras d ON(d.idObra = c.obra_idObra)
            INNER JOIN clientes e ON(e.idCliente = d.id_fk_cliente)";
            
            //numFatura, statusPagamento, dtEmissao, dtVencimento, (vlTotal+adicional), cliente

        if (!empty($requestData['search']['value'])) //verifica se eu digitei algo no campo de filtro
        { 
            $first = TRUE;

            foreach ($column_search as $field)
            {     
                $search = strtoupper($requestData['search']['value']); //tranforma em maiúsculo

                //filtra no banco
                if ($first) 
                {
                    $query .= " WHERE ($field LIKE '%$search%'"; //primeiro caso
                    $first = FALSE;
                
                } else {
                     
                    if($field == "statusPagamento") //--------------
                    {
                        $aux = strtoupper($search); //deixa a string em maiúsculo
                        $aux = substr($aux, 0, 5); //pega os 5 primeiros caracteres

                        //0-pendente, 1-parcial, 2-pago, 3-cancelado, 4-perdido
                        switch($aux) { 
                            case "PEND": //pendente
                                $value = 0;
                                break;
                            case "PARC": //parcial
                                $value = 1;
                                break;
                            case "PAGO": //pago
                                $value = 2;             
                                break;
                            case "CANC": //cancelado
                                $value = 3;
                                break;
                        }
                
                        if(isset($value)){
                            $query .= " OR $field = $value";
                        }
                    } else if(($field == "dtEmissao") || ($field == "dtVencimento")) //----------------------------
                    { 
                        if(strlen($search) >= 10){ //precisa digitar a data completa no campo pesquisar, ex: 20/09/2020
              
                            //trata a data (dia/mes/ano -> ano-mes-dia)
                            $aux = str_replace("/", "-", $search);
                            $data = date('Y-m-d', strtotime($aux));

                            $data .= substr($search, 10, strlen($search) ); //pega o resto da string (as horas)
                            //echo "$field: $data";

                            $query .= " OR $field = '$data'";

                        }else {
                            $query .= " OR $field = '$search'";
                        }
                    } //----------------------------
 
                } //fim do primeiro else

            } //fim do foreach

            if (!$first) {
                $query .= ")"; //termina o WHERE e a query
            }

        }

        //print_r($query);
        $res = $this->searchAll($query);
        $this->setTotalFiltered(count($res));

        //ordenar o resultado
        $query .= " ORDER BY b.statusPagamento, b.dtVencimento " . $requestData['order'][0]['dir'] . 
        "  LIMIT " . $requestData['start'] . " ," . $requestData['length'] . "   "; 
        
        $freights = new Freight();
        //echo $query;
        
        return array(
            'totalFiltered'=>$this->getTotalFiltered(),
            'data'=>$freights->searchAll($query)
        );

    } // fim do método get_datatable()

    public function loadInvoice($id)
    {
        //echo "loadInvoice id: $id";
        $sql = new Sql();

        $query = "SELECT * FROM faturas a
        INNER JOIN fatura_cobrancas b ON(b.idFatura = a.idFatura)
        WHERE idFatura = :id";

        $rent = $sql->select($query, array(
            ":id"=>$id
        ));

        return json_encode($rent[0]);
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