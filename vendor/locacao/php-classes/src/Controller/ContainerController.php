<?php

namespace Locacao\Controller;

use \Locacao\Generator;
use \Locacao\Model\User;
use \Locacao\Model\Product;

class ContainerController extends Generator
{

    //construtor
    public function __construct()
    {
    }


    public function save($update = false) //Add a new user or Update
    {

        User::verifyLogin();

        $error = $this->verifyFields($update); //verifica os campos do formulário
        $aux = json_decode($error);

        if ($aux->error) {
            return $error;
        }

        $user = new User(); //Model

        $user->setData($_POST);

        if ($update) { //se for atualizar

            $search = new User();
            //pega o caminho da imagem atual
            $res = $search->get((int) $_POST['idUsuario']);
            $desOldImagePath = $res['foto'];
        }else{
            $desOldImagePath = "";
        }

        $image = $this->uploadImage($_FILES, $desOldImagePath); //salva imagem na pasta

        $user->setfoto($image);

        if ($update) { //se for atualizar
            return $user->update();

        } else { // se for cadastrar novo usuário
            return $user->insert();
        }
    }


    public function verifyFields($update = false)
    {/*Verifica todos os campos ---------------------------*/

        $errors = array();

        $errors = array();

        if ($_POST["codigo"] == "") {
            $errors["#codigo"] = "Código é obrigatório!";
        }
        if ($_POST["medida"] == "") {
            $errors["#medida"] = "Medida é obrigatória!";
        }
        if ($_POST["tipoContainer"] == "") {
            $errors["#tipoContainer"] = "Tipo de container é obrigatório!";
        }
        if (($_POST["tipoPorta"] == "") && (!$update)) {
            $errors["#tipoPorta"] = "Tipo de porta é obrigatória!";
        }

        $exists = Container::searchCodigo($_POST["codigo"]);
        if (count($exists) > 0) { //se existe nome completo igual já registrado

            if ($update) {
                foreach ($exists as $container) {

                    if (($_POST['codigo'] == $container['codigo']) && ($_POST['idContainer'] != $container['idContainer'])) {
                        $errors["#codigo"] = "Já existe um Container com esse Código";
                        break;
                    }
                }
            } else {
                $errors["#codigo"] = "Já existe um usuário com esse Código";
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
    } *//*-------------end uploadDesImagePath() ---------------------------------------------------------*/


    /*-------------------------------- DataTables -------------------------------------------------------------------*/

    /* CAMPOS VIA POST (Para trabalhar como DataTables)

		$_POST['search']['value'] = Campo para busca
		$_POST['order'] = [['0, 'asc']]
			$_POST['order'][0]['column'] = index da coluna
			$_POST['order'][0]['dir'] = tipo de ordenação (asc, desc)
		$_POST['length'] = Quantos campos mostrar
		$_POST['length'] = Qual posição começar
    */

    public function ajax_list_users($requestData)
    {

        $column_search = array("codigo", "medida", "status", "vlAluguel"); //colunas pesquisáveis pelo datatables
        $column_order = array("codigo", "medida", "status", "vlAluguel"); //ordem que vai aparecer (o nome primeiro)

        //faz a pesquisa no banco de dados
        $users = new User(); //model

        $datatable = $users->get_datatable($requestData, $column_search, $column_order);

        $data = array();

        foreach ($datatable['data'] as $user) { //para cada registro retornado

            $id = $user['idUsuario'];

            // Ler e criar o array de dados ---------------------
            $row = array();

            $row[] = $user['nomeUsuario'];
            $row[] = $user['nomeCompleto'];
            $row[] = $user['funcao'];
            $row[] = $isAdm;
            $row[] = "<button type='button' title='ver detalhes' class='btn btn-warning btnEdit'
                onclick='loadUser($id);'>
                    <i class='fas fa-bars sm'></i>
                </button>
                <button type='button' title='excluir' onclick='deleteUser($id);'
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
}//end class ProductController
