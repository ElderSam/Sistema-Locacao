$(function() { //quando a página carrega

	//carrega a tabela de suppliers
	myTable = $("#dataTable").DataTable({ 
		"oLanguage": DATATABLE_PTBR, //tradução
		"autoWidth": false, //largura
		"processing": true, //mensagem 'processando'
		"serverSide": true, 
		"ajax": {
			"url": "/suppliers/list_datatables", //chama a rota para carregar os dados 
			"type": "POST",

		},
		"columnDefs": [
			{ targets: "no-sort", orderable: false }, //para não ordenar
			{ targets: "text-center", className: "text-center" },
		]
	});

	$("#btnAddSupplier").click(function(){
		let i = 0
		clearFieldsValues();

		if(i == 0){
			showsNextNumber()
		}
		
	});

	 
	/* Cadastrar ou Editar Fornecedor --------------------------------------------------------------*/	
	$("#btnSaveSupplier").click(function(e) { //quando enviar o formulário de Fornecedor
		e.preventDefault();
		
		let form = $('#formSupplier');
		let formData = new FormData(form[0]);

		idFornecedor = $('#idFornecedor').val()
		//console.log("idFornecedor:" + idFornecedor)

		if((idFornecedor == 0) || (idFornecedor == undefined)){ //se for para cadastrar --------------------------------------------------

			//console.log("você quer cadastrar")

			$.ajax({
				type: "POST",
				url: '/suppliers/create',
				data: formData,
				contentType: false,
				processData: false,
				beforeSend: function() {
					clearErrors();
					$("#btnSaveSupplier").parent().siblings(".help-block").html(loadingImg("Verificando..."));
				
				},
				success: function (response) {
					clearErrors();
	
					if (JSON.parse(response).error) {
						console.log('erro ao cadastrar novo Fornecedor!')
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
						
					} else { // Se cadastrou com sucesso

						$('#SupplierModal').modal('hide');
						
						//console.log(response)
						Swal.fire(
							'Sucesso!',
							'Fornecedor cadastrado!',
							'success'
							)
	
						loadTableSuppliers();
						$('#formSupplier').trigger("reset");
						
					}
					
				},
				error: function (response) {
					//$('#SupplierModal').modal('hide');
					$('#formSupplier').trigger("reset");
					console.log(`Erro! Mensagem: ${response}`);
	
				}
			});

		}else{ /* se for para Editar -------------------------------------------------- */

			//console.log('você quer editar o Fornecedor: ' + idFornecedor)
			
			$.ajax({
				type: "POST",
				url: `/suppliers/${idFornecedor}`, //rota para editar
				data: formData,
				contentType: false,
				processData: false,
				beforeSend: function() {
					clearErrors();
					$("#btnSaveSupplier").parent().siblings(".help-block").html(loadingImg("Verificando..."));
				
				},
				success: function (response) {
					clearErrors();

					if (JSON.parse(response).error) {
						console.log('erro ao atualizar Fornecedor!')

						response = JSON.parse(response)

						Swal.fire(
							'Erro!',
							'Ocorreu algum erro ao Atualizar',
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
						$('#SupplierModal').modal('hide');

						Swal.fire(
							'Sucesso!',
							'Fornecedor atualizado!',
							'success'
						);

						loadTableSuppliers();
						$('#formSupplier').trigger("reset");
					}
	
				},
				error: function (response) {
	
					//$('#SupplierModal').modal('hide');
					$('#formSupplier').trigger("reset");
					console.log(`Erro! Mensagem: ${response}`);
	
				}
			});
		}	

		return false;
	});

});


	function showsNextNumber(){ //mostra o próximo número de série relacionado à categoria
		console.log('shows next number')
		$.ajax({
			type: "POST",
			url: `/suppliers/showsNextNumber`,
			contentType: false,
			processData: false,
			
			success: function (response) {
		
				//console.log('próximo código de fornecedor: ' + response)
				$('#codigo').val(response)						
									
			},
			error: function (response) {

				console.log(`Erro! Mensagem: ${response}`);		
			}
		});	
	}


function loadTableSuppliers(){ //carrega a tabela de Fornecedores

	myTable.destroy(); //desfaz as paginações
	
	myTable = $("#dataTable").DataTable({ 
		"oLanguage": DATATABLE_PTBR,
		"autoWidth": false, //largura
		"processing": true, //mensagem 'processando'
		"serverSide": true,
		"ajax": {
			"url": "/suppliers/list_datatables", //para chamar o método ajax_list_suppliers
			"type": "POST",
		},
		"columnDefs": [
			{ targets: "no-sort", orderable: false }, //para não ordenar
			{ targets: "text-center", className: "text-center" },
		]
	});
}


//detalhes do Fornecedor
function loadSupplier(idFornecedor) { //carrega todos os campos do modal referente ao Fornecedor escolhido
	clearFieldsValues();
	clearErrors();

	$('#modalTitle').html('Detalhes do Fornecedor')
	$('#btnClose').val('Fechar').removeClass('btn-danger').addClass('btn-primary')
	$('#btnSaveSupplier').hide();
	$('#btnUpdate').show();

	$.getJSON(`/suppliers/json/${idFornecedor}`, function (data) { //ajax
		console.log(data)

		$("#idFornecedor").val(data.idFornecedor);
		//console.log('load View Fornecedor idFornecedor: ' + $("#idFornecedor").val())

		$("#formSupplier #codigo").val(data.codFornecedor).prop('disabled', true);
		$("#formSupplier #nome").val(data.nome).prop('disabled', true);
		$("#formSupplier #telefone1").val(data.telefone1).prop('disabled', true);
		$("#formSupplier #telefone2").val(data.telefone2).prop('disabled', true);
		$("#formSupplier #email1").val(data.email1).prop('disabled', true);
		$("#formSupplier #email2").val(data.email2).prop('disabled', true);

		$("#formSupplier #endereco").val(data.endereco).prop('disabled', true);
		$("#formSupplier #numero").val(data.numero).prop('disabled', true);
		$("#formSupplier #bairro").val(data.bairro).prop('disabled', true);
		$("#formSupplier #complemento").val(data.complemento).prop('disabled', true);
		$("#formSupplier #cep").val(data.cep).prop('disabled', true);
		$("#formSupplier #cidade").val(data.cidade).prop('disabled', true);
		$("#formSupplier #status").val(data.status).prop('disabled', true);
		$("#formSupplier #uf").val(data.uf).prop('disabled', true);
		
		/* Atualizar Fornecedor ------------------------------------------------------------------ */
		$('#btnUpdate').click(function(){ //se eu quiser atualizar o Fornecedor atual

			$('#modalTitle').html('Editar Fornecedor');
			$('#btnClose').html('Cancelar').removeClass('btn-primary').addClass('btn-danger');
			$('#btnSaveSupplier').val('Atualizar').show();
			$('#btnUpdate').hide();
		
			//$('#desImagePath').parent().show();

			$("#formSupplier #codigo").prop('disabled', false);
			
			$("#formSupplier #nome").prop('disabled', false);
			$("#formSupplier #telefone1").prop('disabled', false);
			$("#formSupplier #telefone2").prop('disabled', false);
			$("#formSupplier #email1").prop('disabled', false);
			$("#formSupplier #email2").prop('disabled', false);
			$("#formSupplier #endereco").prop('disabled', false);
			$("#formSupplier #numero").prop('disabled', false);
			$("#formSupplier #bairro").prop('disabled', false);
			$("#formSupplier #complemento").prop('disabled', false);
			$("#formSupplier #cidade").prop('disabled', false);
			$("#formSupplier #uf").prop('disabled', false);
			$("#formSupplier #cep").prop('disabled', false);
			$("#formSupplier #status").prop('disabled', false);

			
				
		}); /* Fim Atualizar Fornecedor ---------------------------------------------------------- */
			

	}).then(() => { 

		$("#SupplierModal").modal();
	}).fail(function () {
		console.log("Rota não encontrada! (/suppliers/json/:idFornecedor)");
	});

}

function deleteSupplier(idFornecedor){

	Swal.fire({
		title: 'Você tem certeza?',
		text: "Você não será capaz de reverter isso!",
		icon: 'warning',
		showCancelButton: true,
		confirmButtonColor: '#d33',
		cancelButtonColor: '#3085d6',
		confirmButtonText: 'Sim, apagar!'

	}).then((result) => {

		if (result.value) {

			$.ajax({
				type: "POST",
				url: `/suppliers/${idFornecedor}/delete`,
				beforeSend: function() {
					
					$('.swal2-content').hide()
					$('.swal2-actions').hide()
					$('.swal2-title').html(`<div class="help-block">${loadingImg("Verificando...")}</div>`);
				
				},
				success: function (response) {
		
					if (JSON.parse(response).error) {
						console.log('erro ao excluir!')
						response = JSON.parse(response)
						
						Swal.fire(
							'Erro!',
							'Não foi possível excluir',
							'error'
						)
						
					} else {					
										
						Swal.fire(
							'Excluído!',
							'Registro apagado!',
							'success'
						)

						loadTableSuppliers();						
					}					
				},
				error: function (response) {
					Swal.fire(
						'Erro!',
						'Não foi possível excluir',
						'error'
					)
					console.log(`Erro! Mensagem: ${response}`);		
				}
			});		

		}
	})

	$('.swal2-cancel').html('Cancelar');
}


//limpar campos do modal para Cadastrar
function clearFieldsValues(){

	//$("#formSupplier #codigo").prop('disabled', true)
	$('#modalTitle').html('Cadastrar Fornecedor');
	$('#btnClose').html('Fechar').removeClass('btn-danger').addClass('btn-secondary');
	$('#btnSaveSupplier').val('Cadastrar').show();
	$('#btnUpdate').hide();

	$('#dtCadastro').parent().hide(); //aparece a data de cadastro (só para visualizar)
	//$('#desImagePath').parent().show();

	$("#formSupplier #codigo").prop('disabled', false);
	$("#formSupplier #nome").prop('disabled', false);
	$("#formSupplier #telefone1").prop('disabled', false);
	$("#formSupplier #telefone2").prop('disabled', false);
	$("#formSupplier #email1").prop('disabled', false);
	$("#formSupplier #email2").prop('disabled', false);
	$("#formSupplier #endereco").prop('disabled', false);
	$("#formSupplier #numero").prop('disabled', false);
	$("#formSupplier #bairro").prop('disabled', false);
	$("#formSupplier #cidade").prop('disabled', false);
	$("#formSupplier #uf").prop('disabled', false);
	$("#formSupplier #cep").prop('disabled', false);
	$("#formSupplier #status").prop('disabled', false);

	//$('#image-preview').attr('src', "/res/img/suppliers/supplier-default.jpg");
	$('#dtCadastro').parent().hide();

	$('#codigo').val('');
	$('#nome').val('');
	$('#telefone1').val('');
	$('#telefone2').val('');
	$('#email1').val('');
	$('#email2').val('');

	$('#endereco').html('');
	$('#numero').html('');
	$('#bairro').html('');
	$('#cidade').html('');

	$('#uf').val('SP');
	$('#cep').val('');

	
	$('#status').val('1');
	$('#dtCadastro').val('');
	
	$('#idFornecedor').val('0');
	
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

