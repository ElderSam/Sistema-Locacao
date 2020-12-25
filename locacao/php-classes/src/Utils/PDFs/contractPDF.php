<?php

namespace Locacao\Utils\PDFs;

class ContractPDF
{
    private $codContrato;
    private $nomeEmpresa;
    private $enderecoEmpresa;
    private $complementoEmpresa;
    private $bairroEmpresa;
    private $cidadeEmpresa;
    private $cpfCnpjEmpresa;
    private $cepEmpresa;
    private $estadoEmpresa;
    private $ieRgEmpresa;
    private $tipoEmpresa;

    private $enderecoCliente;
    private $complementoCliente;
    private $cidadeCliente;
    private $bairroCliente;
    private $cepCliente;
    private $estadoCliente;
    private $cpfCnpjCliente;
    private $ieRgCliente;
    private $tipoCliente;

    private $enderecoObra;
    private $complementoObra;
    private $bairroObra;
    private $cidadeObra;
    private $cepObra;

    private $dtEmissao;


    private $nomeCliente; 
    private $solicitante; 
    private $telefone;
    private $email;

    private $listItems; //array

    public function __construct($contract, $listItems, $empresa)
    {
        $contract = json_decode($contract);
        $empresa = json_decode($empresa);

        $this->setContractValues($contract, $empresa[0]);
        $this->setTableItemsValues($listItems);
    }

