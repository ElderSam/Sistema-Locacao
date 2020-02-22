<?php if(!class_exists('Rain\Tpl')){exit;}?><div id="layoutSidenav_content">
    <main>
        <div class="container-fluid">
            <h1 class="mt-4">Clientes</h1>
            <ol class="breadcrumb mb-4">
                <li class="breadcrumb-item active">Clientes</li>
            </ol>

           
            <div class="card mb-4">
                <div class="card-header"><i class="fas fa-table mr-1"></i>Lista de Clientes</div>
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
                                        <input type="button" onclick="viewORedit('.$data->id.', \'view\')" value="View"
                                            class="btn btn-warning">
                                        <input type="button" onclick="viewORedit('.$data->id.', \'edit\')" value="Edit"
                                            class="btn btn-primary">
                                        <input type="button" onclick="deleteRow('.$data->id.')" value="Delete"
                                            class="btn btn-danger">
                                    </td>
                                </tr>
                                <tr>
                                    <td>Escritório</td>
                                    <td>C002</td>
                                    <td>Alugado</td>
                                    <td>Container</td>
                        
                                    <td>$170,750</td>
                                    <td>
                                        <input type="button" onclick="viewORedit('.$data->id.', \'view\')" value="View"
                                            class="btn btn-warning">
                                        <input type="button" onclick="viewORedit('.$data->id.', \'edit\')" value="Edit"
                                            class="btn btn-primary">
                                        <input type="button" onclick="deleteRow('.$data->id.')" value="Delete"
                                            class="btn btn-danger">
                                    </td>
                                </tr>
                                <tr>
                                    <td>Almoxarifado</td>
                                    <td>C003</td>
                                    <td>Disponível</td>
                                    <td>Container</td>
                        
                                    <td>$860,00</td>
                                    <td>
                                        <input type="button" onclick="viewORedit('.$data->id.', \'view\')" value="View"
                                            class="btn btn-warning">
                                        <input type="button" onclick="viewORedit('.$data->id.', \'edit\')" value="Edit"
                                            class="btn btn-primary">
                                        <input type="button" onclick="deleteRow('.$data->id.')" value="Delete"
                                            class="btn btn-danger">
                                    </td>
                                </tr>
                                <tr>
                                    <td>Padrão</td>
                                    <td>C004</td>
                                    <td>Manutenção</td>
                                    <td>Container</td>
                        
                                    <td>$433,00</td>
                                    <td>
                                        <input type="button" onclick="viewORedit('.$data->id.', \'view\')" value="View"
                                            class="btn btn-warning">
                                        <input type="button" onclick="viewORedit('.$data->id.', \'edit\')" value="Edit"
                                            class="btn btn-primary">
                                        <input type="button" onclick="deleteRow('.$data->id.')" value="Delete"
                                            class="btn btn-danger">
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

  	$("#linkClientes").addClass("active"); 

	$('#example').DataTable();
	
});

</script>
-->



    <!--script>
        $("#linkProdutos").addClass("active"); 
    </script-->