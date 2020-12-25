<?php

namespace Locacao\Utils\PDFs;
/*$orcamento = [
    'idContrato'=>'230',
    'codContrato'=>'001/2020',
    'dtEmissao'=>'2020-05-29',
    'nomeEmpresa'=>'',
    'descCliente'=>'Cliente XYZ',
    'solicitante'=>'Douglas R.',
    'telefone'=>'19 99834-2334',
    'email'=>'teste@gmail.com',
    //''=>'',
];

$listItems = json_encode(array(
    [
        "idItem"=>"84",
        "idContrato"=>"230",
        "idProduto_gen"=>"191",
        "vlAluguel"=>"200",
        "quantidade"=>"12",
        "custoEntrega"=>"150",
        "custoRetirada"=>"120",
        "periodoLocacao"=>"semanal",
        "observacao"=>"Entrega em 5DD úteis",
        "dtCadastro"=>"2020-05-25 08:17:37",
        "descricao"=>"Tubular Painel 1,00m",
        "descCategoria"=>"Andaime"
    ],
    [
        "idItem"=>"94",
        "idContrato"=>"230",
        "idProduto_gen"=>"193",
        "vlAluguel"=>"600",
        "quantidade"=>"2",
        "custoEntrega"=>"200",
        "custoRetirada"=>"190",
        "periodoLocacao"=>"mensal",
        "observacao"=>"Entrega em 5DD úteis",
        "dtCadastro"=>"2020-05-30 09:53:34",
        "descricao"=>"6M sanitário DC",
        "descCategoria"=>"Container"
    ]
));

$x = new BudgetPDF($orcamento, $listItems);

$x->show();
*/
class BudgetPDF
{
    private $codContrato;
    private $dtEmissao;
    private $nomeCliente;
    private $solicitante;
    private $telefone;
    private $email;

    private $listItems; //array

    public function __construct($budget, $listItems)
    {
       // echo $budget;
        $budget = json_decode($budget);

        $this->setHeaderValues($budget);
        $this->setTableItemsValues($listItems);
    }

