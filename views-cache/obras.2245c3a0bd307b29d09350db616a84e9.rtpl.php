<?php if(!class_exists('Rain\Tpl')){exit;}?><div id="layoutSidenav_content">
    <main>
        <div class="container-fluid">

            <h2 class="mt-4">Obras</h2>
            <ol class="breadcrumb mb-4 justify-content-between">
                <li class="breadcrumb-item active">Início/Cliente/Obras</li>
                <!-- Button trigger modal -->
                <button type="button" class="btn btn-success" data-toggle="modal" data-target="#constructionModal">
                    Nova Obra
                </button>
            </ol>
        </div>

        <div class="card mb-4">
            <div class="card-body">
                <div class="table-responsive">
                    <table class="table table-bordered table-hover" id="dataTable" width="100%" cellspacing="0">
                        <thead class="bg-secondary text-white">
                            <tr>
                                <th>Código</th>
                                <th title="responsável pela obra">Resposavel</th>
                                <th>Telefone</th>
                                <th>E-mail</th>
                                <th>Cidade</th>
                                <th>Data de Cadastro</th>
                                <th>Opções</th>
                            </tr>
                        </thead>

                        <tbody>
                            <tr>
                                <td>1</td>
                                <td>Fulano de tal</td>
                                <td>(19) 3543-3432</td>
                                <td>hr_construcoes@gmail.com</td>
                                <td>Piracicaba-SP</td>
                                <td>22/12/2019</td>
                                <td>
                                    <button type="button" title="ver detalhes" class="btn btn-warning"
                                        data-toggle="modal" data-target="#constructionModal">
                                        <i class="fas fa-bars sm"></i>
                                    </button>
                                    <button type="button" title="excluir" onclick="deleteRow('.$data->id.')"
                                        class="btn btn-danger">
                                        <i class="fas fa-trash"></i>
                                    </button>
                                </td>
                            </tr>
                        </tbody>

                        <tfoot  class="bg-secondary text-white">
                            <tr>
                                <th>Código</th>
                                <th title="responsável pela obra">Resposavel</th>
                                <th>Telefone</th>
                                <th>E-mail</th>
                                <th>Cidade</th>
                                <th>Data de Cadastro</th>
                                <th>Opções</th>
                            </tr>
                        </tfoot>
                    </table>
                </div>
            </div>
        </div>
        <!-- end container-fluid -->



        <!--Work Modal------------------------------------------------------------------------------->
        <div class="modal fade" id="constructionModal" tabindex="-1" role="dialog" aria-labelledby="Detalhes da Obra"
            aria-hidden="true">
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
                                    <input type="text" class="form-control" name="nome" id="nome"
                                        placeholder="Nome Completo">
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
                                    <input type="email" class="form-control" id="inputEmail4"
                                        placeholder="exemplo@exemplo.com">
                                </div>

                                <div class="form-group col-lg-6">
                                    <label for="inputEmail4">Email 02</label>
                                    <input type="email" class="form-control" id="inputEmail4"
                                        placeholder="exemplo@exemplo.com">
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
                                    <input type="text" class="form-control" id="inputPassword4"
                                        placeholder="Rua Exemplo">
                                </div>

                                <div class="form-group col-lg-8">
                                    <label for="inputAddress">Endereço</label>
                                    <input type="text" class="form-control" id="inputAddress" placeholder="000">
                                </div>
                                <div class="form-group col-lg-4">
                                    <label for="inputAddress">Número</label>
                                    <input type="text" class="form-control" id="inputAddress" placeholder="000">
                                </div>
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
        <!-- end Construction Modal -->

        <!--script>
	$(document).ready(function() {

  	$("#linkObras").addClass("active"); 

	$('#example').DataTable();
	
});
</script-->