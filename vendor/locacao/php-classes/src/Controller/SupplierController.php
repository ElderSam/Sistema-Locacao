<?php

namespace Locacao\Controller;

use \Locacao\Generator;
use \Locacao\Model\User;
use \Locacao\Model\Supplier;

class SupplierController extends Generator
{

    //construtor
    public function __construct()
    {
    }


    public function save($update = false) //Add a new Supplier or Update
    {
        
        User::verifyLogin();
        
        $error = $this->verifyFields($update); //verifica os campos do formulário
        $aux = json_decode($error);

        if ($aux->error) {
            return $error;
        }

        $supplier = new Supplier(); //Model

        $supplier->setData($_POST);

      /*  if ($update) { //se for atualizar

            $search = new Supplier();
            //pega o caminho da imagem atual
            $res = $search->get((int) $_POST['idFornecedor']);
            $desOldImagePath = $res['foto'];
        }else{
            $desOldImagePath = "";
        }*/

       // $image = $this->uploadImage($_FILES, $desOldImagePath); //salva imagem na pasta

        //$supplier->setfoto($image);

        if ($update) { //se for atualizar
            
            $upd =  $supplier->update();

            if($this->getcodCategory() == '001'){ //se for um container
                
                $aux = json_decode($upd);

                if(isset($aux->error) && ($aux->error == true)){
                    return $upd;

                }else{

                    $aux = json_decode($upd);
                    //print_r($aux);
                    $idFornecedor = $aux->idFornecedor;
                   
                    //echo "id: " . $idFornecedor . "<br>";            

                    $upd = json_decode($upd);
                 
                    return json_encode([
                        "Fornecedor"=>$upd
                    ]);
                }


            }else{
                return $upd;
            }

        } else { // se for cadastrar novo Fornecedor
            $res = $supplier->insert();
            //print_r($res);
            if($this->getcodCategory() == '001'){ //se for um container

                $aux = json_decode($res);

                if(isset($aux->error) && ($aux->error == true)){
                    return $res;

                }else{
                    $aux = json_decode($res);
                    //print_r($aux);
                    $idFornecedor = $aux->idFornecedor;
                
                    //echo "id: " . $idFornecedor . "<br>";
                }

            }else{
                return $res;
            }
            
        }
    }


    public function verifyFields($update = false)
    {/*Verifica todos os campos ---------------------------*/

        $errors = array();

        if ($_POST["codigo"] == "") {
            $errors["#codigo"] = "Código é obrigatório!";
        }

        if ($_POST["nome"] == "") {
            $errors["#nome"] = "Nome é obrigatório!";
        }
        
        if ($_POST["telefone1"] == "") {
            $errors["#telefone1"] = "Telefone é obrigatório!";
        }

        /*if ($_POST["email1"] == "") {
            $errors["#email1"] = "Email é obrigatório!";
        }  */ 

        if (($_POST["endereco"] == "")) {
            $errors["#endereco"] = "Endereço é obrigatório!";       
        }

        
        if (($_POST["numero"] == "")) {
            $errors["#numero"] = "obrigatório!";       
        }

        if (($_POST["bairro"] == "")) {
            $errors["#bairro"] = "Bairro é obrigatório!";       
        }

        if (($_POST["cidade"] == "")) {
            $errors["#cidade"] = "Cidade é obrigatória!";       
        }

        if (($_POST["uf"] == "")) {
            $errors["#uf"] = "Estado é obrigatório!";       
        }

        if (($_POST["status"] == "")) {
            $errors["#status"] = "status é obrigatório!";       
        }

        $exists = 0;
        $exists = Supplier::searchName($_POST["nome"]);
        if (count($exists) > 0) { //se existe nome completo igual já registrado

            if ($update) {
                foreach ($exists as $supplier) {

                    if (($_POST['nome'] == $supplier['nome']) && ($_POST['idFornecedor'] != $supplier['idFornecedor'])) {
                        $errors["#nome"] = "Já existe um Fornecedor com essa Descrição";
                        break;
                    }
                }
            } else {
                $errors["#descricao"] = "Já existe um Fornecedor com essa Descrição";
            }
        }

        if (count($errors) > 0) { //se tiver algum erro de input (campo) do formulário

            return json_encode([
                'error' => true,
                'error_list' => $errors
            ]);
        } else { //se ainda não tem erro

            return json_encode([
                'error' => false
            ]);

            /*if($this->getfoto() == ""){
                $json["error_list"]["#desImagePath"] = "Não foi possível fazer Upload da imagem!";               
            }*/
        }
    }/* --- fim verificaErros() ---------------------------*/


