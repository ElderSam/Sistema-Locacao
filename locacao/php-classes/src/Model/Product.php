<?php

namespace Locacao\Model;

use Exception;
use \Locacao\DB\Sql;
use \Locacao\Generator;


class Product extends Generator
{

    public static function listAll()
    {

        $sql = new Sql();

        return json_encode($sql->select("SELECT * FROM produtos_gen ORDER BY codigoGen"));
    }


    public function insert()
    {
        $sql = new Sql();
        
        if (($this->getcodigoGen() != "") && ($this->getcategoria() != "")) {

            //insere
            $results = $sql->select("CALL sp_produtos_gen_save(:codigoGen, :descricao, :vlBaseAluguel, :tipo1, :tipo2, :tipo3, :tipo4, :categoria)", array(
                ":codigoGen" => $this->getcodigoGen(),
                ":descricao" => $this->getdescricao(),
                ":vlBaseAluguel" => $this->getvlBaseAluguel(),
                ":tipo1" => $this->gettipo1(),
                ":tipo2" => ($this->gettipo2()  == '' ? NULL : $this->gettipo2()),
                ":tipo3" => ($this->gettipo3()  == '' ? NULL : $this->gettipo3()),
                ":tipo4" => ($this->gettipo4()  == '' ? NULL : $this->gettipo4()),
                ":categoria" => $this->getcategoria()
            ));

            if (count($results) > 0) {

                $this->setData($results[0]); //carrega atributos desse objeto com o retorno da inserção no banco

                return json_encode($results[0]);
            } else {
                return json_encode([
                    "error" => true,
                    "msg" => "Erro ao inserir Produto!"
                ]);
            }
        } else {

            return json_encode([
                'error' => true,
                "msg" => "Campos Incompletos!"
            ]);
        }
    }

    public function get($idproduct)
    {

        $sql = new Sql();

        $results = $sql->select("SELECT * FROM produtos_gen
        WHERE idProduto_gen = :idProduto_gen", array(
            ":idProduto_gen" => $idproduct
        ));

