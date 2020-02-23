<?php if(!class_exists('Rain\Tpl')){exit;}?><div id="layoutSidenav_content">
    <main>
        <div class="container-fluid">
            <h2 class="mt-4">Obras</h2>
            <ol class="breadcrumb mb-4">
                <li class="breadcrumb-item active">Lista de Obras</li>
            </ol>
            <div class="row">
                <div class="col-sm-9"></div>
                <div class="col-sm-3 mb-2">
                    <button type="button" class="btn btn-success" data-toggle="modal" data-target="#modal-novaObra">
                        NOVA OBRA
                    </button>
                </div>    
            </div>  
            <div class="card mb-4">
                <div class="card-header"><i class="fas fa-table mr-1"></i>DataTable Example</div>
                <div class="card-body">
                    <div class="table-responsive">
                        <table class="table table-bordered" id="dataTable" width="100%" cellspacing="0">
                            <thead>
                                <tr>
                                    <th>Código</th>
                                    <th>Resp/Obra</th>
                                    <th>Telefone</th>
                                    <th>E-mail</th>
                                    <th>Cidade</th>
                                    <th>Status</th>
                                    <th></th>
                                </tr>
                            </thead>
                            <tfoot>
                                <tr>
                                    <th>Código</th>
                                    <th>Resp/Obra</th>
                                    <th>Telefone</th>
                                    <th>E-mail</th>
                                    <th>Cidade</th>
                                    <th>Status</th>
                                    <th></th>
                                </tr>
                            </tfoot>
                            <tbody>
                                <tr>
                                    <td>1</td>
                                    <td>Fulano de tal</td>
                                    <td>(19) 3543-3432</td>
                                    <td>hr_construcoes@gmail.com</td>
                                    <td>Piracicaba-SP</td>
                                    <td>22/12/2019</td>
                                    <td>
                                        <button type="button" title="ver detalhes" class="btn btn-warning" data-toggle="modal" data-target="#modal-obras">
                                            <i class="fas fa-bars sm"></i>
                                        </button>
                                        <button type="button" title="excluir" onclick="deleteRow('.$data->id.')" class="btn btn-danger">                                      
                                            <i class="fas fa-trash"></i>
                                        </button>
                                    </td>
                                </tr>

                                <tr>
                                    <td>1</td>
                                    <td>Fulano de tal</td>
                                    <td>(19) 3543-3432</td>
                                    <td>hr_construcoes@gmail.com</td>
                                    <td>Piracicaba-SP</td>
                                    <td>22/12/2019</td>
                                    <td>
                                        <button type="button" title="ver detalhes" class="btn btn-warning" data-toggle="modal" data-target="#modal-obras">
                                            <i class="fas fa-bars sm"></i>
                                        </button>
                                        <button type="button" title="excluir" onclick="deleteRow('.$data->id.')" class="btn btn-danger">                                      
                                            <i class="fas fa-trash"></i>
                                        </button>
                                    </td>
                                </tr>

                                <tr>
                                    <td>1</td>
                                    <td>Fulano de tal</td>
                                    <td>(19) 3543-3432</td>
                                    <td>hr_construcoes@gmail.com</td>
                                    <td>Piracicaba-SP</td>
                                    <td>22/12/2019</td>
                                    <td>
                                        <button type="button" title="ver detalhes" class="btn btn-warning" data-toggle="modal" data-target="#modal-obras">
                                            <i class="fas fa-bars sm"></i>
                                        </button>
                                        <button type="button" title="excluir" onclick="deleteRow('.$data->id.')" class="btn btn-danger">                                      
                                            <i class="fas fa-trash"></i>
                                        </button>
                                    </td>    
                                </tr>
                            </tbody>     
<!--
<script>
	$(document).ready(function() {

  	$("#linkClientes").addClass("active"); 

	$('#example').DataTable();
	
});

</script>

-->


<!--MODAIS-->    
<!--Modal de detalhes de Obras-->
<div class="modal fade" id="modal-obras" tabindex="-1" role="dialog" aria-labelledby="Detalhes do Cliente" aria-hidden="true">
    <div class="modal-dialog modal-lg" role="document">
        <div class="modal-content">
            <div class="modal-header">
            <h3 class="modal-title"><b>Detalhes da Obra</b></h3>
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

                    <div class="form-group col-lg-9">
                    <label for="nome">Responsavel pela Obra</label>
                    <input type="text" class="form-control" name="nome" id="nome" placeholder="Nome Completo">
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
                    <label for="inputAddress">Cidade</label>
                    <input type="text" class="form-control" id="inputAddress">
                    </div>

                    <div class="form-group col-lg-6">
                    <label for="inputAddress">CEP</label>
                    <input type="text" class="form-control" id="inputAddress" placeholder="00000-000">
                    </div>              

                    <div class="form-group col-lg-6">
                    <label for="inputPassword4">Bairro</label>
                    <input type="text" class="form-control" id="inputPassword4" placeholder="Rua Exemplo">
                    </div>

                    </div>
                    <div class="form-group col-lg-8">
                    <label for="inputAddress">Endereço</label>
                    <input type="text" class="form-control" id="inputAddress" placeholder="000">
                    </div>   

                    
                    </div>
                    <div class="row">
                        <div class="col-sm-5"></div>       
                        <div class="col-sm-7">
        
                            <button type="button" class="btn btn-primary" data-dismiss="modal">Editar</button>
                            <button type="button" class="btn btn-danger" data-dismiss="modal">Fechar</button>
                        </div>        
                              
                    </div>

                </div>
               
            </div>

        </div>
    </div>
</div>

<!--Modal de Nova Obra-->
<div class="modal fade" id="modal-novaObra" tabindex="-1" role="dialog" aria-labelledby="Detalhes do Cliente" aria-hidden="true">
    <div class="modal-dialog modal-lg" role="document">
        <div class="modal-content">
            <div class="modal-header">
            <h3 class="modal-title"><b>Nova Obra</b></h3>
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

                    <div class="form-group col-lg-9">
                    <label for="nome">Responsavel pela Obra</label>
                    <input type="text" class="form-control" name="nome" id="nome" placeholder="Nome Completo">
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
                    <label for="inputAddress">Cidade</label>
                    <input type="text" class="form-control" id="inputAddress">
                    </div>

                    <div class="form-group col-lg-6">
                    <label for="inputAddress">CEP</label>
                    <input type="text" class="form-control" id="inputAddress" placeholder="00000-000">
                    </div>              

                    <div class="form-group col-lg-6">
                    <label for="inputPassword4">Bairro</label>
                    <input type="text" class="form-control" id="inputPassword4" placeholder="Rua Exemplo">
                    </div>

                    </div>
                    <div class="form-group col-lg-8">
                    <label for="inputAddress">Endereço</label>
                    <input type="text" class="form-control" id="inputAddress" placeholder="000">
                    </div>   

                    
                    </div>
                    <div class="form-group col-lg-4">
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
