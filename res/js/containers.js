$(function() { //quando a página carrega

	//carrega a tabela de Products
	myTable = $("#dataTable").DataTable({ 
		"oLanguage": DATATABLE_PTBR, //tradução
		"autoWidth": false, //largura
		"processing": true, //mensagem 'processando'
		"serverSide": true, 
		"ajax": {
			"url": "/products/containers/list_datatables", //chama a rota para carregar os dados 
			"type": "POST",
		},
		"columnDefs": [
			{ targets: "no-sort", orderable: false }, //para não ordenar
			{ targets: "text-center", className: "text-center" },
		]
	});

	$('#btnAddContainer').click(function(){
		limparCampos();

		clearErrors();
	});

	 
	 
	/* Cadastrar ou Editar Usuario --------------------------------------------------------------*/	
	$("#btnSaveContainer").click(function(e) { //quando enviar o formulário de Usuarios
		e.preventDefault();
		
		let form = $('#formContainer');
		let formData = new FormData(form[0]);

		idProduto = $('#idProduto').val()
		//console.log("idProduto:" + idProduto)

		if(idProduto == 0){ //se for para cadastrar --------------------------------------------------

			//console.log("você quer cadastrar")

			$.ajax({
				type: "POST",
				url: '/products/containers/create',
				data: formData,
				contentType: false,
				processData: false,
				beforeSend: function() {
					clearErrors();
					$("#btnSaveContainer").parent().siblings(".help-block").html(loadingImg("Verificando..."));
				
				},
				success: function (response) {
					clearErrors();
	
					if (JSON.parse(response).error) {
						console.log('erro ao cadastrar novo Container!')
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
						$('#containerModal').modal('hide');
						
						//console.log(response)
						Swal.fire(
							'Sucesso!',
							'Produto cadastrado!',
							'success'
							)
	
						loadTableProducts();
						$('#formContainer').trigger("reset");
						
					}
					
				},
				error: function (response) {
					//$('#containerModal').modal('hide');
					$('#formContainer').trigger("reset");
					console.log(`Erro! Mensagem: ${response}`);
	
				}
			});

		}else{ /* se for para Editar -------------------------------------------------- */

			//console.log('você quer editar o usuario: ' + idProduto)
			
			$.ajax({
				type: "POST",
				url: `/products/containers/${idProduto}`, //rota para editar
				data: formData,
				contentType: false,
				processData: false,
				beforeSend: function() {
					clearErrors();
					$("#btnSaveContainer").parent().siblings(".help-block").html(loadingImg("Verificando..."));
				
				},
				success: function (response) {
					clearErrors();

					if (JSON.parse(response).error) {
						console.log('erro ao editar Container!')

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
						$('#containerModal').modal('hide');

						Swal.fire(
							'Sucesso!',
							'Product atualizado!',
							'success'
						);

						loadTableProducts();
						$('#formContainer').trigger("reset");
					}
	
				},
				error: function (response) {
	
					//$('#containerModal').modal('hide');
					$('#formContainer').trigger("reset");
					console.log(`Erro! Mensagem: ${response}`);
	
				}
			});
		}	

		return false;
	});

});


function loadTableProducts(){ //carrega a tabela de Containers

	myTable.destroy(); //desfaz as paginações
	
	myTable = $("#dataTable").DataTable({ 
		"oLanguage": DATATABLE_PTBR,
		"autoWidth": false, //largura
		"processing": true, //mensagem 'processando'
		"serverSide": true,
		"ajax": {
			"url": "/products/containers/list_datatables", //para chamar o método ajax_list_products
			"type": "POST",
		},
		"columnDefs": [
			{ targets: "no-sort", orderable: false }, //para não ordenar
			{ targets: "text-center", className: "text-center" },
		]
	});
}


