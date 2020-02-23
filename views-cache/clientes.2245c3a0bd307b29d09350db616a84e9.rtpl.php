<?php if(!class_exists('Rain\Tpl')){exit;}?><div id="layoutSidenav_content">
    <main>
        <div class="container-fluid">
            <h2 class="mt-4">Clientes</h2>
            <ol class="breadcrumb mb-4">
                <li class="breadcrumb-item active">Início/Clientes</li>
            </ol>
            <div class="row">
                <div class="col-sm-9"></div>
                <div class="col-sm-3 mb-1">
                    <button type="button" class="btn btn-success" data-toggle="modal" data-target="#modal-novoCliente">
                        NOVO CLIENTE
                    </button>
                </div>    
            </div>  
           
            <div class="card mb-4">
                <div class="card-body">
                    <div class="table-responsive">
                        <table class="table table-bordered table-hover" id="dataTable" width="100%" cellspacing="0">
                            <thead class="bg-dark text-white">
                                <tr>
                                    <th>#</th>
                                    <th>Nome</th>
                                    <th>Telefone</th>
                                    <th>E-mail</th>
                                    <th>Cidade</th>
                                    <th>Status</th>
                                    <th>Ver - Excluir</th>
                                </tr>
                            </thead>
                            <tbody>
                                <tr>
                                    <td>1</td>
                                    <td>HR Construções</td>
                                    <td>(19) 3543-3432</td>
                                    <td>hr_construcoes@gmail.com</td>
                                    <td>Araras-SP</td>
                                    <td>Ativo</td>
                                    <td>
                                        <button type="button" title="ver detalhes" class="btn btn-warning" data-toggle="modal" data-target="#modal-cliente">
                                            <i class="fas fa-bars sm"></i>
                                        </button>
                                        <button type="button" title="excluir" onclick="deleteRow('.$data->id.')" class="btn btn-danger">                                     
                                            <i class="fas fa-trash"></i>
                                        </button>
                                    </td>
                                </tr>

                                <tr>
                                    <td>2</td>
                                    <td>Famz</td>
                                    <td>(16) 3253-1865</td>
                                    <td>adm@famz.com</td>
                                    <td>Ribeirão Preto-SP</td>
                                    <td>Ativo</td>
                                    <td>
                                        <button type="button" title="ver detalhes" class="btn btn-warning" data-toggle="modal" data-target="#modal-cliente">
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
                                    <th>#</th>
                                    <th>Name</th>
                                    <th>Telefone</th>
                                    <th>E-mail</th>
                                    <th>Cidade</th>
                                    <th>Status</th>
                                    <th>Ver - Excluir</th>
                                </tr>
                            </tfoot>
                        </table>
                    </div>
                </div>
            </div>
        </div>

   
   <!--Modais-->

