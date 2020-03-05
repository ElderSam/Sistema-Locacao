$(function() { //quando a página carrega

	//carrega a tabela de Containers
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


	$("#btnChangePassword").click(function() {
		Swal.fire(
			'OPS! :(',
			'Essa função ainda não foi implementada!',
			'info'
		)
	 });

	 
	 
	/* Cadastrar ou Editar Usuario --------------------------------------------------------------*/	
	$("#btnSaveContainer").click(function(e) { //quando enviar o formulário de Usuarios
		e.preventDefault();
		
		let form = $('#formContainer');
		let formData = new FormData(form[0]);

		idContainer = $('#idContainer').val()
		//onsole.log("idContainer:" + idContainer)

		if(idContainer == 0){ //se for para cadastrar --------------------------------------------------

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
							'Por favor verifique os campos',
							'error'
						)
	
						if(response['error_list']){
							
							showErrorsModal(response['error_list'])
						}
						
					} else {
						$('#containerModal').modal('hide');
						
						//console.log(response)
						Swal.fire(
							'Sucesso!',
							'Container cadastrado!',
							'success'
							)
	
						loadTableContainers();
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

			//console.log('você quer editar o usuario: ' + idContainer)
			
			$.ajax({
				type: "POST",
				url: `/products/containers/${idContainer}`, //rota para editar
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
							'Por favor verifique os campos',
							'error'
						);

						if(response['error_list']){
							
							showErrorsModal(response['error_list'])
						}

					} else {
						$('#containerModal').modal('hide');

						Swal.fire(
							'Sucesso!',
							'Container atualizado!',
							'success'
						);

						loadTableContainers();
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


function loadTableContainers(){ //carrega a tabela de Containers

	myTable.destroy(); //desfaz as paginações
	
	myTable = $("#dataTable").DataTable({ 
		"oLanguage": DATATABLE_PTBR,
		"autoWidth": false, //largura
		"processing": true, //mensagem 'processando'
		"serverSide": true,
		"ajax": {
			"url": "/products/containers/list_datatables", //para chamar o método ajax_list_container
			"type": "POST",
		},
		"columnDefs": [
			{ targets: "no-sort", orderable: false }, //para não ordenar
			{ targets: "text-center", className: "text-center" },
		]
	});
}


//detalhes do Container
function loadContainer(idContainer) { //carrega todos os campos do modal referente ao Container escolhido
	clearErrors();

	$('#modalTitle').html('Detalhes do Container')
	$('#btnClose').val('Fechar').removeClass('btn-danger').addClass('btn-primary')
	$('#btnSaveContainer').hide();
	$('#btnUpdate').show();
	

	$('#senha').parent().hide();
	$('#btnChangePassword').parent().show(); //botão para mudar senha
	$('#dtCadastro').parent().show(); //aparece a data de cadastro (só para visualizar)
	$('#desImagePath').parent().hide();


	$.getJSON(`/products/containers/json/${idContainer}`, function (data) {
		//console.log(data)
		//$("#formContainer").data("id", idContainer);

		$("#formContainer #nomeCompleto").val(data.nomeCompleto).prop('disabled', true);
		$("#formContainer #funcao").val(data.funcao).prop('disabled', true);
		$("#formContainer #nomeUsuario").val(data.nomeUsuario).prop('disabled', true);
		//$("#formContainer #senha").val(data.senha).prop('disabled', true);
		$("#formContainer #email").val(data.email).prop('disabled', true);
		$("#formContainer #administrador").val(data.administrador).prop('disabled', true);
		$("#idContainer").val(data.idContainer);

		//console.log('load View Container idContainer: ' + $("#idContainer").val())

		dtCadastro = formatDate(data.dtCadastro)
		//console.log('data: ' + dtCadastro)
		$("#formContainer #dtCadastro").val(dtCadastro);
			
		$("#formContainer #image-preview").attr("src", data.foto); //mostra a imagem atual
		//$("#desmagePath").val(data.foto);

		/* Atualizar Container ------------------------------------------------------------------ */
		$('#btnUpdate').click(function(){ //se eu quiser atualizar o Container atual

			$('#modalTitle').html('Editar Container');
			$('#btnClose').html('Cancelar').removeClass('btn-primary').addClass('btn-danger');
			$('#btnSaveContainer').val('Atualizar').show();
			$('#btnUpdate').hide();
		
			$('#desImagePath').parent().show();

			$("#formContainer #nomeCompleto").prop('disabled', false);
			$("#formContainer #funcao").prop('disabled', false);
			$("#formContainer #nomeUsuario").prop('disabled', false);
			//$("#formContainer #senha").prop('disabled', false);
			$("#formContainer #email").prop('disabled', false);
			$("#formContainer #administrador").prop('disabled', false);
				
		}); /* Fim Atualizar Container ---------------------------------------------------------- */
			

	}).then(() => { 

		$("#containerModal").modal();
	}).fail(function () {
		console.log("Rota não encontrada!");
	});

}

function deleteProduct(idContainer){

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
				url: `/products/containers/${idContainer}/delete`,
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
							'Por favor verifique os campos',
							'error'
						)
						
					} else {					
										
						Swal.fire(
							'Excluído!',
							'Registro apagado!',
							'success'
						)

						loadTableContainers();						
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

	$('#senha').parent().show();
	$('#btnChangePassword').parent().hide(); //botão para mudar senha
	$('#dtCadastro').parent().hide(); //aparece a data de cadastro (só para visualizar)
	$('#desImagePath').parent().show();


	$("#formContainer #nomeCompleto").prop('disabled', false);
	$("#formContainer #funcao").prop('disabled', false);
	$("#formContainer #nomeUsuario").prop('disabled', false);
	//$("#formContainer #senha").prop('disabled', false);
	$("#formContainer #email").prop('disabled', false);
	$("#formContainer #administrador").prop('disabled', false);
	

	$('#image-preview').attr('src', "/res/img/productsp/product-default.jpg");
	$('#btnChangePassword').parent().hide();
	$('#dtCadastro').parent().hide();
	$('#senha').parent().show();

	$('#nomeCompleto').val('');
	$('#funcao').val('');
	$('#nomeUsuario').val('');
	$('#senha').val('');
	$('#email').val('');
	$('#administrador').val('0');
	$('#idContainer').val('0');
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

