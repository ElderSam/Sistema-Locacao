<?php

namespace Locacao\Model;

use Exception;
use \Locacao\DB\Sql;
use \Locacao\Generator;


class Contract extends Generator{

    public static function listAll(){

        $sql = new Sql();

        $results = $sql->select("SELECT * FROM contratos ORDER BY dtEmissao DESC");
    
        return json_encode($results);
    }

    public function insert(){
        
        $sql = new Sql();
        /*echo "<br>codContrato " . $this->getcodigo() .
        "<br>obra_idObra " . $this->getobra_idObra() .
        "<br>dtEmissao " . $this->getdtEmissao() .
        "<br>solicitante " . $this->getsolicitante() .
        "<br>telefone " . $this->gettelefone() .
        "<br>email " . $this->getemail() .
        "<br>dtAprovacao " . $this->getdtAprovacao() .
        "<br>notas " . $this->getnotas() .
        "<br>valorTotal " . $this->getvalorTotal() .
        "<br>dtInicio " . $this->getdtInicio() .
        "<br>prazoDuracao " . $this->getprazoDuracao() .
        "<br>statusOrcamento " . $this->getstatus();*/

        if(($this->getcodigo() != "") && ($this->getdtEmissao() != "") && ($this->getstatus() != "")){
           
            $results = $sql->select("CALL sp_contratos_save(:codContrato, :obra_idObra, :dtEmissao, :solicitante, :telefone, :email, :dtAprovacao, :notas, :valorTotal, :dtInicio, :prazoDuracao, :statusOrcamento)", array(
                ":codContrato"=>$this->getcodigo(),
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

        $results = $sql->select("SELECT a.*, b.idObra FROM contratos a
                    INNER JOIN obras b ON(a.obra_idObra = b.idObra)
                    WHERE idContrato = :idContrato", array(
            ":idContrato"=>$idContrato
        ));

        if(count($results) > 0){
            $this->setData($results[0]);

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
        
        $query = "SELECT * FROM contratos";

        $query = "SELECT a.idContrato, a.codContrato, a.dtEmissao, a.statusOrcamento, a.valorTotal, b.codObra, c.nome FROM contratos a 
        INNER JOIN obras b  ON(a.obra_idObra = b.idObra)
        INNER JOIN clientes c ON(b.cliente_idCliente = c.idCliente)
        WHERE a.statusOrcamento IN (0, 1)"; //pega orçamentos pendentes e arquivados

        if (!empty($requestData['search']['value'])) { //verifica se eu digitei algo no campo de filtro

            $first = TRUE;

            foreach ($column_search as $field) {
                
               
                $search = strtoupper($requestData['search']['value']); //tranforma em maiúsculo


                if ($field == "status") {
                    $search = substr($search, 0, 4);  // retorna os 4 primeiros caracteres

                    if (($search == "ATIV")) {
                        $search = 1;
                    } else if ($search == "INAT") {
                        $search = 0;
                    }

                    //echo "status: ".$search;
                }

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

    public function get_datatable_contracts($requestData, $column_search, $column_order){
        
        $query = "SELECT * FROM contratos";

        $query = "SELECT a.idContrato, a.codContrato, a.dtEmissao, a.statusOrcamento, a.valorTotal, b.codObra, c.nome FROM contratos a 
        INNER JOIN obras b  ON(a.obra_idObra = b.idObra)
        INNER JOIN clientes c ON(b.cliente_idCliente = c.idCliente)
        WHERE a.statusOrcamento IN (2, 3, 4, 5)"; //pega contratos aprovados, em andamento, vencidos e encerrados

        if (!empty($requestData['search']['value'])) { //verifica se eu digitei algo no campo de filtro

            $first = TRUE;

            foreach ($column_search as $field) {
                
               
                $search = strtoupper($requestData['search']['value']); //tranforma em maiúsculo


                if ($field == "status") {
                    $search = substr($search, 0, 4);  // retorna os 4 primeiros caracteres

                    if (($search == "ATIV")) {
                        $search = 1;
                    } else if ($search == "INAT") {
                        $search = 0;
                    }

                    //echo "status: ".$search;
                }


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

        //ordenar o resultado
        $query .= " ORDER BY " . $column_order[$requestData['order'][0]['column']] . " " . $requestData['order'][0]['dir'] . 
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
        
        $sql = new Sql();

        $results = $sql->select("CALL sp_contratosUpdate_save(:idContrato, :codContrato, :obra_idObra, :dtEmissao, :solicitante, :telefone, :email, :dtAprovacao, :notas, :valorTotal, :dtInicio, :prazoDuracao, :statusOrcamento)", array(
            ":idContrato"=>$this->getidContrato(),
            ":codContrato"=>$this->getcodigo(),
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
        
        $results = $sql->select("SELECT MAX(codContrato) FROM contratos WHERE YEAR(dtCadastro) =:ano", array(
            'ano'=>$ano
        ));
        $nextNumber = 1 + $results[0]['MAX(codContrato)'];
       
       /* if($nextNumber < 10){
            $nextNumber = "00". $nextNumber;

        }else if($nextNumber < 100){
            $nextNumber = "0". $nextNumber;
            
        }*/
        
        return $nextNumber . "/" . $ano; //retorna o próximo número de série da categoria

    }
    
}