<!--Modal de novo Cliente-->
<div class="modal fade" id="modal-novoCliente" tabindex="-1" role="dialog" aria-labelledby="Detalhes do Cliente" aria-hidden="true">
    <div class="modal-dialog modal-lg" role="document">
        <div class="modal-content">
            <div class="modal-header">
            <h3 class="modal-title"><b>Novo Cliente</b></h3>
            <button type="button" class="close" data-dismiss="modal">
            <span>&times;</span>
            </button>
            </div>

            <div class="modal-body">
                <form>
                <div class="form-row">

                    <div class="form-group col-lg-2">
                    <label for="codigo">Código</label>
                    <input type="text" class="form-control" name="codigo" id="codigo" placeholder="000">
                    </div>

                    <div class="form-group col-lg-8">
                    <label for="nome">Nome</label>
                    <input type="text" class="form-control" name="nome" id="nome" placeholder="Nome Completo">
                    </div>

                    <div class="form-group col-lg-2">
                    <label for="status">Status</label>
                    <select id="status" class="form-control">
                    <option selected>Ativo</option>
                    <option>Inativo</option>
                    </select>
                    </div>

                    <div class="form-group col-lg-12">
                    <label for="status">Tipo de Cliente</label>
                    <select id="status" class="form-control">
                    <option selected>Pessoa Física</option>
                    <option>Pessoa Jurídica</option>
                    </select>
                    </div>

                    <div class="form-group col-lg-6">
                    <label for="nome">RG</label>
                    <input type="text" class="form-control" name="nome" id="nome" placeholder="00 000 000-0">
                    </div>

                    <div class="form-group col-lg-6">
                    <label for="nome">CPF</label>
                    <input type="text" class="form-control" name="nome" id="nome" placeholder="000 000 000-00">
                    </div>

                    <div class="form-group col-lg-6">
                    <label for="nome">EI</label>
                    <input type="text" class="form-control" name="nome" id="nome" placeholder="00 000 000-0">
                    </div>

                    <div class="form-group col-lg-6">
                    <label for="nome">CNPJ</label>
                    <input type="text" class="form-control" name="nome" id="nome" placeholder="000 000 000-00">
                    </div>

                    <div class="form-group col-lg-6">
                    <label for="inputAddress">Telefone 01</label>
                    <input type="text" class="form-control" id="telefone01" placeholder="(00)00000000">
                    </div>

                    <div class="form-group col-lg-6">
                    <label for="inputAddress">Telefone 02</label>
                    <input type="phone" class="form-control" id="telefone02" placeholder="(00)00000000">
                    </div>

                    <div class="form-group col-lg-6">
                    <label for="inputEmail4">Email 01</label>
                    <input type="email" class="form-control" id="inputEmail4" placeholder="exemplo@exemplo.com">
                    </div>

                    <div class="form-group col-lg-6">
                    <label for="inputEmail4">Email 02</label>
                    <input type="email" class="form-control" id="inputEmail4" placeholder="exemplo@exemplo.com">
                    </div>

                    <div class="form-group col-lg-4">
                    <label for="inputEmail4">Data de Cadastro</label>
                    <input type="date" class="form-control" id="inputEmail4" placeholder="00/00/0000">
                    </div>

                    <div class="form-group col-lg-4">
                    <label for="inputEstado">UF</label>
                    <select id="inputEstado" class="form-control">
                    <option selected>Escolher...</option>
                    <option>...</option>
                    </select>
                    </div>

                    <div class="form-group col-lg-4">
                    <label for="inputAddress">CEP</label>
                    <input type="text" class="form-control" id="inputAddress" placeholder="00000-000">
                    </div>

                    <div class="form-group col-lg-3">
                    <label for="inputAddress">Cidade</label>
                    <input type="text" class="form-control" id="inputAddress">
                    </div>


                    <div class="form-group col-lg-6">
                    <label for="inputPassword4">Endereço</label>
                    <input type="text" class="form-control" id="inputPassword4" placeholder="Rua Exemplo">
                    </div>

                    </div>
                    <div class="form-group col-lg-3">
                    <label for="inputAddress">Número</label>
                    <input type="text" class="form-control" id="inputAddress" placeholder="000">
                    </div>
                    </form>

                    <div class="row">
                        <div class="col-sm-5"></div>       
                        <div class="col-sm-7">
        
                            <button type="button" class="btn btn-primary" data-dismiss="modal">Salvar</button>
                            <button type="button" class="btn btn-danger" data-dismiss="modal">Fechar</button>
                        </div>        
                              
                    </div>

                </div>
               
            </div>

        </div>
    </div>
</div>

