<?php

namespace Locacao\Controller;

use \Locacao\Generator;
use \Locacao\Model\User;
use \Locacao\Model\Product;
use \Locacao\Model\ProductEsp;
use \Locacao\Model\Container;
use \Locacao\Model\Category;
use \Locacao\Model\Supplier;

/* -------- Controller de Produtos Específicos --------- */
class ProductEspController extends Generator
{

    //construtor
    public function __construct()
    {
    }


    public function save($update = false) //Add a new Product or Update
    {
        
        User::verifyLogin();
        
        $error = $this->verifyFields($update); //verifica os campos do formulário
        $aux = json_decode($error);

        if ($aux->error) {
            return $error;
        }

        $product = new ProductEsp(); //Model

        $product->setData($_POST);

        if ($update) { //se for atualizar
            
            $upd =  $product->update();

            if($this->getcodCategory() == '001'){ //se for um container
                
                $aux = json_decode($upd);

                if(isset($aux->error) && ($aux->error == true)){
                    return $upd;

                }else{

                    $container = new Container();
                    $aux = json_decode($upd);
                    //print_r($aux);
                    $idProduto = $aux->idProduto_esp;
                   
                    //echo "id: " . $idProduto_esp . "<br>";
                    $container->setData($_POST);
                    $upd2 = $container->update();               

                    $upd = json_decode($upd);
                    $upd2 = json_decode($upd2);
                 
                    return json_encode([
                        "produto"=>$upd,
                        "container"=>$upd2
                    ]);
                }


            }else{
                return $upd;
            }

        } else { // se for cadastrar novo Produto

            $res = $product->insert();
            //print_r($res);
            if($this->getcodCategory() == '001'){ //se for um container

                $aux = json_decode($res);

                if(isset($aux->error) && ($aux->error == true)){
                    return $res;

                }else{
                    $container = new Container();
                    $aux = json_decode($res);
                    //print_r($aux);
                    $idProduto_esp = $aux->idProduto_esp;
                
                    //echo "id: " . $idProduto_esp . "<br>";
                    $container->setData($_POST);
                    $res2 = $container->insert($idProduto_esp);
                    return $res2;
                }

            }else{
                return $res;
            }
            
        }
    }


    public function verifyFields($update = false)
    {/*Verifica todos os campos ---------------------------*/

        $errors = array();
        
        if ($_POST["valorCompra"] == "") {
            $errors["#valorCompra"] = "Valor de Compra é obrigatório!";
        }
        if ($_POST["status"] == "") {
            $errors["#status"] = "Status é obrigatório!";
        }   

        if ($_POST["categoria"] == "") {
            $errors["#categoria"] = "Categoria é obrigatória!";
           
        }else{
         
            $this->setcodCategory(substr($_POST["categoria"], -3)); //do antepenúltimo caracter até o final

            //testes para Container
            if($this->getcodCategory() == '001'){
                if ($_POST["tipoPorta"] == "") {
                    $errors["#tipoPorta"] = "Tipo de porta é obrigatório!";
                }  


                if (!isset($_POST["janelasLat"]) || ($_POST["janelasLat"] == "")) {
                    $errors["#janelasLat"] = "campo obrigatório!";
                }  

                if (!isset($_POST["janelasCirc"]) || ($_POST["janelasCirc"] == "")) {
                    $errors["#janelasCirc"] = "campo obrigatório!";
                }  

                if (!isset($_POST["sanitarios"]) || ($_POST["sanitarios"] == "")) {
                    $errors["#sanitarios"] = "Tipo de porta é obrigatório!";
                }  

                /*if (!isset($_POST["entradasAC"]) || ($_POST["entradasAC"] == "")) {
                    $errors["#entradasAC"] = "campo obrigatório!";
                }  */

                if (!isset($_POST["tomadas"]) || ($_POST["tomadas"] == "")) {
                    $errors["#tomadas"] = "campo obrigatório!";
                }  

                if (!isset($_POST["lampadas"]) || ($_POST["lampadas"] == "")) {
                    $errors["#lampadas"] = "campo obrigatório!";
                }  
            }
    
        }

        if (($_POST["fornecedor"] == "")) {
            $errors["#fornecedor"] = "Fornecedor é obrigatório!";

        }

        if(empty($_POST["forrado"])){
            $_POST["forrado"] = 0;
        }

        if(empty($_POST["eletrificado"])){
            $_POST["eletrificado"] = 0;
        }

        if(empty($_POST["chuveiro"])){
            $_POST["chuveiro"] = 0;
        }

       /* $exists = 0;
        $exists = ProductEsp::searchDesc($_POST["descricao"]);
        if (count($exists) > 0) { //se existe descricao completo igual já registrado

            if ($update) {
                foreach ($exists as $product) {

                    if (($_POST['descricao'] == $product['descricao']) && ($_POST['idProduto'] != $product['idProduto'])) {
                        $errors["#descricao"] = "Já existe um Produto com essa Descrição";
                        break;
                    }
                }
            } else {
                $errors["#descricao"] = "Já existe um produto com essa Descrição";
            }
        }*/

        if (count($errors) > 0) { //se tiver algum erro de input (campo) do formulário

            return json_encode([
                'error' => true,
                'error_list' => $errors
            ]);
        } else { //se ainda não tem erro

            $this->createCode();
         /*   $category = new Category();
            $_POST["categoria"] = $category->get(false, $_POST["categoria"]); //manda o codcategoria em vez do id

            $supplier = new Supplier();
            $_POST["fornecedor"] = $supplier->get(false, $_POST["fornecedor"]); //manda o codFornecedor em vez do id
*/

            return json_encode([
                'error' => false
            ]);
        }
    }/* --- fim verificaErros() ---------------------------*/

