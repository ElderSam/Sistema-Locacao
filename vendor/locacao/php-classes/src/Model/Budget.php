<?php

namespace Locacao\Model;

use Exception;
use \Locacao\DB\Sql;
use \Locacao\Generator;

/* Status do Orçamento;
0 - Pendente
1 - Arquivado */

class Budget extends Generator{

    public static function listAll(){

        $sql = new Sql();

        $query = "SELECT * FROM contratos 
            WHERE (statusOrcamento IN (0, 1))
            ORDER BY dtEmissao DESC";

        $results = $sql->select($query);

        return json_encode($results);
    }

    public function insert(){

        if ($this->getobra_idObra() == "") {        
            $this->setobra_idObra(NULL);
        }
        
        $sql = new Sql();

        if(($this->getcodigo() != "") && ($this->getdtEmissao() != "") && ($this->getstatus() != "")){
           
            $results = $sql->select("CALL sp_contratos_save(:codContrato, :nomeEmpresa, :obra_idObra, :dtEmissao, :solicitante, :telefone, :email, :dtAprovacao, :notas, :valorTotal, :dtInicio, :dtFim, :statusOrcamento)", array(
                ":codContrato"=>$this->getcodigo(),
                ":nomeEmpresa"=>$this->getnomeEmpresa(),
                ":obra_idObra"=>$this->getobra_idObra(),
                ":dtEmissao"=>$this->getdtEmissao(),
                ":solicitante"=>$this->getsolicitante(),
                ":telefone"=>$this->gettelefone(),
                ":email"=>$this->getemail(),
                ":dtAprovacao"=>$this->getdtAprovacao(),
                /*":custoEntrega"=>$this->getcustoEntrega(),
                ":custoRetirada"=>$this->getcustoRetirada(),*/
                ":notas"=>$this->getnotas(),
                ":valorTotal"=>$this->getvalorTotal(),
                ":dtInicio"=>$this->getdtInicio(),
                ":dtFim"=>$this->getdtFim(),
                ":statusOrcamento"=>$this->getstatus()
            ));

            //print_r($results);

            if(count($results) > 0){

                $this->setData($results[0]); //carrega atributos desse objeto com o retorno da inserção no banco

                return json_encode($results[0]);

            }else{
                return json_encode([
                    "error"=>true,
                    "msg"=>"Erro ao inserir Orçamento!"
                    ]);
            }
       
        }else{

            return json_encode([
				'error' => true,
				"msg" => "Campos incompletos!"
			]);
        }
    }

    
    public function get($idContrato){

        $sql = new Sql();

        $results = $sql->select("SELECT a.*, b.idObra, c.idCliente, c.nome as descCliente FROM contratos a
                    LEFT JOIN obras b ON(a.obra_idObra = b.idObra)
                    LEFT JOIN clientes c ON(b.id_fk_cliente = c.idCliente)
                    WHERE (a.idContrato = :idContrato AND statusOrcamento IN (0, 1))", array(
            ":idContrato"=>$idContrato
        ));

