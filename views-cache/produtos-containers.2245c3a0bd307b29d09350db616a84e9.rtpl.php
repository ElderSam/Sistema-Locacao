<?php if(!class_exists('Rain\Tpl')){exit;}?><div id="layoutSidenav_content">
    <main>
        <div class="container-fluid">
            <h1 class="mt-4">Containers</h1>
            <ol class="breadcrumb mb-4 justify-content-between">
                <li class="breadcrumb-item active">Início/Produtos/Containers</li>
                <!-- Button trigger modal -->
                <button type="button" class="btn btn-success" data-toggle="modal" data-target="#containerModal">
                    Novo Container
                </button>           
            </ol>

           
            <div class="card mb-4">
                <div class="card-body">
                    <div class="table-responsive">
                        <table class="table table-bordered table-hover" id="dataTable" width="100%" cellspacing="0">
                            <thead class="bg-dark text-white">
                            
                                <tr>
                                    <th>Código</th>
                                    <th>Medida</th>
                                    <th>Status</th>
                                    <th>Valor Aluguel</th>
                                    <th>Opções</th>
                                </tr>
                            </thead>
                            <tbody>
                                <tr>
                                    <td>C0003</td>
                                    <td>12 Metros</td>
                                    <td>Disponível</td>
                                    <td>R$: 1200,00</td>
                                    <td>
                                        <button type="button" title="ver detalhes" class="btn btn-warning" data-toggle="modal" data-target="#containerModal">
                                            <i class="fas fa-bars sm"></i>
                                        </button>
                                        <button type="button" title="excluir" onclick="deleteRow('.$data->id.')" class="btn btn-danger">                                      
                                            <i class="fas fa-trash"></i>
                                        </button>
                                    </td>
                                </tr>
                              
                            </tbody>
                            <tfoot class="bg-dark text-white">
                                <tr>
                                    <th>Código</th>
                                    <th>Medida</th>
                                    <th>Status</th>
                                    <th>Valor Aluguel</th>
                                    <th>Opções</th>
                                </tr>
                            </tfoot>
   
                        </table>
                    </div>
                </div>
            </div>
        </div>

   
        <!--Work Modal------------------------------------------------------------------------------->
        <div class="modal fade" id="containerModal" tabindex="-1" role="dialog" aria-labelledby="Detalhes da Obra"
            aria-hidden="true">
            <div class="modal-dialog modal-lg" role="document">
                <div class="modal-content">
                    <div class="modal-header">
                        <h3 class="modal-title"><b>Novo Container</b></h3>
                        <button type="button" class="close" data-dismiss="modal">
                            <span>&times;</span>
                        </button>
                    </div>

                    <div class="modal-body">
                        <form>
                            <div class="form-row">

                                <div class="form-group col-lg-3">
                                    <label for="codigo">Código</label>
                                    <input type="text" class="form-control" name="codigo" id="codigo" placeholder="000">
                                </div>

                                <div class="form-group col-lg-3">
                                    <label for="exampleFormControlSelect1">Medida</label>
                                    <select class="form-control" id="medida">
                                      <option>3M</option>
                                      <option>4M</option>
                                      <option>6M</option>
                                      <option>12M</option>
                                      <option>14M</option>
                                    </select>
                                </div>
                                
                                <div class="form-group col-lg-6">
                                    <label for="inputAddress">Tipo</label>
                                    <input type="text" class="form-control" name="tipoPorta" id="tipoPorta">
                                </div>

                                <div class="form-group col-lg-4">
                                    <label for="inputAddress">Tipo de Porta</label>
                                    <input type="text" class="form-control" name="tipoPorta" id="tipoPorta">
                                </div>

                                <div class="form-group col-lg-4">
                                    <label for="exampleFormControlSelect1">Janelas Laterais</label>
                                    <select class="form-control" id="janelasLaterais">
                                      <option>0</option>
                                      <option>1</option>
                                      <option>2</option>
                                      <option>3</option>
                                      <option>4</option>
                                      <option>5</option>
                                      <option>6</option>
                                      <option>7</option>
                                      <option>8</option>
                                    </select>
                                  </div>
                                
                                <div class="form-group col-lg-4">
                                <label for="exampleFormControlSelect1">Janelas Circulares</label>
                                <select class="form-control" id="janelasCirculares">
                                    <option>0</option>
                                    <option>1</option>
                                    <option>2</option>
                                    <option>3</option>
                                    <option>4</option>
                                    <option>5</option>
                                    <option>6</option>
                                    <option>7</option>
                                    <option>8</option>
                                </select>
                                </div>

                                <div class="form-group col-lg-4">
                                    <div class="custom-control custom-switch">
                                        <input type="checkbox" class="custom-control-input" id="customSwitch1">
                                        <label class="custom-control-label" for="customSwitch1">Forrado ?</label>
                                    </div>
                                </div>

                                <div class="form-group col-lg-4">
                                    <div class="custom-control custom-switch">
                                        <input type="checkbox" class="custom-control-input" id="customSwitch2">
                                        <label class="custom-control-label" for="customSwitch2">Eletrificado ?</label>
                                    </div>
                                </div>
                                
                                <div class="form-group col-lg-4">
                                    <label for="exampleFormControlSelect1">Tomadas</label>
                                    <select class="form-control" id="tomadas">
                                        <option>0</option>
                                        <option>1</option>
                                        <option>2</option>
                                        <option>3</option>
                                        <option>4</option>
                                        <option>5</option>
                                        <option>6</option>
                                        <option>7</option>
                                        <option>8</option>
                                    </select>
                                </div>

                                <div class="form-group col-lg-4">
                                    <label for="exampleFormControlSelect1">Lampadas</label>
                                    <select class="form-control" id="lampadas">
                                        <option>0</option>
                                        <option>1</option>
                                        <option>2</option>
                                        <option>3</option>
                                        <option>4</option>
                                        <option>5</option>
                                        <option>6</option>
                                        <option>7</option>
                                        <option>8</option>
                                    </select>
                                </div>

                                <div class="form-group col-lg-4">
                                    <label for="eletrificado">Entrada P/A/C</label>
                                    <select id="eletrificado" class="form-control">
                                        <option value="0" selected>Não</option>
                                        <option value="1">N/A</option>
                                        <option value="1">Sim</option>
                                    </select>
                                </div>

                                <div class="form-group col-lg-4">
                                    <label for="exampleFormControlSelect1">Sanitários</label>
                                    <select class="form-control" id="sanitarios">
                                        <option>0</option>
                                        <option>1</option>
                                        <option>2</option>
                                        <option>3</option>
                                    </select>
                                </div>

                                <div class="form-group col-lg-4">
                                    <div class="custom-control custom-switch">
                                        <input type="checkbox" class="custom-control-input" id="customSwitch3">
                                        <label class="custom-control-label" for="customSwitch3">Chuveiro ?</label>
                                    </div>
                                </div>
                                
                                <div class="form-group col-lg-4">
                                    <label for="inputEmail4">Data de Fabricação</label>
                                    <input type="date" class="form-control" id="dtFabricação" placeholder="00/00/0000">
                                </div>


                                <div class="form-group col-lg-4">
                                    <label for="inputEmail4">Data de Cadastro</label>
                                    <input type="date" class="form-control" id="dtCadastro" placeholder="00/00/0000">
                                </div>

                                <div class="form-group col-lg-12">
                                    <label for="exampleFormControlTextarea1">Anotações</label>
                                    <textarea class="form-control" id="exampleFormControlTextarea1" rows="3"></textarea>
                                </div>

                         </form>
    
                         <div style="margin: 0px;" class="breadcrumb justify-content-between">

                            <button type="button" class="btn btn-secondary" data-dismiss="modal">Fechar</button>
                            <input type="button" onclick="viewORedit('.$data->id.', 'edit')" value="Editar"
                                class="btn btn-primary">
                        </div>
                    </div>

                </div>

            </div>
        </div>    

<!--
<script>
	$(document).ready(function() {

  	$("#linkProdutos").addClass("active"); 

	$('#example').DataTable();
	
});

</script>
-->



    <!--script>
        $("#linkProdutos").addClass("active"); 
    </script-->