    public function setContractValues($contract, $empresa){ //Informações do Contrato, para o cabeçalho do documento PDF

        $this->codContrato = $contract->codContrato;
        $this->nomeEmpresa = $empresa->nome;
        $this->enderecoEmpresa = $empresa->endereco;
        $this->bairroEmpresa = $empresa->bairro;
        $this->cidadeEmpresa = $empresa->municipio;
        $this->cpfCnpjEmpresa = $empresa->cpf == "" ? $empresa->cnpj : $empresa->cpf;
        $this->cepEmpresa = $empresa->cep;
        $this->estadoEmpresa = $empresa->estado;
        $this->ieRgEmpresa = $empresa->rg == "" ? $empresa->ie : $empresa->rg;
        $this->tipoEmpresa = $empresa->tipoEmpresa;

        $this->enderecoCliente = $contract->enderecoCliente;
        $this->complementoCliente = $contract->complementoCliente;
        $this->cidadeCliente = $contract->cidadeCliente;
        $this->bairroCliente = $contract->bairroCliente;
        $this->cepCliene = $contract->cepCliente;
        $this->estadoCliente = $contract->ufCliente;
        $this->cpfCnpjCliente = $contract->cpfCliente == "" ? $contract->cnpjCliente : $contract->cpfCliente;
        $this->ieRgCliente = $contract->rgCliente == "" ? $contract->ieCliente : $contract->rgCliente;
        $this->tipoCliente = $contract->tipoCliente;

        $this->enderecoObra = $contract->enderecoObra;
        $this->complementoObra = $contract->complementoObra;
        $this->bairroObra = $contract->bairroObra;
        $this->cidadeObra = $contract->cidadeObra;
        $this->cepObra = $contract->cepObra;

        $this->nomeCliente = $contract->nomeEmpresa;
        $this->solicitante = $contract->solicitante;
        $this->telefone = $contract->telefone;
        $this->email = $contract->email;


        
       
        $this->title = "CONTRATO - Nº $this->codContrato - $this->nomeCliente";

        setlocale(LC_TIME, 'pt_BR', 'pt_BR.utf-8', 'pt_BR.utf-8', 'portuguese'); //para a data ficar em Português
        //date_default_timezone_set('America/Sao_Paulo');
        //$data = strftime('%d/%m/%Y', strtotime($budget->dtEmissao));
        $this->dtEmissao = utf8_encode(strftime('%d de %B de %Y', strtotime($contract->dtEmissao)));

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
            <meta http-equiv='Content-Type' content='text/html; charset=utf-8'/>
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

            
            .left{
                float: left;
                margin-right: 350px;
            }

            .right{
                float: right;
                margin-left: 350px;
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
            <div> <!-- CABEÇALHO DO CONTRATO -->
                <div id='logo'>
                    <img src='http://". $_SERVER["HTTP_HOST"]."/res/img/logo-COMFAL.jpg' alt='logo'>
                </div>
            
                <div id='direitaHeader'>
                    <b class='text-success' style='color: green;'>CONTRATO</b><br>
                    <b>Data: $this->dtEmissao</b><br>
                    <b>Nº $this->codContrato</b><br>
                    Rerefência: <b>locação</b>. validade: 3 três dias
                </div>
                
                <div id='esquerdaHeader'>
                    <b style='color: green;'>EMPRESA:</b> $this->nomeCliente<br>
                    <b>A/C SR./SRA:</b> $this->solicitante<br>
                    <b>CONTATO:</b> $this->telefone / $this->email
                </div>
            </div>
            
                <div id='corpo'>
                    <h4><u>INSTRUMENTO PARTICULAR DE LOCAÇÃO</u></h4>    
                    
                    <div class='flexBox2'>
                        <div class='left'>
                            <table class='tabela'>
                                <caption><b>LOCADORA</b></caption>
                                
                                <tr><td><b>Nome:</b></td><td>$this->nomeEmpresa 5345345345 456456546</td></tr>
                                <tr><td><b>Endereço:</b></td><td>$this->enderecoEmpresa</td></tr> 
                                <tr><td><b>Complemento:</b></td><td>$this->complementoEmpresa</td></tr>
                                <tr><td><b>Bairro:</b></td><td>$this->bairroEmpresa</td></tr>
                                <tr><td><b>Município:</b></td><td>$this->cidadeEmpresa</td></tr>
                                <tr><td><b>CEP:</b></td><td>$this->cepEmpresa</td></tr>
                                <tr><td><b>Estado:</b></td><td>$this->estadoEmpresa</td></tr>
                                <tr><td><b>Pessoa:</b></td><td>$this->tipoEmpresa</td></tr>
                                <tr><td><b>IE/RG:</b></td><td>$this->ieRgEmpresa</td></tr>
                                <tr><td><b>CNPJ/CPF:</b></td><td>$this->cpfCnpjEmpresa</td></tr>
                            </table>
                        </div>
                        <div class='right'>        
                            <table class='tabela'>
                                <caption><b>LOCATÁRIO</b></caption>
                                
                                <tr><td><b>Nome:</b></td><td>$this->nomeCliente</td></tr>
                                <tr><td><b>Endereço:</b></td><td>$this->enderecoCliente</td></tr> 
                                <tr><td><b>Complemento:</b></td><td>$this->complementoCliente</td></tr>
                                <tr><td><b>Bairro:</b></td><td>$this->bairroCliente</td></tr>
                                <tr><td><b>Município:</b></td><td>$this->cidadeCliente</td></tr>
                                <tr><td><b>CEP:</b></td><td>$this->cepCliente</td></tr>
                                <tr><td><b>Estado:</b></td><td>$this->estadoCliente</td></tr>
                                <tr><td><b>Pessoa:</b></td><td>$this->tipoCliente</td></tr>
                                <tr><td><b>IE/RG:</b></td><td>$this->ieRgCliente</td></tr>
                                <tr><td><b>CNPJ/CPF:</b></td><td>$this->cpfCnpjCliente</td></tr>
                            </table>
                        </div>
                    </div> 
                    <p>Pelo presente instrumento particular de locação de equipamentos que entre si fazem, as partes acima qualificadas, como tais,
                    têm entre si, justos e contratados, mediante as condições e forma a seguir ajustadas, que mutuamente aceitam e outorgam, a saber:
                    </p>
                    
                    <h4><u>OBJETO E VALORES DA LOCAÇÃO</u></h4>
                    <p>1.  Constitui objeto deste contrato a locação dos equipamentos abaixo relacionados, de propriedade da <b>LOCADORA</b>, com seus 
                        respectivos valores de mensalidade de aluguel e fretes:
                    </p>
        
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
                        foreach($this->listItems as $item){
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
                        }

                      $result .= "  
                      </tbody>
                    </table>
        
                <p>2.  Os bens ora locados serão entregues no endereço fornecido pela <b>LOCATÁRIA</b>, onde deverão permanecer durante todo o período
                        da locação até a data da efetiva rescisão ou término da locação. Caso haja remoção do bem locado, a <b>LOCADORA</b> deverá ser
                        notificada para possível revisão de custos.
                    </p>
                
                    <h4><b>3.  LOCAL DE ENTREGA:</b></h4>
                    <table class='tabela'>     
                        
                        <tr><td><b>Local:</b></td><td>$this->nomeEmpresa</td></tr>
                        <tr><td><b>Endereço:</b></td><td>$this->enderecoObra</td></tr> 
                        <tr><td><b>Complemento:</b></td><td>$this->complementoObra</td></tr>
                        <tr><td><b>Bairro:</b></td><td>$this->bairroObra</td></tr>
                        <tr><td><b>Município:</b></td><td>$this->cidadeObra</td></tr>
                        <tr><td><b>CEP:</b></td><td>$this->cepObra</td></tr>
                    </table>
                    <div class='fleBox'></div>
                    <p>4.  A <b>LOCATÁRIA</b> aceita a condição de depositária dos bens locados, e realizará vistoria dos mesmos no ato de entrega, a fim 
                        de verificar o estado de conservação e funcionamento, obrigando-se a conservá-los nas mesmas condições de uso e funcionamento para
                        assim os restituir quando findo ou rescindido o presente, sob pena de suportar os prejuízos decorrentes do mau uso e falta de 
                        conservação do bem locado, ficando desde já a <b>LOCADORA</b>, devidamente autorizada a promover o reparo dos referidos danos e 
                        cobrar posteriormente da <b>LOCATÁRIA</b> o reembolso dos valores despendidos na reparação, através de ação judicial, 
                        caso seja necessário.
                    </p>
        
                    <p>5.  O prazo de duração e data de início do presente contrato se encontram abaixo. Por ocasião do vencimento ou da rescisão da locação,
                        a <b>LOCATÁRIA</b> obriga-se a restituir os bens locados nas mesmas condições em que foram recebidos, mediante vistoria da <b>LOCADORA.</b> Ao término
                        do contrato, o mesmo poderá ser renovado mediante reajustes das condições de locação por parte da <b>LOCADORA.</b>
                    </p>    
        
                    <table>
                        <tr><td><b>Prazo de duração: </b></td><td></td></tr>
                        <tr><td><b>Data de Início: </b></td><td>$this->dtEmissao</td></tr>
                    </table>
                    <p>6.  A locação mínima é de 30 dias, sendo que dias à disposição devido a: condição climática desfavorável, ordens burocráticas
                        para entrada e saída do equipamento, vistoria, serão cobrados normalmente.
                    </p>
        
                    <p>6.1  A <b>LOCADORA</b> fica responsável pela retirada dos bens locados mediante os valores descritos na cláusula 1 do presente contrato
                        sob o campo “retirada”.
                    </p>
                        
                    <p>7.  Por ocasião da rescisão do presente contrato, por qualquer motivo, não promovendo a LOCATÁRIA a restituição voluntária dos bens
                        locados, poderá a <b>LOCADORA</b> utilizar-se dos meios judiciais a fim de reintegrar-se na posse dos referidos bens, ficando sob 
                        responsabilidade da <b>LOCATÁRIA</b> o pagamento de todas as despesas desembolsadas pela <b>LOCADORA</b>, inclusive honorários advocatícios.
                    </p>
        
                    <p>8.  A devolução dos bens locados antes do vencimento deste contrato não enseja à <b>LOCATÁRIA</b> o direito a qualquer forma de reembolso
                        pelo período que este deixar de utilizá-los seja por valores já pagos ou em processo de cobrança pela <b>LOCADORA.</b>
                    </p>
        
                    <p>9.  A não devolução dos bens locados ao termo deste contrato dará a <b>LOCADORA</b> o direito de considerar este contrato automaticamente
                        prorrogado por prazo indeterminado, ou de exigir a imediata devolução dos equipamentos.
                    </p>    
                    
                    <h4><u>DISPOSIÇÕES SOBRE O VALOR DA LOCAÇÃO</u></h4>
                    
                    <p>10.  A título de pagamento da presente locação, a  <b>LOCATÁRIA</b> pagará à LOCADORA as quantias descritas na sessão 1, com o que as partes
                        declaram estar de pleno acordo. Os valores descritos como “locação” são cobrados mensalmente. Os valores descritos como “frete de 
                        entrega” e “frete de retirada” são cobrados unitariamente na ocasião da movimentação de entrega ou retirada dos bens.
                    </p>
        
                    <p>11.  Os valores serão cobrados pela <b>LOCADORA</b>, via emissão de fatura de locação, em condição de pagamento a vista, sendo que os 
                        pagamentos serão efetuados através de boleto bancário ou Transferência bancária como segue favorecido: Comfal Locações de
                        Máquinas LTDA-ME, banco: Caixa Econômica Federal, agência 0278 e conta corrente 1435-1.
                    </p>
        
                    <p>12.  No caso de atraso no pagamento ou falta do pagamento do aluguel nas datas fixadas, ficará a <b>LOCATÁRIA</b> sujeita ao pagamento de uma
                        multa correspondente a 2% (dois por cento) sobre o valor do aluguel, que ainda será acrescido de juros de mora de 1% a.m.,
                        e correção monetária.
                    </p>
        
                    <p>
                        13.  No caso de atraso ou falta de pagamento do aluguel, sem prejuízo das penalidades previstas na cláusula anterior, poderá a
                        <b>LOCADORA</b> - mediante aviso prévio à <b>LOCATÁRIA</b> para que esta tome as ações cabíveis, seja de pagamento ou esclarecimento de
                        algum erro - tomar as medidas judiciais cabíveis tendentes à resolução do presente contrato por falta de pagamento e imediata
                        restituição dos bens locados.
                    </p>
                    
                    <h4><u>DISPOSIÇÔES GERAIS</u></h4>
                    <p>
                      14.  A contratante deverá possuir espaço (altura, largura e comprimento) suficiente para colocação do equipamento na obra,
                        bem como informar quaisquer imprevistos com antecedência, responsabilizando-se pelos valores contratados em caso de
                        deslocamento em vão. As medidas necessárias para acomodação do bem são aquelas das dimensões padrão de um container
                        marítimo de até 40 pés, a saber: 2,50m de largura, 2,80m de altura e 12m de comprimento. A área de colocação deverá
                        possuir espaço para a manobra e entrada de uma carreta de 12m, a qual transporta o bem, e a parada de um caminhão
                        guindaste articulado paralelamente a mesma para realização da operação de içamento. Na ocasião de se utilizarem dois
                        caminhões guindaste articulado, é necessário espaço para o patolamento em ambas as laterais da carreta.
                    </p>
                
                    <p>
                       15.  Os bens locados não poderão, sob hipótese alguma, ser sublocados, cedidos, emprestados ou alienados a terceiros. 
                        Tais ocorrências ensejarão a imediata rescisão do contrato, sem prejuízo das ações civis e criminais cabíveis.
                    </p>
                
                    <p>
                       16.  A <b>LOCADORA</b> poderá declarar antecipadamente vencido este instrumento – mediante aviso prévio à <b>LOCATÁRIA</b> para que esta realize
                        seu posicionamento formal quanto à continuidade do contrato -, independente de interpelação, aviso ou notificação judicial
                        ou extrajudicial, na ocorrência dos seguintes eventos:
                    </p>
        
                    <p>
                       16.1  ia, encerramento de atividade ou liquidação judicial ou extrajudicial da <b>LOCATÁRIA</b>, bem como,
                        requerimento de Protesto legítimo de título de crédito, insolvência, decretação de falêncconcordata;
                        <br><br>
                       16.2 Falta de pagamento do aluguel e qualquer débito decorrente deste instrumento;
                       <br><br>
                       16.3  Se a <b>LOCATÁRIA</b> e seus funcionários utilizarem inadequadamente os equipamentos locados, ou sem seguir as normas
                        técnicas de segurança do trabalho, ou utilizados para fins diversos do que o previsto, ou mesmo transferi-los de local
                        sem prévia autorização.
                    </p>
        
                    <p>
                       17.  Os bens ora locados não poderão, no todo ou em parte, ser objeto de penhora ou qualquer outra modalidade de garantia
                        de eventuais débitos existentes, obrigando-se a <b>LOCATÁRIA</b> a informar o órgão competente por ocasião da providência de
                        que o bem não lhe pertence, sob pena de suportar os honorários advocatícios da <b>LOCADORA</b> para a liberação do bem de
                        eventual constrição judicial.
                    </p>
        
                    <p>
                        18.  A <b>LOCATÁRIA</b> ficará integralmente responsável pela reparação dos danos materiais ou danos causados a pessoas em
                         consequência de acidentes ou sinistros de qualquer natureza e origem que envolvam o bem locado, desde sua retirada
                         até a sua efetivação devolução, excluindo a <b>LOCADORA</b>, de quaisquer responsabilidades civis e trabalhistas,
                        de pagamento de indenizações, seja a que título for. Assim, em caso da <b>LOCADORA</b> ser responsabilizada por eventuais
                         acidentes, ou débitos, envolvendo bem de sua propriedade, fica desde já a <b>LOCADORA</b> autorizada a cobrar regressivamente
                          da <b>LOCATÁRIA</b> os valores que for obrigado a despender para a reparação dos danos.
                    </p>
        
                    <p>
                       19.  <b>LOCADORA</b> se isenta de quaisquer danos, deteriorações, furtos ou todo tipo de sinistro que venha a ocorrer com os bens,
                        de posse da <b>LOCATÁRIA</b>, armazenados dentro de containers alugados. Essa condição se aplica mesmo em situações nas quais os
                         containers alugados estejam alocados nas dependências da <b>LOCADORA</b>.
                    </p>
        
                    <p>
                       20.  A <b>LOCATÁRIA</b> é responsável por perdas e danos causados ao bem locado, inclusive por imperícia, imprudência ou
                        negligência de seus funcionários, prepostos ou contratados terceirizados
                    </p>
        
                    <p>
                       21.  Todos e quaisquer débitos e obrigações assumidas pela <b>LOCATÁRIA</b> durante o período em que perdurar a locação,
                        serão de responsabilidade exclusiva do mesmo, ficando a <b>LOCADORA</b>, isenta de qualquer responsabilidade, seja qual for
                         a natureza do débito, mesmo que de natureza trabalhista, haja vista que a relação trabalhista será firmada diretamente
                          entre <b>LOCATÁRIA</b> e funcionários, em nada afetando a <b>LOCADORA</b>
                    </p>
        
                    <p>
                        22.  Fica a <b>LOCATÁRIA</b> proibida de mudar as características dos bens locados, sendo que no caso de qualquer modificação,
                         a <b>LOCADORA</b> poderá considerar rescindido o presente instrumento, tomando as medidas judiciais cabíveis para a solução da
                          questão, sem prejuízo de outras medidas tendentes ao ressarcimento de eventuais prejuízos sofridos. 
                    </p>
        
                    <p>
                       23.  A <b>LOCADORA</b> se obriga a contratar seguro dos bens locados, bem como da carga em caso de transporte, e ainda,
                        seguro de responsabilidade civil em relação às pessoas envolvidas direta ou indiretamente com a atividade a ser exercida,
                         inclusive contra terceiros, sendo certo que o seguro deverá ser contratado de imediato, devendo o seguro vigorar durante
                          todo o período da locação, consignando-se na apólice a <b>LOCADORA</b> como segurada da apólice. O seguro será realizado após
                           a entrada dos bens nas instalações da <b>LOCATÁRIA</b>, uma vez que será necessária a emissão da Nota Fiscal para inclusão dos
                            mesmos na apólice.
                    </p>
        
                    <p>
                        24.  A <b>LOCADORA</b> é inteiramente responsável por todos e quaisquer danos e prejuízos pessoais ou materiais que causar a <b>LOCATÁRIA</b>
                         através dos funcionários, prepostos, empregados ou contratados desta, bem como a terceiros, decorrentes da execução das
                          atividades relativas ao objeto deste contrato, desde que comprovada a relação do dano e de sua responsabilidade.
                    </p>
        
                    <p>
                       25.  A <b>LOCADORA</b> responderá e se responsabilizará por eventuais acidentes que venham a sofrer os seus funcionários, prepostos,
                        empregados ou contratados, sejam nas instalações da <b>LOCATÁRIA</b>, em suas próprias instalações ou em qualquer outro local
                         relacionado, ou aonde vierem a serem prestados os serviços, isentando desde já a <b>LOCATÁRIA</b> de qualquer tipo de
                          responsabilidade.
                    </p>
        
                    <p>
                       26.  Os serviços a serem executados pela <b>LOCADORA</b> serão sem exclusividade, sem subordinação, livre de hierarquia ou
                        horários de trabalho, ficando, todavia, ajustado que a <b>LOCADORA</b> deverá respeitar as Normas Internas, de Segurança e
                         horário de funcionamento da <b>LOCATÁRIA</b>.
                    </p>
        
                    <p>
                       27.  A <b>LOCADORA</b> declara que conhece e compromete-se a obedecer, bem como fazer com que seus funcionários, empregados ou
                        contratados obedeçam, durante a execução deste contrato, os Procedimentos Internos e de Segurança do Trabalho da <b>LOCATÁRIA</b>.
                    </p>
        
                    <p>
                        28.  A <b>LOCADORA</b> compromete-se a fazer com que seus empregados, funcionários e contratados designados para realização dos
                         serviços aqui contratados sejam treinados em segurança, utilizem corretamente os EPIs os quais serão fornecidos pela
                         <b>LOCADORA</b>, e recebam treinamentos baseados nos Procedimentos Internos da <b>LOCATÁRIA</b> antes do início das atividades,
                           em data a serem agendadas após a qual receberão o crachá de identificação e liberação da entrada.
                    </p>
        
                    <p>
                        29.  As partes contratantes declaram aceitar o presente nos expressos termos em que foi lavrado, obrigando-se a si seus
                         herdeiros e sucessores ao fiel e exato cumprimento de todas as obrigações ajustadas.
                    </p>
        
                    <p>
                        30.  As partes contratantes declaram aceitar o presente nos expressos termos em que foi lavrado, obrigando-se a si seus
                         herdeiros e sucessores ao fiel e exato cumprimento de todas as obrigações ajustadas.
                    </p>
        
                    <p>
                        estarem assim justos e contratados, assinam o presente em duas vias de igual teor e forma para que produza seus jurídicos
                         e legais efeitos, juntamente com a testemunha presencial.
                    </p>
                    <p>
                       <b>LOCATÁRIO:</b> 
                        <br>
                        <b>NOME:</b>$this->nomeCliente 
                        <br>
                        <b>RG/IE:</b>$this->ieRgCliente 
                        <br>
                        <b>CPF/CNPJ:</b>$this->cpfCnpjCliente 
                    </p>
                    
                </div>
     
            </body>
            </html>";

            $this->title = "PROPOSTA - N. $this->codContrato - $this->nomeCliente";

        return array($this->title, $result);
    }
}