        if (count($results) > 0) {

            $this->setData($results[0]);
        }
    }

    public function loadProduct($idproduct)
    {

        $sql = new Sql();

        //dados das tabelas: produtos_gen, categorias
        $res1 = $sql->select("SELECT a.idProduto_gen, a.codigoGen, a.descricao, a.vlBaseAluguel,
        b.idCategoria, b.codCategoria 
        FROM produtos_gen a INNER JOIN prod_categorias b ON(a.idCategoria = b.idCategoria)
            WHERE a.idProduto_gen = :idProduto_gen", array(
            ":idProduto_gen" => $idproduct
        ));


        if (count($res1) > 0) {

            //dados das tabela tipos de produtos_gen
            $res2 = $sql->select("SELECT b.id, b.descTipo, b.ordem_tipo, b.codTipo
        FROM produtos_gen a RIGHT JOIN prod_tipos b ON(a.tipo1 = b.id OR a.tipo2 = b.id OR a.tipo3 = b.id OR a.tipo4 = b.id)
        WHERE idProduto_gen = :idProduto_gen
        ORDER BY b.ordem_tipo, b.codTipo", array(
                ":idProduto_gen" => $idproduct
            ));


            $res3 = $sql->select("SELECT * FROM prod_containers 
        WHERE idProduto_gen = :idProduto_gen", array(
                ":idProduto_gen" => $idproduct
            ));


            return json_encode([
                $res1[0], //produto
                $res2, //tipos
                $res3 //container (se não for container, vai reotornar um array vazio)
            ]);
        }
    }

    public static function getByCode($code)
    { //pega o produto genérico a partir do código

        $sql = new Sql();

        $query = "SELECT a.idProduto_gen, a.codigoGen, a.descricao, a.vlBaseAluguel, b.descCategoria FROM produtos_gen a 
        INNER JOIN prod_categorias b  ON(a.idCategoria = b.idCategoria)
        WHERE (codigoGen = :codigoGen)";

        $results = $sql->select($query, array(
            ":codigoGen" => $code
        ));

        if (count($results) > 0) {
            return json_encode($results[0]);
        } else {

            return json_encode([
                'error' => true,
                'msg' => 'código inválido!'
            ]);
        }
    }

    public static function searchCode($code)
    { //search if name or desc already exists

        $sql = new Sql();

        $results = $sql->select("SELECT * FROM produtos_gen WHERE (codigoGen = :codigoGen)", array(
            ":codigoGen" => $code
        ));

        return $results;
    }

    public function get_datatable($requestData, $column_search, $column_order)
    {

        $query = "SELECT a.idProduto_gen, a.codigoGen, a.descricao,
            b.descCategoria,
            count(c.idProduto_esp) as qtdTotal,
            SUM( IF(c.status = 1, 1, 0)) as qtdDisponivel
            FROM produtos_gen a
            INNER JOIN prod_categorias b  ON(a.idCategoria = b.idCategoria)
            LEFT JOIN produtos_esp as c ON(a.idProduto_gen = c.idProduto_gen)";

        if (!empty($requestData['search']['value'])) { //verifica se eu digitei algo no campo de filtro

            $first = TRUE;

            foreach ($column_search as $field) {

                $search = strtoupper($requestData['search']['value']); //tranforma em maiúsculo

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

        $query .= " GROUP BY a.idProduto_gen";

        $res = $this->searchAll($query);
        $this->setTotalFiltered(count($res));

        //ordenar o resultado
        $query .= " ORDER BY " . $column_order[$requestData['order'][0]['column']] . " " . $requestData['order'][0]['dir'] .
            " LIMIT " . $requestData['start'] . " ," . $requestData['length'] . "   ";

        $products = new Product();
        //echo "query: $query";

        return array(
            'totalFiltered' => $this->getTotalFiltered(),
            'data' => $products->searchAll($query)
        );
    }

    public function searchAll($query)
    { //pesquisa genérica (para todos os campos). Recebe uma query

        $sql = new Sql();

        $results = $sql->select($query);

        return $results;
    }

    public static function total()
    { //retorna a quantidade todal de registros na tabela

        $sql = new Sql();

        $results = $sql->select("SELECT COUNT(idProduto_gen) as qtd FROM produtos_gen");

        return $results[0]['qtd'];
    }

    public function update()
    {

        $sql = new Sql();

        $dependentes = $sql->select("SELECT COUNT(idProduto_esp) as qtd FROM produtos_esp WHERE idProduto_gen = :idProduto_gen", array(
            ":idProduto_gen" => $this->getidProduto_gen()
        ));

        if ($dependentes[0]['qtd'] > 0) { //se existir algum produto específico dependente

            return json_encode([
                "error" => true,
                "msg" => "Não pode atualizar o produto genérico, pois existem produtos específicos dependentes dele! OBS: se você alterar, vai modificar todos os produtos dependentes!"
            ]);

        } else {

            $results = $sql->select("CALL sp_produtos_genUpdate_save(:idProduto_gen, :codigoGen, :descricao, :vlBaseAluguel,
            :tipo1, :tipo2, :tipo3, :tipo4, :categoria)", array(
                ":idProduto_gen" => $this->getidProduto_gen(),
                ":codigoGen" => $this->getcodigoGen(),
                ":descricao" => $this->getdescricao(),
                ":vlBaseAluguel" => $this->getvlBaseAluguel(),
                ":tipo1" => $this->gettipo1(),
                ":tipo2" => $this->gettipo2(),
                ":tipo3" => $this->gettipo3(),
                ":tipo4" => $this->gettipo4(),
                ":categoria" => $this->getcategoria()
            ));

            if (count($results) > 0) {

                $this->setData($results[0]); //carrega atributos desse objeto com o retorno da atualização no banco

                return json_encode($results[0]);
            } else {
                return json_encode([
                    "error" => true,
                    "msg" => "Erro ao atualizar Produto!"
                ]);
            }
        }
    }

    public function delete()
    {

        $sql = new Sql();

        $dependentes = $sql->select("SELECT * FROM produtos_esp WHERE idProduto_gen = :idProduto_gen", array(
            ":idProduto_gen" => $this->getidProduto_gen()
        ));

        if (count($dependentes) > 0) { //se tiver produtos específicos dependentes

            $qtd = count($dependentes);

            return json_encode([
                "error" => true,
                "msg" => "Não pode excluir, pois possui $qtd produtos específicos dependentes"
            ]);
        } else {

            try { //tente excluir
                $sql->query("CALL sp_produtos_gen_delete(:idProduto_gen)", array(
                    ":idProduto_gen" => $this->getidProduto_gen()
                ));

                if ($this->get($this->getidProduto_gen())) {

                    return json_encode([
                        "error" => true,
                        "msg" => 'Erro ao excluir produto'
                    ]);
                } else {
                    return json_encode([
                        "error" => false,
                    ]);
                }
            } catch (Exception $e) {

                return json_encode([
                    "error" => true,
                    "msg" => $e->getMessage()
                ]);
            }
        }
    }

    public function createDescription()
    {

        $sql = new Sql();

        $busca = $sql->select("SELECT * FROM prod_tipos WHERE idCategoria = :categoria AND 
        ((ordem_tipo = 1 AND id = :tipo1)
        OR (ordem_tipo = 2 AND id = :tipo2)
        OR (ordem_tipo = 3 AND id = :tipo3)
        OR (ordem_tipo = 4 AND id = :tipo4))", array(
            ":categoria" => $this->getcategoria(),
            ":tipo1" => $this->gettipo1(),
            ":tipo2" => $this->gettipo2(),
            ":tipo3" => $this->gettipo3(),
            ":tipo4" => $this->gettipo4()
        ));

        $descricao = $busca[0]['descTipo'];

        for ($i = 1; $i < 4; $i++) {
            if (isset($busca[$i])) { //se essa posição do array existe
                $descricao .= ' ' . $busca[$i]['descTipo'];
            }
        }

        $this->setdescricao($descricao);
    }
}