    public function setHeaderValues($budget){ //Informações do Orçamento, para o cabeçalho do documento PDF
        //print_r($budget);

        $this->codContrato = $budget->codContrato;
        $this->solicitante = $budget->solicitante;
        $this->telefone = $budget->telefone;
        $this->email = $budget->email;

        if ($budget->nomeEmpresa != "") {
            $this->nomeCliente = $budget->nomeEmpresa;

        } else { //se nomeEmpresa estiver vazio
            $this->nomeCliente = $budget->descCliente;
        }

        $this->title = "PROPOSTA - Nº $this->codContrato - $this->nomeCliente";

        setlocale(LC_TIME, 'pt_BR', 'pt_BR.utf-8', 'pt_BR.utf-8', 'portuguese'); //para a data ficar em Português
        //date_default_timezone_set('America/Sao_Paulo');
        //$data = strftime('%d/%m/%Y', strtotime($budget->dtEmissao));
        $this->dtEmissao = utf8_encode(strftime('%d de %B de %Y', strtotime($budget->dtEmissao)));

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
            $newItem['vlUnit'] = $this->toRealMoney($item->vlAluguel);
            $newItem['qtd'] = $item->quantidade;
        
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
        $result = "
        <!DOCTYPE html>
        <html lang='pt-br'>

        <head>
            <meta http-equiv='Content-Type' content='text/html; charset=utf-8'/>
            <meta name='viewport' content='width=device-width, initial-scale=1.0'>
            <title>$this->title</title>

            <style>
                table{
                    font-size: 14px;
                }

                .center{
                    text-align: center;
                }

                th{
                    border-right: 1px solid black;
                }

                td{
                    border-right: 1px solid black;
                }

                #div-lateral{
                    position:relative;
                    width:20rem;
                    float: left;
                }
                #div-logo{
                    position: relative;
                    width: 20rem;
                    left:0px;
                    float: left;
                }

                #div-logo img{
                    width: 80%;
                    border: 3px solid grey;
                }

                .group:before,
                .group:after {
                    content: '';
                    display: table;
                } 
                .group:after {
                    clear: both;
                }
            </style>
        </head>

        <body>

            <div class='text-center bg-warning' style='background-color: yellow; text-align: center;'><small>$this->title</small></div><br>
        
            <div> <!-- CABEÇALHO DO ORÇAMENTO -->
                <div class='group'>
                    <div id='div-logo' class='bg-dark p-1'>
                        <img src='http://". $_SERVER["HTTP_HOST"]."/res/img/logo-COMFAL.jpg' alt='logo'>
                    </div>
                    <div id='div-lateral' class='text-center mt-4 ml-4 center' style='padding-left: 40px;'>
                        <h3 class='text-success' style='color: green;'>ORÇAMENTO</h3>
                        <b>Data: $this->dtEmissao</b><br>
                        <b>Nº $this->codContrato</b><br>
                        Rerefência: <b>locação</b>. validade: 3 três dias
                    </div>
                </div>

                <div class='mt-2'>
                    <b>EMPRESA:</b> $this->nomeCliente<br>
                    <b>A/C SR./SRA:</b> $this->solicitante<br>
                    <b>CONTATO:</b> $this->telefone / $this->email
                </div>
            </div>

            <div class='mt-2'> <!-- TABELA DOS ITENS -->
                <h4 class='text-center pt-4 center'><b>FORNECIMENTO</b></h4>

                <!--... tabela de itens -->
                <table class='table table-bordered table-hover' id='dataTable' cellspacing='0'>
                    <thead style='background-color: lightgreen;'>
                    
                        <tr>
                            <th>ITEM</th>
                            <th>PERÍODO</th>
                            <th>VALOR UNIT.</th>
                            <th>QTD.</th>
                            <th>VALOR TOTAL</th>
                            <th>OBS</th>
                        </tr>
                    </thead>
                    <tbody id='cart'>
                        ";
                        foreach($this->listItems as $row)
                    {
                            //print_r($row);
                            $desc = $row['desc'];
                            $periodo = $row['periodo'];
                            $vlUnit = $row['vlUnit'];
                            $qtd = $row['qtd'];
                            $vlTotal = $row['vlTotal'];
                            $obs = $row['obs'];

                            $auxP = "a/" . substr($periodo, 0, 1); //pega o primeiro caracter do período. ex: 'semanal' -> 's'

                            $result .= "<tr>
                                <td><b>$desc</b></td>
                                <td class='center'>$periodo</td>
                                <td class='center'>$vlUnit $auxP</td>
                                <td class='center'>$qtd</td>
                                <td class='center'>$vlTotal $auxP</td>
                                <td class='center'>$obs</td>
                            </tr>";

                            $tipoFrete = ['ENTREGA', 'RETIRADA'];

                            for($i=0; $i<count($row['frete']); $i++){
                                //print_r($row['frete']);

                                $vlUnit = $row['frete'][$i]['vlUnit'];
                                $vlTotal = $row['frete'][$i]['vlTotal']; 

                                $result .= "<tr style='background-color: lightgrey'>
                                <td>$tipoFrete[$i]</td>
                                <td></td>
                                <td class='center'>$vlUnit</td>
                                <td class='center'>$qtd</td>
                                <td class='center'>$vlTotal</td>
                                <td></td>
                                </tr>";
                            }
                        }

                        $result .= "
                    </tbody>        
                </table>
                </small>
            </div>
            
            <div> <!-- DISPOSIÇÕES GERAIS -->
                <h4 class='text-center center'><b>DISPOSIÇÕES GERAIS</b></h4>
                <small>
                <div class='text-justify'>
                <p><b>1. FORMA DE PAGAMENTO:</b> Faturamento do aluguel realizado mensalmente, através de boleto bancário ou deposito em conta. (Primeiro faturamento em 30 dias após entrega).<br>
                <b>2. PRAZO DE ENTREGA:</b> Descrito na seção “FORNECIMENTO” ou conforme combinado com locatário.<br>
                <b>3. IMPOSTOS:</b> Impostos - federais, estaduais, municipais, trabalhistas ou encargos sociais - incluídos no preço, exceto quando descritos separadamente na seção “FORNECIMENTO”.<br>
                <b>4. OBSERVAÇÕES:</b> O período de locação mínimo é de 30 (trinta) dias. Os valores dos fretes, descritos na seção “FORNECIMENTO”, são referentes ao carregamento e/ou descarregamento dos equipamentos no local dentro do prazo de uma hora (ou período previamente acordado). Horas adicionais à disposição, devido a mau tempo, processos de liberação, ou outros quaisquer, serão cobradas sobre o valor do frete previamente acordado.</p>
                </div>
                </small>
            </div> 
            <!-- se colocar o bootstrap terá que ler o arquivo inteiro, e demorará mais tempo para carregar -->
            <!--link href='http://localhost/res/plugins/bootstrap.min.css' rel='stylesheet' /-->

        </body>

        </html>";
        
        $this->title = "PROPOSTA - N. $this->codContrato - $this->nomeCliente";

        return array($this->title, $result);
    }
}