    /*public function uploadImage($files, $desOldImagePath = "")
    {

        //print_r($files);

        if ($desOldImagePath == "" && $files["desImagePath"]["name"] == "") { //Não subiu imagem e não tem antiga
            //echo "<br>SEM IMAGEM<br>";
            $this->desImagePath = "/res/img/suppliers/supplier-default.jpg";
            return $this->desImagePath;
        } elseif ($files["desImagePath"]["name"] != "") { //se subiu imagem nova
            //echo "nome_imagem " . $files["desImagePath"]["name"] . "<br>";

            if ($desOldImagePath != "/res/img/suppliers/supplier-default.jpg" && $desOldImagePath != "") { //se vai substituir imagem antiga    
                //echo " tem que APAGAR! desOldImagePath: " . $desOldImagePath . "<br>";    
                unlink($desOldImagePath);
            }
        } elseif ($desOldImagePath != "" && $files["desImagePath"]["name"] == "") { //se não subiu, mas tem imagem antiga

            //echo "<br> já tem imagem antiga! <br>";

            return $desOldImagePath;
        }


        $target_dir = "res/img/suppliers/";
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

    public function ajax_list_suppliers($requestData)
    {

        $column_search = array("codFornecedor", "nome", "status", "telefone1", "cidade"); //colunas pesquisáveis pelo datatables
        $column_order = array("codFornecedor", "nome", "status", "telefone1", "cidade"); //ordem que vai aparecer (o codigo primeiro)

        //faz a pesquisa no banco de dados
        $supplier = new Supplier(); //model

        $datatable = $supplier->get_datatable($requestData, $column_search, $column_order);

        $data = array();

        foreach ($datatable['data'] as $supplier) { //para cada registro retornado

            if ($supplier['status'] == 1) {
                $status = "Ativo";
            } else{
                $status = "Inativo";
            }

            $id = $supplier['idFornecedor'];

            // Ler e criar o array de dados ---------------------
            
            $row = [
                "id"=>$id,
                "codFornecedor"=>$supplier['codFornecedor'],
                "nome"=>$supplier['nome'],
                "status"=>$status,
                "telefone1"=>$supplier['telefone1'],
                "cidade"=>$supplier['cidade'],            
            ];

           /* $row[] = $supplier['codFornecedor'];
            $row[] = $supplier['nome'];
            $row[] = $status;
            $row[] = "<p class='telefone'>".$supplier['telefone1']."</p>";
            $row[] = $supplier['cidade'];
            $row[] = "<button type='button' title='ver detalhes' class='btn btn-warning btnEdit'
                onclick='loadSupplier($id);'>
                    <i class='fas fa-bars sm'></i>
                </button>
                <button type='button' title='excluir' onclick='deleteSupplier($id);'
                    class='btn btn-danger btnDelete'>
                    <i class='fas fa-trash'></i>
                </button>";*/

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

        return Supplier::total();
    }



    public function delete($idsupplier){
       
        $supplier = new Supplier();
        echo "id: " . $idsupplier;
        $supplier->get((int)$idsupplier); //carrega o usuário, para ter certeza que ainda existe no banco
       
        return $supplier->delete();
        
        
    }
}//end class SupplierController
