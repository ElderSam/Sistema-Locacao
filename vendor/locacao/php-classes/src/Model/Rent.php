<?php

namespace Locacao\Model;

use Exception;
use \Locacao\DB\Sql;
use \Locacao\Generator;


class Rent extends Generator{

    public static function listAll(){

        $sql = new Sql();

        $results = $sql->select("SELECT * FROM historicoalugueis ORDER BY nome");
    
        return json_encode($results);
    }

    public function createNewCode(){
        
        $ano = strtotime($this->getdtInicio());
        $ano = date("Y", $ano);

        $sql = new Sql();

        $query = "SELECT MAX(codigo) FROM historicoalugueis 
            WHERE (YEAR(dtInicio) = :ano)";

        $ultimo = $sql->select($query, array(
            ":ano"=>$ano
        ));

        $nextCode = $ultimo[0]['MAX(codigo)'] + 1;

        return $nextCode;

    }

    public function insert(){
    
        $this->setcodigo($this->createNewCode());
        
        $sql = new Sql();
        
        //print_r($_POST);

        if(($this->getproduto_idProduto() != "") && ($this->getcontrato_idContrato() != "") && ($this->getstatus() != "")){

            $results = $sql->select("CALL sp_historicoalugueis_save(:codigo, :contrato_idContrato, :produto_idProduto, :status, :vlAluguel, :dtInicio, :dtFinal, :custoEntrega, :custoRetirada, :observacao)", array(
                ":codigo"=>$this->getcodigo(),
                ":contrato_idContrato"=>$this->getcontrato_idContrato(),
                ":produto_idProduto"=>$this->getproduto_idProduto(), //produto específico
                ":status"=>$this->getstatus(),
                ":vlAluguel"=>$this->getvlAluguel(),
                ":dtInicio"=>$this->getdtInicio(),
                ":dtFinal"=>$this->getdtFinal(),
                ":custoEntrega"=>$this->getcustoEntrega(),
                ":custoRetirada"=>$this->getcustoRetirada(),
                ":observacao"=>$this->getobservacao()
            ));

            if(count($results) > 0){

                $this->setData($results[0]); //carrega atributos desse objeto com o retorno da inserção no banco

                return json_encode($results[0]);

            }else{
                return json_encode([
                    "error"=>true,
                    "msg"=>"Erro ao inserir Aluguel!"
                    ]);
            }
       
        }else{

            return json_encode([
				'error' => true,
				"msg" => "Campos incompletos!"
			]);
        }
    }

    
    public function get($id){

        $sql = new Sql();

        $results = $sql->select("SELECT * FROM historicoalugueis WHERE idHistoricoAluguel = :id", array(
            ":id"=>$id
        ));

        if(count($results) > 0){
            $this->setData($results[0]);

        }
    }

    public function get_datatable($requestData, $column_search, $column_order){
        
        $query = "SELECT a.*, 
            b.idProduto_esp, b.idProduto_gen, b.codigoEsp,
            c.codContrato FROM historicoalugueis a
            INNER JOIN produtos_esp b ON(a.produto_idProduto = b.idProduto_esp)
            INNER JOIN contratos c ON(c.idContrato = a.contrato_idContrato)";

        if (!empty($requestData['search']['value'])) { //verifica se eu digitei algo no campo de filtro

            $first = TRUE;

            foreach ($column_search as $field) {     
               
                $search = strtoupper($requestData['search']['value']); //tranforma em maiúsculo

                //filtra no banco
                if ($first) {
                    $query .= " WHERE ($field LIKE '%$search%'"; //primeiro caso
                    $first = FALSE;
                } else {

                    if($field == "a.status") {
                        $aux = strtoupper($search);
                        $aux = substr($aux, 0, 5);
                        
                        if($aux ==  "ENTRE") { //entrega pendente
                            $value = 0;

                        }else if($aux ==  "ATIVO") { //ativo
                            $value = 1;
                        }else if($aux ==  "RETIR") { //retirada pendente
                            $value = 2;
                        }else if($aux ==  "ENCER") { //encerrado
                            $value = 3;
                        }

                        if(isset($value)){
                            $query .= " OR $field = $value";
                        }           
                        
                    }else if($field == "b.codigoEsp") {
                        
                        $query .= " OR $field LIKE '%$search%'";

                    }else if(($field == "a.dtInicio") || ($field == "a.dtFinal")){ //dtInicio e dtFinal
                        
                        if(strlen($search) == 10){ //precisa digitar a data completa no campo pesquisar, ex: 20/09/2020
                            $aux = explode("-", $search);
                            $aux = str_replace("/", "-", $search);
                            $data = date('Y-m-d', strtotime($aux));
                            //echo "$field " .$data;
                            $query .= " OR $field = '$data'";
                        }
                          
                    }else { //codContrato
                        $query .= " OR $field LIKE '$search%'";
                    }
                    
                }
            } //fim do foreach
            if (!$first) {
                $query .= ")"; //termina o WHERE e a query
            }

        }
        //print_r($query);
        
        $res = $this->searchAll($query);
        $this->setTotalFiltered(count($res));

        //ordenar o resultado
        $query .= " ORDER BY " . $column_order[$requestData['order'][0]['column']] . " " . $requestData['order'][0]['dir'] . 
        "  LIMIT " . $requestData['start'] . " ," . $requestData['length'] . "   "; 
        
        $rents = new Rent();
        //echo $query;
        return array(
            'totalFiltered'=>$this->getTotalFiltered(),
            'data'=>$rents->searchAll($query)
        );
    }

