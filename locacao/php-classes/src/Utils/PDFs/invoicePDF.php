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

        $this->numFatura = $invoice->numFatura;
        $this->nomeEmpresa = $empresa->nome;
        //$this->cpfCnpjEmpresa = $empresa->cpf == "" ? $empresa->cnpj : $empresa->cpf;
        
       
        $this->title = "FATURA - Nº $this->numFatura - $this->nomeCliente";

        setlocale(LC_TIME, 'pt_BR', 'pt_BR.utf-8', 'pt_BR.utf-8', 'portuguese'); //para a data ficar em Português
        //date_default_timezone_set('America/Sao_Paulo');
        //$data = strftime('%d/%m/%Y', strtotime($budget->dtEmissao));
        $this->dtEmissao = utf8_encode(strftime('%d de %B de %Y', strtotime($invoice->dtEmissao)));

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
            <title>Document</title>
        
        <style>
            
            body{
                /* background-color: #d6d3d3fd;
                color: rgba(0,0,0,1); */
                font-family: Cambria, Cochin, Georgia, Times, 'Times New Roman', serif;
                font-size: 12px;
            }
            
            #corpo{
            position: relative;
            top: -30px;
            }
            
            #logo{
                position: relative;
                width: 20rem;
                left:0px;
                float: left;
            }

            #logo img{
                width: 80%;
                border: 3px solid grey;
            }
            
            table.tabela{
                border-spacing: 0px;
                margin-left: auto;
                margin-right: auto; 
            }
            
            table.tabela th{
            
                border-right: 1px solid black;
            }
            table.tabela td{
                padding: 4px;
                text-align: left;
                vertical-align: middle;
                border-right: 1px solid black;
            }
            
            tr:nth-child(even) {background-color: #f2f2f2;}
            
            h4 { 
            text-align: center;
            }
            
            p {    
            text-align: justify;
            }
            
            #direitaHeader { 
                width: 200px;       
                height: 100px;
                padding-top: 0px;
                position: relative;
                margin-left: 520px;
                font-size: 13px;
            }

            #esquerdaHeader {
                width: 200px;
                height: 100px;
                margin-top: 40px;
                font-size: 13px;
            }

        </style>
        
        
        </head>
        <body>

            <div class='text-center bg-warning' style='background-color: yellow; text-align: center;'><small>$this->title</small></div><br>
            <div> <!-- CABEÇALHO DA FATURA -->
                <div id='logo'>
                    <img src='http://localhost/res/img/logo-COMFAL.jpg' alt='logo'>
                </div>
            
                <div id='direitaHeader'>
                    <b class='text-success' style='color: green;'>FATURA</b><br>
                    <b>Data: $this->dtEmissao</b><br>
                    <b>Nº $this->numFatura</b><br>
                    Rerefência: <b>locação</b>. validade: 3 três dias
                </div>
                
                <div id='esquerdaHeader'>
                    <b style='color: green;'>EMPRESA:</b> $this->nomeCliente<br>
                    <b>A/C SR./SRA:</b> $this->solicitante<br>
                    <b>CONTATO:</b> $this->telefone / $this->email
                </div>
            </div>
            
                <div id='corpo'>
        
                    <table class='tabela'>
                        <thead style='background-color: lightgreen;'>
                            <tr> 
                                <th>ITEM</th>
                                <th>PERIODO</th>
                                <th>LOCAÇÃO</th>
                                <th>QUANTIDADE</th>
                                <th>ENTREGA</th>
                                <th>RETIRADA</th>
                                
                            </tr>
                        </thead>
                        <tbody>";
                        /*foreach($this->listItems as $item){
                            $desc = $item['desc'];
                            $periodo = $item['periodo'];
                            $locacao = $item['locacao'];
                            $quantidade = $item['quantidade'];
                            $entrega = $item['entrega'];
                            $retirada = $item['retirada'];

                            $entrega =  $this->toRealMoney($entrega);
                            $retirada = $this->toRealMoney($retirada);

                            $result .= "<tr>
                                <td class='center'><b>$desc</b></td>
                                <td class='center'><b>$periodo</b></td>
                                <td class='center'>$locacao</td>
                                <td class='center' style='text-align: center;'>$quantidade</td>
                                <td class='center'>$entrega</td>
                                <td class='center'>$retirada</td>
                            </tr>";
                        }*/

                      $result .= "  
                      </tbody>
                    </table>
                    
                </div>
     
            </body>
            </html>";
        
        $this->title = "FATURA - N. $this->numFatura - $this->nomeCliente";

        return array($this->title, $result);
    }
}