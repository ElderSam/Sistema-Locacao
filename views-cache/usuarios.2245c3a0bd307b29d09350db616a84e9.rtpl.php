<?php if(!class_exists('Rain\Tpl')){exit;}?><div id="layoutSidenav_content">
    <main>
        <div class="container-fluid">
            <h2 class="mt-4">Usuários</h2>
            <ol class="breadcrumb mb-4 justify-content-between">
                <li class="breadcrumb-item active">Início/Usuários</li>
                <!-- Button trigger modal -->
                <button type="button" class="btn btn-success" data-toggle="modal" data-target="#userModal">
                    Novo Usuário
                </button>
            </ol>

            <div class="card mb-4">
                <div class="card-body">
                    <div class="table-responsive">
                        <table class="table table-bordered table-hover" id="dataTable" width="100%" cellspacing="0">
                            <thead class="bg-dark text-white">
                                <tr>
                                    <th>Foto</th>
                                    <th>Usuário</th>
                                    <th>Função</th>
                                    <th>Data de Cadastro</th>
                                    <th>Administrador</th>
                                    <th></th>
                                </tr>
                            </thead>
                            <tbody>
                                <?php $counter1=-1;  if( isset($users) && ( is_array($users) || $users instanceof Traversable ) && sizeof($users) ) foreach( $users as $key1 => $value1 ){ $counter1++; ?>
                                <tr>
                                    <td><img src="../res/assets/img/default-50x50.gif" alt="imagem-usuario"
                                            class="rounded-circle rounded-sm"></td>
                                    <td>João</td>
                                    <td>Aux Administrativo</td>
                                    <td>20/03/2017</td>
                                    <td>Sim</td>
                                    <td>
                                        <button type="button" title="ver detalhes" class="btn btn-warning"
                                            data-toggle="modal" data-target="#usersModal">
                                            <i class="fas fa-bars sm"></i>
                                        </button>
                                        <button type="button" title="excluir" onclick="deleteRow('.$data->id.')"
                                            class="btn btn-danger">
                                            <i class="fas fa-trash"></i>
                                        </button>
                                    </td>
                                </tr>

                                <?php } ?>

                            </tbody>
                            <tfoot class="bg-dark text-white">
                                <tr>
                                    <th>Foto</th>
                                    <th>Usuário</th>
                                    <th>Função</th>
                                    <th>Data de Cadastro</th>
                                    <th>Administrador</th>
                                    <th></th>
                                </tr>
                            </tfoot>
                        </table>
                    </div>
                </div>
            </div>
        </div> <!-- fim container-fluid-->

        <!-- Users Modal -->

        <div class="modal fade" id="userModal" tabindex="-1" role="dialog" aria-labelledby="Detalhes do Usuario"
            aria-hidden="true">
            <div class="modal-dialog modal-lg" role="document">
                <div class="modal-content">
                    <div style="margin-bottom: -1rem;" class="modal-header">
                        <h3 class="modal-title"><b>Detalhes do Usuário</b></h3>
                        <button type="button" class="close" data-dismiss="modal">
                            <span>&times;</span>
                        </button>
                    </div>

                    <div class="modal-body">
                        <form>
                            <div class="form-row">

                                <div class="form-group col-lg-12">
                                    <figure class="figure">
                                        <img src="../res/assets/img/user2-160x160.jpg"
                                            class="figure-img img-fluid rounded" alt="...">
                                    </figure>
                                </div>

                                <div style="margin-top: -1.9rem;" class="form-group col-lg-12">

                                    <input type="file" class="form-control-file" id="exampleFormControlFile1">
                                </div>



                                <div class="form-group col-lg-6">
                                    <label for="nome">Nome Completo</label>
                                    <input type="text" class="form-control" name="nome" id="nome"
                                        placeholder="Nome Completo">
                                </div>

                                <div class="form-group col-lg-6">

                                    <label for="funcao">Função</label>
                                    <input type="text" class="form-control" name="funcao" id="funcao"
                                        placeholder="Função na empresa">
                                </div>

                                <div class="form-group col-lg-6">
                                    <label for="usuario">Usuário</label>
                                    <input type="text" class="form-control" name="usuario" id="usuario"
                                        placeholder="Nome de Usuário">
                                </div>


                                <div class="form-group col-lg-6">
                                    <label for="senha">Senha</label>
                                    <input type="password" class="form-control" name="senha" id="senha" minlength="6"
                                        required="" placeholder="sua senha">
                                </div>


                                <div class="form-group col-lg-6">
                                    <label for="email">Email</label>
                                    <input type="email" class="form-control" id="email" name="email"
                                        placeholder="exemplo@exemplo.com">
                                </div>

                                <div class="form-group col-lg-2">
                                    <label for="administrador">Administrador?</label>
                                    <select id="administrador" class="form-control">
                                        <option value="0" selected>Não</option>
                                        <option value="1">Sim</option>
                                    </select>
                                </div>


                                <div hidden="true">
                                    <label for="id">Id</label>
                                    <input type="text" id="id" name="id">
                                </div>
                            </div>

                        <div style="margin: 0px;" class="breadcrumb justify-content-between">

                            <button type="button" class="btn btn-secondary" data-dismiss="modal">Fechar</button>
                            <input type="button" onclick="viewORedit('.$data->id.', 'edit')" value="Editar"
                                class="btn btn-primary">
                        </div>

                            
                        </form>

                    </div>

                </div>
            </div>

            <!-- end Users Modal-->

            <!--
<script>
	$(document).ready(function() {

  	$("#linkUsuarios").addClass("active"); 

	$('#example').DataTable();
	
});

</script>
-->