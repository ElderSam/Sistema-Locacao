<?php

namespace Locacao\Utils\PDFs;

class InvoicePDF
{
    private $numFatura;
    private $nomeEmpresa;
    //...

    private $listItems; //array

    public function __construct($invoice, $listItems, $empresa)
    {
       // echo $budget;
        $invoice = json_decode($invoice);
        $empresa = json_decode($empresa);

        $this->setInvoiceValues($invoice, $empresa[0]);
        $this->setTableItemsValues($listItems);
    }

    public function setInvoiceValues($invoice, $empresa){ //Informações da Fatura, para o cabeçalho do documento PDF

        $this->numFatura = ""; // $invoice->numFatura
        $this->nomeEmpresa = $empresa->nome;
        //$this->cpfCnpjEmpresa = $empresa->cpf == "" ? $empresa->cnpj : $empresa->cpf;
        
       
        // $this->title = "FATURA - Nº $this->numFatura - $this->nomeCliente";

        setlocale(LC_TIME, 'pt_BR', 'pt_BR.utf-8', 'pt_BR.utf-8', 'portuguese'); //para a data ficar em Português
        //date_default_timezone_set('America/Sao_Paulo');
        //$data = strftime('%d/%m/%Y', strtotime($budget->dtEmissao));
        // $this->dtEmissao = utf8_encode(strftime('%d de %B de %Y', strtotime($invoice->dtEmissao)));

    }

    public function setTableItemsValues($listItems){ //configura os valores de cada linha da tabela de itens de orçamento
        //print_r($listItems);
        $listItems = json_decode($listItems);
        //print_r($listItems);

        $this->listItems = [];

        foreach($listItems as $item){
            $newItem = [];

            //print_r($item);
            $newItem['desc'] = strtoupper($item->descCategoria) ." ". $item->descricao; //recebe a descrição completa do item
            $newItem['periodo'] = $item->periodoLocacao;
            $newItem['locacao'] = $this->toRealMoney($item->vlAluguel);
            $newItem['quantidade'] = $item->quantidade;
            $newItem['entrega'] = $item->custoEntrega;
            $newItem['retirada'] = $item->custoRetirada;
        
            $auxVlTotal = $item->vlAluguel * $item->quantidade; //calcula valor total do item
            $newItem['vlTotal'] = $this->toRealMoney($auxVlTotal); //formata em moeda Real com 2 casas decimais
            //echo ' DINHEIRO: '. $newItem['vlTotal'];

            $newItem['obs'] = $item->observacao;

            /* ---------- frete ------------- */

            $arrFrete = array(
                ['custoEntrega', $item->custoEntrega],
                ['custoRetirada', $item->custoRetirada],
            );

            //print_r($arrFrete);
            $newItem['frete'] = [];

            for($i=0; $i<2; $i++){
               // echo $arrFrete[$i][0] . "<br>";
                $desc = $arrFrete[$i][0];
                $value = $arrFrete[$i][1];

                $newItem['frete'][$i]['vlUnit'] = $this->toRealMoney($value);
                $auxVlTotalEntrega = $value * $item->quantidade;
                $newItem['frete'][$i]['vlTotal'] = $this->toRealMoney($auxVlTotalEntrega);           
            }

            $this->listItems[] = $newItem; //insere no array de itens que irão para a tabela
        }  

        //print_r($this->listItems);
    }

    public function toRealMoney($value){
        return "R$ ".number_format($value,2,",",".");
    }

    function show()
    {
        $result = "<!DOCTYPE html>
        <html lang='pt-br'>
            <head>
                <meta charset='UTF-8'>
                <meta name='viewport' content='width=device-width, initial-scale=1.0'>
                <title>Modelo Fatura</title>
            
            <style>
                
                header {
                    position:absolute;
                    width: 730px;
                    height: 130px;
                    border: solid;
                    padding: 10px;
                    padding-top: 10px;
                }

                #logo{
                    position: relative;
                    width: 350px;
                    float: left;
                    margim-left:300px;
                }

                #logo img{
                    width: 80%;
                }
                
                #direitaHeader { 
                    width: 300px;       
                    height: 100px;
                    padding-top: 20px;
                    position: relative;
                    margin-left: 420px;
                    font-size: 13px;
                }

                #direitaHeader, strong{
                    text-align: right;
                }

                #corpoSuperior {
                    margin-top: 152px;
                    width: 750px;
                    height: 200px;
                    border: solid;
                }

                #esquerdaBody, strong {
                    font-size: 13px;
                }

                #corpoSuperior-esquerda {
                    
                 
                    margin: 5px;
                    width: 350px;
                    height: 150px;

                } 

                #corpoSuperior-direita {
                    
                    position: relative;
         
                    width: 350px;
                    height: 180px;
                    left: 370px;
                    top: -150px;
                    padding: 20px;
                } 

                #esquerda-superior {
            
                    position: relative;
                    width: 170px
                    height: 50px;
                    left: 0px;
                    top: 40px;
                    margin: 5px;
                }

                #direita-superior {
               
                    position: relative;
                    width: 150px
                    height: 50px;
                    left: 190px;
                    top: -45px;
                }

                #numero-fatura {
                    position: relative;
                    font-size: 27px;
                    color: red;
                    left: 80px;
                }

             </style>
            
            
            </head>

            <header>
               
                <div id='logo'>
                    <img src='http://localhost/res/img/logo-COMFAL.jpg' alt='logo'>
                </div>
            
                <div id='direitaHeader'>
                    <strong>COMFAL LOCAÇÃO DE MÁQUINAS LTDA - ME</strong><br>
                    <strong>Tel: () 99999 - 9999</strong><br>
                    <strong>E-mail: exemplo@exemplo.com</strong><br>
                    <strong>Site: www.cofal.com.br</strong>
                </div>
                <br><br><br><br><br>
               <div id='linhaCabecalho'></div>
            </header>
            <body>
                <div id='corpoSuperior'>

                    <div id='corpoSuperior-esquerda'>            
                        <div id='esquerdaBody'></div>
                        <br><br>
                        <strong>COMFAL LOCAÇÃO DE MÁQUINAS LTDA - ME</strong><br>
                        <strong>Rua Hugo Rondelli, 66 - Praia Azul - Americana</strong><br>
                        <strong>CEP: 13476-692 - UF: SP</strong><br>
                        <strong>CNPJ: 71.528.426/0001-60 IE: 165.122.184.116</strong>
                    </div>

                    <div id='corpoSuperior-direita'>
                        <strong>FATURA DE LOCAÇÃO</strong><br>
                        <strong>Data de emissão: 21/12/2020</strong>
                        &nbsp;&nbsp;&nbsp;&nbsp;
                        <strong id='numero-fatura'>Nº: 4343</strong>
                        <div id='esquerda-superior'>
                            <p>1º Via: Dest/Remetente</p>
                            <p>2º Via: Arq. Fiscal</p>
                        </div>
                        <div id='direita-superior'>
                            <p>3º Via: Fisco Destino</p>
                            <p>4º Via: Fisco Destino</p>
                        </div>
                    </div>

                </div>    

                
            </body>
            <footer>
            
            </footer>
        </html>";
        
        $this->title = "FATURA - N. 324343 - Cliente teste"; // $this->numFatura - $this->nomeCliente

        return array($this->title, $result);
    }
}