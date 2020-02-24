<?php if(!class_exists('Rain\Tpl')){exit;}?><div id="layoutSidenav_content">
    <main>
        <div class="container-fluid">
            <h1 class="mt-4">Containers</h1>
            <ol class="breadcrumb mb-4 justify-content-between">
                <li class="breadcrumb-item active">Início/Produtos/Containers</li>
                <!-- Button trigger modal -->
                <button type="button" class="btn btn-success" data-toggle="modal" data-target="#productModal">
                    Novo Container
                </button>           
            </ol>

           
            <div class="card mb-4">
                <div class="card-body">
                    <div class="table-responsive">
                        <table class="table table-bordered table-hover" id="dataTable" width="100%" cellspacing="0">
                            <thead class="bg-dark text-white">
                            
                                <tr>
                                    <th>Descrição</th>
                                    <th>Código</th>
                                    <th>Status</th>
                                    <th>Tipo</th>
                                    <th>Base Aluguel</th>
                                    <th>Opções</th>
                                </tr>
                            </thead>
                            <tbody>
                                <tr>
                                    <td>Almoxarifado</td>
                                    <td>C001</td>
                                    <td>Disponível</td>
                                    <td>Container</td>
                        
                                    <td>$1320,800</td>
                                    <td>
                                        <button type="button" title="ver detalhes" class="btn btn-warning" data-toggle="modal" data-target="#modal-produto">
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
                                    <th>Descrição</th>
                                    <th>Código</th>
                                    <th>Status</th>
                                    <th>Tipo</th>
                                    <th>Base Aluguel</th>
                                    <th>Opções</th>
                        
                                </tr>
                            </tfoot>
   
                        </table>
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