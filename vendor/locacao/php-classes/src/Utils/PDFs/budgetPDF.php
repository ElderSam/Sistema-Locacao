<?php

namespace Locacao\Utils\PDFs;
$orcamento = [
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

class BudgetPDF
{
    //private $id;
    private $codContrato;
    private $dtEmissao;
    private $nomeCliente;
    private $solicitante;
    private $telefone;
    private $email;

    private $listItems; //array

    public function __construct($budget, $listItems)
    {
        $this->setHeaderValues($budget);
        $this->setTableItemsValues($listItems);
    }

    public function setHeaderValues($budget){ //Informações do Orçamento, para o cabeçalho do documento PDF
        //print_r($budget);
        $this->idOrcamento = $budget['idContrato'];
        $this->codContrato = $budget['codContrato'];
        //$data = strftime('%d/%m/%Y', strtotime($budget['dtEmissao']));
        $this->solicitante = $budget['solicitante'];
        $this->telefone = $budget['telefone'];
        $this->email = $budget['email'];

        if ($budget['nomeEmpresa'] != "") {
            $this->nomeCliente = $budget['nomeEmpresa'];

        } else { //se nomEmpresa estiver vazio
            $this->nomeCliente = $budget['descCliente'];
            
        }

        $this->title = "PROPOSTA - Nº $this->codContrato - $this->nomeCliente.pdf";

        setlocale(LC_TIME, 'pt_BR', 'pt_BR.utf-8', 'pt_BR.utf-8', 'portuguese');
        date_default_timezone_set('America/Sao_Paulo');
        $this->dtEmissao = utf8_encode(strftime('%d de %B de %Y', strtotime($budget['dtEmissao'])));

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
        echo "
        <!DOCTYPE html>
        <html lang='pt-br'>

        <head>
            <meta charset='UTF-8'>
            <meta name='viewport' content='width=device-width, initial-scale=1.0'>
            <title>$this->title</title>
        </head>

        <body>

            <p class='text-center'>$this->title</p><br>

            <session>
                <div> <!-- CABEÇALHO DO ORÇAMENTO -->
                    <div class='row'>
                        <div class='bg-dark p-1 col-4' >
                            <img src='./img/logo-COMFAL.jpg' alt='logo' width='100%'>
                        </div>
                        <div class='col-8 text-center'>
                            <h5 class='text-success'>ORÇAMENTO<h5>
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
                    <h6 class='text-center pt-4'>FORNECIMENTO</h6>

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

                                echo "<tr>
                                    <td><b>$desc</b></td>
                                    <td>$periodo</td>
                                    <td>$vlUnit $auxP</td>
                                    <td>$qtd</td>
                                    <td>$vlTotal $auxP</td>
                                    <td>$obs</td>
                                </tr>";

                                $tipoFrete = ['ENTREGA', 'RETIRADA'];

                                for($i=0; $i<count($row['frete']); $i++){
                                    //print_r($row['frete']);

                                    $vlUnit = $row['frete'][$i]['vlUnit'];
                                    $vlTotal = $row['frete'][$i]['vlTotal']; 

                                    echo "<tr style='background-color: lightgrey'>
                                    <td>$tipoFrete[$i]</td>
                                    <td></td>
                                    <td>$vlUnit</td>
                                    <td>$qtd</td>
                                    <td>$vlTotal</td>
                                    <td></td>
                                    </tr>";
                                }



                            }
                            echo "
                        </tbody>        
                    </table>
                </div>
                <hr>
                <div> <!-- DISPOSIÇÕES GERAIS -->
                    <h6 class='text-center'>DISPOSIÇÕES GERAIS</h6>
                    <ol>
                    <li><b>FORMA DE PAGAMENTO:</b> Faturamento do aluguel realizado mensalmente, através de boleto bancário ou deposito em conta. (Primeiro faturamento em 30 dias após entrega).</li>
                    <li><b>PRAZO DE ENTREGA:</b> Descrito na seção “FORNECIMENTO” ou conforme combinado com locatário.</li>
                    <li><b>IMPOSTOS:</b> Impostos - federais, estaduais, municipais, trabalhistas ou encargos sociais - incluídos no preço, exceto quando descritos separadamente na seção “FORNECIMENTO”.</li>
                    <li><b>OBSERVAÇÕES:</b> O período de locação mínimo é de 30 (trinta) dias. Os valores dos fretes, descritos na seção “FORNECIMENTO”, são referentes ao carregamento e/ou descarregamento dos equipamentos no local dentro do prazo de uma hora (ou período previamente acordado). Horas adicionais à disposição, devido a mau tempo, processos de liberação, ou outros quaisquer, serão cobradas sobre o valor do frete previamente acordado.</li>
                    </ol>
                </div> 
            </session>

            <form hidden='true'>
                <input id='fk_idOrcamento' value='$this->idOrcamento'>
            </form>

            <link href='./bootstrap.min.css' rel='stylesheet' />

        </body>

        </html>";
    }
}

