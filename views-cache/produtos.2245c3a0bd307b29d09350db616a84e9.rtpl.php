<?php if(!class_exists('Rain\Tpl')){exit;}?><div class="content">
    <div class="container-fluid">
        <div class="row">
            <div class="col-md-12">
                <div class="card">
                    <div class="header">
                        <h4 class="title">Produtos</h4>
                        <p class="category"> Lista de Produtos</p>
                    </div>
                    <div class="content">
                        <table id="example" class="table table-striped table-bordered" style="width:100%">
                            <thead>
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
                            <tfoot>
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
    </div>

    <script>
        $("#linkProdutos").addClass("active"); 
    </script>