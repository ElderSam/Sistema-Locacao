<?php

namespace Locacao\Controller;

use \Locacao\Generator;
use \Locacao\Model\User;
use \Locacao\Model\Product;
use \Locacao\Model\Container;
use \Locacao\Model\Category;
use \Locacao\Model\Supplier;

class ProductController extends Generator
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

        $product = new Product(); //Model

        $product->setData($_POST);
        $product->createDescription();

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
                    $idProduto_gen = $aux->idProduto_gen;
                   
                    //echo "id: " . $idProduto_gen . "<br>";
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

            return $res;      
        }
    }


    public function verifyFields($update = false)
    {/*Verifica todos os campos ---------------------------*/

        $errors = array();
        
        /*if ($_POST["descricao"] == "") {
            $errors["#descricao"] = "Descrição é obrigatória!";
        }*/

        if ($_POST["vlBaseAluguel"] == "") {
            $errors["#vlBaseAluguel"] = "Valor de Aluguel é obrigatório!";
        }

        if (($_POST["categoria"] == "")) {
            $errors["#categoria"] = "Categoria é obrigatória!";
           
        }else{
         
            $this->setcodCategory(substr($_POST["categoria"], -3)); //do antepenúltimo caracter até o final
            
            if (($_POST["tipo1"] == "")) {
                $errors["#tipo1"] = "tipo 1 é obrigatório!";
            }

            if (($_POST["tipo2"] == "") && ($this->getcodCategory() != "004")) { //Se não for uma Escora, mas tiver tipo2
                
                if($this->getcodCategory() == "003"){ //se for Andaime

                    if(($_POST["tipo1"] == "31-02") || ($_POST["tipo1"] == "32-03")){ //se o tipo1 for Fachadeiro ou Multidirecional
                       
                        $_POST["tipo2"] = NULL;
                        $_POST["tipo3"] = NULL;

                    }else if(($_POST["tipo2"] == "40-08") || ($_POST["tipo2"] == "41-09") || ($_POST["tipo2"] == "42-10")){ //se o tipo2 for Sapataregulável ou Sapata fixa ou Rodízio com trava

                        $_POST["tipo3"] = NULL;
                        
                    }else{
                        $errors["#tipo2"] = "tipo 2 é obrigatório!";
                    }

                }else{
                    $errors["#tipo2"] = "tipo 2 é obrigatório!";
                }
                
            }else if($this->getcodCategory() == "004"){ //se for Categoria Escora (cod 004)
  
                $_POST["tipo4"] = NULL;
            }
            
            if (($_POST["tipo3"] == "") && ($this->getcodCategory() != "004")) { //nas categorias 1, 2 e 3 o tipo 3 é obrigatório para a maioria dos casos
                
                $codTipo2 = substr($_POST["tipo2"], -2);

                if(($this->getcodCategory() == "001") && ($codTipo2 == "03")){ //se escolheu categoria Container e tipo 2 sanitário
                    $_POST["tipo3"] = NULL;
                    
                }if($this->getcodCategory() == "003"){ //se escolheu categoria Andaime
                    
                    if(($_POST["tipo1"] == "31-02") || ($_POST["tipo1"] == "32-03")){ //se o tipo1 for Fachadeiro ou Multidirecional
                       
                        $_POST["tipo3"] = NULL;

                    }else{
                        $errors["#tipo3"] = "tipo 3 é obrigatório!";
                    }
                    
                    
                }else if(($this->getcodCategory() == "001") && ($_POST["tipo2"] == "7-03")){ //se for um Container Sanitário
                    $_POST["tipo3"] = NULL;

                }else{
                   
                    $errors["#tipo3"] = "tipo 3 é obrigatório!";
                }                
            }            

            if (($this->getcodCategory() == "003") || ($this->getcodCategory() == "004")) { //se for Andaime ou Escora
                $_POST["tipo4"] = NULL;
                
            }else if(($_POST["tipo4"] == "") && ($this->getcodCategory() == "002") && ($_POST["tipo3"] == "19-02")){ //se for Betoneira com tipo3 Combustão, então não tem tipo4
                $_POST["tipo4"] = NULL;
                
            }else if($this->getcodCategory() == "005"){ //se for um Ar-condicionado
                    $_POST["tipo4"] = NULL;
                    
            }else{

                if ($_POST["tipo4"] == "") {
                    $errors["#tipo4"] = "tipo 4 é obrigatório!";
                }
            }
    
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

            $exists = 0;
            $exists = Product::searchCode($_POST["codigoGen"]);
            if (count($exists) > 0) { //se existe codigoGen completo igual já registrado

                if ($update) {
                    foreach ($exists as $product) {

                        if (($_POST['codigoGen'] == $product['codigoGen']) && ($_POST['idProduto_gen'] != $product['idProduto_gen'])) {
                            /*$errors["#codigoGen"] = "Já existe um Produto com essa Descrição";
                            break; */

                            return json_encode([
                                'error' => true,
                                'msg'=> "Já existe um Produto com o código " . $_POST['codigoGen']
                            ]);
                            
                        }
                    }
                } else {
                    //$errors["#codigoGen"] = "Já existe um produto com essa Descrição";
                    return json_encode([
                        'error' => true,
                        'msg'=> "Já existe um Produto com o código " . $_POST['codigoGen']
                    ]);
                }
            }

            return json_encode([
                'error' => false
            ]);

            /*if($this->getfoto() == ""){
                $json["error_list"]["#desImagePath"] = "Não foi possível fazer Upload da imagem!";               
            }*/
        }
    }/* --- fim verificaErros() ---------------------------*/

    public function createCode(){
      
        
        $_POST["categoria"] = substr($_POST["categoria"], 0, -4); //pega o id (tirando os quatro últimos caracteres)
      
        //echo "id: " . $_POST["categoria"]. ", codigoGen: ". $this->getcodCategory() . "<br>";
        

        /*---------------------- Tipos ------------------------------------------------*/
        $qtdTipos = 4;

        if(($this->getcodCategory() == "001")/* || ($this->getcodCategory() == "002")*/){
            //$qtdTipos = 4;

            if(/*($this->getcodCategory() == "001") && */($_POST["tipo2"] == "7-03")){ //se for container sanitário
                $_POST["tipo3"] = NULL; 
     
            }


        }else if($this->getcodCategory() == "003"){
            //$qtdTipos = 3;
            $_POST["tipo4"] = NULL;

        } else if($this->getcodCategory() == "004"){
            //$qtdTipos = 1;
            $_POST["tipo2"] = NULL;
            $_POST["tipo3"] = NULL;
            $_POST["tipo4"] = NULL;
            
        }
        

        $arrTipos = array("", $_POST["tipo1"], $_POST["tipo2"], $_POST["tipo3"], $_POST["tipo4"]);

        $codTipos = '';

        for($i=1; $i<=$qtdTipos; $i++){
            
            if($arrTipos[$i] == NULL){
                $codTipos .= ".xx";

                //echo $codTipos;

            }else{ 
                
                $codTipos .= "." . substr($arrTipos[$i], -2); //do penúltimo caracter até o final
                $auxTipo = 'tipo' . $i;
                $_POST[$auxTipo] = substr($arrTipos[$i], 0, -3); //pega o id (tirando os três últimos caracteres)
                
                //echo "pos $auxTipo ". $_POST[$auxTipo] ."<br>";

            }
        }

        $_POST["codigoGen"] = $this->getcodCategory() . $codTipos;
        //echo 'código: ' . $_POST["codigoGen"];
        
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

    public function ajax_list_products($requestData)
    {

        $column_search = array("codigoGen", "descCategoria", "qtdTotal", "qtdDisponivel", "descricao"); //colunas pesquisáveis pelo datatables
        $column_order = array("codigoGen", "descCategoria", "qtdTotal", "qtdDisponivel", "descricao"); //ordem que vai aparecer (o codigoGen primeiro)

        //faz a pesquisa no banco de dados
        $product = new Product(); //model

        $datatable = $product->get_datatable($requestData, $column_search, $column_order);

        $data = array();

        foreach ($datatable['data'] as $product) { //para cada registro retornado

            // Ler e criar o array de dados ---------------------
            $row = [
                "id"=>$product['idProduto_gen'],
                "codigoGen"=>$product['codigoGen'],
                "descCategoria"=>$product['descCategoria'],
                "descricao"=>$product['descricao'],
                "qtdTotal"=>$product['qtdTotal'],
                "qtdDisponivel"=>$product['qtdDisponivel']
            ];

            $data[] = $row;
        }

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

        return Product::total();
    }



    public function delete($idproduct){

        $container = new Container();

        $container->get((int)$idproduct);

        $id = $container->getidProduto_gen();
        
        if(isset($id)){ //se for um container
            
           $res = $container->delete();

           if(json_decode($res)->error){

                return $res;
           }
        }
       
        $product = new Product();

        $product->get((int)$idproduct); //carrega o usuário, para ter certeza que ainda existe no banco
       
        return $product->delete();
        
        
    }
}//end class ProductController
