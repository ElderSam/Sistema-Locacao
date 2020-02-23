<?php if(!class_exists('Rain\Tpl')){exit;}?><div id="layoutSidenav_content">
    <main>
        <div class="container-fluid">
            <h1 class="mt-4">Usuários</h1>
            <ol class="breadcrumb mb-4 justify-content-between">
                <li class="breadcrumb-item active">home/Usuários</li>
                <!-- Button trigger modal -->
                <button type="button" class="btn btn-primary" data-toggle="modal" data-target="#usersModal">
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
                                    <th>Usuário</th>
                                    <th>Função</th>
                                    <th>Nome Completo</th>
                                    <th>E-mail</th>
                                    <th>Administrador</th>
                                    <th>Opções</th>
                                </tr>
                            </thead>
                            <tbody>
                                <?php $counter1=-1;  if( isset($users) && ( is_array($users) || $users instanceof Traversable ) && sizeof($users) ) foreach( $users as $key1 => $value1 ){ $counter1++; ?>
                                <tr>
                                    <td><?php echo htmlspecialchars( $value1["idUsuario"], ENT_COMPAT, 'UTF-8', FALSE ); ?></td>
                                    <td><?php echo htmlspecialchars( $value1["nomeUsuario"], ENT_COMPAT, 'UTF-8', FALSE ); ?></td>
                                    <td><?php echo htmlspecialchars( $value1["funcao"], ENT_COMPAT, 'UTF-8', FALSE ); ?></td>
                                    <td><?php echo htmlspecialchars( $value1["nomeCompleto"], ENT_COMPAT, 'UTF-8', FALSE ); ?></td>
                                    <td><?php echo htmlspecialchars( $value1["email"], ENT_COMPAT, 'UTF-8', FALSE ); ?></td>
                                    <td><?php if( $value1["administrador"] == 1 ){ ?>Sim<?php }else{ ?>Não<?php } ?></td>
                                    <td>
                                        <button type="button" title="ver detalhes" class="btn btn-warning" data-toggle="modal" data-target="#modal-cliente">
                                            <i class="fas fa-bars"></i>
                                        </button>
                                        <button type="button" title="excluir" class="btn btn-danger">                                     
                                            <i class="fas fa-trash"></i>
                                        </button>
                                    </td>
                                </tr>
                                <?php } ?>
                                
                                </tbody>
                            <tfoot class="bg-dark text-white">
                                <tr>
                                    <th>#</th>      
                                    <th>Usuário</th>
                                    <th>Função</th>
                                    <th>Nome Completo</th>
                                    <th>E-mail</th>
                                    <th>Administrador</th>
                                    <th>Opções</th>
                                </tr>
                            </tfoot>
                        </table>
                    </div>
                </div>
            </div>
        </div> <!-- fim container-fluid-->

        <!--Modal de Cliente-->
        <div class="modal fade" id="usersModal" tabindex="-1" role="dialog" aria-labelledby="Detalhes do Cliente"
        aria-hidden="true">
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

                                <div class="form-group col-lg-12">
                                    <label for="nome">Nome Completo</label>
                                    <input type="text" class="form-control" name="nome" id="nome" placeholder="Nome Completo">
                                </div>

                                <div class="form-group col-lg-6">
                                    <label for="funcao">Função</label>
                                    <input type="text" class="form-control" name="funcao" id="funcao" placeholder="Função na empresa">
                                </div>
                                
                                <div class="form-group col-lg-6">
                                    <label for="usuario">Usuário</label>
                                    <input type="text" class="form-control" name="usuario" id="usuario" placeholder="Nome de Usuário">
                                </div>


                                <div class="form-group col-lg-6">
                                    <label for="senha">Senha</label>
                                    <input type="password" class="form-control" name="senha" id="senha" placeholder="00 000 000-0">
                                </div>


                                <div class="form-group col-lg-6">
                                    <label for="email">Email</label>
                                    <input type="email" class="form-control" id="email" name="email" placeholder="exemplo@exemplo.com">
                                </div>

                                <div class="form-group col-lg-2">
                                    <label for="administrador">Administrador?</label>
                                    <select id="administrador" class="form-control">
                                        <option value="0" selected>Não</option>
                                        <option value="1" >Sim</option>
                                    </select>
                                </div>

                                <div class="form-group">
                                    <label for="file">Foto</label>
                                    <input type="file" class="form-control" id="file" name="file" value="">
                                    <div class="box box-widget">
                                      <div class="box-body">
                                        <img class="img-responsive" id="image-preview" src="" alt="Photo">
                                      </div>
                                    </div>
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
        <!-- FIM de Modal de Cliente-->

   
<!--
<script>
	$(document).ready(function() {

  	$("#linkUsuarios").addClass("active"); 

	$('#example').DataTable();
	
});

</script>
-->
