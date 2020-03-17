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

      /*  if ($update) { //se for atualizar

            $search = new Product();
            //pega o caminho da imagem atual
            $res = $search->get((int) $_POST['idProduto']);
            $desOldImagePath = $res['foto'];
        }else{
            $desOldImagePath = "";
        }*/

       // $image = $this->uploadImage($_FILES, $desOldImagePath); //salva imagem na pasta

        //$product->setfoto($image);

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
                    $idProduto = $aux->idProduto;
                   
                    //echo "id: " . $idProduto . "<br>";
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
                    $idProduto = $aux->idProduto;
                
                    //echo "id: " . $idProduto . "<br>";
                    $container->setData($_POST);
                    $res2 = $container->insert($idProduto);
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
        
        if ($_POST["descricao"] == "") {
            $errors["#descricao"] = "Descrição é obrigatória!";
        }
        if ($_POST["valorCompra"] == "") {
            $errors["#valorCompra"] = "Valor de Compra é obrigatório!";
        }
        if ($_POST["status"] == "") {
            $errors["#status"] = "Status é obrigatório!";
        }   

        if (($_POST["categoria"] == "")) {
            $errors["#categoria"] = "Categoria é obrigatória!";
           
        }else{
         
            $this->setcodCategory(substr($_POST["categoria"], -3)); //do antepenúltimo caracter até o final
            
            if (($_POST["tipo1"] == "")) {
                $errors["#tipo1"] = "tipo 1 é obrigatório!";
            }

            if (($_POST["tipo2"] == "") && ($this->getcodCategory() != "004")) {
                
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
                
            }else{ //se for Categoria Escora (cod 004)
                $_POST["tipo4"] = NULL;
            }
            
            if (($_POST["tipo3"] == "") && ($this->getcodCategory() != "004")) {
                
                $codTipo2 = substr($_POST["tipo2"], -2);

                if(($this->getcodCategory() == "001") && ($codTipo2 == "03")){ //se escolheu categoria Container e tipo 2 sanitário
                    $_POST["tipo3"] = NULL;
                    
                }if($this->getcodCategory() == "003"){ //se escolheu categoria Andaime
                    
                    if(($_POST["tipo1"] == "31-02") || ($_POST["tipo1"] == "32-03")){ //se o tipo1 for Fachadeiro ou Multidirecional
                       
                        $_POST["tipo3"] = NULL;

                    }else{
                        $errors["#tipo3"] = "tipo 3 é obrigatório!";

                    }
                    
                    
                }else{
                    $errors["#tipo3"] = "tipo 3 é obrigatório!";
                }
                
            }

            if (($this->getcodCategory() == "003") || ($this->getcodCategory() == "004")) {
                $_POST["tipo4"] = NULL;
                
            }else if(($_POST["tipo4"] == "") && ($this->getcodCategory() == "002") && ($_POST["tipo3"] == "19-02")){ //se for Betoneira com tipo3 Combustão, então não tem tipo4
                $_POST["tipo4"] = NULL;
                
            }else{
                $errors["#tipo4"] = "tipo 4 é obrigatório!";
            }

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

        $exists = 0;
        $exists = Product::searchDesc($_POST["descricao"]);
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
      
        //echo "id: " . $_POST["categoria"]. ", codigo: ". $this->getcodCategory() . "<br>";
        

        /*---------------------- Tipos ------------------------------------------------*/
        if(($this->getcodCategory() == "001") || ($this->getcodCategory() == "002")){
            $qtdTipos = 4;

            if(($this->getcodCategory() == "001") && ($_POST["tipo2"] == "7-03")){ //se for container sanitário
                $_POST["tipo3"] = NULL; 
     
            }


        }else if($this->getcodCategory() == "003"){
            $qtdTipos = 3;
            $_POST["tipo4"] = NULL;

        } else if($this->getcodCategory() == "004"){
            $qtdTipos = 1;
            $_POST["tipo2"] = NULL;
            $_POST["tipo3"] = NULL;
            $_POST["tipo4"] = NULL;
            
        }
        

        $arrTipos = array("", $_POST["tipo1"], $_POST["tipo2"], $_POST["tipo3"], $_POST["tipo4"]);

        $codTipos = '';

        for($i=1; $i<=$qtdTipos; $i++){
            
            if($arrTipos[$i] == NULL){
                $codTipos .= "xx.";

            }else{ 
                
                $codTipos .= substr($arrTipos[$i], -2) . "."; //do penúltimo caracter até o final
                $auxTipo = 'tipo' . $i;
                $_POST[$auxTipo] = substr($arrTipos[$i], 0, -3); //pega o id (tirando os três últimos caracteres)
                
                //echo "pos $auxTipo ". $_POST[$auxTipo] ."<br>";

            }
        }
       
        $codFornecedor = substr($_POST["fornecedor"], -3); //pega do antepenúltimo caracter até o final
        $_POST["fornecedor"] = substr($_POST["fornecedor"], 0, -4); //pega o id (tirando os quatro últimos caracteres)
        //echo 'id: '. $_POST["fornecedor"]. ", cod: " . $codFornecedor;

        if(($this->getcodCategory() == 3) || ($this->getcodCategory() == 4)){ //Andaimes e Escoras não tem número de série (número de inventário)
            $_POST["numSerie"] = NULL;
            $numSerie = "xxxx";

        }else{
          
            $numSerie = $_POST["numSerie"];
        }

        $_POST["codigo"] = $this->getcodCategory() . "." . $codTipos . $codFornecedor . "-" . $numSerie;
        //echo $_POST["codigo"];
        
        /*----------------------fim de Tipos ------------------------------------------------*/


        
        //fornecedortem que ter 4 caracteres
        
    
    }

    /*public function uploadImage($files, $desOldImagePath = "")
    {

        //print_r($files);

        if ($desOldImagePath == "" && $files["desImagePath"]["name"] == "") { //Não subiu imagem e não tem antiga
            //echo "<br>SEM IMAGEM<br>";
            $this->desImagePath = "/res/img/products/product-default.jpg";
            return $this->desImagePath;
        } elseif ($files["desImagePath"]["name"] != "") { //se subiu imagem nova
            //echo "nome_imagem " . $files["desImagePath"]["name"] . "<br>";

            if ($desOldImagePath != "/res/img/products/product-default.jpg" && $desOldImagePath != "") { //se vai substituir imagem antiga    
                //echo " tem que APAGAR! desOldImagePath: " . $desOldImagePath . "<br>";    
                unlink($desOldImagePath);
            }
        } elseif ($desOldImagePath != "" && $files["desImagePath"]["name"] == "") { //se não subiu, mas tem imagem antiga

            //echo "<br> já tem imagem antiga! <br>";

            return $desOldImagePath;
        }


        $target_dir = "res/img/products/";
        $newName = time() . "_" . basename($files["desImagePath"]["name"]);
        $target_file = $target_dir . $newName;
        $imageFileType = strtolower(pathinfo($target_file, PATHINFO_EXTENSION));
        //echo "<br>target_file: " . $target_file . "<br>imageFileType " . $imageFileType;

        if (getimagesize($files["desImagePath"]["tmp_name"]) === false) {
            return json_encode([
                'error' => true,
                "message" => "Arquivo não é uma imagem válida!"
            ]);
            //exit;
        }

        if (file_exists($target_file)) {
            return json_encode([
                'error' => true,
                "message" => "Imagem já existente em nosso banco de dados!"
            ]);
            //exit;
        }

        if ($files["desImagePath"]["size"] > 5 * 1024 * 1024) {
            return json_encode([
                'error' => true,
                "message" => "Imagem muito grande. Insira uma imagem de até 5MB!"
            ]);
            //exit;
        }

        if ($imageFileType != "jpg" && $imageFileType != "png" && $imageFileType != "jpeg" && $imageFileType != "gif") {
            return json_encode([
                'error' => true,
                "message" => "Tipo de imagem incorreto, somente JPG, JPEG, PNG e GIF!"
            ]);
            //exit;
        }

        if (move_uploaded_file($files["desImagePath"]["tmp_name"], $target_file)) { //se subiu a imagem
            $this->desImagePath = "/" . $target_file;

            return $this->desImagePath;
        } else {
            return json_encode([
                'error' => true,
                "message" => "Erro ao transferir imagem!"
            ]);
            //exit;
        }
    }*/ /*-------------end uploadDesImagePath() ---------------------------------------------------------*/


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

        $column_search = array("codigo", "descCategoria", "status", "tipo1", "descricao"); //colunas pesquisáveis pelo datatables
        $column_order = array("codigo", "descCategoria", "status", "tipo1", "descricao"); //ordem que vai aparecer (o codigo primeiro)

        //faz a pesquisa no banco de dados
        $product = new Product(); //model

        $datatable = $product->get_datatable($requestData, $column_search, $column_order);

        $data = array();

        foreach ($datatable['data'] as $product) { //para cada registro retornado

            if ($product['status'] == 0) {
                $status = "Alugado";
            } else if ($product['status'] == 1){
                $status = "Disponível";
            }else if ($product['status'] == 2){
                $status = "Manutenção";
            }
    

            $id = $product['idProduto'];

            // Ler e criar o array de dados ---------------------
            $row = array();

            $row[] = $product['codigo'];
            $row[] = $product['descCategoria'];
            $row[] = $status;
            $row[] = $product['tipo1'];
            $row[] = $product['descricao'];
            $row[] = "<button type='button' title='ver detalhes' class='btn btn-warning btnEdit'
                onclick='loadProduct($id);'>
                    <i class='fas fa-bars sm'></i>
                </button>
                <button type='button' title='excluir' onclick='deleteProduct($id);'
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

        return Product::total();
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
       
        $product = new Product();

        $product->get((int)$idproduct); //carrega o usuário, para ter certeza que ainda existe no banco
       
        return $product->delete();
        
        
    }
}//end class ProductController