    public function searchAll($query){ //pesquisa genérica (para todos os campos). Recebe uma query

        $sql = new Sql();

        $results = $sql->select($query);

        return $results;

    }

    public static function total() { //retorna a quantidade todal de registros na tabela

        $sql = new Sql();

        $results = $sql->select("SELECT * FROM historicoalugueis");

        return count($results);		
	}
    

    public function update(){
        
        $sql = new Sql();

        $results = $sql->select("CALL sp_historicoalugueisUpdate_save(:idHistoricoAluguel, :status, :vlAluguel, :dtInicio, :dtFinal, :custoEntrega, :custoRetirada, :observacao)", array(
            //":produto_idProduto"=>$this->getproduto_idProduto(),
            ":idHistoricoAluguel"=>$this->getidHistoricoAluguel(),
            ":status"=>$this->getstatus(),
            ":vlAluguel"=>$this->getvlAluguel(),
            ":dtInicio"=>$this->getdtInicio(),
            ":dtFinal"=>$this->getdtFinal(),
            ":custoEntrega"=>$this->getcustoEntrega(),
            ":custoRetirada"=>$this->getcustoRetirada(),
            ":observacao"=>$this->getobservacao()
        ));

        if(count($results) > 0){

            $this->setData($results[0]); //carrega atributos desse objeto com o retorno da atualização no banco

            if($this->getstatus() == 3){ //aluguel encerrado, então devolve o produto

                $query = "UPDATE produtos_esp
                    SET status = 1 /* status 1 = disponível */
                    WHERE (idProduto_esp = :idProduto_esp)";

                $sql->query($query, array(
                    ":idProduto_esp"=>$this->getproduto_idProduto()
                ));

            }

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
            $sql->query("CALL sp_historicoalugueis_delete(:idHistoricoAluguel)", array(
                ":idHistoricoAluguel"=>$this->getidHistoricoAluguel()
            ));

            if($this->get($this->getidHistoricoAluguel())){

                return json_encode([
                    "error"=>true,
                    "msg"=>"Erro ao excluir Aluguel"
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

    public function loadRent($id) {
        //echo "loadRent id: $id";

        $sql = new Sql();

        $query = "SELECT a.*, b.idContrato, b.codContrato, 
            d.idCliente, d.codigo as codigoCliente, d.nome as nomeCliente, 
            e.idProduto_esp, e.codigoEsp, 
            f.idProduto_gen, f.descricao, 
            g.descCategoria 
            FROM historicoalugueis a
            INNER JOIN contratos b ON(b.idContrato = a.contrato_idContrato)
            INNER JOIN obras c ON(c.idObra = b.obra_idObra)
            INNER JOIN clientes d ON(d.idCliente = c.id_fk_cliente)
            INNER JOIN produtos_esp e ON(a.produto_idProduto = e.idProduto_esp)
            INNER JOIN produtos_gen f ON (f.idProduto_gen = e.idProduto_gen)
            INNER JOIN prod_categorias g ON (g.idCategoria = f.idCategoria)
            WHERE idHistoricoAluguel = :id";

        $rent = $sql->select($query, array(
            ":id"=>$id
        ));

        return json_encode($rent[0]);

    }
    
}

