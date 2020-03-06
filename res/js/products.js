$(function() { //quando a página carrega

	//carrega a tabela de Products
	myTable = $("#dataTable").DataTable({ 
		"oLanguage": DATATABLE_PTBR, //tradução
		"autoWidth": false, //largura
		"processing": true, //mensagem 'processando'
		"serverSide": true, 
		"ajax": {
			"url": "/products/list_datatables", //chama a rota para carregar os dados 
			"type": "POST",
		},
		"columnDefs": [
			{ targets: "no-sort", orderable: false }, //para não ordenar
			{ targets: "text-center", className: "text-center" },
		]
	});

	$('#btnAddProduct').click(function(){
		limparCampos();

		clearErrors();
	});

	 
	 
	/* Cadastrar ou Editar Usuario --------------------------------------------------------------*/	
	$("#btnSaveProduct").click(function(e) { //quando enviar o formulário de Usuarios
		e.preventDefault();
		
		let form = $('#formProduct');
		let formData = new FormData(form[0]);

		idProduto = $('#idProduto').val()
		//console.log("idProduto:" + idProduto)

		if(idProduto == 0){ //se for para cadastrar --------------------------------------------------

			//console.log("você quer cadastrar")

			$.ajax({
				type: "POST",
				url: '/products/create',
				data: formData,
				contentType: false,
				processData: false,
				beforeSend: function() {
					clearErrors();
					$("#btnSaveProduct").parent().siblings(".help-block").html(loadingImg("Verificando..."));
				
				},
				success: function (response) {
					clearErrors();
	
					if (JSON.parse(response).error) {
						console.log('erro ao cadastrar novo Produto!')
						response = JSON.parse(response)
						
						Swal.fire(
							'Erro!',
							'Ocorreu algum problema ao cadastrar',
							'error'
						)
	
						if(response['error_list']){
							
							showErrorsModal(response['error_list'])

							Swal.fire(
								'Atenção!',
								'Por favor verifique os campos',
								'error'
							)
						}
						
					} else {
						$('#productModal').modal('hide');
						
						//console.log(response)
						Swal.fire(
							'Sucesso!',
							'Produto cadastrado!',
							'success'
							)
	
						loadTableProducts();
						$('#formProduct').trigger("reset");
						
					}
					
				},
				error: function (response) {
					//$('#productModal').modal('hide');
					$('#formProduct').trigger("reset");
					console.log(`Erro! Mensagem: ${response}`);
	
				}
			});

		}else{ /* se for para Editar -------------------------------------------------- */

			//console.log('você quer editar o usuario: ' + idProduto)
			
			$.ajax({
				type: "POST",
				url: `/products/${idProduto}`, //rota para editar
				data: formData,
				contentType: false,
				processData: false,
				beforeSend: function() {
					clearErrors();
					$("#btnSaveProduct").parent().siblings(".help-block").html(loadingImg("Verificando..."));
				
				},
				success: function (response) {
					clearErrors();

					if (JSON.parse(response).error) {
						console.log('erro ao editar Produto!')

						response = JSON.parse(response)

						Swal.fire(
							'Erro!',
							'Ocorreu algum erro ao Editar',
							'error'
						);

						if(response['error_list']){
							
							showErrorsModal(response['error_list'])

							Swal.fire(
								'Atenção!',
								'Por favor verifique os campos',
								'error'
							);
						}

					} else {
						$('#productModal').modal('hide');

						Swal.fire(
							'Sucesso!',
							'Product atualizado!',
							'success'
						);

						loadTableProducts();
						$('#formProduct').trigger("reset");
					}
	
				},
				error: function (response) {
	
					//$('#productModal').modal('hide');
					$('#formProduct').trigger("reset");
					console.log(`Erro! Mensagem: ${response}`);
	
				}
			});
		}	

		return false;
	});

});


function loadTableProducts(){ //carrega a tabela de Produtos

	myTable.destroy(); //desfaz as paginações
	
	myTable = $("#dataTable").DataTable({ 
		"oLanguage": DATATABLE_PTBR,
		"autoWidth": false, //largura
		"processing": true, //mensagem 'processando'
		"serverSide": true,
		"ajax": {
			"url": "/products/list_datatables", //para chamar o método ajax_list_products
			"type": "POST",
		},
		"columnDefs": [
			{ targets: "no-sort", orderable: false }, //para não ordenar
			{ targets: "text-center", className: "text-center" },
		]
	});
}