//detalhes do Container
function loadProduct(idProduto) { //carrega todos os campos do modal referente ao Container escolhido
	clearErrors();

	$('#modalTitle').html('Detalhes do Container')
	$('#btnClose').val('Fechar').removeClass('btn-danger').addClass('btn-primary')
	$('#btnSaveContainer').hide();
	$('#btnUpdate').show();
	
	$('#dtCadastro').parent().show(); //aparece a data de cadastro (só para visualizar)
	//$('#desImagePath').parent().hide();


	$.getJSON(`/products/containers/json/${idProduto}`, function (data) {
		//console.log(data)

		$("#formContainer #codigo").val(data.codigo).prop('disabled', true);
		$("#formContainer #descricao").val(data.descricao).prop('disabled', true);
		$("#formContainer #valorCompra").val(data.valorCompra).prop('disabled', true);
		$("#formContainer #status").val(data.status).prop('disabled', true);
		$("#formContainer #dtFabricacao").val(data.dtFabricacao).prop('disabled', true);
		$("#formContainer #tipo").val(data.tipo).prop('disabled', true);
		$("#formContainer #anotacoes").val(data.anotacoes).prop('disabled', true);
		$("#formContainer #idFornecedor").val(data.idFornecedor).prop('disabled', true);
		$("#formContainer #idCategoria").val(data.idCategoria).prop('disabled', true);
		$("#formContainer #dtCadastro").val(data.dtCadastro).prop('disabled', true);
		$("#idProduto").val(data.idProduto);
		//console.log('load View Container idProduto: ' + $("#idProduto").val())

		dtCadastro = formatDate(data.dtCadastro)
		//console.log('data: ' + dtCadastro)
		$("#formContainer #dtCadastro").val(dtCadastro);
			
		//$("#formContainer #image-preview").attr("src", data.foto); //mostra a imagem atual
		//$("#desmagePath").val(data.foto);

		/* Atualizar Container ------------------------------------------------------------------ */
		$('#btnUpdate').click(function(){ //se eu quiser atualizar o Container atual

			$('#modalTitle').html('Editar Container');
			$('#btnClose').html('Cancelar').removeClass('btn-primary').addClass('btn-danger');
			$('#btnSaveContainer').val('Atualizar').show();
			$('#btnUpdate').hide();
		
			//$('#desImagePath').parent().show();

			$("#formContainer #codigo").prop('disabled', false);
			$("#formContainer #descricao").prop('disabled', false);
			$("#formContainer #valorCompra").prop('disabled', false);
			$("#formContainer #status").prop('disabled', false);
			$("#formContainer #dtFabricacao").prop('disabled', false);
			$("#formContainer #tipo").prop('disabled', false);
			$("#formContainer #anotacoes").prop('disabled', false);
			$("#formContainer #idFornecedor").prop('disabled', false);
			$("#formContainer #idCategoria").prop('disabled', false);
				
		}); /* Fim Atualizar Container ---------------------------------------------------------- */
			

	}).then(() => { 

		$("#containerModal").modal();
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
				url: `/products/containers/${idProduto}/delete`,
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

	$('#modalTitle').html('Cadastrar Container');
	$('#btnClose').html('Fechar').removeClass('btn-danger').addClass('btn-secondary');
	$('#btnSaveContainer').val('Cadastrar').show();
	$('#btnUpdate').hide();

	$('#dtCadastro').parent().hide(); //aparece a data de cadastro (só para visualizar)
	//$('#desImagePath').parent().show();


	$("#formContainer #codigo").prop('disabled', false);
	$("#formContainer #descricao").prop('disabled', false);
	$("#formContainer #valorCompra").prop('disabled', false);
	$("#formContainer #status").prop('disabled', false);
	$("#formContainer #dtFabricacao").prop('disabled', false);
	$("#formContainer #tipo").prop('disabled', false);
	$("#formContainer #anotacoes").prop('disabled', false);
	$("#formContainer #idFornecedor").prop('disabled', false);
	$("#formContainer #idCategoria").prop('disabled', false);
	

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

