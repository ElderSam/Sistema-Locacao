<?php

namespace Locacao;

use Rain\Tpl; //para saber que quando usarmos a classe Tpl ela está no namespace Rain

class Page{

    private $tpl;
    private $options = [];
    private $defaults = [
        "header"=>true,
		"footer"=>true,
        "data"=>[]
    ];

    public function __construct($opts = array(), $tpl_dir = "/views/"){
        
        $this->options = array_merge($this->defaults, $opts);

        $config = array(
		    "base_url"      => null,
		    "tpl_dir"       => $_SERVER['DOCUMENT_ROOT'].$tpl_dir,
		    "cache_dir"     => $_SERVER['DOCUMENT_ROOT']."/views-cache/",
		    "debug"         => false //set to false to improve the speed
        );
        
        Tpl::configure( $config );

        $this->tpl = new Tpl();

        $this->setData($this->options["data"]);

        if($this->options["header"] == true) $this->tpl->draw("header"); //desenha o header
   
    }

    private function setData($data = array()){

        //pega chave e valor de cada um
        foreach($data as $key => $value){
            $this->tpl->assign($key, $value); 

        }

    }
    //desenha a sua página na tela
    public function setTpl($name, $data = array(), $returnHTML = false){

        $this->setData($data);

        //desenha o template
        return $this->tpl->draw($name, $returnHTML);
    }

    public function __destruct(){

        if($this->options["footer"] == true) $this->tpl->draw("footer");

    }
}


?>