<?php

namespace Locacao\Model;

use Exception;
use \Locacao\DB\Sql;
use \Locacao\Generator;


class Budget extends Generator{

    public static function listAll(){

        $sql = new Sql();

        $results = $sql->select("SELECT * FROM contratos ORDER BY dtEmissao DESC");
    
        return json_encode($results);
    }

    public function insert(){

        if ($this->getobra_idObra() == "") {        
            $this->setobra_idObra(NULL);
        }
        
        $sql = new Sql();

        if(($this->getcodigo() != "") && ($this->getdtEmissao() != "") && ($this->getstatus() != "")){
           
            $results = $sql->select("CALL sp_contratos_save(:codContrato, :nomeEmpresa, :obra_idObra, :dtEmissao, :solicitante, :telefone, :email, :dtAprovacao, :notas, :valorTotal, :dtInicio, :prazoDuracao, :statusOrcamento)", array(
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
                ":prazoDuracao"=>$this->getprazoDuracao(),
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
                    WHERE idContrato = :idContrato", array(
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

 
    public static function searchName($dtEmissao){ //search if name or desc already exists

        $sql = new Sql();

        $results = $sql->select("SELECT * FROM contratos WHERE (dtEmissao = :dtEmissao)", array(
            ":dtEmissao"=>$dtEmissao
        ));

        return $results;
    }

    public function get_datatable_budgets($requestData, $column_search, $column_order){
        
        $query = "SELECT a.idContrato, a.codContrato, a.nomeEmpresa, a.dtEmissao, a.statusOrcamento, a.valorTotal, b.codObra, c.nome FROM contratos a 
        LEFT JOIN obras b ON(a.obra_idObra = b.idObra)
        LEFT JOIN clientes c ON(b.id_fk_cliente = c.idCliente)
        WHERE a.statusOrcamento IN (0, 1)"; //pega orçamentos pendentes e arquivados

        if (!empty($requestData['search']['value'])) { //verifica se eu digitei algo no campo de filtro

            $first = TRUE;

            foreach ($column_search as $field) {
                
               
                $search = strtoupper($requestData['search']['value']); //tranforma em maiúsculo


               /* if ($field == "status") {
                    $search = substr($search, 0, 4);  // retorna os 4 primeiros caracteres

                    if (($search == "ATIV")) {
                        $search = 1;
                    } else if ($search == "INAT") {
                        $search = 0;
                    }

                    //echo "status: ".$search;
                }*/

                //filtra no banco
                if ($first) {
                    $query .= " WHERE ($field LIKE '%$search%'"; //primeiro caso
                    $first = FALSE;
                } else {
                    $query .= " OR $field LIKE '%$search%'";
                }
            } //fim do foreach
            
            if (!$first) {
                $query .= ")"; //termina o WHERE e a query
            }

        }
        
        $res = $this->searchAll($query);
        $this->setTotalFiltered(count($res));

        //echo 'ordena por: ' . $column_order[$requestData['order'][2]['column']];
       
        //ordenar o resultado
        $query .= " ORDER BY statusOrcamento " . $requestData['order'][0]['dir'] . 
        "  LIMIT " . $requestData['start'] . " ," . $requestData['length'] . "   "; 
        
        $suppliers = new Supplier();
        //echo $query;
        return array(
            'totalFiltered'=>$this->getTotalFiltered(),
            'data'=>$suppliers->searchAll($query)
        );
    }

    public function searchAll($query){ //pesquisa genérica (para todos os campos). Recebe uma query

        $sql = new Sql();

        $results = $sql->select($query);

        return $results;

    }

    public static function total() { //retorna a quantidade todal de registros na tabela

        $sql = new Sql();

        $results = $sql->select("SELECT * FROM contratos");

        return count($results);		
	}
    

    public function update(){

        if ($this->getobra_idObra() == "") {        
            $this->setobra_idObra(NULL);
        }
        
        $sql = new Sql();

        $results = $sql->select("CALL sp_contratosUpdate_save(:idContrato, :codContrato, :nomeEmpresa, :obra_idObra, :dtEmissao, :solicitante, :telefone, :email, :dtAprovacao, :notas, :valorTotal, :dtInicio, :prazoDuracao, :statusOrcamento)", array(
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
            ":prazoDuracao"=>$this->getprazoDuracao(),
            ":statusOrcamento"=>$this->getstatus()
        ));

        if(count($results) > 0){

            $this->setData($results[0]); //carrega atributos desse objeto com o retorno da atualização no banco

            return json_encode($results[0]);

        }else{
            return json_encode([
                "error"=>true,
                "msg"=>"Erro ao atualizar Orçamento!"
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
                    "msg"=>'Erro ao excluir Orçamento'
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

        $results = $sql->select("SELECT MAX(codContrato) FROM contratos WHERE (statusOrcamento IN (0, 1)  AND YEAR(dtCadastro) =:ano)", array( //pega apenas orçamentos pendentes ou arquivados do ano
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
                    WHERE idContrato = :idBudget", array(
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