<!--Modal de detalhe de Clientes-->
<div class="modal fade" id="modal-cliente" tabindex="-1" role="dialog" aria-labelledby="Detalhes do Cliente" aria-hidden="true">
    <div class="modal-dialog modal-lg" role="document">
        <div class="modal-content">
            <div class="modal-header">
            <h3 class="modal-title"><b>Detalhes do Cliente</b></h3>
            <button type="button" class="close" data-dismiss="modal">
            <span>&times;</span>
            </button>
            </div>

            <div class="modal-body">
                <form>
                <div class="form-row">

                    <div class="form-group col-lg-2">
                    <label for="codigo">Código</label>
                    <input type="text" class="form-control" name="codigo" id="codigo" placeholder="000">
                    </div>

                    <div class="form-group col-lg-8">
                    <label for="nome">Nome</label>
                    <input type="text" class="form-control" name="nome" id="nome" placeholder="Nome Completo">
                    </div>

                    <div class="form-group col-lg-2">
                    <label for="status">Status</label>
                    <select id="status" class="form-control">
                    <option selected>Ativo</option>
                    <option>Inativo</option>
                    </select>
                    </div>

                    <div class="form-group col-lg-12">
                    <label for="status">Tipo de Cliente</label>
                    <select id="status" class="form-control">
                    <option selected>Pessoa Física</option>
                    <option>Pessoa Jurídica</option>
                    </select>
                    </div>

                    <div class="form-group col-lg-6">
                    <label for="nome">RG</label>
                    <input type="text" class="form-control" name="nome" id="nome" placeholder="00 000 000-0">
                    </div>

                    <div class="form-group col-lg-6">
                    <label for="nome">CPF</label>
                    <input type="text" class="form-control" name="nome" id="nome" placeholder="000 000 000-00">
                    </div>

                    <div class="form-group col-lg-6">
                    <label for="nome">EI</label>
                    <input type="text" class="form-control" name="nome" id="nome" placeholder="00 000 000-0">
                    </div>

                    <div class="form-group col-lg-6">
                    <label for="nome">CNPJ</label>
                    <input type="text" class="form-control" name="nome" id="nome" placeholder="000 000 000-00">
                    </div>

                    <div class="form-group col-lg-6">
                    <label for="inputAddress">Telefone 01</label>
                    <input type="text" class="form-control" id="telefone01" placeholder="(00)00000000">
                    </div>

                    <div class="form-group col-lg-6">
                    <label for="inputAddress">Telefone 02</label>
                    <input type="phone" class="form-control" id="telefone02" placeholder="(00)00000000">
                    </div>

                    <div class="form-group col-lg-6">
                    <label for="inputEmail4">Email 01</label>
                    <input type="email" class="form-control" id="inputEmail4" placeholder="exemplo@exemplo.com">
                    </div>

                    <div class="form-group col-lg-6">
                    <label for="inputEmail4">Email 02</label>
                    <input type="email" class="form-control" id="inputEmail4" placeholder="exemplo@exemplo.com">
                    </div>

                    <div class="form-group col-lg-4">
                    <label for="inputEmail4">Data de Cadastro</label>
                    <input type="date" class="form-control" id="inputEmail4" placeholder="00/00/0000">
                    </div>

                    <div class="form-group col-lg-4">
                    <label for="inputEstado">UF</label>
                    <select id="inputEstado" class="form-control">
                    <option selected>Escolher...</option>
                    <option>...</option>
                    </select>
                    </div>

                    <div class="form-group col-lg-4">
                    <label for="inputAddress">CEP</label>
                    <input type="text" class="form-control" id="inputAddress" placeholder="00000-000">
                    </div>

                    <div class="form-group col-lg-3">
                    <label for="inputAddress">Cidade</label>
                    <input type="text" class="form-control" id="inputAddress">
                    </div>


                    <div class="form-group col-lg-6">
                    <label for="inputPassword4">Endereço</label>
                    <input type="text" class="form-control" id="inputPassword4" placeholder="Rua Exemplo">
                    </div>

                    </div>
                    <div class="form-group col-lg-3">
                    <label for="inputAddress">Número</label>
                    <input type="text" class="form-control" id="inputAddress" placeholder="000">
                    </div>

                    <input type="button" onclick="viewORedit('.$data->id.', \'edit\')" value="Editar" class="btn btn-primary">
                    <br><br>
                    </form>

                    <div class="row">
                        <div class="col-sm-5"></div>
                        <div class="col-sm-7">
                            <a class="btn btn-dark" href="/construction" role="button">Obras</a>
                            <button type="button" class="btn btn-danger" data-dismiss="modal">Fechar</button>
                        </div>                 
                    </div>

                </div>
               
            </div>

        </div>
    </div>
</div>
<!--
<script>
	$(document).ready(function() {

  	$("#linkClientes").addClass("active"); 

	$('#example').DataTable();
	
});

</script>
-->
