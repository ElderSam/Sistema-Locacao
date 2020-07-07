<?php

namespace Locacao\Model;

use Exception;
use \Locacao\DB\Sql;
use \Locacao\Model\Budget;

/* Status do Contrato;
2 - vencido 
3 - aprovado 
4 - em andamento/vigência
5 - encerrado */

class Contract extends Budget{

    public function insert(){
        parent::insert();
    }

    public static function showsNextCode($dtEmissao){

        $sql = new Sql();

        $query = "SELECT dtEmissao FROM contratos
            WHERE (dtEmissao = :dtEmissao AND statusOrcamento IN(2, 3, 4, 5))";

        $res = $sql->select($query, array(
            ":dtEmissao"=>$dtEmissao
        ));

        $dtEmissao = strtotime($dtEmissao); //formata o valor para data

        $nextNumber = count($res) + 1;

        $ano = date("Y", $dtEmissao);
        $mes = date("m", $dtEmissao);
        $dia = date("d", $dtEmissao);

        if(strlen($nextNumber) < 3){ //se tiver menos que 3 dígitos
            if(strlen($nextNumber) < 2){
                $nextNumber = '00' . $nextNumber;

            }else{ //se tiver 2 dígitos
                $nextNumber = '0' . $nextNumber;
            }
        }       

        $nextCode = $ano . $mes . $dia . "-" . $nextNumber;

        return $nextCode;
    }

    public static function listAll(){

        $sql = new Sql();
        
        $query = "SELECT * FROM contratos 
            WHERE statusOrcamento IN (2, 3, 4, 5)
            ORDER BY dtEmissao DESC";

        $results = $sql->select($query);
    
        return json_encode($results);
    }

    public function get($idContrato){

        $sql = new Sql();

        $results = $sql->select("SELECT a.*, b.idObra, c.idCliente, c.nome as descCliente FROM contratos a
                    LEFT JOIN obras b ON(a.obra_idObra = b.idObra)
                    LEFT JOIN clientes c ON(b.id_fk_cliente = c.idCliente)
                    WHERE (idContrato = :idContrato AND a.statusOrcamento IN (2, 3, 4, 5))", array(
            ":idContrato"=>$idContrato
        ));

        if(count($results) > 0){
            $this->setData($results[0]);

            //$auxData = strtotime(date("Y-m-d H:i:s"));//para teste
            $auxData = strtotime($results[0]['dtCadastro']);      
            $auxAno = date('Y', $auxData);
            $this->setcodContrato($results[0]['codContrato']);
        }
    }

    public function get_datatable_contracts($requestData, $column_search, $column_order){

        $query = "SELECT a.idContrato, a.codContrato, a.nomeEmpresa, a.dtEmissao, a.statusOrcamento, a.valorTotal, b.codObra, c.nome FROM contratos a 
        LEFT JOIN obras b  ON(a.obra_idObra = b.idObra)
        LEFT JOIN clientes c ON(b.id_fk_cliente = c.idCliente)
        WHERE (a.statusOrcamento IN (2, 3, 4, 5)"; //pega contratos vencidos, aprovados, em andamento e encerrados

        if (!empty($requestData['search']['value'])) { //verifica se eu digitei algo no campo de filtro

            $first = TRUE;

            foreach ($column_search as $field) {
               
                $search = strtoupper($requestData['search']['value']); //tranforma em maiúsculo

                if ($field == "statusOrcamento") {
                    
                    $search = substr($search, 0, 4);  // retorna os 4 primeiros caracteres
                    
                    if ($search == "VENC") { //Vencido
                        $search = 2;
                    } else if (($search == "APRO")) { //Aprovado
                        $search = 3;
                    } else if ($search == "EM A") { //Em andamento
                        $search = 4;
                    } else if ($search == "ENCE") { //Encerrado
                        $search = 5;
                    } 

                    //echo "status: ".$search;

                } else if ($field == "dtEmissao") {
                                        
                    $ano= substr($search, 6);
                    $mes= substr($search, 3,-5);
                    $dia= substr($search, 0,-8);

                    $search = $ano."-".$mes."-".$dia;
                }

                //filtra no banco
                if ($first) {
                    $query .= " AND ($field LIKE '%$search%'"; //primeiro caso
                    $first = FALSE;
                } else {
                    $query .= " OR $field LIKE '%$search%'";
                }
            } //fim do foreach
            if (!$first) {
                $query .= "))"; //termina o WHERE e a query
            }

        } else { //Se não pesquisou nada
            $query .= ")";
        }

        //echo $query;

        $res = parent::searchAll($query);
        $this->setTotalFiltered(count($res));

        //ordenar o resultado
        $query .= " ORDER BY " . $column_order[$requestData['order'][0]['column']] . " " . $requestData['order'][0]['dir'] . 
        "  LIMIT " . $requestData['start'] . " ," . $requestData['length'] . "   "; 
        
        //echo $query;
        return array(
            'totalFiltered'=>$this->getTotalFiltered(),
            'data'=>$this->searchAll($query)
        );
    }

    public static function total() { //retorna a quantidade todal de registros na tabela

        $sql = new Sql();
        
        $query = "SELECT * FROM contratos
            WHERE (statusOrcamento IN (2, 3, 4, 5))"; //pega contratos vencidos, aprovados, em andamento e encerrados
        
        $results = $sql->select($query);

        return count($results);		
    }
    
    public function getValuesToContractPDF($idContract){

        $sql = new Sql();

        $results = $sql->select("SELECT c.*, b.idObra, cl.idCliente FROM contratos c 
                                LEFT JOIN obras b ON(c.obra_idObra = b.IdObra)
                                LEFT JOIN clientes cl ON(b.id_fk_cliente = cl.idCliente)
                                WHERE c.idContrato = :idContract AND c.statusOrcamento IN (3, 4 ,5))", array(
                                ":idContract"=>$idContract    
                                ));
        if(count($results) > 0){
            $res = $results[0];
            $auxData = strtotime($res['dtCadastro']);      
            $auxAno = date('Y', $auxData);
            $res['codContrato'] = $res['codContrato'] . "/". $auxAno;
        
            return json_encode($res);
        }
    }

}