        if(count($results) > 0){
            $this->setData($results[0]);

            //$auxData = strtotime(date("Y-m-d H:i:s"));//para teste
            $auxData = strtotime($results[0]['dtCadastro']);      
            $auxAno = date('Y', $auxData);
            $auxCode = $results[0]['codContrato'] . "/". $auxAno;
            $this->setcodContrato($auxCode);
        }
    }

    public function get_datatable_budgets($requestData, $column_search, $column_order){
        
        $query = "SELECT a.idContrato, a.codContrato, a.nomeEmpresa, a.dtEmissao, a.statusOrcamento, a.valorTotal, b.codObra, c.nome FROM contratos a 
        LEFT JOIN obras b ON(a.obra_idObra = b.idObra)
        LEFT JOIN clientes c ON(b.id_fk_cliente = c.idCliente)
        WHERE (a.statusOrcamento IN (0, 1)"; //pega orçamentos pendentes e arquivados

        if (!empty($requestData['search']['value'])) { //verifica se eu digitei algo no campo de filtro

            $first = TRUE;

            foreach ($column_search as $field) {
                
               
                $search = strtoupper($requestData['search']['value']); //tranforma em maiúsculo

                if ($field == "statusOrcamento") {
                    $search = substr($search, 0, 4);  // retorna os 4 primeiros caracteres

                    if (($search == "PEND")) { //Pendente
                        $search = 0;
                    } else if ($search == "ARQU") { //Arquivado
                        $search = 1;
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
        
        $res = $this->searchAll($query);
        $this->setTotalFiltered(count($res));

        //echo 'ordena por: ' . $column_order[$requestData['order'][2]['column']];
       
        //ordenar o resultado
        $query .= " ORDER BY statusOrcamento " . $requestData['order'][0]['dir'] . 
        "  LIMIT " . $requestData['start'] . " ," . $requestData['length'] . "   "; 
        
        $budgets = new Budget();
        //echo $query;
        return array(
            'totalFiltered'=>$this->getTotalFiltered(),
            'data'=>$budgets->searchAll($query)
        );
    }

    public function searchAll($query){ //pesquisa genérica (para todos os campos). Recebe uma query

        $sql = new Sql();

        $results = $sql->select($query);

        return $results;

    }

    public static function total() { //retorna a quantidade todal de registros na tabela

        $sql = new Sql();

        $query = "SELECT * FROM contratos
            WHERE (statusOrcamento IN (0, 1))"; //pega orçamentos pendentes ou arquivados
        
        $results = $sql->select($query);

        return count($results);		
	}
    

    public function update(){

        
        if ($this->getobra_idObra() == "") {        
            $this->setobra_idObra(NULL);
        }
        
        $sql = new Sql();

        $results = $sql->select("CALL sp_contratosUpdate_save(:idContrato, :codContrato, :nomeEmpresa, :obra_idObra, :dtEmissao, :solicitante, :telefone, :email, :dtAprovacao, :notas, :valorTotal, :dtInicio, :dtFim, :statusOrcamento)", array(
            ":idContrato"=>$this->getidContrato(),
            ":codContrato"=>$this->getcodigo(),
            ":nomeEmpresa"=>$this->getnomeEmpresa(),
            ":obra_idObra"=>$this->getobra_idObra(),
            ":dtEmissao"=>$this->getdtEmissao(),
            ":dtAprovacao"=>$this->getdtAprovacao(),
            ":solicitante"=>$this->getsolicitante(),
            ":telefone"=>$this->gettelefone(),
            ":email"=>$this->getemail(),
            /*":custoEntrega"=>$this->getcustoEntrega(),
            ":custoRetirada"=>$this->getcustoRetirada(),*/
            ":notas"=>$this->getnotas(),
            ":valorTotal"=>$this->getvalorTotal(),
            ":dtInicio"=>$this->getdtInicio(),
            ":dtFim"=>$this->getdtFim(),
            ":statusOrcamento"=>$this->getstatus()
        ));

        if(count($results) > 0){

            $this->setData($results[0]); //carrega atributos desse objeto com o retorno da atualização no banco

            return json_encode($results[0]);

        }else{
            return json_encode([
                "error"=>true,
                "msg"=>"Erro ao atualizar!"
                ]);
        }

    }

    public function delete(){
      
        $sql = new Sql();

        try{

            $sql->query("CALL sp_contratos_delete(:idContrato)", array(
                ":idContrato"=>$this->getidContrato()
            ));

            if($this->get($this->getidContrato())){

                return json_encode([
                    "error"=>true,
                    "msg"=>'Erro ao excluir!'
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

    public static function showsNextNumber(){

        $sql = new Sql();

        /*$hoje = date('d/m/Y'); //pega a data atual
        echo $hoje . "<br>";*/

        $ano = date('Y'); //pega o ano atual

        $query = "SELECT MAX(codContrato) FROM contratos
            WHERE (statusOrcamento IN (0, 1) AND YEAR(dtCadastro) =:ano)"; //pega apenas orçamentos pendentes ou arquivados do ano
        
        $results = $sql->select($query, array(
            'ano'=>$ano
        ));

        $nextNumber = 1 + $results[0]['MAX(codContrato)'];
       
        return $nextNumber . "/" . $ano; //retorna o próximo número de série da categoria

    }

    public function getValuesToBudgetPDF($idBudget){
        $sql = new Sql();

        $results = $sql->select("SELECT a.*, b.idObra, c.idCliente, c.nome as descCliente FROM contratos a
                    LEFT JOIN obras b ON(a.obra_idObra = b.idObra)
                    LEFT JOIN clientes c ON(b.id_fk_cliente = c.idCliente)
                    WHERE (a.idContrato = :idBudget AND a.statusOrcamento IN (0, 1))", array(
            ":idBudget"=>$idBudget
        ));   

        if(count($results) > 0){
            $res = $results[0];
            //print_r($res);
            //$this->setData($results[0]);

            //$auxData = strtotime(date("Y-m-d H:i:s"));//para teste
            $auxData = strtotime($res['dtCadastro']);      
            $auxAno = date('Y', $auxData);
            $res['codContrato'] = $res['codContrato'] . "/". $auxAno;
        
            return json_encode($res);
        }

    }    
}

