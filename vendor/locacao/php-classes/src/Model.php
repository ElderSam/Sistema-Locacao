<?php

/* Usando a classe Model para fazer automaticamente todos os Getters e Setters de todas as Classes */
namespace Locacao;

class Model{

    private $values = [];

    //executa __call toda vez que eu chamo o método que não foi definido
    public function __call($name, $args){

        //Descobre qual é o método chamado (Get ou Set)
        $method = substr($name, 0, 3); //pega os três primeiros caracteres
        $fieldName = substr($name, 3, strlen($name)); //pega o resto da string

       /* echo "método e campo: ";
        var_dump($method, $fieldName);
        exit;*/

        switch($method){

            case "get":
                return (isset($this->values[$fieldName])) ? $this->values[$fieldName] : NULL; //para casos em que não passo o id. por exemplo: cadastro de categoria
            break;

            case "set":
                return $this->values[$fieldName] = $args[0]; // $args recebe o valor passado (argumeto)
            break;

        }
                        
    }

    public function setData($data = array()){

        foreach($data as $key => $value){

            $this->{"set".$key}($value); //toda vez que for fazer algo dinamicamente no PHP, tem qeu ser por chaves {}
        }
    }

    public function getValues(){

        return $this->values;
    }
}