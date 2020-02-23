<?php if(!class_exists('Rain\Tpl')){exit;}?><div id="layoutSidenav_content">
    <main>
        <div class="container-fluid">
            <h1 class="mt-4">Clientes</h1>
            <ol class="breadcrumb mb-4 justify-content-between">
                <li class="breadcrumb-item active">home/Clientes</li>
                <!-- Button trigger modal -->
                <button type="button" class="btn btn-primary" data-toggle="modal" data-target="#costumerModal">
                    Cadastrar
                </button>
            </ol>

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
                                        <button type="button" title="ver detalhes" class="btn btn-warning"
                                            data-toggle="modal" data-target="#costumerModal">
                                            <i class="fas fa-bars sm"></i>
                                        </button>
                                        <button type="button" title="excluir" onclick="deleteRow('.$data->id.')"
                                            class="btn btn-danger">
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
        <!-- end container-fluid -->


        <!--Costumer Modal------------------------------------------------------------------------------->
        <div class="modal fade" id="costumerModal" tabindex="-1" role="dialog" aria-labelledby="Detalhes do Cliente"
            aria-hidden="true">
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

                                <div class="form-group col-lg-10">
                                    <label for="nome">Nome</label>
                                    <input type="text" class="form-control" name="nome" id="nome"
                                        placeholder="Nome Completo">
                                </div>

                                <div class="form-group col-lg-2">
                                    <label for="status">Status</label>
                                    <select id="status" class="form-control">
                                        <option selected>Ativo</option>
                                        <option>Inativo</option>
                                    </select>
                                </div>

                                <div class="form-group col-lg-10">
                                    <label for="tipoCliente">Tipo de Cliente</label>
                                    <select id="tipoCliente" name="tipoCliente" class="form-control">
                                        <option value="J" selected>Pessoa Jurídica</option>
                                        <option value="F">Pessoa Física</option>

                                    </select>
                                </div>

                                <div class="form-group col-lg-6">
                                    <label for="rg">RG</label>
                                    <input type="text" class="form-control" name="rg" id="rg"
                                        placeholder="00.000.000-0">
                                </div>

                                <div class="form-group col-lg-6">
                                    <label for="cpf">CPF</label>
                                    <input type="text" class="form-control" name="cpf" id="cpf"
                                        placeholder="000.000.000-00">
                                </div>

                                <div class="form-group col-lg-6">
                                    <label for="ie" title="Inscrição Estadual">I.E.</label>
                                    <input type="text" class="form-control" name="ie" id="ie"
                                        placeholder="00 000 000-0">
                                </div>

                                <div class="form-group col-lg-6">
                                    <label for="cnpj">CNPJ</label>
                                    <input type="text" class="form-control" name="cnpj" id="cnpj"
                                        placeholder="000 000 000-00">
                                </div>

                                <div class="form-group col-lg-6">
                                    <label for="telefone1">Telefone 1</label>
                                    <input type="text" class="form-control" id="telefone1" name="telefone1"
                                        placeholder="(00)00000000">
                                </div>

                                <div class="form-group col-lg-6">
                                    <label for="telefone2">Telefone 2</label>
                                    <input type="phone" class="form-control" id="telefone2" name="telefone2"
                                        placeholder="(00)00000000">
                                </div>

                                <div class="form-group col-lg-6">
                                    <label for="email1">Email 1</label>
                                    <input type="email1" class="form-control" id="email1" name="email1"
                                        placeholder="exemplo@exemplo.com">
                                </div>

                                <div class="form-group col-lg-6">
                                    <label for="email2">Email 2</label>
                                    <input type="email" class="form-control" id="email2" name="email2"
                                        placeholder="exemplo@exemplo.com">
                                </div>

                                <div class="form-group col-lg-4">
                                    <label for="dtCadastro">Data de Cadastro</label>
                                    <input type="date" class="form-control" id="dtCadastro" name="dtCadastro"
                                        placeholder="00/00/0000" disabled>
                                </div>

                                <div class="form-group col-lg-4">
                                    <label for="uf" title="Unidade Federativa">UF</label>
                                    <select id="uf" name="uf" class="form-control">
                                        <option selected>Escolher...</option>
                                        <option>...</option>
                                    </select>
                                </div>

                                <div class="form-group col-lg-4">
                                    <label for="cep">CEP</label>
                                    <input type="text" class="form-control" id="cep" name="cep" placeholder="00000-000">
                                </div>

                                <div class="form-group col-lg-3">
                                    <label for="cidade">Cidade</label>
                                    <input type="text" class="form-control" id="cidade" name="cidade">
                                </div>

                                <div class="form-group col-lg-6">
                                    <label for="endereco">Endereço</label>
                                    <input type="text" class="form-control" id="endereco" name="endereco"
                                        placeholder="Rua Exemplo">
                                </div>
                                <div class="form-group col-lg-3">
                                    <label for="numero">Número</label>
                                    <input type="text" class="form-control" id="numero" placeholder="000">
                                </div>
                            </div>
                            <a class="btn btn-warning ml-3" href="/works" role="button">Obras</a>

                            <br><br>
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
        <!-- end Costmer Modal -->


        <!--
<script>
	$(document).ready(function() {

  	$("#linkClientes").addClass("active"); 

	$('#example').DataTable();
	
});

</script>
-->