//detalhes do Produto
function loadProduct(idProduto) { //carrega todos os campos do modal referente ao produto escolhido
	clearErrors();

	$('#modalTitle').html('Detalhes do Produto')
	$('#btnClose').val('Fechar').removeClass('btn-danger').addClass('btn-primary')
	$('#btnSaveProduct').hide();
	$('#btnUpdate').show();
	
	$('#dtCadastro').parent().show(); //aparece a data de cadastro (só para visualizar)
	//$('#desImagePath').parent().hide();


	$.getJSON(`/products/json/${idProduto}`, function (data) {
		//console.log(data)

		$("#formProduct #codigo").val(data.codigo).prop('disabled', true);
		$("#formProduct #descricao").val(data.descricao).prop('disabled', true);
		$("#formProduct #valorCompra").val(data.valorCompra).prop('disabled', true);
		$("#formProduct #status").val(data.status).prop('disabled', true);
		$("#formProduct #dtFabricacao").val(data.dtFabricacao).prop('disabled', true);
		$("#formProduct #tipo").val(data.tipo).prop('disabled', true);
		$("#formProduct #anotacoes").val(data.anotacoes).prop('disabled', true);
		$("#formProduct #idFornecedor").val(data.idFornecedor).prop('disabled', true);
		$("#formProduct #idCategoria").val(data.idCategoria).prop('disabled', true);
		$("#formProduct #dtCadastro").val(data.dtCadastro).prop('disabled', true);
		$("#idProduto").val(data.idProduto);
		//console.log('load View Produto idProduto: ' + $("#idProduto").val())

		dtCadastro = formatDate(data.dtCadastro)
		//console.log('data: ' + dtCadastro)
		$("#formProduct #dtCadastro").val(dtCadastro);
			
		//$("#formProduct #image-preview").attr("src", data.foto); //mostra a imagem atual
		//$("#desmagePath").val(data.foto);

		/* Atualizar Produto ------------------------------------------------------------------ */
		$('#btnUpdate').click(function(){ //se eu quiser atualizar o Produto atual

			$('#modalTitle').html('Editar Produto');
			$('#btnClose').html('Cancelar').removeClass('btn-primary').addClass('btn-danger');
			$('#btnSaveProduct').val('Atualizar').show();
			$('#btnUpdate').hide();
		
			//$('#desImagePath').parent().show();

			$("#formProduct #codigo").prop('disabled', false);
			$("#formProduct #descricao").prop('disabled', false);
			$("#formProduct #valorCompra").prop('disabled', false);
			$("#formProduct #status").prop('disabled', false);
			$("#formProduct #dtFabricacao").prop('disabled', false);
			$("#formProduct #tipo").prop('disabled', false);
			$("#formProduct #anotacoes").prop('disabled', false);
			$("#formProduct #idFornecedor").prop('disabled', false);
			$("#formProduct #idCategoria").prop('disabled', false);
				
		}); /* Fim Atualizar Produto ---------------------------------------------------------- */
			

	}).then(() => { 

		$("#productModal").modal();
	}).fail(function () {
		console.log("Rota não encontrada!");
	});

}

function deleteProduct(idProduto){

	Swal.fire({
		title: 'Você tem certeza?',
		text: "Você não será capaz de reverter isso!",
		icon: 'warning',
		showCancelButton: true,
		confirmButtonColor: '#3085d6',
		cancelButtonColor: '#d33',
		confirmButtonText: 'Sim, apagar!'

	}).then((result) => {

		if (result.value) {

			$.ajax({
				type: "POST",
				url: `/products/${idProduto}/delete`,
				contentType: false,
				processData: false,
				/*beforeSend: function() {
					//...
				},*/
				success: function (response) {
		
					if (JSON.parse(response).error) {
						console.log('erro ao excluir!')
						response = JSON.parse(response)
						
						Swal.fire(
							'Erro!',
							'Não foi possível deletar',
							'error'
						)
						
					} else {					
										
						Swal.fire(
							'Excluído!',
							'Registro apagado!',
							'success'
						)

						loadTableProducts();						
					}					
				},
				error: function (response) {

					console.log(`Erro! Mensagem: ${response}`);		
				}
			});		

		}
	})

	$('.swal2-cancel').html('Cancelar');
}


//limpar campos do modal para Cadastrar
function limparCampos(){

	$("#formProduct #codigo").prop('disabled', true)
	$('#modalTitle').html('Cadastrar Produto');
	$('#btnClose').html('Fechar').removeClass('btn-danger').addClass('btn-secondary');
	$('#btnSaveProduct').val('Cadastrar').show();
	$('#btnUpdate').hide();

	$('#dtCadastro').parent().hide(); //aparece a data de cadastro (só para visualizar)
	//$('#desImagePath').parent().show();


	$("#formProduct #codigo").prop('disabled', false);
	$("#formProduct #descricao").prop('disabled', false);
	$("#formProduct #valorCompra").prop('disabled', false);
	$("#formProduct #status").prop('disabled', false);
	$("#formProduct #dtFabricacao").prop('disabled', false);
	$("#formProduct #tipo").prop('disabled', false);
	$("#formProduct #anotacoes").prop('disabled', false);
	$("#formProduct #idFornecedor").prop('disabled', false);
	$("#formProduct #idCategoria").prop('disabled', false);
	

	//$('#image-preview').attr('src', "/res/img/productsp/product-default.jpg");
	$('#dtCadastro').parent().hide();

	$('#codigo').val('');
	$('#descricao').val('');
	$('#valorCompra').val('');
	$('#status').val('0');
	$('#dtFabricacao').val('');
	$('#tipo').val('');
	$('#anotacoes').val('');
	$('#idFornecedor').val('0');
	$('#idContainer').val('0');
	$('#idCategoria').val('0');
	$('#dtCadastro').val('');
	
	$('#idProduto').val('0');
	//...	
}

function formatDate(dateX){ //format Date to input in Form
    var data = new Date(dateX),
        dia  = data.getDate().toString(),
        diaF = (dia.length == 1) ? '0'+dia : dia,
        mes  = (data.getMonth()+1).toString(), //+1 pois no getMonth Janeiro começa com zero.
        mesF = (mes.length == 1) ? '0'+mes : mes,
        anoF = data.getFullYear();
	//return diaF+"/"+mesF+"/"+anoF;
	return anoF+"-"+mesF+"-"+diaF;
}