    public function createCode(){
      
        //echo "id: " . $_POST["categoria"]. ", codigo: ". $this->getcodCategory() . "<br>";
        

        //pega código do produto genérico
        $productGen = new Product();
        //print_r($_POST['idProduto_gen']);

        $productGen->get($_POST['idProduto_gen']);

        //echo 'id: ' . $productGen->getidProduto_gen();

        $codProdGenerico = $productGen->getcodigoGen();
        


        $_POST["categoria"] = substr($_POST["categoria"], 0, -4); //pega o id (tirando os quatro últimos caracteres)
      

        $codFornecedor = substr($_POST["fornecedor"], -3); //pega do antepenúltimo caracter até o final
        $_POST["fornecedor"] = substr($_POST["fornecedor"], 0, -4); //pega o id (tirando os quatro últimos caracteres)
        //echo 'id: '. $_POST["fornecedor"]. ", cod: " . $codFornecedor;

        if(($this->getcodCategory() == 3) || ($this->getcodCategory() == 4)){ //Andaimes e Escoras não tem número de série (número de inventário)
            $_POST["numSerie"] = NULL;
            $numSerie = "xxxx";

        }else{
          
            $numSerie = $_POST["numSerie"];
        }
        
        $_POST["codigoEsp"] = $codProdGenerico . "." . $codFornecedor . "-" . $numSerie;
        //echo '<br>código final: ' . $_POST["codigo"];
        
        /*----------------------fim de Tipos ------------------------------------------------*/


        
        //fornecedortem que ter 4 caracteres
        
    
    }


    /*-------------------------------- DataTables -------------------------------------------------------------------*/

    /* CAMPOS VIA POST (Para trabalhar como DataTables)

		$_POST['search']['value'] = Campo para busca
		$_POST['order'] = [['0, 'asc']]
			$_POST['order'][0]['column'] = index da coluna
			$_POST['order'][0]['dir'] = tipo de ordenação (asc, desc)
		$_POST['length'] = Quantos campos mostrar
		$_POST['length'] = Qual posição começar
    */

    public function ajax_list_products($requestData, $idProduct_gen=false)
    {

        $column_search = array("codigoEsp", "descCategoria", "status", "descricao"); //colunas pesquisáveis pelo datatables
        $column_order = array("codigoEsp", "descCategoria", "status", "descricao"); //ordem que vai aparecer (o codigo primeiro)

        //faz a pesquisa no banco de dados
        $product = new ProductEsp(); //model

        $datatable = $product->get_datatable($requestData, $column_search, $column_order, $idProduct_gen);

        $data = array();

        foreach ($datatable['data'] as $product) { //para cada registro retornado

            if ($product['status'] == 0) {
                $status = "Alugado";
                $color = "red";
            } else if ($product['status'] == 1){
                $status = "Disponível";
                $color = "green";
            }else if ($product['status'] == 2){
                $status = "Manutenção";
                $color = "orange";
            }

            $id = $product['idProduto_esp'];

            // Ler e criar o array de dados ---------------------
            $row = array();

            $row[] = $product['codigoEsp'];
            $row[] = $product['descCategoria'];
            $row[] = "<strong style='color:$color'>$status</strong>";
            $row[] = $product['descricao'];
            $row[] = "<button type='button' title='ver detalhes' class='btn btn-warning btnEdit'
                onclick='loadProductEsp($id);'>
                    <i class='fas fa-bars sm'></i>
                </button>
                <button type='button' title='excluir' onclick='deleteProductEsp($id);'
                    class='btn btn-danger btnDelete'>
                    <i class='fas fa-trash'></i>
                </button>";

            $data[] = $row;
        } //

        //Cria o array de informações a serem retornadas para o Javascript
        $json = array(
            "draw" => intval($requestData['draw']), //para cada requisição é enviado um número como parâmetro
            "recordsTotal" => $this->records_total(),  //Quantidade de registros que há no banco de dados
            "recordsFiltered" => $datatable['totalFiltered'], //Total de registros quando houver pesquisa
            "data" => $data,  //Array de dados completo dos dados retornados da tabela 
        );

        return json_encode($json); //enviar dados como formato json

    }

    public function records_total()
    {

        return ProductEsp::total();
    }



    public function delete($idproduct){

        $container = new Container();

        $container->get((int)$idproduct);

        $id = $container->getidProduto();
        
        if(isset($id)){ //se for um container
            
           $res = $container->delete();

           if(json_decode($res)->error){

                return $res;
           }
        }
       
        $product = new ProductEsp();

        $product->get((int)$idproduct); //carrega o usuário, para ter certeza que ainda existe no banco
       
        return $product->delete();
        
        
    }
}//end class ProductEspController
